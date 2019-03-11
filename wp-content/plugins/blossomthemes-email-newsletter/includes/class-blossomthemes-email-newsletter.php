<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://blossomthemes.com
 * @since      1.0.0
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author     blossomthemes <blossomthemes.com>
 */
class Blossomthemes_Email_Newsletter {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Blossomthemes_Email_Newsletter_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'blossomthemes-email-newsletter';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Blossomthemes_Email_Newsletter_Loader. Orchestrates the hooks of the plugin.
	 * - Blossomthemes_Email_Newsletter_i18n. Defines internationalization functionality.
	 * - Blossomthemes_Email_Newsletter_Admin. Defines all hooks for the admin area.
	 * - Blossomthemes_Email_Newsletter_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossomthemes-email-newsletter-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossomthemes-email-newsletter-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-blossomthemes-email-newsletter-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-blossomthemes-email-newsletter-public.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossomthemes-email-newsletter-settings.php';

		/**
		 * The class responsible for general functions.
		 * 
		 */
		require BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/class-blossomthemes-email-newsletter-functions.php';

		/**
		 * The class responsible for meta for subscription form.
		 * 
		 */
		require BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/class-blossomthemes-email-newsletter-form-meta.php';

		/**
		 * The class responsible for mailerlite libraries.
		 * 
		 */
		if (!class_exists('ML_Lists')) {
			require_once BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/libs/mailerlite_rest/ML_Lists.php';
		}

		/**
		 * The class responsible for mailchimp libraries.
		 * 
		 */
		if(!class_exists('MC_Lists')) {	
			require_once BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/libs/mailchimp/MC_Lists.php';
		}

		/**
		 * The class responsible for convertkit libraries.
		 * 
		 */
		if (!class_exists('Convertkit')) {
			require_once BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/libs/convertkit/convertkit.php';
		}
			
		/**
		 * The class responsible for doing mailerlite libraries.
		 * 
		 */
		if (!class_exists('ML_Webforms')) {
			require_once BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/libs/mailerlite_rest/ML_Webforms.php';
		}
		
		/**
		 * The class responsible for doing mailerlite actions.
		 * 
		 */
		require BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/class-blossomthemes-email-newsletter-mailerlite.php';

		/**
		 * The class responsible for doing mailchimp actions.
		 * 
		 */
		require BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/class-blossomthemes-email-newsletter-mailchimp.php';

		/**
		 * The class responsible for doing convertkit actions.
		 * 
		 */
		require BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/class-blossomthemes-email-newsletter-convertkit.php';

		/**
		 * The class responsible for generating shortcode.
		 * 
		 */
		require BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/class-blossomthemes-email-newsletter-shortcodes.php';

		/**
		 * The class responsible for generating widget.
		 * 
		 */
		require BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/widgets/widget-blossomthemes-newsletter.php';

		/**
		 * Privacy content and sections.
		 * 
		 */
		require BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/privacy-sections.php';


		$this->loader = new Blossomthemes_Email_Newsletter_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Blossomthemes_Email_Newsletter_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Blossomthemes_Email_Newsletter_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Blossomthemes_Email_Newsletter_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'blossomthemes_register_form' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'blossomthemes_email_newsletter_settings_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'blossomthemes_email_newsletter_register_settings' );
		$this->loader->add_filter('pll_get_post_types', $plugin_admin, 'add_subscribe_form_cpt_to_pll', 10, 2);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Blossomthemes_Email_Newsletter_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'script_loader_tag', $plugin_public, 'blossom_email_newsletter_js_defer_files', 10 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Blossomthemes_Email_Newsletter_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
