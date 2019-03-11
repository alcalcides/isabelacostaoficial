<?php
function bttk_custom_categories_load_widget() {
    register_widget( 'Bttk_Custom_Categories' );
}
add_action( 'widgets_init', 'bttk_custom_categories_load_widget' );
 
// Creating the widget 
class Bttk_Custom_Categories extends WP_Widget {
	function __construct() {
		parent::__construct(
		 
		// Base ID of your widget
		'Bttk_Custom_Categories', 
		 
		// Widget name will appear in UI
		__('Blossom: Custom Categories', 'blossomthemes-toolkit'), 
		 
		// Widget description
		array( 'description' => __( 'Widget to display categories with Image and Posts Count', 'blossomthemes-toolkit' ), ) 
		);
	}
		 
	// Creating widget front-end
		 
	public function widget( $args, $instance ) {
        $title  = ! empty( $instance['title'] ) ? $instance['title'] : '';
		 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		ob_start();
		$target = 'target="_self"';
        if( isset($instance['target']) && $instance['target']!='' )
        {
            $target = 'target="_blank"';
        }
		if( $title ) echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];
        
		echo '<div class="blossomthemes-custom-categories-wrap">';
		echo '<ul class="blossomthemes-custom-categories-meta-wrap">';
		$cats[] = '1';
		if( isset( $instance['categories'] ) &&  $instance['categories']!='' )
		{
			$cats[] = '';
			$cats = $instance['categories'];
		}
		foreach ($cats as $key => $value) 
		{
			$url[] = '';
			$img = get_term_meta( $value, 'category-image-id', false );
			$category = get_category($value);
			if($category)
			{
				$count = $category->category_count;

				if( isset($img) && is_array($img) && isset($img[0]) )
				{
					$ccw_img_size = apply_filters('bttk_ccw_img_size','post-slider-thumb-size');
					$url1 = wp_get_attachment_image_url( $img[0], $ccw_img_size );
	                if(!isset($url))
	                {
	                    $url1 = wp_get_attachment_image_url( $img[0], 'thumbnail' );
	                }
				}
				else{
					$url1 = apply_filters( 'blossom_toolkit_no_thumb', esc_url(BTTK_FILE_URL).'/public/css/image/no-featured-img.png' );
				}
				echo '<li style="background: url('.$url1.') no-repeat">';
				echo '<a '.$target.' href="'. esc_url(get_category_link( $value )) .'"><span class="cat-title">'.get_cat_name( $value ).'</span>';
				if( $count > 0 ) {
					echo '<span class="post-count">'.esc_html($count).__(' Post(s)','blossomthemes-toolkit').'</span>';
				}
				echo '</a></li>';
			}
		}
		echo '</ul></div>';
		// This is where you run the code and display the output
		$html = ob_get_clean();
        echo apply_filters( 'blossom_custom_categories_widget_filter', $html, $args, $instance );
		echo $args['after_widget'];
	}
		         
		// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'blossomthemes-toolkit' );
		}
		$categories[] = '';
		if ( isset( $instance[ 'categories' ] ) && $instance[ 'categories' ]!='' ) {
			$categories = $instance[ 'categories' ];
		}
        $target     = ! empty( $instance['target'] ) ? $instance['target'] : '';

		// Widget admin form
		$ran = rand(1,1000); $ran++;
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'blossomthemes-toolkit' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" value="1" <?php echo checked($target,1);?> /><?php esc_html_e( 'Open in new Tab', 'blossomthemes-toolkit' ); ?> </label>
        </p>
		<?php
		// echo 
		// '<script>
		// 	$(".bttk-categories-select-'.$ran.'").siblings(".chosen-container").eq( 1 ).css( "display", "none" );
		// </script>';
		echo
			'<script>
			jQuery(document).ready(function($){
				$(".bttk-categories-select-'.$ran.'").chosen({
                change: _.throttle( function() { // For Customizer
                $(this).trigger( "chosen:updated" );
                }, 3000 ),
                clear: _.throttle( function() { // For Customizer
                $(this).trigger( "chosen:updated" );
                }, 4000 )
                });
				$(".bttk-categories-select-'.$ran.'").val('.json_encode($categories).').trigger("chosen:updated");
				if( $( ".bttk-categories-select-'.$ran.'" ).siblings( ".chosen-container" ).length > 1 )
				{
				 	$(".bttk-categories-select-'.$ran.'").siblings(".chosen-container").eq( 2 ).css( "display", "none" );
				}
			});
			</script>';
		?>
		<style>
		.chosen-container{
			width: 100% !important;
			margin-bottom: 10px;
		}
		.chosen-container:nth-of-type(2) {
    		display: none;
		} 
		</style>
		<select name="<?php echo $this->get_field_name( 'categories[]' );?>" class="bttk-categories-select-<?php echo $ran;?>" id="bttk-categories-select-<?php echo $ran;?>" multiple style="width:350px;" tabindex="4">
		  	<?php
		  	$categories = get_categories();
		  	$categories = get_categories( array(
			    'orderby' => 'name',
			) );
			 
			foreach ( $categories as $category ) {
			    printf( '<option value="%1$s">%2$s</option>',
			        esc_html( $category->term_id ),
			        esc_html( $category->name )
			    );
			}
		  	?>
		</select>
		<span class="bttk-option-side-note" class="example-text"><?php $bold = '<b>'; $boldclose = '</b>'; echo sprintf( __('To set thumbnail for categories, go to %1$sPosts > Categories%2$s and %3$sEdit%4$s the categories.','blossomthemes-toolkit'), $bold, $boldclose, $bold, $boldclose);?></span>
		<?php 
	}
		     
		// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['categories'] = '';
		if( isset( $new_instance['categories'] ) && $new_instance['categories']!='' )
		{
			$instance['categories'] = $new_instance['categories'];
		}
        $instance['target']                  = ! empty( $new_instance['target'] ) ? esc_attr( $new_instance['target'] ) : '';
		return $instance;
	}
}