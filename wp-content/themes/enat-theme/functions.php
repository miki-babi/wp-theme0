<?php

function boilerplate_load_assets() {
  wp_enqueue_script('ourmainjs', get_theme_file_uri('/src/index.js'), array('wp-element'), '1.0', true);
  wp_enqueue_style('ourmaincss', get_theme_file_uri('/build/index.css'));

  wp_localize_script('ourmainjs', 'ourData', array(
    'root_url' => get_site_url()
  ));
}

add_action('wp_enqueue_scripts', 'boilerplate_load_assets');

function boilerplate_add_support() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'boilerplate_add_support');

register_nav_menu('main-menu', 'Main Menu');


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
