<?php
get_header();
?>

<div class="content">
    <?php
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
    ?>
 <h1>
    hellloooo 
 </h1>
</div>

<?php
get_footer();
?>