<?php

if ( ! defined ( 'ABSPATH' ) ) {
    exit;
}

/* --------------------------------------------------------------
ADD SHORTCODE - USAGE [wpcpolls_shortcode id=X]
-------------------------------------------------------------- */
function wpcpolls_shortcode_function($atts, $content) {
    global $post;
    $current_id = $post->ID;
    $args = array('post_type' => 'wpcpolls_polls', 'posts_per_page' => 1, 'post__in' => array($atts['id']));
    $polls = new WP_Query($args);
    if ($polls->have_posts()) : ?>
<div id="<?php echo $current_id; ?>" class="wpcpolls-container">
    <div class="wpcpolls-content">
        <?php while ($polls->have_posts()) : $polls->the_post(); ?>
        <div class="wpcpolls-descripcion">
            <?php the_content(); ?>
        </div>
        <ul class="wpcpolls-list">
            <?php for ($i = 1; $i <= 4; $i++) { ?>
            <?php $polls_post_meta = 'wpcpolls_option_' . $i; ?>
            <li id="item_<?php echo $polls_post_meta ?>" class="wpcpolls-item">
                <div class="wpcpolls-title">
                    <input onclick="wpcpolls_select(<?php echo $i; ?>)" type="radio" id="<?php echo esc_attr($polls_post_meta); ?>" name="wpcpolls_option" value="<?php echo $polls_post_meta; ?>" />
                    <label for="<?php echo esc_attr($polls_post_meta); ?>"><?php echo get_post_meta( get_the_ID(), $polls_post_meta, true ); ?></label>
                    <input type="hidden" name="<?php echo $polls_post_meta; ?>_value" value="">
                </div>
                <div class="wpcpolls-progress-bar">
                    <div class="wpcpolls-progress"><span style="width: 0%;"></span></div><div class="wpcpolls-percentage"><span>0%</span></div>
                </div>
            </li>
            <?php } ?>
        </ul>
        <?php endwhile; ?>
        <button class="wpcpolls-button"><?php _e('Votar', 'wordpress-custom-polls'); ?></button>
    </div>
</div>
<?php endif;
    wp_reset_query();
?>

<?php }

add_shortcode('wpcpolls_shortcode', 'wpcpolls_shortcode_function' );


