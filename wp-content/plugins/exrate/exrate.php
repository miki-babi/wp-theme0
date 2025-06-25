<?php

/*
    * Plugin Name: Exrate
    * Description: A plugin to display exchange rates.
    * Version: 1.0  
    * Author: Mikiyas Shiferaw
    * Author URI: https://t.me/mikiyas_sh

    * License: GPL2

*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


// Register CPT
add_action('init', function() {
    register_post_type('currency', [
        'label' => 'Currencies',
        'public' => true,
        'supports' => ['title', 'thumbnail'],
        'show_in_rest' => true,
    ]);
});

// Create custom table for price history on plugin activation
register_activation_hook(__FILE__, function() {
    global $wpdb;
    $table = $wpdb->prefix . 'currency_price_history';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        currency_id BIGINT UNSIGNED NOT NULL,
        cash_buy DECIMAL(10,2),
        cash_sell DECIMAL(10,2),
        transactional_buy DECIMAL(10,2),
        transactional_sell DECIMAL(10,2),
        recorded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        INDEX (currency_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
});

// Save price history on currency save
add_action('save_post_currency', function($post_id) {
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) return;

    global $wpdb;
    $table = $wpdb->prefix . 'currency_price_history';

    $cash_buy = get_post_meta($post_id, 'cash_buy', true);
    $cash_sell = get_post_meta($post_id, 'cash_sell', true);
    $transactional_buy = get_post_meta($post_id, 'transactional_buy', true);
    $transactional_sell = get_post_meta($post_id, 'transactional_sell', true);

    // Only insert if values exist
    if ($cash_buy !== '' && $cash_sell !== '' && $transactional_buy !== '' && $transactional_sell !== '') {
        $wpdb->insert($table, [
            'currency_id' => $post_id,
            'cash_buy' => $cash_buy,
            'cash_sell' => $cash_sell,
            'transactional_buy' => $transactional_buy,
            'transactional_sell' => $transactional_sell,
            'recorded_at' => current_time('mysql'),
        ]);
    }
});

// Add meta boxes for rates
add_action('add_meta_boxes', function() {
    add_meta_box('currency_rates', 'Currency Rates', function($post) {
        $fields = [
            'cash_buy' => 'Cash Buy',
            'cash_sell' => 'Cash Sell',
            'transactional_buy' => 'Transactional Buy',
            'transactional_sell' => 'Transactional Sell',
        ];
        foreach ($fields as $key => $label) {
            $value = get_post_meta($post->ID, $key, true);
            echo '<p><label>' . esc_html($label) . ': <input type="number" step="0.01" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" style="width:100px;"></label></p>';
        }
    }, 'currency', 'normal', 'default');
});

// Save meta box data
add_action('save_post_currency', function($post_id) {
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) return;
    $fields = ['cash_buy', 'cash_sell', 'transactional_buy', 'transactional_sell'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, floatval($_POST[$field]));
        }
    }
});

// Register REST API route to get price history
add_action('rest_api_init', function() {
    register_rest_route('currency-tracker/v1', '/rates/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => function($request) {
            global $wpdb;
            $id = (int) $request['id'];
            $table = $wpdb->prefix . 'currency_price_history';
            return $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE currency_id = %d ORDER BY recorded_at ASC", $id));
        },
        'permission_callback' => '__return_true',
    ]);
});

// Shortcode to display graph
add_shortcode('currency_graph', function($atts) {
    $id = intval($atts['id'] ?? 0);
    if (!$id) return 'No currency selected';

    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', [], null, true);

    $rest_url = esc_url_raw(rest_url("currency-tracker/v1/rates/$id"));

    $script = <<<JS
    document.addEventListener('DOMContentLoaded', function() {
        fetch('$rest_url')
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    document.getElementById('currencyChart').parentNode.innerHTML = 'No data to display';
                    return;
                }
                const labels = data.map(item => new Date(item.recorded_at).toLocaleDateString());
                const cashBuy = data.map(item => parseFloat(item.cash_buy));
                const cashSell = data.map(item => parseFloat(item.cash_sell));
                const transactionalBuy = data.map(item => parseFloat(item.transactional_buy));
                const transactionalSell = data.map(item => parseFloat(item.transactional_sell));

                const ctx = document.getElementById('currencyChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            { label: 'Cash Buy', data: cashBuy, borderColor: 'blue', fill: false },
                            { label: 'Cash Sell', data: cashSell, borderColor: 'red', fill: false },
                            { label: 'Transactional Buy', data: transactionalBuy, borderColor: 'green', fill: false },
                            { label: 'Transactional Sell', data: transactionalSell, borderColor: 'orange', fill: false },
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: { mode: 'index', intersect: false },
                        stacked: false,
                        scales: { y: { beginAtZero: false }, x: { display: true } }
                    }
                });
            });
    });
    JS;

    return '<canvas id="currencyChart" height="250"></canvas><script>'.$script.'</script>';
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('alpine', 'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js', [], null, true);
});

add_shortcode('currency_table', function () {
    $args = [
        'post_type'      => 'currency',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ];
    $q = new WP_Query($args);
    if (!$q->have_posts()) return '<p>No currencies found.</p>';

    $currency_array = [];
    foreach ($q->posts as $post) {
        $cid = $post->ID;
        $currency_array[] = [
            'name'      => get_the_title($cid),
            'rate'      => get_post_meta($cid, 'rate', true),
            'cash_buy'  => get_post_meta($cid, 'cash_buy', true),
            'cash_sell' => get_post_meta($cid, 'cash_sell', true),
            'tran_buy'  => get_post_meta($cid, 'transactional_buy', true),
            'tran_sell' => get_post_meta($cid, 'transactional_sell', true),
            'flag'      => get_the_post_thumbnail_url($cid, [60, 60]),
        ];
    }

    $default = $currency_array[0];

    ob_start(); ?>

    <div
        class="currency-widget main"
        x-data='{
            currencies: <?= json_encode($currency_array) ?>,
            selected: <?= json_encode($default) ?>
        }'
    >
      <div class="exchange-header">
        
        <div class="currency-display">
          <div class="currency-code">
            <img :src="selected.flag" class="currency-flag"/>
            <h2 x-text="selected.name"></h2>
            <span class="rate" x-text="selected.rate"></span>
          </div>

          <ul class="rate-list">
            <li><span>Cash Buying</span><span x-text="selected.cash_buy"></span></li>
            <li><span>Cash Selling</span><span x-text="selected.cash_sell"></span></li>
            <li><span>Transactional Buying</span><span x-text="selected.tran_buy"></span></li>
            <li><span>Transactional Selling</span><span x-text="selected.tran_sell"></span></li>
          </ul>
        </div>
      </div>

      <div class="currency-selector">
        <template x-for="(currency, index) in currencies" :key="index">
          <div
              class="currency-icon"
              :class="{'selected': selected.name === currency.name}"
              @click="selected = currency" 
          >
            <img :src="currency.flag" class="selector-flag" />
            <span x-text="currency.name"></span>
          </div>
        </template>
      </div>
    </div>

    <style>
      .main {
        max-width: 100%;
        margin: 0 auto;
        padding: 20px;
        border: 2px solid red;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }
      .currency-widget {
        /* border: 2px solid #0070c0; */
        
        padding: 20px;
        font-family: Arial, sans-serif;
        background: #ffffff;
      }
      .exchange-header {
        display: flex;
        justify-content: space-between;
        gap: 20px;
      }
      .exchange-info {
        width: 40%;
      }
      .currency-display {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
      }
      .currency-code img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
      }
      .currency-code h2 {
        margin: 8px 0;
        font-size: 1.75em;
      }
      .currency-code .rate {
        font-size: 2.5em;
        color: #002b80;
        font-weight: bold;
      }
      .rate-list {
        list-style: none;
        padding: 0;
        margin-top: 15px;
        text-align: left;
      }
      .rate-list li {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        font-size: 0.95em;
        border-bottom: 1px solid #e0e0e0;
      }
      .currency-selector {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
      }
      .currency-icon {
        text-align: center;
        cursor: pointer;
        width: 60px;
        height: 60px;
        border: 2px solid transparent;
        border-radius: 100%;
        padding: 5px;
        transition: all 0.2s ease;
      }
      .currency-icon.selected {
        border-color: #0070c0;
        background: #e6f0ff;
      }
      .currency-icon img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: block;
        margin: 0 auto 5px;
      }
    </style>

    <?php
    wp_reset_postdata();
    return ob_get_clean();
});
