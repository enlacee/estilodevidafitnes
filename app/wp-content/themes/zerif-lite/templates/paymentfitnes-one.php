<?php
/* Template Name: Template Paymentfitnes One*/

get_header();
$post = get_post();
$userid = get_current_user_id();

$user_info = get_userdata( $userid );
$user_info = is_object($user_info) ? $user_info : new stdClass();
// var_dump($user_info);exit;
?>
<div class="clear"></div>
</header> <!-- / END HOME SECTION  -->

<div class="site-content">
	<div class="container" style="min-height: 1px;">
		<div class="content-left-wrap col-md-9">
			<div class="clear"></div>
			<article class="status-publish hentry">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<div class="entry-content"><?php echo $post->post_content; ?></div>
			</article>

			<div class="clear"></div>
			<article  class="status-publish hentry">
				<form autocomplete="off" id="datos-de-compra" style="display: none">
					<h3 class="entry-title">Datos de compra</h3>
					<div class="form-group row">
						<label for="firstname" class="col-sm-2 col-form-label">Nombres</label>
						<div class="col-sm-10">
							<input type="text" class="form-control-plaintext" id="firstname" 
							value="<?php echo isset($user_info->first_name) ? $user_info->first_name : ''; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="lastname" class="col-sm-2 col-form-label">Apellidos</label>
						<div class="col-sm-10">
							<input type="text" class="form-control-plaintext" id="lastname" 
							value="<?php echo isset($user_info->last_name) ? $user_info->last_name : ''; ?>">
						</div>
					</div>
					<div class="form-group row" style="display: none">
						<label for="email" class="col-sm-2 col-form-label">Correo</label>
						<div class="col-sm-10">
							<input type="text" readonly="" class="form-control-plaintext" id="email" 
							value="<?php echo isset($user_info->user_email) ? $user_info->user_email : ''; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="address" class="col-sm-2 col-form-label">Dirección</label>
						<div class="col-sm-10">
							<input type="text" class="form-control-plaintext" id="address">
						</div>
					</div>

					<br>
					<!-- Campos oculos -->
					<input type="hidden" readonly="" name="country_code" value="PE"><br>
					<input type="hidden" readonly="" name="phone_number" value="999999999"><br>
				</form>

				<!-- boton CULQI comprar -->
				<button id="buyButton" class="btn btn-lg" style="float: left;color:white;">Comprar!</button>
				<a href="#TB_inline?&width=300&height=130&modal=true&inlineId=obligate-login" class="thickbox" style="display: none;">Comprar modal</a>
			</article>
		</div>
	</div>
</div>

<div id="obligate-login" style="display:none;">
	<p>
		Debes <strong><a href="<?php echo site_url('/wp-login.php'); ?>"
			onclick="Cookies.set( 'pageRedirect', '<?php echo get_permalink(); ?>' );">Iniciar Sessión</a></strong>
		<br>
		o crear tú  <a href="<?php echo site_url('/wp-login.php?action=register'); ?>"
			onclick="Cookies.set( 'pageRedirect', '<?php echo get_permalink(); ?>' );">cuenta</a> con nosotros.
	</p>
</div>
<?php
get_footer();