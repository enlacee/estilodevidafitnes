<?php
/**
 *
 */
class PaymentFitnesCulqi { 
	
	public static function init() {
		$class = __CLASS__;
		new $class;
	}

	public function __construct() {
		$this->includes();
		$this->publicKey = 'pk_test_tSSZYaWVxtNULtvX';
		

		add_action( 'wp_footer', array( $this, 'add_js_isLogin' ), 20 ); // Ultima prioridad
		add_action( 'wp_footer', array( $this, 'add_js_functions' ), 20 ); // Ultima prioridad

		//add_action( 'wp_login', array( $this, 'my_custom_login_redirect' ) ); // Ultima prioridad
		// add_action( 'login_redirect', array( $this, 'my_custom_login_redirect' ) ); // Ultima prioridad
		add_action( 'wp_enqueue_scripts', array( $this, 'culqui_enqueue_scripts' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'culqui_enqueue_scripts_login' ), 1 );

		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'culqui_enqueue_scripts' );
	}


	public function my_custom_login_redirect($redirect_to, $request, $user) {
		global $user;


		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( 'subscriber', $user->roles ) ) {
				// var_dump($_GET);exit;
				return home_url("suscripcion");
			}
		}

		return $redirect_to;
	}


	/**
	 * Incluye dependencias
	 *
	 */
	private function includes() {
		// Cargamos Requests y Culqi PHP
		include_once dirname(PAYMENTFITNES_FILE).'/includes/libraries/culqi-php/lib/culqi.php';
	}

	/**
	 * PUBLIC
	 * Mostrar y oculat login Iniciar session y Crear cuenta
	 */
	public function add_js_isLogin(){
		global $current_user;

		if ( is_user_logged_in() === true ) {
		?>
		<script type="text/javascript">
			var counterMenu = document.querySelectorAll("#site-navigation ul li").length;
			document.querySelectorAll("#site-navigation ul li")[counterMenu -1].style='display:none';
			document.querySelectorAll("#site-navigation ul li")[counterMenu -2].style='display:none';
		</script>
		<?php
		}
	}

	public function isTemplatePaymentFitnes(){

		return is_page_template('templates/paymentfitnes-one.php');
	}

	/**
	 * PUBLIC
	 * 
	 */
	public function add_js_functions() {
		if ( $this->isTemplatePaymentFitnes() === true ) {
			include_once dirname(PAYMENTFITNES_FILE).'/includes/libraries/paymentfitnes-culqi/add_js_functions.php';
		}
	}

	public function culqui_enqueue_scripts(){
		if ( $this->isTemplatePaymentFitnes() === true ) {
			wp_enqueue_script( 'culqui-core', 'https://checkout.culqi.com/js/v3', array( 'jquery' ), false, true );
			wp_enqueue_script( 'culqui-core-cookie', 'https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js', array( 'jquery', ), false, true );
		}
	}

	public function culqui_enqueue_scripts_login(){
		wp_enqueue_script( 'culqui-core-cookie-admin', 'https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js', false );
	}


}

add_action( 'plugins_loaded', array( 'PaymentFitnesCulqi', 'init' ));