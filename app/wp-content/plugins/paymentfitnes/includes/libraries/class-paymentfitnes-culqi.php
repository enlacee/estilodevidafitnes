<?php
/**
 *
 */
class PaymentFitnesCulqi { 

	public static $PUBLIC_KEY = 'pk_test_tSSZYaWVxtNULtvX';
	public static $PRIVATE_KEY = 'sk_test_NeloGflgK7XYFNRt';


	public static $PLAN_50_MENSUAL = 'pln_test_2MVFw5f97V6j5iok'; // revisar si este token pertenece al valor de 53 SOLES
	public static $PLAN_50_NUMBER = 5300;
	public static $PLAN_50_DESCRIPTION = 'Suscripción mensual';

	public static $CURRENCY_CODE = 'PEN';

	public static function init() {
		$class = __CLASS__;
		new $class;
	}

	public function __construct() {
		$this->includes();

		add_action( 'wp_footer', array( $this, 'add_js_isLogin' ), 20 ); // Ultima prioridad
		add_action( 'wp_footer', array( $this, 'add_js_functions' ), 20 ); // Ultima prioridad

		//add_action( 'wp_login', array( $this, 'my_custom_login_redirect' ) ); // Ultima prioridad
		// add_action( 'login_redirect', array( $this, 'my_custom_login_redirect' ) ); // Ultima prioridad
		add_action( 'wp_enqueue_scripts', array( $this, 'culqui_enqueue_scripts' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'culqui_enqueue_scripts_login' ), 1 );

		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'culqui_enqueue_scripts' );
		add_action( 'show_user_profile', array( $this, 'add_extra_user_profile_fields' ) );
		// add_action( 'edit_user_profile', array( $this, 'add_extra_user_profile_fields' ) );
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

	/**
	 *
	 * Crear metadatos CULQI en perfil del Usuario (WP ADMIN)
	 */
	public function add_extra_user_profile_fields( $user ){
		?>
		<h3><?php esc_html_e( 'Informacion Culqui', 'crf' ); ?></h3>

		<table class="form-table">
			<tr>
				<th><label for="culqi_customer_id"><?php _e("culqi_customer_id"); ?></label></th>
				<td>
					<input type="text" name="culqi_customer_id" readonly="" id="culqi_customer_id" value="<?php echo esc_attr( get_the_author_meta( 'culqi_customer_id', $user->ID ) ); ?>" class="regular-text" /><br />
				</td>
			</tr>
			<tr>
				<th><label for="culqi_card_id"><?php _e("culqi_card_id (ultima tarjeta)"); ?></label></th>
				<td>
					<input type="text" name="culqi_card_id" readonly="" id="culqi_card_id" value="<?php echo esc_attr( get_the_author_meta( 'culqi_card_id', $user->ID ) ); ?>" class="regular-text" /><br />
				</td>
			</tr>
			
			<tr>
				<th><label for="culqi_subscription_id"><?php _e("culqi_subscription_id"); ?></label></th>
				<td>
					<input type="text" name="culqi_subscription_id" readonly="" id="culqi_subscription_id" value="<?php echo esc_attr( get_the_author_meta( 'culqi_subscription_id', $user->ID ) ); ?>" class="regular-text" /><br />
				</td>
			</tr>

			<?php if ( !empty( get_the_author_meta( 'culqi_subscription_id', $user->ID ) ) ): ?>
				<tr>
					<th><label for="culqi_subscription_id"><?php _e("Suscripción"); ?></label></th>
					<td>
						<button class="button" id="dar-de-baja" style="color:red;">Dar de baja</button><br />
						<script type="text/javascript">

							jQuery( document ).ready(function(){

								jQuery('#dar-de-baja').click(function( e ){
									e.preventDefault();

									if (confirm('Estas seguro dar de baja?')) {
										jQuery.ajax({
											type : "post",
											url : '<?php echo admin_url('admin-ajax.php'); ?>',
											data : {
												'action': 'ajax_paymentfitnes',
												'culqi_subscription_id': jQuery('#culqi_subscription_id').val()
											},
											// dataType: 'json',
											success: function(response) {
												console.log('response', response);
												if ( response ) {
													if (response.status === true) {
														location.reload();
													} else {
														alert('Ocurrio un error. Intente en otro momento.');
													}
												}
											}
										});
									}
								});
							});
						</script>
					</td>
				</tr>
			<?php endif;?>
		</table>
	<?php
	}

}

add_action( 'plugins_loaded', array( 'PaymentFitnesCulqi', 'init' ));