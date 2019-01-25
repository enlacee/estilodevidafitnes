<?php
/* Template Name: Template Paymentfitnes One*/

get_header();

?>
<div class="clear"></div>
</header> <!-- / END HOME SECTION  -->

<div class="site-content">
	<div class="container" style="min-height: 1px;">
		<div class="content-left-wrap col-md-9">
			<article class="status-publish hentry">
				<h1 class="entry-title"><?php the_title(); ?></h1>

				<div class="entry-content">
					<?php echo get_the_content(); ?>
				</div>
			</article>
		</div>

	</div>
</div>
<?php
get_footer();