<?php
/**
 * Template Name: Home Page
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php
        // Start the loop.
        while ( have_posts() ) :
            the_post();
            the_content();

            // Include the page content template.
            get_template_part( 'template-parts/page', 'about-us' );


            

        // End the loop.
        endwhile;
        ?>

    </main><!-- .site-main -->
</div><!-- .content-area -->


<?php get_footer(); ?>