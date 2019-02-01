<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.altimea.com
 * @since      1.0.0
 *
 * @package    PaymentFitnes
 * @subpackage PaymentFitnes/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    PaymentFitnes
 * @subpackage PaymentFitnes/admin
 * @author     Altimea <apps@altimea.com>
 */
class PaymentFitnesAdmin {

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
	 * @param      string    $paymentfitnes       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $paymentfitnes, $version ) {

		$this->paymentfitnes = $paymentfitnes;
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
		 * defined in PaymentFitnesLoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PaymentFitnesLoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->paymentfitnes, plugin_dir_url( __FILE__ ) . 'css/paymentfitnes-admin.css', array(), $this->version, 'all' );

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
		 * defined in PaymentFitnesLoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PaymentFitnesLoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->paymentfitnes, plugin_dir_url( __FILE__ ) . 'js/paymentfitnes-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function ajax_paymentfitnes() {
		global $current_user;
		get_currentuserinfo();

		// Check parameters
		$rs = [];
		$message  = isset( $_POST['card_token'] ) ? $_POST['card_token'] : false;
		$dataPOST = $_POST;
		$culqi_customer_id = get_the_author_meta( 'culqi_customer_id', $current_user->ID );
		$culqui_subscription_id = get_the_author_meta( 'culqi_subscription_id', $current_user->ID );

		// 01. Realizar baja subscription (SIEMPRE ESPERANDO)
		$this->eliminar_subscription();
		$this->crear_nuevo_usuario();

		// 02. Realizar subscripcion
		if( !$message ) {
			wp_send_json( array('message' => __('Message not received :(', 'wpduf') ) );
		} else {
			// execute payment
			$culqi = new Culqi\Culqi(array('api_key' => PaymentFitnesCulqi::$PRIVATE_KEY));

			try {
				// 01. Crear Cliente
				if ( empty( $culqi_customer_id ) === true ) {
					$customer = $culqi->Customers->create(
						array(
							"address" => $_POST['address'],
							"address_city" => "Lima",
							"country_code" => $_POST['country_code'],
							"email" => $_POST['email'],
							"first_name" => $_POST['firstname'],
							"last_name" => $_POST['lastname'],
							"phone_number" => $_POST['phone_number'] 
						)
					);

					if ( is_object( $customer ) && isset( $customer->id ) === true ) {
						update_user_meta( $current_user->ID, 'culqi_customer_id', $customer->id );
						$culqi_customer_id = $customer->id;
					}
				}

				// 02.Crear Tarjeta
				$card = $culqi->Cards->create(
					array(
						"customer_id" => $culqi_customer_id,
						"token_id" => $_POST['card_token']
					)
				);
				if ( is_object( $card ) && isset( $card->id ) === true ) {
					update_user_meta( $current_user->ID, 'culqi_card_id', $card->id );
					//var_dump($card->id); // UTIL GUARDAMOS PARA LA SIGUIENTE VEZ NO CREAR TARJETA Y SOLO CLICK COMPRAR
				}

				// 03. Creando Cargo a una tarjeta (Subscriptions)
				$subscription = $culqi->Subscriptions->create(
					array(
						"card_id"=> $card->id,
						"plan_id" => PaymentFitnesCulqi::$PLAN_50_MENSUAL
					)
				);
				if ( is_object( $subscription ) && isset( $subscription->id ) === true ) {
					update_user_meta( $current_user->ID, 'culqi_subscription_id', $subscription->id );
					$culqui_subscription_id = $subscription->id;
				}

				// return
				$rs = array(
					'status' => true,
					'message' => 'Su compra ha sido exitosa'
				);
			} catch (Exception $e) {
				// echo json_encode($e->getMessage()); // DEBUG muestra errores del proceso
				$rs = $e->getMessage();
			}

			wp_send_json( $rs );
		}
	}

	/**
	 * SUSCRIPTION
	 */
	public function eliminar_subscription() {
		global $current_user;
		get_currentuserinfo();

		$tokenID = isset( $_POST['culqi_subscription_id'] ) ? $_POST['culqi_subscription_id'] : false;
		$rs = [];

		if ( $tokenID !== false) {

			try {
				$culqi = new Culqi\Culqi(array('api_key' => PaymentFitnesCulqi::$PRIVATE_KEY));
				$rsObject = $culqi->Subscriptions->delete($tokenID);
				update_user_meta( $current_user->ID, 'culqi_subscription_id', '' );

				$rs = array(
					'status' => true,
					'message' => $rsObject->merchant_message
				);
			} catch (Exception $e) {
				echo json_encode($e->getMessage());
			}
			
			wp_send_json( $rs );
		}
	}

	public function crear_nuevo_usuario() {
		$operacionIsValid = (isset( $_POST['op'] ) && ($_POST['op'] === 'crear-usuario')) ? true : false;
		$rs = [];

		if ( $operacionIsValid === true ) {

			$data = array(
				'firstname'		=> $_POST['firstname'],
				'lastname'		=> $_POST['lastname'],
				'iuser'			=> $_POST['email'], //user
				'iuser_mail'	=> $_POST['email'], // mail
				'iuser_pass'	=> wp_generate_password(),
			);

			if ( null == username_exists( $data['iuser'] ) ) {

				$user_id = wp_create_user($data['iuser'], $data['iuser_pass'], $data['iuser_mail']);

				// set values to super user
				if (is_int($user_id)) {
					$wp_user_object = new WP_User($user_id);
					$wp_user_object->set_role('subscriber');
					wp_set_password($_POST['password'], $user_id);
					update_user_meta($user_id, 'first_name', $data['firstname']);
					update_user_meta($user_id, 'last_name', $data['lastname']);

					//login
					wp_set_current_user($user_id, $data['iuser']);
					wp_set_auth_cookie($user_id);
					do_action('wp_login', $data['iuser']);

					//result
					$rs = array(
						'status' => true,
						'message' => 'El usuario se registro con éxito.'
					);
				}
			} else {
				$rs = array(
					'status' => false,
					'message' => 'El usuario con este correo ya se encuentra registrado.'
				);
			}

			wp_send_json( $rs );
		}
	}

	/**
	 * Solo pruebas
	 */
	public function eliminarUsandoAPICulqi() {
		// Eliminar USUARIO (Customers)
		// $culqi->Customers->delete('cus_test_zdkobPzozh5Ry9aL'); exit;
		// $culqi->Customers->delete('cus_test_wE98dYCN9KFCSrDJ');
		// var_dump($culqi->Customers->all());
	}

}
