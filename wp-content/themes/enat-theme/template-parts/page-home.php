<?php
/**
 * Template part for displaying the home page content
 *
 * @package enat-theme
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <section class="home-intro">
            <h1>Welcome to Our Website</h1>
            <p>This is a simple home page template for your WordPress theme.</p>
        </section>

        <section class="home-content">
            <?php
            // Display the content of the page.
            the_content();
            ?>
        </section>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
?>
