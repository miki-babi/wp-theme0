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

// 3. Frontend Shortcode
add_shortcode('atm_list', function () {
    ob_start();
    $args = ['post_type' => 'atm_location', 'posts_per_page' => -1];
    $atms = get_posts($args);

    if (empty($atms)) return '<p>No ATM locations found.</p>';

    $first_atm = $atms[0];
    $first_lat = get_post_meta($first_atm->ID, '_atm_latitude', true);
    $first_lng = get_post_meta($first_atm->ID, '_atm_longitude', true);
    $first_iframe = "<iframe width=\"100%\" height=\"300\" frameborder=\"0\" style=\"border:0\" loading=\"lazy\" allowfullscreen src=\"https://maps.google.com/maps?q={$first_lat},{$first_lng}&hl=en&z=14&amp;output=embed\"></iframe>";

    echo '<div id="atm-container" style="display: flex; flex-wrap: wrap; gap: 20px;">
            <div id="atm-list" style="flex: 1 1 40%; max-width: 40%;">
                <button id="find-nearest">Find Nearest ATM</button>
                <ul style="padding-left: 0;">';

    foreach ($atms as $atm) {
        $lat = get_post_meta($atm->ID, '_atm_latitude', true);
        $lng = get_post_meta($atm->ID, '_atm_longitude', true);
        echo '<li data-lat="' . esc_attr($lat) . '" data-lng="' . esc_attr($lng) . '" data-id="' . $atm->ID . '" class="atm-item" style="list-style:none; cursor:pointer; padding:5px; border:1px solid #ccc; margin-bottom:5px;">' . esc_html($atm->post_title) . '</li>';
    }

    echo '  </ul>
            </div>
            <div id="atm-map-view" style="flex: 1 1 55%; max-width: 55%;">
                <div id="atm-map-content">' . $first_iframe . '</div>
            </div>
        </div>';

    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.atm-item').forEach(function (el) {
                el.addEventListener('click', function () {
                    const lat = el.dataset.lat;
                    const lng = el.dataset.lng;
                    const mapDiv = document.getElementById('atm-map-content');
                    mapDiv.innerHTML = `<iframe width="100%" height="300" frameborder="0" style="border:0" loading="lazy" allowfullscreen src="https://maps.google.com/maps?q=${lat},${lng}&hl=es&z=14&amp;output=embed"></iframe>`;
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
    <?php
    return ob_get_clean();
});
