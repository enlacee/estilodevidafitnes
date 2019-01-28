<?php

?>
<script type="text/javascript">
	console.log( 'add_js_functions loaded!!' );
	var $buttonBuy;

	( function( $ ) {

		$( document ).ready( function() {
			var validationForm = function(){
				var flag = false;
				if ( 
					jQuery( '#firstname' ).val().length > 0 &&
					jQuery( '#lastname' ).val().length > 0 &&
					jQuery( '#email' ).val().length > 0 &&
					jQuery( '#address' ).val().length > 0
				) {
					flag = true;
				}

				return flag;
			};

			$buttonBuy = $( '#buyButton' );
			( jQuery( 'body' ).hasClass( 'logged-in' ) ) ? jQuery( '#datos-de-compra' ).fadeIn() : false;

			// 01 Event click comprar
			$buttonBuy.on( 'click', function( e ) {

				if ( jQuery( 'body' ).hasClass( 'logged-in' ) ) {

					if ( validationForm() === true ) {

						//Abre el formulario con las opciones de Culqi.settings
						Culqi.open();
						e.preventDefault();
					} else {
						alert( 'Llene todos los campos correctamente.' );
					}
				} else {
					$buttonBuy.next().click(); // show popup login
				}
			});

			// ADD INTEGRATION CULQUI
			if ( typeof( Culqi ) !== 'undefined' ) {

				// Configura tu llave pública
				Culqi.publicKey = '<?php echo $this->publicKey; ?>';

				// Configura tu Culqi Checkout
				Culqi.settings({
					title: jsVars.bloginfo,
					currency: 'PEN',
					description: 'Suscripción mensual',
					amount: 5000
				});
			}
		});

	}( jQuery ) );

	/*
	**********************************
	* CALLBACK CULQI
	***********************************
	*/
	// Recibimos Token del Culqi.js
	function culqi() {
		if ( Culqi.error ) {

			// Mostramos JSON de objeto error en consola
			console.log( Culqi.error );
		} else {
			$buttonBuy.prop( 'disabled', true );
			console.log( Culqi.token.id );

			// 01. Ajax de PAGO
			// 02. crear proceso ajax php y CREAR tarjeta y cargo
		}
	}
</script>
<?php
