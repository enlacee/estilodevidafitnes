(function( $ ) {
	'use strict';

	 $(document).ready(function(){

		if (jQuery('body.subscriber')) {

			// MENU DASHBOARD
			jQuery('#menu-dashboard').hide();

			// MENU PERFIL
			jQuery('form#your-profile h2:first').hide();
			jQuery('form#your-profile table.form-table:first').hide();

			// input users
			jQuery('form#your-profile table.form-table').eq(1).find('tr').eq(3).hide(); // ALIAS
			jQuery('form#your-profile table.form-table').eq(1).find('tr').eq(4).hide(); // NICK


			jQuery('form#your-profile table.form-table').eq(2).find('tr').eq(1).hide(); //hide URL WEB

			jQuery('form#your-profile h2').eq(3).hide();
			jQuery('form#your-profile table.form-table').eq(3).hide();
		}
	 });

})( jQuery );
