<?php
$image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
$title     = get_the_title();
$author    = get_the_author();
$date      = get_the_date();
$link      = get_permalink();
?>

<div class="blog-card" style="
    position: relative;
    width: 300px;
    height: 400px;
    border-radius: 15px;
    overflow: hidden;
    background-image: url('<?php echo esc_url($image_url); ?>');
    background-size: cover;
    background-position: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    color: white;
    display: flex;
    align-items: flex-end;
">
    <div class="blog-card-content" style="
        width: 100%;
        padding: 20px;
        background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0));
    ">
        <div class="blog-card-meta" style="
            display: inline-block;
            background: black;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            margin-bottom: 10px;
        ">
            <?php echo esc_html($date); ?>
        </div>
        <h3 class="blog-card-title" style="margin: 0 0 10px;"><?php echo esc_html($title); ?></h3>
        <div class="blog-card-author" style="font-size: 14px; display: flex; align-items: center; gap: 5px; margin-bottom: 15px;">
            <!-- <span style="display: inline-block; width: 16px; height: 16px; background: white; border-radius: 50%; "></span> -->
            <span><?php echo esc_html($author); ?></span>
        </div>
        <a href="<?php echo esc_url($link); ?>" class="blog-card-read-more" style="
            display: inline-block;
            padding: 8px 16px;
            background: red;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
        ">Read More</a>
    </div>
</div>
