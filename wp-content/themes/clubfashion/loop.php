<?php
/**
 * The template for displaying all the posts
 *
 * @package ClubFashion
 */

?>
<div class="posts">
	<div class="row">
<?php $clubfashion_i = 1; ?>
<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php if ( 1 === $clubfashion_i ) : ?>
			<div class="col-12 col-lg-6 mb-3 mb-md-5">
		<?php elseif ( 2 === $clubfashion_i || 3 === $clubfashion_i ) : ?>
			<div class="col-12 col-sm-6 col-lg-3 mb-3 mb-md-5">
		<?php else : ?>
			<div class="col-12 col-md-6 mb-3 mb-md-5">
		<?php endif; ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( 1 === $clubfashion_i ) : ?>
					<?php if ( has_post_thumbnail() ) : ?>
						<?php $clubfashion_backgroundimg = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium_large' ); ?>
						<div class="post-item post-item-<?php echo esc_attr( $clubfashion_i ); ?> position-relative" style="background-image: url('<?php echo esc_url( $clubfashion_backgroundimg[0] ); ?>');">
					<?php else : ?>
						<div class="post-item post-item-<?php echo esc_attr( $clubfashion_i ); ?> position-relative">
					<?php endif; ?>
				<?php else : ?>
					<div class="post-item post-item-<?php echo esc_attr( $clubfashion_i ); ?> position-relative">
				<?php endif; ?>
					<?php if ( 2 === $clubfashion_i || 3 === $clubfashion_i ) : ?>
						<?php if ( has_post_thumbnail() ) : ?>
							<?php $clubfashion_backgroundimg = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium_large' ); ?>
							<div class="post-image position-relative" style="background-image: url('<?php echo esc_url( $clubfashion_backgroundimg[0] ); ?>');">
								<div class="post-categories-list position-absolute">
									<?php echo wp_kses_post( get_the_category_list() ); ?>
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( 1 !== $clubfashion_i && 2 !== $clubfashion_i && 3 !== $clubfashion_i ) : ?>
						<div class="post-image position-relative">
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
								<?php the_post_thumbnail( 'medium_large' ); ?>
							</a>
						</div>
					<?php endif; ?>
					<?php if ( 1 === $clubfashion_i ) : ?>
						<div class="post-info position-absolute p-4">
					<?php elseif ( 2 === $clubfashion_i || 3 === $clubfashion_i ) : ?>
						<div class="post-info p-4">
					<?php else : ?>
						<div class="row">
							<div class="col-12 col-sm-10 offset-sm-1">
								<div class="post-info position-relative bg-white p-4 text-center">
					<?php endif; ?>
						<?php if ( 1 !== $clubfashion_i && 2 !== $clubfashion_i && 3 !== $clubfashion_i ) : ?>
							<div class="post-categories-list mb-2">
								<?php echo wp_kses_post( get_the_category_list( ', ' ) ); ?>
							</div>
						<?php endif; ?>
							<div class="post-title">
								<h2 class="mb-3"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
							</div>
						<?php if ( 'post' === get_post_type() ) : ?>
							<div class="post-meta mb-3">
								<span class="post-date">
									<i><?php echo esc_html( the_time( get_option( 'date_format' ) ) ); ?></i>
								</span>
							<?php if ( post_password_required() !== true ) : ?>
								<span class="post-comments ms-2">
									<i class="far fa-comment me-1"></i><i><?php comments_number( __( '0 Comments', 'clubfashion' ), __( '1 Comment', 'clubfashion' ), __( '% Comments', 'clubfashion' ) ); ?></i>
								</span>
							<?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if ( 1 !== $clubfashion_i && 2 !== $clubfashion_i && 3 !== $clubfashion_i ) : ?>
							<div class="post-message">
								<?php the_excerpt(); ?>
							</div>
						<?php endif; ?>
					<?php if ( 1 === $clubfashion_i ) : ?>
						</div>
					<?php elseif ( 2 === $clubfashion_i || 3 === $clubfashion_i ) : ?>
						</div>
					<?php else : ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
					</div>
				</div>
			</div>
		<?php $clubfashion_i++; ?>
	<?php endwhile; ?>
<?php else : ?>
	<?php get_template_part( 'no-results' ); ?>
<?php endif; ?>
	</div>
</div>
<div class="page-nav">
	<?php the_posts_pagination(); ?>
</div>
