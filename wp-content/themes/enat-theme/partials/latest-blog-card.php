<?php
$image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
$title     = get_the_title();
$author    = get_the_author();
$date      = get_the_date();
$link      = get_permalink();
?>
<div style="
    flex: 1;
    min-width: 300px;
    background: url('<?php echo esc_url($image_url); ?>') center/cover;
    padding: 20px;
    color: white;
    border-radius: 10px;
    position: relative;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);

">
    <div class="blog-card-content" style="background: rgba(0,0,0,0.5); padding: 10px; border-radius: 10px;">
        <div class="blog-card-meta" style="font-size: 14px; margin-bottom: 5px;">
            <?php echo esc_html($date); ?> | by <?php echo esc_html($author); ?>
        </div>
        <h2 class="blog-card-title" style="margin: 0 0 10px;"><?php echo esc_html($title); ?></h2>
        <a href="<?php echo esc_url($link); ?>" class="blog-card-read-more" style="
            display: inline-block;
            padding: 8px 12px;
            background: #ff6600;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        ">Read More</a>
    </div>
</div>