<?php

/**
 * Template Name: Home topto
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

get_header();
?>

<div id="content" class="site-content">
	<div id="primary" class="content-area">

		<main id="main" class="site-main">

			<div class="entry-content">
				<?php the_post(); ?>
				<?php the_content(); ?>

				<div class="container">
					<!-- <div class="container d-flex justify-content-center"> -->

					<!-- slide articles -->
					<?php
					echo do_shortcode('[bs-post-slider type="post" category="non-classe" order="ASC" orderby="title" posts="8"]');
					?>

					<!-- slide products -->


				</div>
				<!-- video ?  -->
				<div class="container-fluid text-center">
					<a href="https://qa-lignew-lignew-configurator.preview.arkima.io/configurator/app/index.html" target="_blank" rel="noopener noreferrer">
						<iframe class="elementor-background-video-embed" frameborder="0" allowfullscreen="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" title="YouTube video player" width="640" height="360" src="https://www.youtube.com/embed/R8ljIK_ywz8?controls=0&amp;rel=0&amp;playsinline=1&amp;enablejsapi=1&amp;origin=http%3A%2F%2Flocalhost&amp;widgetid=1" id="widget2" style="width: 680px"></iframe>
					</a>
				</div>


				<?php wp_link_pages(array(
					'before' => '<div class="page-links">' . esc_html__('Pages:', 'bootscore'),
					'after'  => '</div>',
				));
				?>
			</div>

		</main><!-- #main -->

	</div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
