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
add_shortcode('currency_table', function () {
    $args = [
        'post_type' => 'currency',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];
    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p>No currencies found.</p>';
    }

    ob_start();
    ?>
    <div class="currency-widget">
        <div class="exchange-header">
            <div class="exchange-info">
                <h3>Exchange Rate</h3>
                <p>Stay informed with the latest exchange rates for the Ethiopian Birr (ETB). Convert your money seamlessly with competitive rates for international transactions, travel, or remittances. Our rates are updated regularly to help you make informed financial decisions.</p>
            </div>
            <div class="currency-display" id="currencyDisplay">
                <?php
                $query->the_post();
                $id = get_the_ID();
                $flag = get_the_post_thumbnail($id, [40, 40], ['class' => 'currency-flag']);
                ?>
                <div class="currency-code">
                    <?= $flag ?>
                    <h2 id="currencyName"><?= esc_html(get_the_title()) ?></h2>
                    <span class="rate" id="currencyRate"><?= esc_html(get_post_meta($id, 'rate', true)) ?></span>
                </div>
                <ul class="rate-list">
                    <li><span>Cash Buying</span> <span id="cashBuy"><?= esc_html(get_post_meta($id, 'cash_buy', true)) ?></span></li>
                    <li><span>Cash Selling</span> <span id="cashSell"><?= esc_html(get_post_meta($id, 'cash_sell', true)) ?></span></li>
                    <li><span>Transactional Buying</span> <span id="transBuy"><?= esc_html(get_post_meta($id, 'transactional_buy', true)) ?></span></li>
                    <li><span>Transactional Selling</span> <span id="transSell"><?= esc_html(get_post_meta($id, 'transactional_sell', true)) ?></span></li>
                </ul>
            </div>
        </div>
        <div class="currency-selector">
            <?php
            rewind_posts();
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID();
                $data = [
                    'name' => get_the_title(),
                    'rate' => get_post_meta($id, 'rate', true),
                    'cash_buy' => get_post_meta($id, 'cash_buy', true),
                    'cash_sell' => get_post_meta($id, 'cash_sell', true),
                    'trans_buy' => get_post_meta($id, 'transactional_buy', true),
                    'trans_sell' => get_post_meta($id, 'transactional_sell', true),
                    'img' => get_the_post_thumbnail_url($id, [40, 40]),
                ];
                ?>
                <div class="currency-icon" data-currency='<?= json_encode($data) ?>'>
                    <?= get_the_post_thumbnail($id, [40, 40], ['class' => 'selector-flag']) ?>
                    <span><?= esc_html($data['name']) ?></span>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const icons = document.querySelectorAll('.currency-icon');
            icons.forEach(icon => {
                icon.addEventListener('click', function () {
                    const data = JSON.parse(this.getAttribute('data-currency'));
                    document.querySelector('#currencyName').innerText = data.name;
                    document.querySelector('#currencyRate').innerText = data.rate;
                    document.querySelector('#cashBuy').innerText = data.cash_buy;
                    document.querySelector('#cashSell').innerText = data.cash_sell;
                    document.querySelector('#transBuy').innerText = data.trans_buy;
                    document.querySelector('#transSell').innerText = data.trans_sell;
                    document.querySelector('#currencyDisplay .currency-flag').src = data.img;
                });
            });
        });
    </script>
    <style>
        .currency-widget {
            border: 2px solid #0070c0;
            padding: 20px;
            font-family: sans-serif;
            background: #f9f9fb;
        }

        .exchange-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .exchange-info {
            flex: 1;
            max-width: 40%;
        }

        .currency-display {
            flex: 1;
            max-width: 50%;
            border: 1px solid #ccc;
            border-radius: 12px;
            padding: 15px;
            background: white;
            text-align: center;
        }

        .currency-code {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .currency-code h2 {
            margin: 5px 0;
            font-size: 1.5em;
        }

        .currency-code .rate {
            font-size: 2em;
            font-weight: bold;
            color: #002b80;
        }

        .rate-list {
            list-style: none;
            padding: 0;
            margin: 15px 0 0;
            text-align: left;
        }

        .rate-list li {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-weight: 500;
        }

        .currency-selector {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .currency-icon {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            font-size: 0.9em;
            transition: transform 0.2s;
        }

        .currency-icon:hover {
            transform: scale(1.05);
        }

        .selector-flag, .currency-flag {
            border-radius: 50%;
            max-width: 40px;
            height: 40px;
        }
    </style>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
});
