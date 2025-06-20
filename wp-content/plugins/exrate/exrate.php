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

function exrate_shortcode() {
    // Example exchange rates data
    $rates = [
        ['Currency' => 'USD', 'Rate' => '54.00'],
        ['Currency' => 'EUR', 'Rate' => '58.00'],
        ['Currency' => 'GBP', 'Rate' => '67.00'],
    ];

    ob_start();
    echo '<table border="1" cellpadding="5" cellspacing="0">';
    echo '<tr><th>Currency</th><th>Rate</th></tr>';
    foreach ($rates as $rate) {
        echo '<tr>';
        echo '<td>' . esc_html($rate['Currency']) . '</td>';
        echo '<td>' . esc_html($rate['Rate']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    return ob_get_clean();
}
add_shortcode('exrate_table', 'exrate_shortcode');