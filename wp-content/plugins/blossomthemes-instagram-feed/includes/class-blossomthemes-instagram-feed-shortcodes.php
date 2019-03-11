<?php 
/**
 *
 * Generate shortcode to show twitter feeds plugin
 *
 * @package    BlossomThemes_Instagram_Feed
 * @subpackage BlossomThemes_Instagram_Feed/includes
 * @author    blossomthemes
 */
class BlossomThemes_Instagram_Feed_Shortcodes
{
	public function init(){
		add_shortcode( 'blossomthemes_instagram_feed', array( $this, 'blossomthemes_instagram_feed_sc' ) );
	}

	function blossomthemes_instagram_feed_sc(){
		ob_start();

        $options = get_option( 'blossomthemes_instagram_feed_settings', true );
        $photos =  absint($options['photos']);
		$photo_size =  isset( $options['photo_size'] ) ? esc_attr( $options['photo_size'] ) :'low_resolution';
		$photos_row = isset( $options['photos_row'] ) ? esc_attr( $options['photos_row'] ) :'5';
		$instaUrl = 'https://www.instagram.com/';
		$instaUrl .= $options['username'];
		$profile_link_text = isset( $options['follow_me'] ) ? esc_attr( $options['follow_me'] ):'Follow Me!';

       	if ( Blossomthemes_Instagram_Feed_API::getInstance()->is_configured() ) :

	       	$this->api = Blossomthemes_Instagram_Feed_API::getInstance();

	       	$items = $this->api->get_items( $photos, $photo_size );

	       	if ( ! is_array( $items ) ) {
				echo '<b style="color:red;">'.__('No posts available!','blossomthemes-instagram-feed').'</b>';
			} else {

				$count = 0;
		        ?>
		        <ul class="popup-gallery photos-<?php echo $photos_row;?>">

		            <?php foreach ( $items['items'] as $item ) : ?>
		                <?php
						$link         = $item['link'];
						$img_standard = $item['image-standard'];
						$src          = $item['image-url'];
						$likes        = $item['likes_count'];
						$comments     = $item['comments_count'];
		                ?>

		                <li>

		                    <a href="<?php echo esc_url($img_standard); ?>">
		                        <img src="<?php echo esc_url($src); ?>">
		                    </a>
		                    <?php if( isset( $options['meta'] ) )
							{	
								echo '<div class="instagram-meta"><span class="like"><i class="fa fa-heart"></i>'.$likes.'</span>'.'<span class="comment"><i class="fa fa-comment"></i>'.$comments.'</span>'.'</div>';
							}?>
		                </li>

		                <?php if ( ++$count === $photos ) break; ?>

		            <?php endforeach; ?>

		        </ul>
				<?php echo 
		            '<script>
		            jQuery(document).ready(function($){
		                $(".popup-gallery").magnificPopup({
		                        delegate: "a",
			                      type: "image",
			                      gallery:{
			                        enabled:true
			                      }
		                    });

		                $(".popup-modal").magnificPopup({
		                    type: "inline",
		                    preloader: false,
		                    focus: "#username",
		                    modal: true
		                });
		                $(document).on("click", ".popup-modal-dismiss", function (e) {
		                    e.preventDefault();
		                    $.magnificPopup.close();
		                });
		            });
		            </script>';
		        echo ' <a class="profile-link" href="'.esc_url($instaUrl).'" target="_blank"><span class="insta-icon"><i class="fab fa-instagram"></i></span>'.esc_attr($profile_link_text).'</a>';
			}
		else:
			if ( current_user_can( 'edit_theme_options' ) ) {
            ?>
                <p>
                    <?php _e( 'BlossomThemes Instagram Feed misconfigured, check plugin &amp; widget settings.', 'blossomthemes-instagram-feed' ); ?>
                </p>
            <?php
            }
		endif;
		$output = ob_get_contents();
      	ob_end_clean();
	 	return $output;
	}
}
