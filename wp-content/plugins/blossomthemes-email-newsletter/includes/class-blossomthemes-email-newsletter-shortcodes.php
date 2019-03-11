<?php
/**
 * Frontend view of the plugin.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author    blossomthemes
 */
class Blossomthemes_Email_Newsletter_Shortcodes {
	
	function __construct()
	{
    	add_shortcode( 'BTEN', array( $this, 'blossomthemes_email_newsletter_shortcode_callback' ) );
		add_action( 'wp_ajax_subscription_response', array( $this, 'blossomthemes_email_newsletter_ajax_callback' ) );
		add_action( 'wp_ajax_nopriv_subscription_response', array( $this, 'blossomthemes_email_newsletter_ajax_callback' ) );
	}

	//function to generate shortcode
	function blossomthemes_email_newsletter_shortcode_callback( $atts, $content = "" )
	{ 
        $obj = new Blossomthemes_Email_Newsletter_Functions;

        $atts = shortcode_atts( array(
          'id' => '',
          ), $atts, 'BTEN' );
        $atts['id'] = absint($atts['id']);
            $rrsb_bg='';
            $blossomthemes_email_newsletter_setting = get_post_meta( $atts['id'], 'blossomthemes_email_newsletter_setting', true );
            $rrsb_option = ! empty( $blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option'] ) ? sanitize_text_field( $blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option'] ) : 'bg-color';
            if( $rrsb_option == 'image' )
            {
                if( isset( $blossomthemes_email_newsletter_setting['appearance']['bg']) &&  $blossomthemes_email_newsletter_setting['appearance']['bg']!='' )
                {
                    $attachment_id = $blossomthemes_email_newsletter_setting['appearance']['bg'];
                    $newsletter_bio_img_size = apply_filters('bt_newsletter_img_size','medium');
                    $image_array   = wp_get_attachment_image_src( $attachment_id, $newsletter_bio_img_size );
                    $rrsb_bg = 'url('.$image_array[0].') no-repeat';
                }
            }
            else{
                if( isset( $blossomthemes_email_newsletter_setting['appearance']['bgcolor'] ) &&  $blossomthemes_email_newsletter_setting['appearance']['bgcolor']!='' )
                {
                   $rrsb_bg = sanitize_text_field( $blossomthemes_email_newsletter_setting['appearance']['bgcolor']); 
                }
                else{
                    $rrsb_bg = apply_filters('bt_newsletter_bg_color','#ffffff'); 
                }
            }

                ob_start();
                ?>
                <div class="blossomthemes-email-newsletter-wrapper<?php if(isset($blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option']) && $blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option'] == 'image'){ echo ' bg-img'; }?>" id="boxes-<?php echo esc_attr($atts['id']);?>" style="background: <?php echo esc_attr($rrsb_bg);?>">
                    <div class="text-holder" >
                        <?php if( get_the_title( $atts['id'] ) ) { $title = get_the_title( $atts['id'] ); echo '<h3>'.esc_attr($title).'</h3>'; }?>
                        <?php
                        if( isset($blossomthemes_email_newsletter_setting['appearance']['note']) && $blossomthemes_email_newsletter_setting['appearance']['note']!='' )
                        {
                            $note = $blossomthemes_email_newsletter_setting['appearance']['note'];
                            echo '<span>'.esc_attr($note).'</span>';
                        }
                        ?>
                    </div>
                    <form id="blossomthemes-email-newsletter-<?php echo esc_attr($atts['id']);?>" class="blossomthemes-email-newsletter-window-<?php echo esc_attr($atts['id']);?>">
                        <?php
                        $val = isset($blossomthemes_email_newsletter_setting['field']['select']) ? esc_attr($blossomthemes_email_newsletter_setting['field']['select']):'email';
                        if( $val=='email' )
                        { 
                            ?>
                            <input type="text" name="subscribe-email" class="subscribe-email-<?php echo esc_attr($atts['id']);?>" value="" placeholder="<?php echo isset($blossomthemes_email_newsletter_setting['field']['email_placeholder']) ? esc_attr($blossomthemes_email_newsletter_setting['field']['email_placeholder']):'Your Email';?>">
                        <?php
                        }
                        else{ ?>
                            <input type="text" name="subscribe-fname" required="required" class="subscribe-fname-<?php echo esc_attr($atts['id']);?>" value="" placeholder="<?php echo isset($blossomthemes_email_newsletter_setting['field']['first_name_placeholder']) ? esc_attr($blossomthemes_email_newsletter_setting['field']['first_name_placeholder']):'Your Name';?>">
                            <input type="text" name="subscribe-email" required="required" class="subscribe-email-<?php echo esc_attr($atts['id']);?>" value="" placeholder="<?php echo isset($blossomthemes_email_newsletter_setting['field']['email_placeholder']) ? esc_attr($blossomthemes_email_newsletter_setting['field']['email_placeholder']):'Your Email';?>">
                        <?php
                        }
                        if( isset( $blossomthemes_email_newsletter_setting['appearance']['gdpr'] ) && $blossomthemes_email_newsletter_setting['appearance']['gdpr'] == '1' )
                        {
                        ?>
                        <label for="subscribe-confirmation-<?php echo esc_attr($atts['id']);?>">
                            <div class="subscribe-inner-wrap">
                                <input type="checkbox" class="subscribe-confirmation-<?php echo esc_attr($atts['id']);?>" name="subscribe-confirmation" id="subscribe-confirmation-<?php echo esc_attr($atts['id']);?>" required/><span class="check-mark"></span>
                                <span class="text">
                                    <?php
                                    $blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
                                    $gdprmsg = isset($blossomthemes_email_newsletter_settings['gdpr-msg']) ? $blossomthemes_email_newsletter_settings['gdpr-msg']: 'By checking this, you agree to our Privacy Policy.';
                                    echo $gdprmsg;
                                    ?>
                                </span>
                            </div>
                        </label>
                        <?php
                        }
                        ?>
                        <div id="loader-<?php echo esc_attr($atts['id']);?>" style="display: none">
                            <div class="table">
                                <div class="table-row">
                                    <div class="table-cell">
                                        <i class="fas fa-spinner" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="submit" name="subscribe-submit" class="subscribe-submit-<?php echo esc_attr($atts['id']);?>" value="<?php echo isset($blossomthemes_email_newsletter_setting['field']['submit_label']) ? esc_attr($blossomthemes_email_newsletter_setting['field']['submit_label']):'Subscribe';?>">
                        <div class="bten-response" id="bten-response-<?php echo esc_attr($atts['id']);?>"></div>
                    </form>
                    <div id="mask-<?php echo esc_attr($atts['id']);?>"></div>
                </div>
                <?php
                global $post;
                $bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true ); 
                        $style = '<style>
                            #mask-'.$atts['id'].' {
                              position: fixed;
                              width: 100%;
                              height: 100%;
                              left: 0;
                              top: 0;
                              z-index: 9000;
                              background-color: #000;
                              display: none;
                            }

                            #boxes-'.$atts['id'].' #dialog {
                              width: 750px;
                              height: 300px;
                              padding: 10px;
                              background-color: #ffffff;
                              font-family: "Segoe UI Light", sans-serif;
                              font-size: 15pt;
                            }

                            
                            #loader-'.$atts['id'].' {
                                position: absolute;
                                top: 27%;
                                left: 0;
                                width: 100%;
                                height: 80%;
                                text-align: center;
                                font-size: 50px;
                            }

                            #loader-'.$atts['id'].' .table{
                                display: table;
                                width: 100%;
                                height: 100%;
                            }

                            #loader-'.$atts['id'].' .table-row{
                                display: table-row;
                            }

                            #loader-'.$atts['id'].' .table-cell{
                                display: table-cell;
                                vertical-align: middle;
                            }
                        </style>';
                        echo $obj->bten_minify_css($style);
                        // echo $style;

                        $ajax =
                            '<script>
                            jQuery(document).ready(function() { 
                                jQuery(document).on("submit","form#blossomthemes-email-newsletter-'.$atts['id'].'", function(e){
                                e.preventDefault();
                                jQuery(".subscribe-submit-'.$atts['id'].'").attr("disabled", "disabled" );
                                var email = jQuery(".subscribe-email-'.$atts['id'].'").val();
                                var fname = jQuery(".subscribe-fname-'.$atts['id'].'").val();
                                var confirmation = jQuery(".subscribe-confirmation-'.$atts['id'].'").val();
                                var sid = '.$atts['id'].';
                                    jQuery.ajax({
                                        type : "post",
                                        dataType : "json",
                                        url : bten_ajax_data.ajaxurl,
                                        data : {action: "subscription_response", email : email, fname : fname, sid : sid, confirmation : confirmation},
                                        beforeSend: function(){
                                            jQuery("#loader-'.$atts['id'].'").fadeIn(500);
                                        },
                                        success: function(response){
                                            jQuery(".subscribe-submit-'.$atts['id'].'").attr("disabled", "disabled" );';
                                        $bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true ); 
                                        $option = isset($bten_settings['thankyou-option']) ? esc_attr($bten_settings['thankyou-option']):'text';
                                        $ajax .='if(response.type === "success") {';
                                        if($option == 'text')
                                        {
                                            $ajax .= 'jQuery("#bten-response-'.$atts['id'].'").html(response.message).fadeIn("slow").delay("3000").fadeOut("3000",function(){
                                                    jQuery(".subscribe-submit-'.$atts['id'].'").removeAttr("disabled", "disabled" );
                                                    jQuery("form#blossomthemes-email-newsletter-'.$atts['id'].'").find("input[type=text]").val("");
                                                });';
                                        }
                                        else{
                                            $selected_page = isset($bten_settings['page'])?esc_attr($bten_settings['page']):'';
                                            $url = get_permalink($selected_page);
                                            $ajax.= 'window.location.href = "'.$url.'"';
                                        }

                                        $ajax.='}
                                        else{
                                            jQuery("#bten-response-'.$atts['id'].'").html(response.message).fadeIn("slow").delay("3000").fadeOut("3000",function(){
                                                    jQuery(".subscribe-submit-'.$atts['id'].'").removeAttr("disabled", "disabled" );
                                                    jQuery("form#blossomthemes-email-newsletter-'.$atts['id'].'").find("input[type=text]").val("");
                                                    jQuery("form#blossomthemes-email-newsletter-'.$atts['id'].'").find("input[type=checkbox]").prop("checked", false); 

                                                });
                                            }
                                        },
                                        complete: function(){
                                            jQuery("#loader-'.$atts['id'].'").fadeOut(500);             
                                        } 
                                    });  
                                });
                            });
                            </script>';
            echo $obj->bten_minify_js($ajax);
		 	$output = ob_get_contents();
	      	ob_end_clean();
	        return apply_filters( 'blossomthemes_newsletter_shortcode_filter', $output, $atts );
	}

	//function to generate ajax actions
	function blossomthemes_email_newsletter_ajax_callback()
	{
		$email = sanitize_email( $_POST['email'] );
		$fname = isset( $_POST['fname'] )?esc_attr( $_POST['fname'] ):' ';
		$lname = ' ';
		
		$arr['subscriber']['email'] = $email;
		$sid = intval( $_POST['sid'] );
		$to = $email;

		if ( !is_email($to) ) {
			$result['type'] = 'error';
			$result['message'] = __( 'Please enter valid email.', 'blossomthemes-email-newsletter' );
			echo json_encode( $result );
			exit;
		}
	    $subject = 'Subscribe To Newsletter';
	    $admin_email = get_option('admin_email');
	    if($admin_email!='')
	    {
	    	$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
			$platform = $blossomthemes_email_newsletter_settings['platform'];

		    if( $platform == 'mailerlite' )
			{
		    	$obj = new Blossomthemes_Email_Newsletter_Mailerlite;
		    	$response = $obj->bten_mailerlite_action($email,$sid);
		    	if( $response == 200 )
		    	{
					$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true ); 
					if( $bten_settings['mailerlite']['enable_notif'] =='1'){
	        			$result['type'] = 'success';
						$result['message'] = __( 'Please check your email for confirmation.', 'blossomthemes-email-newsletter' );
						echo json_encode( $result );
						exit; 
	        		}
	        		else{
			    		$result['type'] = 'success';
						$result['message'] = isset($bten_settings['msg']) ? $bten_settings['msg']: 'Successfully subscribed.';
						echo json_encode( $result );
						exit;
					}
		    	}

		    	else{

		    		$result['type'] = 'error';
					$result['message'] = __( 'Error in subscription.', 'blossomthemes-email-newsletter' );
					echo json_encode( $result );
					exit;
		    	}
		    }

		    elseif( $platform == 'mailchimp' )
			{
		    	$obj = new Blossomthemes_Email_Newsletter_Mailchimp;
		    	$response = $obj->bten_mailchimp_action($email,$sid,$fname);
		    
		    	if( $response == 200 )
		    	{
					$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true ); 
					if( isset($bten_settings['mailchimp']['enable_notif']) && $blossomthemes_email_newsletter_settings['mailchimp']['enable_notif'] =='1'){
	        			$result['type'] = 'success';
						$result['message'] = __( 'Please check your email for confirmation.', 'blossomthemes-email-newsletter' );
						echo json_encode( $result );
						exit; 
	        		}
	        		else{
			    		$result['type'] = 'success';
						$result['message'] = isset($bten_settings['msg']) ? $bten_settings['msg']: 'Successfully subscribed.';
						echo json_encode( $result );
						exit;
					}
		    	}

		    	else{
		    		$result['type'] = 'error';
					$result['message'] = __( 'Error in subscription.', 'blossomthemes-email-newsletter' );
					echo json_encode( $result );
					exit;
		    	}
		    }

		    elseif( $platform == 'convertkit' )
			{
		    	$obj = new Blossomthemes_Email_Newsletter_Convertkit;
				$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true ); 
		    	$response = $obj->bten_convertkit_action($email,$sid,$fname,$lname);
		    	if( $response == 200 )
		    	{
					$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true ); 
		    		$result['type'] = 'success';
					$result['message'] = isset($bten_settings['msg']) ? $bten_settings['msg']: 'Successfully subscribed.';
					echo json_encode( $result );
					exit;
		    	}

		    	else{
		    		$result['type'] = 'error';
					$result['message'] = __( 'Error in subscription.', 'blossomthemes-email-newsletter' );
					echo json_encode( $result );
					exit;
		    	}
		    }

            elseif( $platform == 'getresponse' )
            {
                require BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/jsonRPCClient.php';
                $api_key = $blossomthemes_email_newsletter_settings['getresponse']['api-key']; //Place API key here
                $api_url = 'http://api2.getresponse.com';
                $client = new jsonRPCClient($api_url);
                
                try{

                    $blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true ); 
            
                    $apiKey = $blossomthemes_email_newsletter_settings['getresponse']['api-key'];
                    
                    $listids = get_post_meta($sid,'blossomthemes_email_newsletter_setting',true);

                    if(!isset($listids['getresponse']['list-id']))
                    {
                        $listids = $blossomthemes_email_newsletter_settings['getresponse']['list-id'];
                        $result_contact = $client->add_contact(
                            $api_key,
                            array (
                            'campaign' => $listids,
                            'name'     => $fname,
                            'email'    => $email
                            )
                        );
                    }
                    else{
                        foreach ($listids['getresponse']['list-id'] as $key => $value) {
                            $result_contact = $client->add_contact(
                                $api_key,
                                array (
                                'campaign' => $key,
                                'name'     => $fname,
                                'email'    => $email
                                )
                            );
                        }
                    }
                    $result['type'] = 'success';
                    $result['message'] = isset($bten_settings['msg']) ? $bten_settings['msg']: 'Successfully subscribed.';
                    echo json_encode( $result );
                    exit;
                }

                catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
            else{
                $result['type'] = 'error';
                $result['message'] = isset($bten_settings['msg']) ? $bten_settings['msg']: 'Error in subscription. Please check the platform and API key used in the Settings.';
                echo json_encode( $result );
                exit;
            }
		}	
	}
}
new Blossomthemes_Email_Newsletter_Shortcodes;