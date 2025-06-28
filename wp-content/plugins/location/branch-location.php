<?php
/*
Plugin Name: Branch Location
Description: A starter template for the Branch Location plugin.
Version: 1.0.0
Author: Mikiyas Shiferaw
Author URI: https://t.me/mikiyas_sh
*/

if (!defined('ABSPATH')) exit;

// Register Custom Post Type for ATM
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
        'taxonomies' => ['category']
    ]);
});

// Meta Boxes for Latitude and Longitude
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
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('alpinejs', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', [], null, true);
});

// Frontend Shortcode Output Markup
add_shortcode('atm_list', function () {
    ob_start();
    $args = ['post_type' => 'atm_location', 'posts_per_page' => -1];
    $atms = get_posts($args);
    $categories = get_categories(['taxonomy' => 'category', 'hide_empty' => false]);

    if (empty($atms)) return '<p>No ATM locations found.</p>';

    ?>
    <div id="atm-locator" x-data="atmLocator()" class="flex flex-wrap gap-4">
        <div class="w-full md:w-2/5">
            <select x-model="selectedCategory" class="mb-4 w-full border px-4 py-2">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></option>
                <?php endforeach; ?>
            </select>

            <button @click="findNearest" class="mb-4 px-4 py-2 bg-blue-500 text-white">Find Nearest ATM</button>
            <ul class="space-y-2">
                <?php foreach ($atms as $atm): 
                    $lat = get_post_meta($atm->ID, '_atm_latitude', true);
                    $lng = get_post_meta($atm->ID, '_atm_longitude', true);
                    $title = esc_html($atm->post_title);
                    $terms = wp_get_post_terms($atm->ID, 'category', ['fields' => 'slugs']);
                    $cat_class = implode(' ', $terms);
                ?>
                    <li x-show="selectedCategory === '' || '<?php echo $cat_class; ?>'.includes(selectedCategory)">
                        <button @click="selectAtm('<?php echo esc_js($lat); ?>','<?php echo esc_js($lng); ?>')" class="w-full text-left px-4 py-2 border"><?php echo $title; ?></button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="w-full md:w-3/5">
            <div id="atm-map-content" class="w-full h-80">
                <iframe :src="iframeSrc" width="100%" height="100%" frameborder="0" style="border:0" loading="lazy" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <script>
        function atmLocator() {
            return {
                iframeSrc: "https://maps.google.com/maps?q=<?php echo get_post_meta($atms[0]->ID, '_atm_latitude', true); ?>,<?php echo get_post_meta($atms[0]->ID, '_atm_longitude', true); ?>&hl=en&z=14&output=embed",
                selectedCategory: '',
                selectAtm(lat, lng) {
                    this.iframeSrc = `https://maps.google.com/maps?q=${lat},${lng}&hl=en&z=14&output=embed`;
                },
                findNearest() {
                    navigator.geolocation.getCurrentPosition(pos => {
                        const userLat = pos.coords.latitude;
                        const userLng = pos.coords.longitude;
                        let nearest = null;
                        let nearestDist = Infinity;
                        document.querySelectorAll('[x-data] button').forEach(btn => {
                            const lat = parseFloat(btn.getAttribute('x-on:click').match(/'(.*?)'/)[1]);
                            const lng = parseFloat(btn.getAttribute('x-on:click').match(/'(.*?)'/g)[1].replace(/'/g, ''));
                            const dist = Math.sqrt(Math.pow(userLat - lat, 2) + Math.pow(userLng - lng, 2));
                            if (dist < nearestDist) {
                                nearestDist = dist;
                                nearest = { lat, lng };
                            }
                        });
                        if (nearest) this.selectAtm(nearest.lat, nearest.lng);
                    });
                }
            }
        }
    </script>
    <?php

    return ob_get_clean();
});
