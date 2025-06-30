<?php
/*
Plugin Name: Branch Location
Description: A starter template for the Branch Location plugin.
Version: 1.0.0
Author: Mikiyas Shiferaw
Author URI: https://t.me/mikiyas_sh
*/

if (!defined('ABSPATH')) exit;

// 1. Register Custom Post Type for ATM
add_action('init', function () {
    register_post_type('atm_location', [
        'labels' => [
            'name' => 'ATM Locations',
            'singular_name' => 'ATM Location',
            'add_new_item' => 'Add New ATM',
            'edit_item' => 'Edit ATM'
        ],
        'public' => true,
        'has_archive' => false,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-location',
        'supports' => ['title'],
    ]);
});

// 2. Add Meta Boxes for Latitude and Longitude
add_action('add_meta_boxes', function () {
    add_meta_box('atm_location_meta', 'ATM Details', function ($post) {
        wp_nonce_field('atm_location_nonce_action', 'atm_location_nonce_field');

        $lat = get_post_meta($post->ID, '_atm_latitude', true);
        $lng = get_post_meta($post->ID, '_atm_longitude', true);

        echo '<label>Latitude:<br><input type="text" name="atm_latitude" value="' . esc_attr($lat) . '" style="width:100%"></label><br><br>';
        echo '<label>Longitude:<br><input type="text" name="atm_longitude" value="' . esc_attr($lng) . '" style="width:100%"></label><br><br>';
    }, 'atm_location', 'normal', 'high');
});

add_action('save_post_atm_location', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['atm_location_nonce_field']) || !wp_verify_nonce($_POST['atm_location_nonce_field'], 'atm_location_nonce_action')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['atm_latitude']))
        update_post_meta($post_id, '_atm_latitude', sanitize_text_field($_POST['atm_latitude']));

    if (isset($_POST['atm_longitude']))
        update_post_meta($post_id, '_atm_longitude', sanitize_text_field($_POST['atm_longitude']));
});


add_shortcode('atm_list', function () {
    ob_start();

    $args = ['post_type' => 'atm_location', 'posts_per_page' => -1];
    $atms = get_posts($args);

    if (empty($atms)) return '<p>No ATM locations found.</p>';

    $first_atm = $atms[0];
    $first_lat = get_post_meta($first_atm->ID, '_atm_latitude', true);
    $first_lng = get_post_meta($first_atm->ID, '_atm_longitude', true);

    // Include external HTML/JS template
    include plugin_dir_path(__FILE__) . 'atm-template.php';

    return ob_get_clean();
});
