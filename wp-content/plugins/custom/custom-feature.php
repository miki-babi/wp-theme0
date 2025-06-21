
<?php

/*
    * Plugin Name: custom-feature
    * Description: A plugin for small custom feature.
    * Version: 1.0
    * Author: Mikiyas Shiferaw
    * Author URI: https://t.me/mikiyas_sh

    * License: GPL2

*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
function latest_three_blog_cards_shortcode() {
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
    );

    $query = new WP_Query($args);
    ob_start();
    echo '<div style="display: flex; gap: 20px; flex-wrap: wrap;">';

    while ($query->have_posts()) {
        $query->the_post();
        include get_template_directory() . '/partials/latest-blog-card.php';
    }

    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('latest_blog_cards', 'latest_three_blog_cards_shortcode');

