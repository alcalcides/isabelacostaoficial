<?php
	$blossomthemes_email_newsletter_setting = get_option( 'blossomthemes_email_newsletter_settings', true );
	$BlossomThemes_Email_Newsletters_lists = get_post_meta( get_the_ID(),'blossomthemes_email_newsletter_setting', true );
	if(isset($blossomthemes_email_newsletter_setting['platform']) && $blossomthemes_email_newsletter_setting['platform']!='')
	{
		$platform = $blossomthemes_email_newsletter_setting['platform'];
		$obj = new BlossomThemes_Email_Newsletter_Settings;
		if ($platform == 'mailerlite' && isset($blossomthemes_email_newsletter_setting['mailerlite']['api-key']) && $blossomthemes_email_newsletter_setting['mailerlite']['api-key']!='')
		{  
			$data = $obj->mailerlite_lists();
			if( isset( $data['Results'] ) )
			{
				$max = max(array_keys($data['Results']));
		        for ($i=0; $i <= $max; $i++) { 
		        	$id = $data['Results'][$i]['id']?>
			  		<input <?php $j = isset( $BlossomThemes_Email_Newsletters_lists['mailerlite']['list-id'][$id] )  ? esc_attr( $BlossomThemes_Email_Newsletters_lists['mailerlite']['list-id'][$id] ): '0';?> value="<?php echo esc_attr($j);?>" <?php if($j=='1'){ echo "checked";}?> class="mailerlite-lists" type="checkbox" id="blossomthemes_email_newsletter_setting[mailerlite][list-id][<?php echo $id;?>]" name="blossomthemes_email_newsletter_setting[mailerlite][list-id][<?php echo $id;?>]"><label for="blossomthemes_email_newsletter_setting[mailerlite][list-id][<?php echo $id;?>]"><?php echo esc_attr($data['Results'][$i]['name']);?></label>
		  	<?php
		  		}
		  	}
		  	?>
		  	<div class="blossomthemes-email-newsletter-note"><?php _e( 'Users will be subscribed to the groups selected above. If no groups are selected then the group selected in the plugin settings page will be used as a default group.', 'blossomthemes-email-newsletter' ); ?></div>
		<?php
		}

		if ($platform == 'mailchimp' && isset($blossomthemes_email_newsletter_setting['mailchimp']['api-key']) && $blossomthemes_email_newsletter_setting['mailchimp']['api-key']!='' )
		{  
			$data = $obj->mailchimp_lists();
			$max = max(array_keys($data['lists']));
	        for ($i=0; $i <= $max; $i++) { 
	        	$id = $data['lists'][$i]['id']?>
		  		<input <?php $j = isset( $BlossomThemes_Email_Newsletters_lists['mailchimp']['list-id'][$id] )  ? esc_attr( $BlossomThemes_Email_Newsletters_lists['mailchimp']['list-id'][$id] ): '0';?> value="<?php echo esc_attr($j);?>" <?php if($j=='1'){ echo "checked";}?> class="mailerlite-lists" type="checkbox" id="blossomthemes_email_newsletter_setting[mailchimp][list-id][<?php echo $id;?>]" name="blossomthemes_email_newsletter_setting[mailchimp][list-id][<?php echo $id;?>]"><label for="blossomthemes_email_newsletter_setting[mailchimp][list-id][<?php echo $id;?>]"><?php echo esc_attr($data['lists'][$i]['name']);?></label>
		  	<?php
		  	}
		  	?>
		  	<div class="blossomthemes-email-newsletter-note"><?php _e( 'Users will be subscribed to the groups selected above. If no groups are selected then the group selected in the plugin settings page will be used as a default group.', 'blossomthemes-email-newsletter' ); ?></div>
		<?php
		}

		if ($platform == 'convertkit' && isset($blossomthemes_email_newsletter_setting['convertkit']['api-key']) && $blossomthemes_email_newsletter_setting['convertkit']['api-key']!='')
		{  
			$data = $obj->convertkit_lists();
	        foreach ($data as $key => $value){
	        	$id = $key;?>
		  		<input <?php $j = isset( $BlossomThemes_Email_Newsletters_lists['convertkit']['list-id'][$id] )  ? esc_attr( $BlossomThemes_Email_Newsletters_lists['convertkit']['list-id'][$id] ): '0';?> value="<?php echo esc_attr($j);?>" <?php if($j=='1'){ echo "checked";}?> class="mailerlite-lists" type="checkbox" id="blossomthemes_email_newsletter_setting[convertkit][list-id][<?php echo $id;?>]" name="blossomthemes_email_newsletter_setting[convertkit][list-id][<?php echo $id;?>]"><label for="blossomthemes_email_newsletter_setting[convertkit][list-id][<?php echo $id;?>]"><?php echo esc_attr($value['name']);?></label>
		  	<?php
		  	}
		  	?>
		  	<div class="blossomthemes-email-newsletter-note"><?php _e( 'Users will be subscribed to the groups selected above. If no groups are selected then the group selected in the plugin settings page will be used as a default group.', 'blossomthemes-email-newsletter' ); ?></div>
		<?php
		}

		if ($platform == 'getresponse' && isset($blossomthemes_email_newsletter_setting['getresponse']['api-key']) && $blossomthemes_email_newsletter_setting['getresponse']['api-key']!='')
		{  
			$data = $obj->getresponse_lists();
			$i = 0;
	        foreach ($data as $key => $value){
	        	$id = $value;?>
		  		<input <?php $j = isset( $BlossomThemes_Email_Newsletters_lists['getresponse']['list-id'][$id] )  ? esc_attr( $BlossomThemes_Email_Newsletters_lists['getresponse']['list-id'][$id] ): '';?> value="1" class="getresponse-lists" type="checkbox" id="blossomthemes_email_newsletter_setting[getresponse][list-id][<?php echo $id;?>]" name="blossomthemes_email_newsletter_setting[getresponse][list-id][<?php echo $id;?>]" <?php echo checked($j,1);?>><label for="blossomthemes_email_newsletter_setting[getresponse][list-id][<?php echo $id;?>]"><?php echo esc_attr($key);?></label>
		  	<?php
		  	$i++;
		  	}
		  	?>
		  	<div class="blossomthemes-email-newsletter-note"><?php _e( 'Users will be subscribed to the groups selected above. If no groups are selected then the group selected in the plugin settings page will be used as a default group.', 'blossomthemes-email-newsletter' ); ?></div>
		<?php
		}
	}?>
	<div class="blossomthemes-email-newsletter-note"><?php echo sprintf( __('Please put your valid API key for the respective platform in %1$s BlossomThemes Email Newsletter > Settings > API Key %2$s if no groups/lists are displayed here.', 'blossomthemes-email-newsletter' ),'<b>','</b>'); ?></div>