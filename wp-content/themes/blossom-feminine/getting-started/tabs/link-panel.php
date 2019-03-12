<?php
/**
 * Right Buttons Panel.
 *
 * @package Blossom Feminine
 */

if( $theme_active == 'blossom-feminine' ){
    $doc_link    = 'blossom-feminine';
}elseif( $theme_active == 'blossom-pretty' ){
    $doc_link    = 'blossom-pretty';
}else{
    $doc_link    = $theme_active . '-free-theme';
}

?>
<div class="panel-right">
	<div class="panel-aside">
		<h4><?php _e( 'Upgrade To Pro', 'blossom-feminine' ); ?></h4>
		<p><?php _e( 'With the Pro version, you can change the look and feel of your website in seconds. You can change color, choose from background patterns, and change the fonts with ease. You will also get more homepage sections that you can reorder and hide as per your needs. Furthermore, the pro version comes with multiple predefined pages like contact page, gallery page, team page, service page, and pricing page.', 'blossom-feminine' ); ?></p>
		<p><?php _e( 'You will also get more frequent updates and quicker support with the Pro version.', 'blossom-feminine' ); ?></p>
		<a class="button button-primary" href="<?php echo esc_url( 'https://blossomthemes.com/downloads/blossom-feminine-pro/' ); ?>" title="<?php esc_attr_e( 'View Premium Version', 'blossom-feminine' ); ?>" target="_blank"><?php _e( 'Read More About the Pro Theme', 'blossom-feminine' ); ?></a>
	</div><!-- .panel-aside Theme Support -->
	<!-- Knowledge base -->
	<div class="panel-aside">
		<h4><?php _e( 'Visit the Knowledge Base', 'blossom-feminine' ); ?></h4>
		<p><?php _e( 'Need help with using the WordPress as quickly as possible? Visit our well-organized Knowledge Base!', 'blossom-feminine' ); ?></p>
		<p><?php _e( 'Our Knowledge Base has step-by-step video and text tutorials, from installing the WordPress to working with themes and more.', 'blossom-feminine' ); ?></p>

		<a class="button button-primary" href="<?php echo esc_url( 'https://blossomthemes.com/' . $doc_link . '-documentation/' ); ?>" title="<?php esc_attr_e( 'Visit the knowledge base', 'blossom-feminine' ); ?>" target="_blank"><?php _e( 'Visit the Knowledge Base', 'blossom-feminine' ); ?></a>
	</div><!-- .panel-aside knowledge base -->
</div><!-- .panel-right -->