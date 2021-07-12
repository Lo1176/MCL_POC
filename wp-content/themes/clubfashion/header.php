<?php
/**
 * The header for our theme including the navigation and the photo header
 *
 * @package ClubFashion
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if ( is_singular() && pings_open() ) : ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php endif; ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'clubfashion' ); ?></a>

<section class="top-bar py-3 position-relative">
	<div class="container">
		<div class="row">
			<div class="col-12 col-sm-4 d-none d-md-flex align-items-center">
				<?php if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) : ?>
					<?php $clubfashion_count = WC()->cart->cart_contents_count; ?>
					<div class="woocommerce-cart-contents position-relative rounded-circle">
						<a href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_attr__( 'View your shopping cart', 'clubfashion' ); ?>" class="w-100 h-100 rounded-circle d-block">
							<i class="fas fa-shopping-bag"></i>
						<?php if ( $clubfashion_count > 0 ) : ?>
							<span class="woocommerce-cart-contents-count"><?php echo esc_html( $clubfashion_count ); ?></span>
						<?php endif; ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
			<div class="col-12 col-sm-6 col-md-4">
				<a class="header-brand mx-auto mx-sm-0 mx-md-auto d-table text-center" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php
				$clubfashion_custom_logo_id = get_theme_mod( 'custom_logo' );
				$clubfashion_logo           = wp_get_attachment_image_src( $clubfashion_custom_logo_id, 'full' );
				$clubfashion_description    = get_bloginfo( 'description', 'display' );
				?>
				<?php if ( has_custom_logo() ) : ?>
					<img src="<?php echo esc_url( $clubfashion_logo[0] ); ?>" class="img-fluid">
				<?php else : ?>
					<span class="site-title fw-bold"><?php bloginfo( 'name' ); ?></span>
					<?php if ( $clubfashion_description || is_customize_preview() ) : ?>
						<span class="site-description d-table"><?php echo esc_html( $clubfashion_description ); ?></span>
					<?php endif; ?>
				<?php endif; ?>
				</a>
			</div>
			<div class="col-12 col-sm-6 col-md-4 d-flex align-items-center">
				<?php get_template_part( 'inc/social' ); ?>
			</div>
		</div>
	</div>
</section>

<nav class="navbar navbar-expand-md navbar-light static-top py-0 border-top">
	<div class="container">
		<span class="navbar-name text-uppercase fw-bolder d-md-none" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><?php echo esc_html( 'Menu', 'clubfashion' ); ?></span>
		<button class="navbar-toggler ml-auto border-0 rounded-0 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-line position-relative mb-1 rounded"></span>
			<span class="navbar-toggler-line position-relative mb-1 rounded"></span>
			<span class="navbar-toggler-line position-relative rounded"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<?php clubfashion_bootstrap_menu(); ?>
		</div>
	</div>
</nav>

<?php if ( is_front_page() && is_home() && ! is_paged() ) : ?>
	<?php if ( get_header_image() ) : ?>
		<section class="site-header site-header-home position-relative">
	<?php else : ?>
		<section class="site-header site-header-home site-header-no-image position-relative pt-5 border-top">
	<?php endif; ?>
<?php elseif ( is_single() ) : ?>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php $backgroundimg = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' ); ?>
		<section class="site-header site-header-single position-relative" style="background-image: url('<?php echo esc_url( $backgroundimg[0] ); ?>');">
	<?php else : ?>
		<?php if ( get_header_image() ) : ?>
			<section class="site-header site-header-single position-relative" style="background-image: url('<?php echo esc_url( header_image() ); ?>');">
		<?php else : ?>
			<section class="site-header site-header-single site-header-no-image position-relative pt-5 border-top">
		<?php endif; ?>
	<?php endif; ?>
<?php elseif ( is_page() ) : ?>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php $backgroundimg = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' ); ?>
		<section class="site-header site-header-page position-relative" style="background-image: url('<?php echo esc_url( $backgroundimg[0] ); ?>');">
	<?php else : ?>
		<?php if ( get_header_image() ) : ?>
			<section class="site-header site-header-page position-relative" style="background-image: url('<?php echo esc_url( header_image() ); ?>');">
		<?php else : ?>
			<section class="site-header site-header-page site-header-no-image position-relative pt-5 border-top">
		<?php endif; ?>
	<?php endif; ?>
<?php else : ?>
	<section class="site-header site-header-page position-relative">
<?php endif; ?>
	<div class="container h-100 position-relative">
		<div class="row h-100">
		<?php if ( is_front_page() && is_home() && ! is_paged() ) : ?>
			<div class="col-12 col-md-8 col-xl-5 d-flex align-items-center">
			<?php if ( is_active_sidebar( 'header-sidebar' ) ) : ?>
				<div class="site-header-main w-100">
					<?php dynamic_sidebar( 'header-sidebar' ); ?>
				</div>
			<?php endif; ?>
			</div>
		<?php elseif ( is_home() ) : ?>
			<div class="col-12 col-md-8 col-xl-5 d-flex align-items-center">
				<div class="site-header-main w-100">
					<h1><?php wp_title( '' ); ?></h1>
				</div>
			</div>
		<?php else : ?>
			<div class="col-12 d-flex align-items-center">
				<div class="site-header-main w-100">
			<?php if ( is_single() || is_page() ) : ?>
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : ?>
						<?php the_post(); ?>
							<h1><?php the_title(); ?></h1>
							<?php get_template_part( 'inc/post', 'meta' ); ?>
					<?php endwhile; ?>
				<?php else : ?>
				<?php endif; ?>
			<?php elseif ( is_category() ) : ?>
				<h1><?php esc_html_e( 'Latest Posts Under:', 'clubfashion' ); ?> <?php single_cat_title(); ?></h1>
			<?php elseif ( is_search() ) : ?>
				<h1><?php esc_html_e( 'Search Results For:', 'clubfashion' ); ?> <strong><?php echo get_search_query(); ?></strong></h1>
			<?php elseif ( is_archive() ) : ?>
				<?php if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) : ?>
					<?php if ( is_shop() ) : ?>
						<h1><?php woocommerce_page_title(); ?></h1>
					<?php else : ?>
						<h1><?php the_archive_title(); ?></h1>
					<?php endif; ?>
				<?php else : ?>
					<h1><?php the_archive_title(); ?></h1>
				<?php endif; ?>
			<?php elseif ( is_404() ) : ?>
				<h1><?php esc_html_e( 'The Page You Are Looking For Doesn&rsquo;t Exist.', 'clubfashion' ); ?></h1>
			<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
</section>

<?php if ( is_active_sidebar( 'primary_sidebar' ) ) : ?>
<section class="sidebar position-fixed">
	<div class="sidebar-button">
		<a class="btn btn-sidebar text-uppercase rounded-0"><?php esc_html_e( 'Sidebar', 'clubfashion' ); ?></a>
	</div>
	<div class="sidebar-frame position-absolute bg-white border-start">
		<div class="sidebar-main p-3 p-sm-5">
			<?php get_sidebar(); ?>
		</div>
	</div>
</section>
<?php endif; ?>
