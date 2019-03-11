<?php
/**
 * Stat Counter Widget
 *
 * @package Rara_Theme_Companion
 */

// register BlossomThemes_Toolkit_FAQs_Widget widget
function blossom_faqs_widget(){
    register_widget( 'BlossomThemes_Toolkit_FAQs_Widget' );
}
add_action('widgets_init', 'blossom_faqs_widget');
 
 /**
 * Adds BlossomThemes_Toolkit_FAQs_Widget widget.
 */
class BlossomThemes_Toolkit_FAQs_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'bttk_faqs_widget', // Base ID
            __( 'Blossom: FAQs', 'blossomthemes-toolkit' ), // Name
            array( 'description' => __( 'A Widget for FAQs.', 'blossomthemes-toolkit' ), ) // Args
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
    public function widget( $args, $instance ) {
        
        $toggle   = ! empty( $instance['toggle'] ) ? $instance['toggle'] : '' ;        

        echo $args['before_widget'];
        ob_start(); 
        ?>
        <div class="col">
            <div class="raratheme-faq-holder">
                <ul class="accordion">
                    <?php 
                    if( $toggle ) { ?>
                        <a href="javascript:void(0);" class="expand-faq">
                            <i class="fa fa-toggle-off" aria-hidden="true"></i>
                            <?php _e('Expand/Close', 'blossomthemes-toolkit'); ?>
                        </a>
                    <?php
                    }
                    if(isset($instance['question']))
                    {
                        foreach ($instance['question'] as $key => $value) { ?>
                             <li><a class="toggle" href="javascript:void(0);"><?php echo esc_html($value);?></a> 
                                <div class="inner">
                                    <?php echo wp_kses_post($instance['answer'][$key]) ?>         
                                </div>
                            </li>
                        <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        echo apply_filters( 'blossom_faqs_widget_filter', $html, $args, $instance );       
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $toggle   = ! empty( $instance['toggle'] ) ? $instance['toggle'] : '' ;        
        $question = ! empty( $instance['question'] ) ? $instance['question'] : '' ;        
        $answer   = ! empty( $instance['answer'] ) ? $instance['answer'] : '' ;
        ?>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'toggle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'toggle' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $toggle ); ?>/>
            <label for="<?php echo esc_attr( $this->get_field_id( 'toggle' ) ); ?>"><?php esc_html_e( 'Enable FAQs Toggle', 'blossomthemes-toolkit' ); ?></label>
        </p>
        <div class="widget-client-faq-repeater" id="<?php echo esc_attr( $this->get_field_id( 'rarathemecompanion-faq-repeater' ) ); ?>">
            <?php
            if( isset( $instance['question'] ) && $instance['question']!='' )
            {
                if(sizeof($instance['question']) > 0 )
                {
                    $arr = $instance['question'];
                    $max = max(array_keys($arr)); 
                    for ($i=1; $i <= $max; $i++) { 
                        if( array_key_exists($i, $arr) )
                        { ?>
                            <div class="faqs-repeat" data-id="<?php echo $i; ?>"><span class="fa fa-times cross"></span>
                            <label for="<?php echo esc_attr( $this->get_field_id( 'question['.$i.']' ) ); ?>"><?php esc_html_e( 'Question', 'blossomthemes-toolkit' ); ?></label> 
                            <input class="widefat demo" id="<?php echo esc_attr( $this->get_field_id( 'question['.$i.']' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'question['.$i.']' ) ); ?>" type="text" value="<?php echo esc_attr($instance['question'][$i]);?>" />   
                            <label for="<?php echo esc_attr( $this->get_field_id( 'answer['.$i.']' ) ); ?>"><?php esc_html_e( 'Answer', 'blossomthemes-toolkit' ); ?></label> 
                            <textarea class="answer" id="<?php echo esc_attr( $this->get_field_id( 'answer['.$i.']' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'answer['.$i.']' ) ); ?>"><?php echo esc_attr($instance['answer'][$i]) ?></textarea>         
                            </div>
                    <?php
                        }
                    }
                }
            }
            ?>
        <span class="cl-faq-holder"></span>
        </div>
        <button id="add-faq" class="button"><?php _e('Add FAQs','blossomthemes-toolkit');?></button>
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['toggle']   = ! empty( $new_instance['toggle'] ) ? sanitize_text_field( $new_instance['toggle'] ) : '' ;
        if(isset($new_instance['question']))
        {
            foreach ( $new_instance['question'] as $key => $value ) {
                $instance['question'][$key]   = $value;
            }
        }

        if(isset($new_instance['answer']))
        {
            foreach ( $new_instance['answer'] as $key => $value ) {
                $instance['answer'][$key]    = $value;
            }
        }

        return $instance;
    }
    
}  // class BlossomThemes_Toolkit_FAQs_Widget