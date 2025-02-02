<?php

?>
<script type="text/javascript">
	var $buttonBuy;
	var $formData;

	( function( $ ) {

		$( document ).ready( function() {
			var validationForm = function(){
				var flag = false;
				if ( 
					jQuery( '#firstname' ).val().length > 0 &&
					jQuery( '#lastname' ).val().length > 0 &&
					jQuery( '#email' ).val().length > 0 &&
					jQuery( '#address' ).val().length > 0 &&
					jQuery( '#aceptar-terminos' ).is(":checked") === true
				) {
					flag = true;
				}

				return flag;
			};

			$buttonBuy = $( '#buyButton' );
			$formData = $( '#datos-de-compra' );

			( jQuery( 'body' ).hasClass( 'logged-in' ) ) ? $formData.fadeIn() : false;

			// 01 Event click comprar
			$buttonBuy.on( 'click', function( e ) {

				if ( jQuery( 'body' ).hasClass( 'logged-in' ) ) {

					if ( validationForm() === true ) {

						//Abre el formulario con las opciones de Culqi.settings
						Culqi.open();
						e.preventDefault();
					} else {
						alert( 'Llene todos los campos correctamente. Y acepte los términos.' );
					}
				} else {
					jQuery("#exampleModal").modal(); // show popup login
				}
			});

			// ADD INTEGRATION CULQUI
			if ( typeof( Culqi ) !== 'undefined' ) {

				// Configura tu llave pública
				Culqi.publicKey = '<?php echo PaymentFitnesCulqi::$PUBLIC_KEY; ?>';

				// Configura tu Culqi Checkout
				Culqi.settings({
					title: jsVars.bloginfo,
					currency: '<?php echo PaymentFitnesCulqi::$CURRENCY_CODE; ?>', 
					description: '<?php echo PaymentFitnesCulqi::$PLAN_50_DESCRIPTION; ?>',
					amount: <?php echo PaymentFitnesCulqi::$PLAN_50_NUMBER; ?> 
				});
			}
		});

	}(window.jQuery || window.$));

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
			alert( 'No se puedo procesar tu pago. Intentalo luego.' )
		} else {
			$buttonBuy.prop( 'disabled', true );
			console.log( Culqi.token.id );
			jQuery( '#card_token' ).val( Culqi.token.id );

			// 01. Ajax de PAGO
			// 02. crear proceso ajax php y CREAR tarjeta y cargo
			jQuery.ajax({
				beforeSend: function (qXHR, settings) {
					jQuery('#loading').fadeIn();
				},
				complete: function () {
					jQuery('#loading').fadeOut();
				},
				type : "post",
				url : jsVars.ajaxUrl,
				data : $formData.serialize(),
				// dataType: 'json',
				success: function(response) {
					var errorMessage = 'Ocurrio un error. Intente en otro momento.';
					console.log('response', response);

					if ( response ) {
						if (response.status === true) {
							alert(response.message);
							window.setTimeout( function(){
								location.reload();
							}, 2000 );
						} else {
							errorMessage += (response.message) ? '\n' + response.message : '';
							alert(errorMessage);
						}
					}
				}
			});
		}
	}

	/*
	**********************************
	* Validation user register
	***********************************
	*/
	function validateFormCrearCuenta(){
		var $form = jQuery("#form-crear-cuenta");

		jQuery.ajax({
			beforeSend: function (qXHR, settings) {
				jQuery('#loading').fadeIn();
			},
			complete: function () {
				jQuery('#loading').fadeOut();
			},
			type : "post",
			url : jsVars.ajaxUrl,
			data : $form.serialize(),
			// dataType: 'json',
			success: function(response) {
				var errorMessage = 'Ocurrio un error. Intente en otro momento.';
				console.log('responseee', response);

				if ( response ) {
					if (response.status === true) {
						alert(response.message);
						window.setTimeout( function(){
							location.reload();
						}, 500 );
					} else {
						errorMessage += (response.message) ? '\n' + response.message : '';
						alert(errorMessage);
					}
				}
			}
		});

		$form.trigger("reset");

		return false;
	}
</script>
<?php
