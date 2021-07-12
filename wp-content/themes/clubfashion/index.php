<?php
/**
 * The main template file
 *
 * @package ClubFashion
 */

get_header(); ?>

<section id="content" class="content content-index py-5">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php get_template_part( 'loop' ); ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
