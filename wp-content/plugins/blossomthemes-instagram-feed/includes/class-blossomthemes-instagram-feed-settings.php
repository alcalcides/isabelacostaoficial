<?php
/**
 * Settings section of the plugin.
 *
 * Maintain a list of functions that are used for settings purposes of the plugin
 *
 * @package    BlossomThemes_Instagram_Feed
 * @subpackage BlossomThemes_Instagram_Feed/includes
 * @author    blossomthemes
 */
class BlossomThemes_Instagram_Feed_Settings
{
    function blossomthemes_instagram_feed_backend_settings()
    {
        $oauth_url = 'https://instagram.com/oauth/authorize/?client_id=8dc488eba3d54eb9806eb27eabb8cd03&response_type=token&redirect_uri=https://blossomthemes.com/instagram/';
        // $oauth_url = 'https://instagram.com/oauth/authorize/?client_id=291228964f8746a9933b6a51c6dcb750&response_type=token&redirect_uri=http://localhost/blossom/wordpress/blossom-insta-check/';
        ?>
        <div class="btif-wrap">
            <?php settings_errors(); ?>
            <div class="btif-header">
                <h3><?php _e( 'BlossomThemes Instagram Feed', 'blossomthemes-instagram-feed' ); ?></h3>
            </div>
        <div class="btif-inner-wrap">
            <h2 class="nav-tab-wrapper">
                <a href="#" class="btss-tab-trigger nav-tab nav-tab-active" data-configuration="general"><?php _e('General','blossomthemes-instagram-feed');?></a>
                <a href="#" class="btss-tab-trigger nav-tab" data-configuration="usage"><?php _e('Usage','blossomthemes-instagram-feed');?></a>
            </h2>  
            <form method="post" action="options.php" class="btif-settings-form">
                <?php
                $options = get_option( 'blossomthemes_instagram_feed_settings', true );
                $size = isset( $options['photo_size'] ) ? esc_attr( $options['photo_size'] ):'low_resolution';
                $sizes = array(
                    'img_thumb'    => 'thumbnail',
                    'img_low'      => 'low_resolution', 
                    'img_standard' => 'standard_resolution' 
                );

                if ( array_key_exists( $size, $sizes ) ) 
                {
                    foreach ( $sizes as $key => $value ) 
                    {
                        if($size == $key)
                            $size = $value; 
                    }
                }
                ?>
                <div class="blossomthemes-instagram-feed-settings general" id="blossomthemes-instagram-feed-settings-general">

                    <div class="btif-option-field-wrap">

                        <h2><?php _e('Connect with Instagram', 'blossomthemes-instagram-feed'); ?></h2>

                        <p><?php _e( 'To get started click the button below. You’ll be prompted to authorize BlossomThemes Instagram Feed to access your Instagram photos.', 'blossomthemes-instagram-feed' ); ?>
                            <a href="https://www.youtube.com/watch?v=U4tkwdtgkt0" target="_blank"><?php _e('For help, please refer to this video tutorial.', 'blossomthemes-instagram-feed');?></a>
                        </p>

                        <p class="description"><?php _e( 'Due to recent Instagram API changes it is no longer possible to display photos from a different Instagram account than yours. The widget will automatically display the latest photos of the account which was authorized on this page.', 'blossomthemes-instagram-feed' ); ?></p>

                        <br /> 

                        <a class="button button-connect" target="_blank" href="<?php echo esc_url( $oauth_url ); ?>">
                            <?php if ( ! Blossomthemes_Instagram_Feed_API::getInstance()->is_configured() ) : ?>
                                <span><?php _e( 'Connect with Instagram', 'blossomthemes-instagram-feed' ); ?></span>
                            <?php else: ?>
                                <span class="btif-instagarm-connected"><?php _e( 'Re-connect with Instagram', 'blossomthemes-instagram-feed' ); ?></span>
                            <?php endif; ?>
                        </a>

                        <p><label for="blossomthemes_instagram_feed_settings[access-token]"><?php _e('Access Token', 'blossomthemes-instagram-feed'); ?></label>
                        <input class="" id="blossomthemes_instagram_feed_settings[access-token]" name="blossomthemes_instagram_feed_settings[access-token]" type="text" value="<?php echo isset( $options['access-token'] ) ? esc_attr( $options['access-token'] ):''; ?>"></p>

                        <p class="btif-description">
                            <?php
                                _e('Access Token is used as key to access your photos from Instagram so they can be displayed.', 'blossomthemes-instagram-feed');
                            ?>
                        </p>

                    </div>

                    <div class="btif-option-field-wrap">
                        <label for="blossomthemes_instagram_feed_settings[username]"><?php _e('Username', 'blossomthemes-instagram-feed'); ?></label>
                        <input id="blossomthemes_instagram_feed_settings[username]" name="blossomthemes_instagram_feed_settings[username]" type="text" value="<?php echo isset( $options['username'] ) ? esc_attr( $options['username'] ):''; ?>">
                    </div>

                    <div class="btif-option-field-wrap">
                        <label for="blossomthemes_instagram_feed_settings[photos]"><?php _e('Number of Photos', 'blossomthemes-instagram-feed'); ?></label>
                        <input min="1" max="20" id="blossomthemes_instagram_feed_settings[photos]" name="blossomthemes_instagram_feed_settings[photos]" type="number" value="<?php echo isset( $options['photos'] ) ? esc_attr( $options['photos'] ):'10'; ?>">
                    </div>
                    <div class="btif-option-field-wrap">
                        <label for="blossomthemes_instagram_feed_settings[photo_size]"><?php esc_html_e( 'Photo size', 'blossomthemes-instagram-feed' ); ?></label>
                        <select id="blossomthemes_instagram_feed_settings[photo_size]" name="blossomthemes_instagram_feed_settings[photo_size]">
                            <option value="thumbnail" <?php selected( 'thumbnail', $size ) ?>><?php esc_html_e( 'Thumbnail', 'blossomthemes-instagram-feed' ); ?></option>
                            <option value="low_resolution" <?php selected( 'low_resolution', $size ) ?>><?php esc_html_e( 'Small', 'blossomthemes-instagram-feed' ); ?></option>
                            <option value="standard_resolution" <?php selected( 'standard_resolution', $size ) ?>><?php esc_html_e( 'Large', 'blossomthemes-instagram-feed' ); ?></option>
                        </select>
                    </div>
                    <div class="btif-option-field-wrap">
                        <label for="blossomthemes_instagram_feed_settings[photos_row]"><?php _e('Photos Per Row', 'blossomthemes-instagram-feed'); ?></label>
                        <input min="1" max="20" id="blossomthemes_instagram_feed_settings[photos_row]" name="blossomthemes_instagram_feed_settings[photos_row]" type="number" value="<?php echo isset( $options['photos_row'] ) ? esc_attr( $options['photos_row'] ):'5'; ?>">
                    </div>
                    <div class="btif-option-field-wrap">
                        <label for="blossomthemes_instagram_feed_settings[follow_me]"><?php _e('Profile Link Text', 'blossomthemes-instagram-feed'); ?></label>
                        <input id="blossomthemes_instagram_feed_settings[follow_me]" name="blossomthemes_instagram_feed_settings[follow_me]" type="text" value="<?php echo isset( $options['follow_me'] ) ? esc_attr( $options['follow_me'] ):'Follow Me!'; ?>">
                    </div>
                    <div class="btif-option-field-wrap">
                    <label for="likes-comments"><?php _e( 'Show Likes/Comments', 'blossomthemes-instagram-feed' ); ?></label>
                            <input type="checkbox" value="1" id="likes-comments" name="blossomthemes_instagram_feed_settings[meta]" <?php
                            if ( isset( $options['meta'] ) ) {
                                checked( $options['meta'], true );
                            }
                            ?>>
                    </div>
                </div>
                <div class="blossomthemes-instagram-feed-settings usage" id="blossomthemes-instagram-feed-settings-usage">
                    <?php $custom_id = get_the_ID(); ?>
                    <h4><?php _e( 'Uses', 'blossomthemes-instagram-feed' ); ?></h4>
                    <div class="wp-tm-settings-wrapper">
                    <h4 class="wp-tm-setting-title"><?php _e('Display via Shortcode','blossomthemes-instagram-feed');?></h4>
                        <div class="wp-tm--option-wrapper">
                            <div class="wp-tm-option-field">
                                <label class="wp-tm-plain-label">
                                    <div class="tm-option-side-note"> <?php _e('Copy this Shortcode to display your instagram gallery in pages/posts => ', 'blossomthemes-instagram-feed') ?><br>
                                    <input type="text" readonly="readonly" class="shortcode-usage" value="[blossomthemes_instagram_feed]" onClick="this.setSelectionRange(0, this.value.length)">
                                    </div>
                                </label>
                            </div>
                        </div>
                    <h4 class="wp-tm-setting-title"><?php _e('Display via PHP Function','blossomthemes-instagram-feed');?></h4>
                        <div class="wp-tm--option-wrapper">
                           <div class="wp-tm-option-field">
                                 <label class="wp-tm-plain-label">
                                    <div class="tm-option-side-note"> <?php _e('Copy the PHP Function below to display your instagram gallery in templates :', 'blossomthemes-instagram-feed') ?> <br>
                                    <textarea rows="2" cols="50" name="shortcode-function" readonly="readonly" onClick="this.setSelectionRange(0, this.value.length)">&lt;?php echo do_shortcode("[blossomthemes_instagram_feed]"); ?&gt; </textarea>
                                    </div>
                                </label>

                            </div>
                        </div>
                    </div>
                </div>
                <?php $nonce = wp_create_nonce( 'blossomthemes-instagram-feed-nonce' ); ?>
                <input type="hidden" name="blossomthemes-instagram-feed-nonce" value="<?php echo $nonce; ?>">
                <div class="blossomthemes-instagram-feed-settings-submit">
                    <?php
                    settings_fields( 'blossomthemes_instagram_feed_settings' );
                    do_settings_sections( __FILE__ );
                    echo submit_button();
                    ?>
                </div>
            </form>
        </div>
            <?php include(BTIF_BASE_PATH . '/includes/template/backend/sidebar.php'); ?>
        </div>
<?php 
    }
}
new BlossomThemes_Instagram_Feed_Settings;
