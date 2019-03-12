<?php
/**
 * Blossom Feminine functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Blossom_Feminine
 */

//define theme version
if ( ! defined( 'BLOSSOM_FEMININE_THEME_VERSION' ) ) {
	$theme_data = wp_get_theme();	
	define ( 'BLOSSOM_FEMININE_THEME_VERSION', $theme_data->get( 'Version' ) );
}

/**
 * Implement the Custom functions.
 */
require get_template_directory() . '/inc/custom-functions.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Custom functions for selective refresh.
 */
require get_template_directory() . '/inc/partials.php';

/**
 * Metabox
 */
require get_template_directory() . '/inc/metabox.php';

/**
 * Custom Controls
 */
require get_template_directory() . '/inc/custom-controls/custom-control.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Widget Areas.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Dynamic Styles
 */
require get_template_directory() . '/css/style.php';

/**
 * Typography Functions
 */
require get_template_directory() . '/inc/typography.php';

/**
 * Plugin Recommendation
*/
require get_template_directory() . '/inc/tgmpa/recommended-plugins.php';

/**
 * Add theme compatibility function for woocommerce if active
*/
if( blossom_feminine_is_woocommerce_activated() ){
    require get_template_directory() . '/inc/woocommerce-functions.php';    
}

/**
 * Toolkit Filters
*/
if( blossom_feminine_is_bttk_activated() )
require get_template_directory() . '/inc/toolkit-functions.php';

/**
 * Getting Started
*/
require get_template_directory() . '/getting-started/getting-started.php';