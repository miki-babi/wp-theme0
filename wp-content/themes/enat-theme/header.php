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
      <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
        <div class="flex-shrink-0 flex items-center">
          <a href="<?php echo get_home_url(); ?>">
            <img class="h-6 w-6" src="<?php echo esc_url( get_parent_theme_file_uri( 'assets/images/logo.png' ) ); ?>" alt="Logo" style="width: 109px; height: 53px;">
          </a>
        </div>
        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
          <a href="<?php echo get_home_url(); ?>" class="text-gray-900 hover:text-teal-600 inline-flex items-center px-1 pt-1 text-sm font-medium">Enat banko</a>
          <a href="<?php echo get_home_url(); ?>" class="text-gray-900 hover:text-teal-600 inline-flex items-center px-1 pt-1 text-sm font-medium">Enat banko</a>
        </div>
          </div>
        </div>
      </nav>
    </div>
