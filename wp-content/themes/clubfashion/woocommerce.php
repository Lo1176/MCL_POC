<?php
/**
 * The template for displaying WooCommerce
 *
 * @package ClubFashion
 */

get_header(); ?>

<section id="content" class="content content-woocommerce py-5">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php woocommerce_content(); ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
