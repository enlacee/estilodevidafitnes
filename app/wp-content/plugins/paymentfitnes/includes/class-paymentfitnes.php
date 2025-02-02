<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.altimea.com
 * @since      1.0.0
 *
 * @package    PaymentFitnes
 * @subpackage PaymentFitnes/includes
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
 * @package    PaymentFitnes
 * @subpackage PaymentFitnes/includes
 * @author     Altimea <apps@altimea.com>
 */
class PaymentFitnes {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      PaymentFitnesLoader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $paymentfitnes    The string used to uniquely identify this plugin.
	 */
	protected $paymentfitnes;

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

		$this->paymentfitnes = 'paymentfitnes';
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
	 * - PaymentFitnesLoader. Orchestrates the hooks of the plugin.
	 * - PaymentFitnesi18n. Defines internationalization functionality.
	 * - PaymentFitnesAdmin. Defines all hooks for the admin area.
	 * - PaymentFitnesPublic. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-paymentfitnes-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-paymentfitnes-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-paymentfitnes-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-paymentfitnes-public.php';

		$this->loader = new PaymentFitnesLoader();

		//
		// Load composer libraries
		/*
		if (file_exists( plugin_dir_path( dirname( __FILE__ ) ).'/vendor/autoload.php')) {
			include plugin_dir_path( dirname( __FILE__ ) ).'/vendor/autoload.php';
		} else {
			die('You must set up the project dependencies, run the following commands:'.PHP_EOL.
					'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
					'php composer.phar install'.PHP_EOL);
		}
		*/
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the PaymentFitnesi18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new PaymentFitnesi18n();

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

		$plugin_admin = new PaymentFitnesAdmin( $this->get_paymentfitnes(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Hook para usuarios logueados
		// add_action('wp_ajax_notify_button_click', 'notify_button_click');
		$this->loader->add_action( 'wp_ajax_ajax_paymentfitnes', $plugin_admin, 'ajax_paymentfitnes' );
		// Hook para usuarios no logueados
		// add_action('wp_ajax_nopriv_ajax_paymentfitnes', 'notify_button_click');
		$this->loader->add_action( 'wp_ajax_nopriv_ajax_paymentfitnes', $plugin_admin, 'ajax_paymentfitnes' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new PaymentFitnesPublic( $this->get_paymentfitnes(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
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
	public function get_paymentfitnes() {
		return $this->paymentfitnes;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    PaymentFitnesLoader    Orchestrates the hooks of the plugin.
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
