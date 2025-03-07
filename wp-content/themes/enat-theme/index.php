<?php

get_header(); ?>

<div class="max-w-4xl mx-auto px-4">
  <script>
    console.log('Hello, World!');
  </script>

  <div class="prose max-w-full">
    <?php
  if (is_page("home") || is_front_page()) { 
    get_template_part('template-parts/page', 'home');
} else {
    get_template_part('template-parts/page', 'default');
}


// Load page-home.php if the current page is the front page
if (is_front_page()) {
  include get_template_directory() . '/page-home.php';
}
?>
  </div>
</div>

<?php get_footer();