<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <div class="bg-white">
      <div class="max-w-4xl mx-auto mb-5 px-4 flex justify-between items-center">
        <div class="text-xl sm:text-3xl py-10"><a href="<?php echo get_home_url(); ?>" class="hover:text-teal-600">
          <img src="./assets/images/logo.png" alt="">
        </a></div>
        <div class="text-xl sm:text-3xl py-10"><a href="<?php echo get_home_url(); ?>" class="hover:text-teal-600">Enat banko</a></div>
        <div class="text-xl sm:text-3xl py-10"><a href="<?php echo get_home_url(); ?>" class="hover:text-teal-600">Enat banko</a></div>
      </div>
    </div>
