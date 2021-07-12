<?php
/**
 * The template for displaying social media icons
 *
 * @package ClubFashion
 */

?>
<?php if ( get_theme_mod( 'clubfashion_facebooklink' ) || get_theme_mod( 'clubfashion_twitterlink' ) || get_theme_mod( 'clubfashion_pinterestlink' ) || get_theme_mod( 'clubfashion_instagramlink' ) || get_theme_mod( 'clubfashion_linkedinlink' ) || get_theme_mod( 'clubfashion_youtubelink' ) || get_theme_mod( 'clubfashion_vimeo' ) || get_theme_mod( 'clubfashion_tumblrlink' ) || get_theme_mod( 'clubfashion_flickrlink' ) ) : ?>
<div class="social-icons mt-3 mt-sm-0 ms-auto me-auto me-sm-0">
	<ul class="list-unstyled d-table mb-0">
<?php endif; ?>
		<?php if ( get_theme_mod( 'clubfashion_facebooklink' ) ) : ?>
		<li class="float-start me-2"><a href="<?php echo esc_url( get_theme_mod( 'clubfashion_facebooklink' ) ); ?>" class="text-dark" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
		<?php endif; ?>
		<?php if ( get_theme_mod( 'clubfashion_twitterlink' ) ) : ?>
		<li class="float-start me-2"><a href="<?php echo esc_url( get_theme_mod( 'clubfashion_twitterlink' ) ); ?>" class="text-dark" target="_blank"><i class="fab fa-twitter"></i></a></li>
		<?php endif; ?>
		<?php if ( get_theme_mod( 'clubfashion_pinterestlink' ) ) : ?>
		<li class="float-start me-2"><a href="<?php echo esc_url( get_theme_mod( 'clubfashion_pinterestlink' ) ); ?>" class="text-dark" target="_blank"><i class="fab fa-pinterest-p"></i></a></li>
		<?php endif; ?>
		<?php if ( get_theme_mod( 'clubfashion_instagramlink' ) ) : ?>
		<li class="float-start me-2"><a href="<?php echo esc_url( get_theme_mod( 'clubfashion_instagramlink' ) ); ?>" class="text-dark" target="_blank"><i class="fab fa-instagram"></i></a></li>
		<?php endif; ?>
		<?php if ( get_theme_mod( 'clubfashion_linkedinlink' ) ) : ?>
		<li class="float-start me-2"><a href="<?php echo esc_url( get_theme_mod( 'clubfashion_linkedinlink' ) ); ?>" class="text-dark" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
		<?php endif; ?>
		<?php if ( get_theme_mod( 'clubfashion_youtubelink' ) ) : ?>
		<li class="float-start me-2"><a href="<?php echo esc_url( get_theme_mod( 'clubfashion_youtubelink' ) ); ?>" class="text-dark" target="_blank"><i class="fab fa-youtube"></i></a></li>
		<?php endif; ?>
		<?php if ( get_theme_mod( 'clubfashion_vimeo' ) ) : ?>
		<li class="float-start me-2"><a href="<?php echo esc_url( get_theme_mod( 'clubfashion_vimeo' ) ); ?>" class="text-dark" target="_blank"><i class="fab fa-vimeo-v"></i></a></li>
		<?php endif; ?>
		<?php if ( get_theme_mod( 'clubfashion_tumblrlink' ) ) : ?>
		<li class="float-start me-2"><a href="<?php echo esc_url( get_theme_mod( 'clubfashion_tumblrlink' ) ); ?>" class="text-dark" target="_blank"><i class="fab fa-tumblr"></i></a></li>
		<?php endif; ?>
		<?php if ( get_theme_mod( 'clubfashion_flickrlink' ) ) : ?>
		<li class="float-start"><a href="<?php echo esc_url( get_theme_mod( 'clubfashion_flickrlink' ) ); ?>" class="text-dark" target="_blank"><i class="fab fa-flickr"></i></a></li>
		<?php endif; ?>
<?php if ( get_theme_mod( 'clubfashion_facebooklink' ) || get_theme_mod( 'clubfashion_twitterlink' ) || get_theme_mod( 'clubfashion_pinterestlink' ) || get_theme_mod( 'clubfashion_instagramlink' ) || get_theme_mod( 'clubfashion_linkedinlink' ) || get_theme_mod( 'clubfashion_youtubelink' ) || get_theme_mod( 'clubfashion_vimeo' ) || get_theme_mod( 'clubfashion_tumblrlink' ) || get_theme_mod( 'clubfashion_flickrlink' ) ) : ?>
	</ul>
</div>
<?php endif; ?>
