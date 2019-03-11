<?php
/**
 * Settings section of the plugin.
 *
 * Maintain a list of functions that are used for settings purposes of the plugin
 *
 * @package    BlossomThemes Email Newsletters
 * @subpackage BlossomThemes_Email_Newsletters/includes
 * @author    blossomthemes
 */
class BlossomThemes_Email_Newsletter_Settings {

	function __construct()
	{
	}
	
	function mailerlite_lists()
	{
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
		if( isset( $blossomthemes_email_newsletter_settings['mailerlite']['api-key'] ) && $blossomthemes_email_newsletter_settings['mailerlite']['api-key'] !='' )
		{
			$api_key =  $blossomthemes_email_newsletter_settings['mailerlite']['api-key'];
			$key = preg_replace('/[^a-z0-9]/i', '', $api_key);
	        $ML_Lists = new ML_Lists($key);
	        $lists = $ML_Lists->getAll();
	        $data = json_decode($lists, TRUE);
	        return $data;
		}
	}

	function mailchimp_lists()
	{
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
		if( isset( $blossomthemes_email_newsletter_settings['mailchimp']['api-key'] ) && $blossomthemes_email_newsletter_settings['mailchimp']['api-key'] !='' )
		{
			$api_key =  $blossomthemes_email_newsletter_settings['mailchimp']['api-key'];
	        $MC_Lists = new MC_Lists($api_key);
	        $lists = $MC_Lists->getAll();
	        $data = json_decode($lists, TRUE);
	        return $data;
		}
	}

	function convertkit_lists($apikey = '')
	{
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
		$list = array();
		if( isset( $blossomthemes_email_newsletter_settings['convertkit']['api-key'] ) && $blossomthemes_email_newsletter_settings['convertkit']['api-key'] !='' )
		{
			
			$apikey = $blossomthemes_email_newsletter_settings['convertkit']['api-key'];
			
			$api = new Convertkit($apikey);

			try {
				$result = $api->getForms();

				if (isset($result->forms)) {
				    foreach ($result->forms as $l) {
				    	$list[$l->id] = array('name' => $l->name);
				    }
				}
			}
			catch (Exception $e) {
			    echo $e->getMessage();
	        }
		}
		return $list;
	}



	/**
	 * Get Response API
	 * 
	 */
	function getresponse_lists($apikey = '')
	{
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
		$list = array();
		if( isset( $blossomthemes_email_newsletter_settings['getresponse']['api-key'] ) && $blossomthemes_email_newsletter_settings['getresponse']['api-key'] !='' )
		{
			
			require BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/jsonRPCClient.php';
			
			$api_key = $blossomthemes_email_newsletter_settings['getresponse']['api-key']; //Place API key here
			$api_url = 'http://api2.getresponse.com';
			
			$client = new jsonRPCClient($api_url);

			try {

				$name = array();
				$result = $client->get_campaigns($api_key);

				//Get Campaigns name and id.
				foreach($result as $r){
					$name = $r['name'];
					$result2 = $client->get_campaigns(
					$api_key,
					array (
					'name' => array ( 'EQUALS' => $name )
					)
					);
					$res = array_keys($result2);
					$CAMPAIGN_IDs = array_pop($res);

					$aray = Array(
						$name => $CAMPAIGN_IDs,
					);
				}
			}
			catch (Exception $e) {
				echo $e->getMessage();
			}
		}
		return $aray;
	}

	function blossomthemes_email_newsletter_backend_settings()
	{ ?>
		<div class="wrap">
			<div class="btnb-header">
	            <h3><?php _e('BlossomThemes Email Newsletter Settings','blossomthemes-email-newsletter');?></h3>
	        </div>
	        <?php
            if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ==true )
            { ?>
                <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
                <p><strong><?php _e('Settings updated.','blossomthemes-instagram-feed');?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.','blossomthemes-instagram-feed');?></span></button>
            	</div>
            <?php
            }
            ?>
			<form method="POST" name="form1" action="options.php" id="form1" class="btemn-settings-form">
				<?php
					settings_fields( 'blossomthemes_email_newsletter_settings' );
					do_settings_sections( __FILE__ );
					$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
					$platform = $blossomthemes_email_newsletter_settings['platform'];
					$selected_page = isset($blossomthemes_email_newsletter_settings['page'])?esc_attr($blossomthemes_email_newsletter_settings['page']):'';
				?>
				<div class="blossomthemes-email-newsletter-settings-platform">
					<label for="blossomthemes_email_newsletter_settings[platform]"><?php _e('Platform : ','blossomthemes-email-newsletter');?></label>
					<select id="platform-select" name="blossomthemes_email_newsletter_settings[platform]">
					  	<option value="mailchimp" <?php selected($platform,'mailchimp');?>>Mailchimp</option>
					  	<option value="mailerlite" <?php selected($platform,'mailerlite');?>>Mailerlite</option>
					  	<option value="convertkit" <?php selected($platform,'convertkit');?>>ConvertKit</option>
					  	<option value="getresponse" <?php selected($platform,'getresponse');?>>GetReponse</option>
					</select>
					<div class="blossomthemes-email-newsletter-note"><?php _e( 'Choose your newsletter platform.', 'blossomthemes-email-newsletter' ); ?></div>	
				</div>		
				<div id="mailchimp" class="newsletter-settings<?php if( $platform == 'mailchimp' || !isset($platform) ){ echo ' current'; }?>">
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[mailchimp][api-key]"><?php _e('API Key : ','blossomthemes-email-newsletter');?></label> 
						<input type="text" id="blossomthemes_email_newsletter_settings[mailchimp][api-key]" name="blossomthemes_email_newsletter_settings[mailchimp][api-key]" value="<?php echo isset($blossomthemes_email_newsletter_settings['mailchimp']['api-key']) ? esc_attr( $blossomthemes_email_newsletter_settings['mailchimp']['api-key'] ): '';?>">
						<?php echo '<div class="blossomthemes-email-newsletter-note">'.sprintf( __( 'Get your api key %1$shere%2$s', 'blossomthemes-email-newsletter' ), '<a href="https://us15.admin.mailchimp.com/account/api/" target="_blank">','</a>' ).'</div>';?>
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[mailchimp][list-id]"><?php _e('List Id : ','blossomthemes-email-newsletter');?></label> 
							<?php
							$data = $this->mailchimp_lists();
					        ?>
					        <select name="blossomthemes_email_newsletter_settings[mailchimp][list-id]">
					        	<?php $mailchimp_list = $blossomthemes_email_newsletter_settings['mailchimp']['list-id'];
					        		  if( sizeof($data['lists']) < 1 ){ ?>
							  <option value="-"><?php _e('No Lists Found','blossomthemes-email-newsletter');?></option>
							  <?php } else{
								$max = max(array_keys($data['lists']));
						        for ($i=0; $i <= $max; $i++) { ?>
							  		<option <?php selected( $mailchimp_list, esc_attr($data['lists'][$i]['id'] ) );?> value="<?php echo esc_attr($data['lists'][$i]['id']);?>"><?php echo esc_attr($data['lists'][$i]['name']);?></option>
						        
							  <?php }} ?>
							</select>
							<div class="blossomthemes-email-newsletter-note"><?php _e( 'Choose the default list.', 'blossomthemes-email-newsletter' ); ?></div>
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[mailchimp][enable_notif]"><?php _e('Confirmation : ','blossomthemes-email-newsletter');?></label> 
						<input type="checkbox" class="enable_notif_opt" name="blossomthemes_email_newsletter_settings[mailchimp][enable_notif]" <?php $j = isset( $blossomthemes_email_newsletter_settings['mailchimp']['enable_notif'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['mailchimp']['enable_notif'] ): '0';?> id="blossomthemes_email_newsletter_settings[mailchimp][enable_notif]" value="<?php echo esc_attr($j);?>" <?php if($j=='1'){ echo "checked";}?>/>
						<div class="blossomthemes-email-newsletter-note"><?php _e( 'Check this box if you want subscribers to receive confirmation mail before they are added to list.', 'blossomthemes-email-newsletter' ); ?></div>
					</div>
				</div>
				<div id="mailerlite" class="newsletter-settings<?php if($platform == 'mailerlite'){ echo ' current'; }?>">
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[mailerlite][api-key]"><?php _e('API Key : ','blossomthemes-email-newsletter');?></label> 
						<input type="text" id="blossomthemes_email_newsletter_settings[mailerlite][api-key]" name="blossomthemes_email_newsletter_settings[mailerlite][api-key]" value="<?php echo isset($blossomthemes_email_newsletter_settings['mailerlite']['api-key']) ? esc_attr( $blossomthemes_email_newsletter_settings['mailerlite']['api-key'] ): '';?>">
						<?php echo '<div class="blossomthemes-email-newsletter-note">'.sprintf( __( 'Get your api key %1$shere%2$s', 'blossomthemes-email-newsletter' ), '<a href="https://app.mailerlite.com/subscribe/api" target="_blank">','</a>' ).'</div>';?>  
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[mailerlite][list-id]"><?php _e('List Id : ','blossomthemes-email-newsletter');?></label> 
						<?php
						$data = $this->mailerlite_lists();
				        ?>
				        <select name="blossomthemes_email_newsletter_settings[mailerlite][list-id]">
				        	<?php $mailerlite_list = $blossomthemes_email_newsletter_settings['mailerlite']['list-id'];
				        		  if( sizeof($data['Results']) < 1 ){ ?>
						  <option value="-"><?php _e('No Lists Found','blossomthemes-email-newsletter');?></option>
						  <?php } else{
							$max = max(array_keys($data['Results']));
					        for ($i=0; $i <= $max; $i++) { ?>
						  		<option <?php selected( $mailerlite_list, esc_attr($data['Results'][$i]['id'] ) );?> value="<?php echo esc_attr($data['Results'][$i]['id']);?>"><?php echo esc_attr($data['Results'][$i]['name']);?></option>
					        
						  <?php }} ?>
						</select>
					</div>
				</div>
				<div id="convertkit" class="newsletter-settings<?php if($platform == 'convertkit'){ echo ' current'; }?>">
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[convertkit][api-key]"><?php _e('API Key : ','blossomthemes-email-newsletter');?></label> 
						<input type="text" id="blossomthemes_email_newsletter_settings[convertkit][api-key]" name="blossomthemes_email_newsletter_settings[convertkit][api-key]" value="<?php echo isset($blossomthemes_email_newsletter_settings['convertkit']['api-key']) ? esc_attr( $blossomthemes_email_newsletter_settings['convertkit']['api-key'] ): '';?>">  
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[convertkit][list-id]"><?php _e('List Id : ','blossomthemes-email-newsletter');?></label> 
							<?php
							$data = $this->convertkit_lists();
					        ?>
					    <select name="blossomthemes_email_newsletter_settings[convertkit][list-id]">
				        	<?php $convertkit_list = $blossomthemes_email_newsletter_settings['convertkit']['list-id'];
				        		  if( sizeof($data) < 1 ){ ?>
						  	<option value="-"><?php _e('No Lists Found','blossomthemes-email-newsletter');?></option>
						  	<?php } else{
					        foreach ($data as $key => $value) {
					 			?>
						  		<option <?php selected( $convertkit_list, esc_attr($key) );?> value="<?php echo esc_attr($key);?>"><?php echo esc_attr($value['name']);?></option>
						  	<?php }} ?>
						</select>
					</div>
				</div>

				<div id="getresponse" class="newsletter-settings<?php if($platform == 'getresponse'){ echo ' current'; }?>">
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[getresponse][api-key]"><?php _e('API Key : ','blossomthemes-email-newsletter');?></label> 
						<input type="text" id="blossomthemes_email_newsletter_settings[getresponse][api-key]" name="blossomthemes_email_newsletter_settings[getresponse][api-key]" value="<?php echo isset($blossomthemes_email_newsletter_settings['getresponse']['api-key']) ? esc_attr( $blossomthemes_email_newsletter_settings['getresponse']['api-key'] ): '';?>">  
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[getresponse][list-id]"><?php _e('List Id : ','blossomthemes-email-newsletter');?></label> 
					    <select name="blossomthemes_email_newsletter_settings[getresponse][list-id]">
				        	<?php 
				        	$getresponse_list = $blossomthemes_email_newsletter_settings['getresponse']['list-id'];
				        	$data = $this->getresponse_lists();
				        	if( sizeof($data) < 1 ){ ?>
						  	<option value="-"><?php _e('No Lists Found','blossomthemes-email-newsletter');?></option>
						  	<?php } else{ ?>
							<option value=""><?php _e('Choose Campaign ID','blossomthemes-email-newsletter');?></option>
							<?php
					        foreach ($data as $key => $value) {
					 			?>
						  		<option <?php selected( $getresponse_list, esc_attr($value) );?> value="<?php echo esc_attr($value);?>"><?php echo esc_attr($key);?></option>
						  	<?php }} ?>
						</select>
					</div>
				</div>

				<div class="success-msg-option">
				<label><?php _e('Display Successful Subscription Message From:','blossomthemes-email-newsletter');?></label> 
				<?php
					$option = isset($blossomthemes_email_newsletter_settings['thankyou-option']) ? esc_attr($blossomthemes_email_newsletter_settings['thankyou-option']):'text';?><br>
    				<label><?php _e('Popup text','blossomthemes-email-newsletter');?><input class="newsletter-success-option" type="radio" name="blossomthemes_email_newsletter_settings[thankyou-option]" value="text" <?php if( $option == 'text' ) echo 'checked'; ?>></label>
    				<label><?php _e('Page','blossomthemes-email-newsletter');?><input class="newsletter-success-option" type="radio" name="blossomthemes_email_newsletter_settings[thankyou-option]" value="page" <?php if( $option == 'page' ) echo 'checked'; ?>></label>
    				<div class="blossomthemes-email-newsletter-note"><?php _e( 'Set how to show the confirmation message to the subscribers after successful subscription.', 'blossomthemes-email-newsletter' ); ?></div>
				</div>
				<div class="blossomthemes-email-newsletter-settings-wrap message">
					<label for="blossomthemes_email_newsletter_settings[msg]"><?php _e('Success Message : ','blossomthemes-email-newsletter');?></label>
					<textarea name="blossomthemes_email_newsletter_settings[msg]" id="blossomthemes_email_newsletter_settings[msg]"><?php echo isset($blossomthemes_email_newsletter_settings['msg']) ? esc_attr( $blossomthemes_email_newsletter_settings['msg']): 'Successfully subscribed.';?></textarea>
					<div class="blossomthemes-email-newsletter-note"><?php _e( 'Set what message to show when the subscriber is successfully subscribed.', 'blossomthemes-email-newsletter' ); ?></div>	
				</div>
				<div class="blossomthemes-email-newsletter-settings-wrap page">
					<label for="blossomthemes_email_newsletter_settings[page]"><?php _e('Thank You Page : ','blossomthemes-email-newsletter');?></label>
					<select name="blossomthemes_email_newsletter_settings[page]"> 
					    <option selected="selected" disabled="disabled" value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option> 
					    <?php
					        $pages = get_pages(); 
					        foreach ( $pages as $page ) {
					            $option = '<option value="' . $page->ID . '" ';
					            $option .= ( $page->ID == $selected_page ) ? 'selected="selected"' : '';
					            $option .= '>';
					            $option .= $page->post_title;
					            $option .= '</option>';
					            echo $option;
					        }
					    ?>
					</select>
					<div class="blossomthemes-email-newsletter-note"><?php _e( 'Set the page user will be redirected to after successful subscription.', 'blossomthemes-email-newsletter' ); ?></div>
				</div>
				<div class="blossomthemes-email-newsletter-settings-wrap message">
					<label for="blossomthemes_email_newsletter_settings[gdpr-msg]"><?php _e('GDPR Message : ','blossomthemes-email-newsletter');?></label>
					<textarea name="blossomthemes_email_newsletter_settings[gdpr-msg]" id="blossomthemes_email_newsletter_settings[gdpr-msg]"><?php echo isset($blossomthemes_email_newsletter_settings['gdpr-msg']) ? $blossomthemes_email_newsletter_settings['gdpr-msg']: 'By checking this, you agree to our Privacy Policy.';?></textarea>
					<div class="blossomthemes-email-newsletter-note"><?php _e( 'Set GDPR message to show on the subscription form.', 'blossomthemes-email-newsletter' ); ?></div>	
				</div>	
				<div class="blossomthemes_email_newsletter_settings-settings-submit">
			        <?php echo submit_button();?>
			    </div>
			</form>
		</div>	
	<?php
	}
}
new BlossomThemes_Email_Newsletter_Settings;