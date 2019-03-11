<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://blossomthemes.com
 * @since      1.0.0
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/admin
 * @author     blossomthemes <blossomthemes.com>
 */
class Blossomthemes_Email_Newsletter_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blossomthemes_Email_Newsletter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blossomthemes_Email_Newsletter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blossomthemes-email-newsletter-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome/css/font-awesome.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blossomthemes_Email_Newsletter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blossomthemes_Email_Newsletter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blossomthemes-email-newsletter-admin.js', array( 'jquery','wp-color-picker' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'bten_uploader', array(
        	'upload' => __( 'Upload', 'blossomthemes-toolkit' ),
        	'change' => __( 'Change', 'blossomthemes-toolkit' ),
        	'msg'    => __( 'Please upload valid image file.', 'blossomthemes-email-newsletter' )
    	));

	}

	/**
	 * Register a subscribe-form post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function blossomthemes_register_form() {
		$labels = array(
			'name'               => _x( 'BlossomThemes Email Newsletter', 'post type general name', 'blossomthemes-email-newsletter' ),
			'singular_name'      => _x( 'BlossomThemes Email Newsletter', 'post type singular name', 'blossomthemes-email-newsletter' ),
			'menu_name'          => _x( 'BlossomThemes Email Newsletter', 'admin menu', 'blossomthemes-email-newsletter' ),
			'name_admin_bar'     => _x( 'BlossomThemes Email Newsletter', 'add new on admin bar', 'blossomthemes-email-newsletter' ),
			'add_new'            => _x( 'Add New', 'BlossomThemes Email Newsletter', 'blossomthemes-email-newsletter' ),
			'add_new_item'       => __( 'Add New Newsletter', 'blossomthemes-email-newsletter' ),
			'new_item'           => __( 'New Newsletter', 'blossomthemes-email-newsletter' ),
			'edit_item'          => __( 'Edit Newsletter', 'blossomthemes-email-newsletter' ),
			'view_item'          => __( '', 'blossomthemes-email-newslett                                                          er' ),
			'all_items'          => __( 'All Newsletters', 'blossomthemes-email-newsletter' ),
			'search_items'       => __( 'Search Newsletters', 'blossomthemes-email-newsletter' ),
			'parent_item_colon'  => __( 'Parent Newsletters:', 'blossomthemes-email-newsletter' ),
			'not_found'          => __( 'No Newsletters found.', 'blossomthemes-email-newsletter' ),
			'not_found_in_trash' => __( 'No Newsletters found in Trash.', 'blossomthemes-email-newsletter' )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( 'Description.', 'blossomthemes-email-newsletter' ),
			'public'             => false,
			'menu_icon' 		 => 'dashicons-editor-table',
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite' 			 => array( 'slug' => 'subscribe-form' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 80,
			'supports'           => array( 'title' )
		);

		register_post_type( 'subscribe-form', $args );
	}

   /**
	* Registers settings page for Subscribe Form.
	*
	* @since 1.0.0
	*/
	public function blossomthemes_email_newsletter_settings_page() {
		add_submenu_page('edit.php?post_type=subscribe-form', 'Blossomthemes Email Newsletter Settings', 'Settings', 'manage_options', basename(__FILE__), array($this,'blossomthemes_email_newsletter_callback_function'));
	}

   /**
	* Registers settings.
	*
	* @since 1.0.0
	*/
	public function blossomthemes_email_newsletter_register_settings(){
	//The third parameter is a function that will validate input values.
		register_setting('blossomthemes_email_newsletter_settings', 'blossomthemes_email_newsletter_settings','');
	}

	/**
	* 
	* Retrives saved settings from the database if settings are saved. Else, displays fresh forms for settings.
	*
	* @since 1.0.0
	*/
	function blossomthemes_email_newsletter_callback_function() { 
		$blossom_themes_settings = new BlossomThemes_Email_Newsletter_Settings();
		$blossom_themes_settings->blossomthemes_email_newsletter_backend_settings();
		$option = get_option('blossomthemes_email_newsletter_settings');
	} 
	
	function add_subscribe_form_cpt_to_pll($post_types, $hide) {
	   if ($hide)
	       // hides 'subscribe-form' from the list of custom post types in Polylang settings
	       unset($post_types['subscribe-form']);
	   else
	       // enables language and translation management for 'subscribe-form'
	       $post_types['subscribe-form'] = 'subscribe-form';
	   return $post_types;
	}
}
