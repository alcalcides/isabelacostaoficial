<?php
/**
 * Getting Started Page.
 *
 * @package Blossom Feminine
 */

require get_template_directory() . '/getting-started/install-activate.php';
require get_template_directory() . '/getting-started/class-getting-start-plugin-helper.php';

/**
 * Load Getting Started styles in the admin
 *
 * since 1.0.0
 */
function blossom_feminine_start_load_admin_scripts() {

	// Load styles only on our page
	global $pagenow;
	if( 'themes.php' != $pagenow )
		return;

	/**
	 * Getting Started scripts and styles
	 *
	 * @since 1.0
	 */

	// Getting Started javascript
	wp_enqueue_script( 'getting-started', get_template_directory_uri() . '/getting-started/js/getting-started.js', array( 'jquery' ), '1.0.0', true );

	// Getting Started styles
	wp_register_style( 'getting-started', get_template_directory_uri() . '/getting-started/css/getting-started.css', false, '1.0.0' );
	wp_enqueue_style( 'getting-started' );
}
add_action( 'admin_enqueue_scripts', 'blossom_feminine_start_load_admin_scripts' );

/**
 *
 * since 1.0.0
 */
function blossom_feminine_getting_started_menu() {
	
	add_theme_page(
		__( 'Getting Started', 'blossom-feminine' ),
		__( 'Getting Started', 'blossom-feminine' ),
		'manage_options',
		'blossom-feminine-starter',
		'blossom_feminine_getting_started_page'
	);
}
add_action( 'admin_menu', 'blossom_feminine_getting_started_menu' );


function blossom_feminine_getting_started_page() {

	/**
	 *
	 * since 1.0.0
	 */

	// Theme info
	$theme = wp_get_theme();
	$theme_active = $theme->get( 'TextDomain' );
	/**
	 * Create recommended plugin install URLs
	 *
	 * since 1.0.0
	 */
	?>

	<div class="wrap getting-started">
		<h2 class="notices"></h2>
		<div class="intro-wrap">
			<div class="intro">
				<h3><?php printf( __( 'Getting started with %1$s v%2$s', 'blossom-feminine' ), $theme['Name'], $theme['Version'] ); ?></h3>

				<h4><?php printf( __( 'You will find everything you need to get started with %1$s below.', 'blossom-feminine' ), $theme['Name'] ); ?></h4>
			</div>
		</div>

		<div class="panels">
			<ul class="inline-list">
				<li class="current"><a id="plugins" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 18"><defs><style>.a{fill:#354052;}</style></defs><path class="a" d="M16,9v4.66l-3.5,3.51V19h-1V17.17L8,13.65V9h8m0-6H14V7H10V3H8V7H7.99A1.987,1.987,0,0,0,6,8.98V14.5L9.5,18v3h5V18L18,14.49V9a2.006,2.006,0,0,0-2-2Z" transform="translate(-6 -3)"/></svg> <?php _e( 'Recommended Plugins', 'blossom-feminine' ); ?></a></li>
				<li><a id="help" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22"><defs><style>.a{fill:#354052;}</style></defs><path class="a" d="M12,23H11V16.43A5.966,5.966,0,0,1,7,18a6.083,6.083,0,0,1-6-6V11H7.57A5.966,5.966,0,0,1,6,7a6.083,6.083,0,0,1,6-6h1V7.57A5.966,5.966,0,0,1,17,6a6.083,6.083,0,0,1,6,6v1H16.43A5.966,5.966,0,0,1,18,17,6.083,6.083,0,0,1,12,23Zm1-9.87v7.74a4,4,0,0,0,0-7.74ZM3.13,13A4.07,4.07,0,0,0,7,16a4.07,4.07,0,0,0,3.87-3Zm10-2h7.74a4,4,0,0,0-7.74,0ZM11,3.13A4.08,4.08,0,0,0,8,7a4.08,4.08,0,0,0,3,3.87Z" transform="translate(-1 -1)"/></svg> <?php _e( 'Getting Started', 'blossom-feminine' ); ?></a></li>
				<li><a id="support" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><defs><style>.a{fill:#354052;}</style></defs><path class="a" d="M11,18h2V16H11ZM12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.011,8.011,0,0,1,12,20ZM12,6a4,4,0,0,0-4,4h2a2,2,0,0,1,4,0c0,2-3,1.75-3,5h2c0-2.25,3-2.5,3-5A4,4,0,0,0,12,6Z" transform="translate(-2 -2)"/></svg> <?php _e( 'FAQ\'s &amp; Support', 'blossom-feminine' ); ?></a></li>
				<li><a id="free-pro-panel" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17.297 20"><defs><style>.a{fill:#354052;}</style></defs><path class="a" d="M19.384,17.534V13.75L14,19.155l5.384,5.405V20.777H31.3V17.534Zm6.53,9.189H14v3.243H25.914V33.75L31.3,28.345l-5.384-5.405Z" transform="translate(-14 -13.75)"/></svg> <?php _e( 'Free Vs Pro', 'blossom-feminine' ); ?></a></li>
			</ul>

			<div id="panel" class="panel">

				<?php require get_template_directory() . '/getting-started/tabs/plugins-panel.php'; ?>
				<?php require get_template_directory() . '/getting-started/tabs/help-panel.php'; ?>
				<?php require get_template_directory() . '/getting-started/tabs/support-panel.php'; ?>
				<?php require get_template_directory() . '/getting-started/tabs/free-vs-pro-panel.php'; ?>
				<?php require get_template_directory() . '/getting-started/tabs/link-panel.php'; ?>
			</div><!-- .panel -->
		</div><!-- .panels -->
	</div><!-- .getting-started -->
	<?php
}