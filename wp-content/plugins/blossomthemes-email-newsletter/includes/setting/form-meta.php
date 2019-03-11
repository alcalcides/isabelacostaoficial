<?php
global $post;
$blossomthemes_email_newsletter_setting = get_post_meta( $post->ID, 'blossomthemes_email_newsletter_setting', true );
?>
<div class="trip-info-meta">
    <div class="trip-info-meta-wrap">
        <span class="label"><?php _e('Display Background From:','blossomthemes-email-newsletter'); ?></span> 
        <?php $option = isset($blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option']) ? esc_attr($blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option']):'bg-color';?>
        <div class="bg-color-option">
            <input class="newsletter-bg-option" type="radio" id="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option-color]" name="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option]" value="bg-color" <?php if( $option == 'bg-color' ) echo 'checked'; ?>>
            <label for="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option-color]"><?php _e('Background Color','blossomthemes-email-newsletter');?></label>
        </div>
        <div class="bg-color-option">
            <input class="newsletter-bg-option" type="radio" id="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option-image]" name="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option]" value="image" <?php if( $option == 'image' ) echo 'checked'; ?>>
            <label for="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option-image]"><?php _e('Background Image','blossomthemes-email-newsletter');?></label>
        </div>
    </div>
    <div class="trip-info-meta-wrap bg-image-uploader">
    	<?php
        $image = ' button">'.__('Upload image','blossomthemes-email-newsletter');
        $image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
        $display = 'none'; // display state ot the "Remove image" button
        $name = 'blossomthemes_email_newsletter_setting[appearance][bg]';
        $value = isset($blossomthemes_email_newsletter_setting['appearance']['bg']) ? esc_attr($blossomthemes_email_newsletter_setting['appearance']['bg']):'';
        if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
            $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
            $display = 'inline-block';
        } 

        echo '
        <div class="bg-image-uploader">
            <a href="#" class="bten_upload_image_button' . $image . '</a>
            <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
            <a href="#" class="bten_remove_image_button" style="display:inline-block;display:' . $display . '">Remove image</a>
        </div>';
        ?>
    </div>
    <div class="trip-info-meta-wrap form-bg-color">
        <div class="form-bg-color">
        <label for="blossomthemes_email_newsletter_setting[appearance][bgcolor]"><?php _e('Background Color: ','blossomthemes-email-newsletter');?></label>	
    	<input type="text" class="blossomthemes-email-newsletter-color-form" id="blossomthemes_email_newsletter_setting[appearance][bgcolor]" name="blossomthemes_email_newsletter_setting[appearance][bgcolor]" value="<?php echo isset($blossomthemes_email_newsletter_setting['appearance']['bgcolor']) ? esc_attr($blossomthemes_email_newsletter_setting['appearance']['bgcolor']):apply_filters('bt_newsletter_bg_color','#ffffff')?>">
        </div>
    </div>
</div>