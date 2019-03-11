<?php
/**
 * Mailerlite handler of the plugin.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author    blossomthemes
 */
class Blossomthemes_Email_Newsletter_Mailerlite {

	/*Function to add main mailchimp action*/
	function bten_mailerlite_action($email,$sid)
	{

		if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL) === false){
	       // mailerlite API credentials
			$blossomthemes_email_newsletter_setting = get_option( 'blossomthemes_email_newsletter_settings', true );

		    $apiKey = $blossomthemes_email_newsletter_setting['mailerlite']['api-key'];

			include BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH.'/includes/libs/mailerlite_rest/ML_Subscribers.php';

	 		$ML_Subscribers = new ML_Subscribers( $apiKey );

			$subscriber = array(
			    'email' => $email,
			    'name' => $fname,
			);
			$listids = get_post_meta($sid,'blossomthemes_email_newsletter_setting',true);
			
			if(!isset($listids['mailerlite']['list-id']))
			{
				$listid = $blossomthemes_email_newsletter_setting['mailerlite']['list-id'];
				$subscriber = $ML_Subscribers->setId( $listid )->add( $subscriber, 1 /* set resubscribe to true*/ );
				$response= '200';
			}
			else{
				foreach ($listids['mailerlite']['list-id'] as $key => $value) {
					$ML_Subscribers->setId( $key )->add( $subscriber, 1 /* set resubscribe to true*/ );
				}
				$response= '200';
			}
		}
		return $response;
	}
}