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

// 2. Add Meta Boxes for Latitude, Longitude, and Iframe
add_action('add_meta_boxes', function () {
    add_meta_box('atm_location_meta', 'ATM Details', function ($post) {
        $lat = get_post_meta($post->ID, '_atm_latitude', true);
        $lng = get_post_meta($post->ID, '_atm_longitude', true);
        $iframe = get_post_meta($post->ID, '_atm_iframe', true);
        echo '<label>Latitude:<br><input type="text" name="atm_latitude" value="' . esc_attr($lat) . '" style="width:100%"></label><br><br>';
        echo '<label>Longitude:<br><input type="text" name="atm_longitude" value="' . esc_attr($lng) . '" style="width:100%"></label><br><br>';
        echo '<label>Google Maps Iframe:<br><textarea name="atm_iframe" rows="5" style="width:100%">' . esc_textarea($iframe) . '</textarea></label>';
    }, 'atm_location', 'normal', 'high');
});

add_action('save_post_atm_location', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['atm_latitude']))
        update_post_meta($post_id, '_atm_latitude', sanitize_text_field($_POST['atm_latitude']));
    if (isset($_POST['atm_longitude']))
        update_post_meta($post_id, '_atm_longitude', sanitize_text_field($_POST['atm_longitude']));
    if (isset($_POST['atm_iframe']))
        update_post_meta($post_id, '_atm_iframe', wp_kses_post($_POST['atm_iframe']));
});

// 3. Frontend Shortcode
add_shortcode('atm_list', function () {
    ob_start();
    $args = ['post_type' => 'atm_location', 'posts_per_page' => -1];
    $atms = get_posts($args);

    echo '<div id="atm-list">
        <button id="find-nearest">Find Nearest ATM</button>
        <ul>';

    foreach ($atms as $atm) {
        $lat = get_post_meta($atm->ID, '_atm_latitude', true);
        $lng = get_post_meta($atm->ID, '_atm_longitude', true);
        $iframe = get_post_meta($atm->ID, '_atm_iframe', true);
        echo '<li data-lat="' . esc_attr($lat) . '" data-lng="' . esc_attr($lng) . '" data-id="' . $atm->ID . '" class="atm-item">' . esc_html($atm->post_title) . '</li>';
        echo '<div id="map-' . $atm->ID . '" class="atm-map" style="display:none">' . $iframe . '</div>';
    }

    echo '</ul></div>';
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.atm-item').forEach(function (el) {
                el.addEventListener('click', function () {
                    document.querySelectorAll('.atm-map').forEach(map => map.style.display = 'none');
                    document.getElementById('map-' + el.dataset.id).style.display = 'block';
                });
            });

            document.getElementById('find-nearest').addEventListener('click', function () {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;

                        let nearest = null;
                        let nearestDist = Infinity;

                        document.querySelectorAll('.atm-item').forEach(el => {
                            const lat = parseFloat(el.dataset.lat);
                            const lng = parseFloat(el.dataset.lng);
                            const d = Math.sqrt(Math.pow(userLat - lat, 2) + Math.pow(userLng - lng, 2));
                            if (d < nearestDist) {
                                nearestDist = d;
                                nearest = el;
                            }
                        });

                        if (nearest) {
                            nearest.click();
                            nearest.scrollIntoView({ behavior: 'smooth' });
                        }
                    });
                } else {
                    alert('Geolocation is not supported by your browser');
                }
            });
        });
    </script>
    <style>
        #atm-list ul { list-style: none; padding: 0; }
        #atm-list li { cursor: pointer; padding: 8px; background: #f9f9f9; margin: 5px 0; border: 1px solid #ccc; }
        .atm-map { margin: 10px 0; }
    </style>
    <?php
    return ob_get_clean();
});
