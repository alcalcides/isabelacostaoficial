<?php
/**
 * Widget Instagram
 *
 * @package BlossomThemes
 */

// register BlossomThemes_Instagram_Widget widget
function btif_register_instagram_widget() {
    register_widget( 'BlossomThemes_Instagram_Widget' );
}
add_action( 'widgets_init', 'btif_register_instagram_widget' );
 
/**
 * Adds BlossomThemes_Instagram_Widget widget.
 */
class BlossomThemes_Instagram_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'btif_instagram_widget', // Base ID
            __( 'BlossomThemes: Instagram', 'blossomthemes-instagram-feed' ), // Name
            array( 'description' => __( 'A Instagram Widget that displays your latest Instagram photos.', 'blossomthemes-instagram-feed' ), ) // Args
        );
    }
    
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */   
    function widget( $args, $instance ) {

        $title              = empty( $instance['title'] ) ? '' : $instance['title'];
        $limit              = empty( $instance['number'] ) ? 6 : $instance['number'];
        $per_row            = empty( $instance['per_row'] ) ? 5 : $instance['per_row'];
        $options            = get_option( 'blossomthemes_instagram_feed_settings', true );
        $username           = empty( $instance['username'] ) ? $options['username'] : $instance['username'];
        $profile_link       = 'https://www.instagram.com/'.$username ;
        $profile_link_text  = 'Follow Me!';

        $size               = empty( $instance['size'] ) ? 'low_resolution' : $instance['size'];
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

        if( isset( $instance['profile_link_text'] ) && $instance['profile_link_text']!='' )
        {
            $profile_link_text = $instance['profile_link_text'];
        } 
        $meta = isset( $instance['meta'] ) ? 'true' : 'false';

        echo $args['before_widget'];

        if ( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];

        ob_start();

        if ( Blossomthemes_Instagram_Feed_API::getInstance()->is_configured() ) {
             
            $ran = rand(1,100); $ran++;

            $this->api = Blossomthemes_Instagram_Feed_API::getInstance();
            $items = $this->api->get_items( $limit, $size );

            add_filter('widget_text','do_shortcode');

            if ( ! is_array( $items ) ) {
                echo '<b style="color:red;">'.__('No posts available!','blossomthemes-instagram-feed').'</b>';
            } else {
                $count = 0;
                echo '<ul class="popup-gallery-'.$ran.' photos-'.$per_row.'">';

                    foreach ( $items['items'] as $item ) 
                    {
                        $link         = $item['link'];
                        $img_standard = $item['image-standard'];
                        $src          = $item['image-url'];
                        $likes        = $item['likes_count'];
                        $comments     = $item['comments_count'];

                        echo '<li><a href="'.esc_url($img_standard).'"><img src="'.esc_url($src).'"></a>';

                        if( isset( $meta ) && $meta == 'true' )
                        {
                            echo '<div class="instagram-meta"><span class="like"><i class="fa fa-heart"></i>'.$likes.'</span>'.'<span class="comment"><i class="fa fa-comment"></i>'.$comments.'</span>'.'</div>';    
                        }
                        echo '</li>';

                        if ( ++$count === $limit ) break;
                    }

                echo '</ul>';
                echo 
                '<script>
                jQuery(document).ready(function($){
                    $(".popup-gallery-'.$ran.'").magnificPopup({
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
                echo '<a class="profile-link" href="'.esc_url($profile_link).'" target="_blank">'.esc_attr($profile_link_text).'</a>';
            }                        
        }
        else{
            if ( current_user_can( 'edit_theme_options' ) ) {
            ?>
                <p>
                    <?php _e( 'BlossomThemes Instagram Feed Widget misconfigured, check plugin &amp; widget settings.', 'blossomthemes-instagram-feed' ); ?>
                </p>
            <?php
            }
        }
        $output = ob_get_clean();
        echo $output;
        echo $args['after_widget'];
    }
    
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    function form( $instance ) {
        $default = array( 
            'title'         => __( 'Instagram', 'blossomthemes-instagram-feed' ), 
            'number'        => 6, 
            'size'          => 'low_resolution',
            'per_row'       => 5 
        );
        $instance = wp_parse_args( (array) $instance, $default );
        $options  = get_option( 'blossomthemes_instagram_feed_settings', true );
        $username           = empty( $instance['username'] ) ? $options['username'] : $instance['username'];
        $title              = empty( $instance['title'] ) ? '' : $instance['title'];
        $limit              = empty( $instance['number'] ) ? 6 : $instance['number'];
        $per_row            = empty( $instance['per_row'] ) ? 5 : $instance['per_row'];
        $profile_link       = 'https://www.instagram.com/'.$username;
        $profile_link_text  = 'Follow Me!';
        if( isset( $instance['profile_link_text'] ) && $instance['profile_link_text']!='' )
        {
            $profile_link_text = $instance['profile_link_text'];
        }

        $meta               = !isset( $instance['meta'] ) ? '' : $instance['meta'];

        $size               = empty( $instance['size'] ) ? 'low_resolution' : $instance['size'];
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

        <?php if ( ! Blossomthemes_Instagram_Feed_API::getInstance()->is_configured() ) : ?>

            <p><b style="color: red;">
                <?php
                printf( __( 'Please configure <a href="%1$s">Plugin Settings</a> before using this widget.', 'blossomthemes-instagram-feed' ),
                    admin_url( 'admin.php?page=class-blossomthemes-instagram-feed-admin.php' ) );
                 ?>
            </b></p>

        <?php endif; ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'blossomthemes-instagram-feed' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e( 'Username', 'blossomthemes-instagram-feed' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of photos', 'blossomthemes-instagram-feed' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" min="1" step="1" max="20" value="<?php echo esc_attr( $limit ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Photo size', 'blossomthemes-instagram-feed' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" class="widefat">
                <option value="thumbnail" <?php selected( 'thumbnail', $size ) ?>><?php esc_html_e( 'Thumbnail', 'blossomthemes-instagram-feed' ); ?></option>
                <option value="low_resolution" <?php selected( 'low_resolution', $size ) ?>><?php esc_html_e( 'Small', 'blossomthemes-instagram-feed' ); ?></option>
                <option value="standard_resolution" <?php selected( 'standard_resolution', $size ) ?>><?php esc_html_e( 'Large', 'blossomthemes-instagram-feed' ); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'per_row' ) ); ?>"><?php esc_html_e( 'Photos Per Row', 'blossomthemes-instagram-feed' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'per_row' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'per_row' ) ); ?>" type="number" min="1" max="5" step="1" value="<?php echo esc_attr( $per_row ); ?>" />
        </p>
        
        <p>
            <input type="checkbox" value="1" id="<?php echo esc_attr( $this->get_field_id( 'meta' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'meta' ) ); ?>" <?php if ( isset( $meta ) ) { checked( $meta, true );} ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'meta' ) ); ?>"><?php esc_html_e( 'Display Comments/Likes', 'blossomthemes-instagram-feed' ); ?></label>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'profile_link_text' ) ); ?>"><?php esc_html_e( 'Profile Link Text', 'blossomthemes-instagram-feed' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'profile_link_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'profile_link_text' ) ); ?>" type="text" value="<?php echo esc_attr( $profile_link_text ); ?>" />
        </p>
        
        <?php
    }
    
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        
        $instance['title']        = strip_tags( $new_instance['title'] );
        $instance['number']       = ! absint( $new_instance['number'] ) ? 6 : $new_instance['number'];
        $instance['size']         = $new_instance['size'];
        $instance['per_row']      = ! absint( $new_instance['per_row'] ) ? 5 : $new_instance['per_row'];
        $instance['meta']         = $new_instance['meta'];
        $instance['profile_link'] = 'https://www.instagram.com/'.$username;
        $instance['username']     = $new_instance['username'] ;
        $instance['profile_link_text']     = $new_instance['profile_link_text'] ;

        return $instance;
    }
}
