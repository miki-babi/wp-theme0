
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



function latest_media_shortcode() {
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
    );

    $query = new WP_Query($args);
    $output = '<div style="display: flex; gap: 20px; flex-wrap: wrap;">';

    while ($query->have_posts()) {
        $query->the_post();
        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
        $title = get_the_title();
        $author = get_the_author();
        $date = get_the_date();
        $link = get_permalink();

        $output .= '
        <div style="
            flex: 1;
            min-width: 300px;
            background: url(' . esc_url($image_url) . ') center/cover;
            padding: 20px;
            color: white;
            border-radius: 10px;
            position: relative;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        ">
            <div style="background: rgba(0,0,0,0.5); padding: 10px; border-radius: 10px;">
                <div style="font-size: 14px; margin-bottom: 5px;">' . esc_html($date) . ' | by ' . esc_html($author) . '</div>
                <h2 style="margin: 0 0 10px;">' . esc_html($title) . '</h2>
                <a href="' . esc_url($link) . '" style="
                    display: inline-block;
                    padding: 8px 12px;
                    background: #ff6600;
                    color: white;
                    border-radius: 5px;
                    text-decoration: none;
                ">Read More</a>
            </div>
        </div>';
    }

    wp_reset_postdata();
    $output .= '</div>';
    return $output;
}
add_shortcode('latest_media', 'latest_media_shortcode');
