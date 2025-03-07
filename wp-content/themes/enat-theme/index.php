<?php

get_header(); ?>

<div class="max-w-4xl mx-auto px-4">
  <script>
    console.log('Hello, World!');
  </script>

  <div class="prose max-w-full">
    <?php
  if (is_page("home")) { 
    get_template_part('template-parts/page', 'home');
} else {
    get_template_part('template-parts/page', 'default');
}
?>
  </div>
</div>

<?php get_footer();