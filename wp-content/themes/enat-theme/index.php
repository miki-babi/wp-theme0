<?php

get_header(); ?>

<div class="max-w-4xl mx-auto px-4">
  <script>
    console.log('Hello, World!');
  </script>

  <div class="prose max-w-full">
    <?php
    // Redirect to /home or /contact based on conditions
    if (is_front_page()) {
      wp_redirect(home_url('/home'));
      exit;
    } elseif (is_page('contact')) {
      wp_redirect(home_url('/contact'));
      exit;
    } else {
      // Default fallback
      include get_template_directory() . '/page-default.php';
    }?>
  </div>
</div>

<?php get_footer();