<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.altimea.com
 * @since      1.0.0
 *
 * @package    PaymentFitnes
 * @subpackage PaymentFitnes/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    PaymentFitnes
 * @subpackage PaymentFitnes/public
 * @author     Altimea <apps@altimea.com>
 */
class PaymentFitnesPublic {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $paymentfitnes    The ID of this plugin.
	 */
	private $paymentfitnes;

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
	 * @param      string    $paymentfitnes       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $paymentfitnes, $version ) {

		$this->paymentfitnes = $paymentfitnes;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in PaymentFitnesLoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PaymentFitnesLoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$fileName = 'paymentfitnes-main.css';
		$newFileName = PaymentFitnesGulpfile::getFileNameMD5( $fileName );

		if ( file_exists( plugin_dir_path( PAYMENTFITNES_FILE ) . 'public/assets/css/' . $newFileName ) ) {
			wp_enqueue_style( $this->paymentfitnes, plugin_dir_url( PAYMENTFITNES_FILE ) . 'public/assets/css/' . $newFileName, array(), $this->version, 'all' );
		} else {
			wp_enqueue_style( $this->paymentfitnes, plugin_dir_url( PAYMENTFITNES_FILE ) . 'public/assets/css/' . $fileName, array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in PaymentFitnesLoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PaymentFitnesLoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$fileName = 'paymentfitnes-main.js';
		$newFileName = PaymentFitnesGulpfile::getFileNameMD5( $fileName );

		if ( file_exists( plugin_dir_path( PAYMENTFITNES_FILE ) . 'public/assets/js/' . $newFileName ) ) {
			wp_enqueue_script( $this->paymentfitnes, plugin_dir_url( PAYMENTFITNES_FILE ) . 'public/assets/js/' . $newFileName, array( 'jquery', 'culqui-core' ), $this->version, true );
		} else {
			wp_enqueue_script( $this->paymentfitnes, plugin_dir_url( PAYMENTFITNES_FILE ) . 'public/assets/js/' . $fileName, array( 'jquery', 'culqui-core' ), $this->version, true );
		}

	}
}
