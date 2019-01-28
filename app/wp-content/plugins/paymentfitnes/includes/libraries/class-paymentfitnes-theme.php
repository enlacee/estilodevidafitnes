<?php
/**
 * Script for THEME
 */
class PaymentFitnesTheme {
	
	public static function init() {
		$class = __CLASS__;
		new $class;
	}

	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'theme_enqueue_scripts' ) );
		add_filter( 'theme_script_vars', array( $this, 'theme_update_script_vars' ) );

		add_filter('wp_enqueue_scripts',array( $this, 'insert_jquery' ));


	}


	/** 
	 * Add libraries core wordpress (jquery, thickbox)
	 * thickbox: show modal or pupup
	 */
	public function insert_jquery(){
		wp_enqueue_style('thickbox'); //include thickbox .css
		//wp_enqueue_script('jquery');  //include jQuery
		wp_enqueue_script('thickbox'); //include Thickbox jQuery plugin

	}

	public function theme_enqueue_scripts(){
		// An empty array that can be filled with variables to send to front-end scripts
		$script_vars = array();
		$handle_plugin = 'jquery';

		// Pass variables to JavaScript at runtime; see: http://codex.wordpress.org/Function_Reference/wp_localize_script
		$script_vars = apply_filters('theme_script_vars', $script_vars);
		if (!empty($script_vars)) {
			wp_localize_script($handle_plugin, 'jsVars', $script_vars);
		}
	}

	// Provision the front-end with the appropriate script variables
	public function theme_update_script_vars($script_vars = array())
	{
		// Non-destructively merge script variables if a particular condition is met (e.g. `is_archive()` or whatever); useful for managing many different kinds of script variables
		return array_merge($script_vars, array(
			'baseUrl' => get_bloginfo('url'),
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'bloginfo' => get_bloginfo('name'),
			'pepe' => 'pepe lucho'
		));
	}
}
add_action( 'plugins_loaded', array( 'PaymentFitnesTheme', 'init' ));