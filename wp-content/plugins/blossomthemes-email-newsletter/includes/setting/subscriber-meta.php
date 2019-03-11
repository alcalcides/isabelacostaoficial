<?php
global $post;
$blossomthemes_email_newsletter_setting = get_post_meta( $post->ID, 'blossomthemes_email_newsletter_setting', true );
?>
	<label for="blossomthemes_email_newsletter_setting[subscriber][email]"><?php _e('Email: ','blossomthemes-email-newsletter');?></label>	
	<input type="text" id="blossomthemes_email_newsletter_setting[subscriber][email]" name="blossomthemes_email_newsletter_setting[subscriber][email]" value="<?php echo isset($blossomthemes_email_newsletter_setting['subscriber']['email']) ? esc_attr($blossomthemes_email_newsletter_setting['subscriber']['email']):'';?>"><br/>

	<label for="blossomthemes_email_newsletter_setting[subscriber][fname]"><?php _e('First Name: ','blossomthemes-email-newsletter');?></label>	
	<input type="text" id="blossomthemes_email_newsletter_setting[subscriber][fname]" name="blossomthemes_email_newsletter_setting[subscriber][fname]" value="<?php echo isset($blossomthemes_email_newsletter_setting['subscriber']['fname']) ? esc_attr($blossomthemes_email_newsletter_setting['subscriber']['fname']):''?>"><br/>

    <label for="blossomthemes_email_newsletter_setting[subscriber][lname]"><?php _e('Last Name: ','blossomthemes-email-newsletter');?></label>    
    <input type="text" id="blossomthemes_email_newsletter_setting[subscriber][lname]" name="blossomthemes_email_newsletter_setting[subscriber][lname]" value="<?php echo isset($blossomthemes_email_newsletter_setting['subscriber']['lname']) ? esc_attr($blossomthemes_email_newsletter_setting['subscriber']['lname']):''?>"><br/>

    <label for="blossomthemes_email_newsletter_setting[subscriber][platform]"><?php _e('Platform: ','blossomthemes-email-newsletter');?></label>    
    <input type="text" id="blossomthemes_email_newsletter_setting[subscriber][platform]" name="blossomthemes_email_newsletter_setting[subscriber][platform]" value="<?php echo isset($blossomthemes_email_newsletter_setting['subscriber']['platform']) ? esc_attr($blossomthemes_email_newsletter_setting['subscriber']['platform']):''?>"><br/>


    <label for="blossomthemes_email_newsletter_setting[subscriber][status]"><?php _e('Status: ','blossomthemes-email-newsletter');?></label>    
    <input type="text" id="blossomthemes_email_newsletter_setting[subscriber][status]" name="blossomthemes_email_newsletter_setting[subscriber][status]" value="<?php echo isset($blossomthemes_email_newsletter_setting['subscriber']['status']) ? esc_attr($blossomthemes_email_newsletter_setting['subscriber']['status']):''?>"><br/>
