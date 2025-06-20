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
add_shortcode('currency_table', function() {
    global $wpdb;
    $args = [
        'post_type' => 'currency',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];
    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p>No currencies found.</p>';
    }

    $table = $wpdb->prefix . 'currency_price_history';

    $output = '<table border="1" cellpadding="5" cellspacing="0">';
    $output .= '<tr>
        <th>Image</th><th>Name</th>
        <th>Cash Buy</th><th>Cash Buy %Δ</th>
        <th>Cash Sell</th><th>Cash Sell %Δ</th>
        <th>Transactional Buy</th><th>Transactional Buy %Δ</th>
        <th>Transactional Sell</th><th>Transactional Sell %Δ</th>
    </tr>';

    while ($query->have_posts()) {
        $query->the_post();
        $id = get_the_ID();
        $name = get_the_title();
        $image = get_the_post_thumbnail($id, [50, 50], ['style' => 'max-width:50px; height:auto;']);

        // Get last two price history entries (latest first)
        $history = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE currency_id = %d ORDER BY recorded_at DESC LIMIT 2",
            $id
        ));

        $current = $history[0] ?? null;
        $previous = $history[1] ?? null;

        // Helper to calc % change
        $percent_change = function($current_val, $prev_val) {
            if ($prev_val == 0 || $prev_val === null) return '-';
            return round((($current_val - $prev_val) / $prev_val) * 100, 2) . '%';
        };

        $cb = $current->cash_buy ?? '-';
        $cb_change = $previous ? $percent_change($cb, $previous->cash_buy) : '-';

        $cs = $current->cash_sell ?? '-';
        $cs_change = $previous ? $percent_change($cs, $previous->cash_sell) : '-';

        $tb = $current->transactional_buy ?? '-';
        $tb_change = $previous ? $percent_change($tb, $previous->transactional_buy) : '-';

        $ts = $current->transactional_sell ?? '-';
        $ts_change = $previous ? $percent_change($ts, $previous->transactional_sell) : '-';

        $output .= "<tr>
            <td>{$image}</td>
            <td>" . esc_html($name) . "</td>
            <td>" . esc_html($cb) . "</td>
            <td>" . esc_html($cb_change) . "</td>
            <td>" . esc_html($cs) . "</td>
            <td>" . esc_html($cs_change) . "</td>
            <td>" . esc_html($tb) . "</td>
            <td>" . esc_html($tb_change) . "</td>
            <td>" . esc_html($ts) . "</td>
            <td>" . esc_html($ts_change) . "</td>
        </tr>";
    }

    wp_reset_postdata();
    $output .= '</table>';
    return $output;
});
