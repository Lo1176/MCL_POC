<?php
/**
 * ClubFashion functions and definitions
 *
 * @package ClubFashion
 */

/**
 * ClubFashion Theme Setup
 */
function clubfashion_setup() {
	// Meta Title.
	add_theme_support( 'title-tag' );

	// Automatic Feed Links.
	add_theme_support( 'automatic-feed-links' );

	// Logo Support.
	add_theme_support(
		'custom-logo',
		array(
			'width'       => 215,
			'height'      => 38,
			'flex-width'  => true,
			'flex-height' => true,
			'header-text' => array(
				'site-title',
				'site-description',
			),
		)
	);

	// Featured Image.
	add_theme_support( 'post-thumbnails' );

	// Language Support.
	load_theme_textdomain( 'clubfashion', get_template_directory() . '/languages' );

	// Header Image.
	$clubfashion_args = array(
		'flex-width'         => true,
		'width'              => 1500,
		'flex-height'        => true,
		'height'             => 900,
		'default-text-color' => '#fff',
	);
	add_theme_support( 'custom-header', $clubfashion_args );

	// Content Width.
	if ( ! isset( $content_width ) ) {
		$content_width = 1170;
	}
}
add_action( 'after_setup_theme', 'clubfashion_setup' );

/**
 * Color / Social Customizer
 *
 * @param array $clubfashion_wp_customize Theme Colors & Social Media.
 */
function clubfashion_customize_register( $clubfashion_wp_customize ) {
	$clubfashion_colors   = array();
	$clubfashion_colors[] = array(
		'slug'    => 'default_color',
		'default' => '#f3b3c2',
		'label'   => __( 'Default Color ', 'clubfashion' ),
	);

	$clubfashion_colors[] = array(
		'slug'    => 'contact_widget_color',
		'default' => '#f3b3c2',
		'label'   => __( 'Contact Widget Color ', 'clubfashion' ),
	);

	foreach ( $clubfashion_colors as $clubfashion_color ) {
		$clubfashion_wp_customize->add_setting(
			$clubfashion_color['slug'], 
			array(
				'default'           => $clubfashion_color['default'],
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$clubfashion_wp_customize->add_control(
			new WP_Customize_Color_Control(
				$clubfashion_wp_customize,
				$clubfashion_color['slug'],
				array(
					'label'    => $clubfashion_color['label'],
					'section'  => 'colors',
					'settings' => $clubfashion_color['slug'],
				)
			)
		);
	}

	$clubfashion_wp_customize->add_panel(
		'widget_images',
		array(
			'priority'       => 70,
			'theme_supports' => '',
			'title'          => esc_html__( 'Widget Images', 'clubfashion' ),
			'description'    => esc_html__( 'Set background images for certain widgets.', 'clubfashion' ),
		)
	);

	$clubfashion_wp_customize->add_section(
		'contact_background',
		array(
			'title'    => esc_html__( 'Contact Background', 'clubfashion' ),
			'panel'    => 'widget_images',
			'priority' => 20,
		)
	);

	$clubfashion_wp_customize->add_setting(
		'contact_bg',
		array(
			'flex-width'  => true,
			'width'       => 1500,
			'flex-height' => true,
			'height'      => 900,
		)
	);

	$clubfashion_wp_customize->add_control(
		new WP_Customize_Image_Control(
			$clubfashion_wp_customize,
			'contact_background_image',
			array(
				'label'    => esc_html__( 'Add Contact Background Here, the width should be approx 1500px', 'clubfashion' ),
				'section'  => 'contact_background',
				'settings' => 'contact_bg',
			)
		)
	);

	$clubfashion_wp_customize->add_section(
		'sociallinks',
		array(
			'title'       => __( 'Social Links', 'clubfashion' ),
			'description' => __( 'Add Your Social Links Here.', 'clubfashion' ),
			'priority'    => '900',
		)
	);

	$clubfashion_wp_customize->add_setting(
		'clubfashion_facebooklink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$clubfashion_wp_customize->add_control(
		'clubfashion_facebooklink',
		array(
			'label'   => __( 'Facebook URL', 'clubfashion' ),
			'section' => 'sociallinks',
		)
	);
	$clubfashion_wp_customize->add_setting(
		'clubfashion_twitterlink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$clubfashion_wp_customize->add_control(
		'clubfashion_twitterlink',
		array(
			'label'   => __( 'Twitter URL', 'clubfashion' ),
			'section' => 'sociallinks',
		)
	);
	$clubfashion_wp_customize->add_setting(
		'clubfashion_pinterestlink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$clubfashion_wp_customize->add_control(
		'clubfashion_pinterestlink',
		array(
			'label'   => __( 'Pinterest URL', 'clubfashion' ),
			'section' => 'sociallinks',
		)
	);
	$clubfashion_wp_customize->add_setting(
		'clubfashion_instagramlink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$clubfashion_wp_customize->add_control(
		'clubfashion_instagramlink',
		array(
			'label'   => __( 'Instagram URL', 'clubfashion' ),
			'section' => 'sociallinks',
		)
	);
	$clubfashion_wp_customize->add_setting(
		'clubfashion_linkedinlink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$clubfashion_wp_customize->add_control(
		'clubfashion_linkedinlink',
		array(
			'label'   => __( 'LinkedIn URL', 'clubfashion' ),
			'section' => 'sociallinks',
		)
	);
	$clubfashion_wp_customize->add_setting(
		'clubfashion_youtubelink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$clubfashion_wp_customize->add_control(
		'clubfashion_youtubelink',
		array(
			'label'   => __( 'YouTube URL', 'clubfashion' ),
			'section' => 'sociallinks',
		)
	);
	$clubfashion_wp_customize->add_setting(
		'clubfashion_vimeo',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$clubfashion_wp_customize->add_control(
		'clubfashion_vimeo',
		array(
			'label'   => __( 'Vimeo URL', 'clubfashion' ),
			'section' => 'sociallinks',
		)
	);
	$clubfashion_wp_customize->add_setting(
		'clubfashion_tumblrlink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$clubfashion_wp_customize->add_control(
		'clubfashion_tumblrlink',
		array(
			'label'   => __( 'Tumblr URL', 'clubfashion' ),
			'section' => 'sociallinks',
		)
	);
	$clubfashion_wp_customize->add_setting(
		'clubfashion_flickrlink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$clubfashion_wp_customize->add_control(
		'clubfashion_flickrlink',
		array(
			'label'   => __( 'Flickr URL', 'clubfashion' ),
			'section' => 'sociallinks',
		)
	);
}
add_action( 'customize_register', 'clubfashion_customize_register' );

/**
 * Includes Plugin Admin
 */
require_once ABSPATH . 'wp-admin/includes/plugin.php';

/**
 * Bootstrap
 */
function clubfashion_bootstrap() {
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/bootstrap/bootstrap.min.css', array(), '1.0.4' );
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/fontawesome/css/fontawesome.min.css', array(), '1.0.4' );
	wp_enqueue_style( 'clubfashion-googlefonts', clubfashion_google_fonts_url(), array(), '1.0.4' );
	wp_enqueue_style( 'clubfashion-style', get_stylesheet_uri(), array(), '1.0.4' );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/bootstrap/bootstrap.min.js', array( 'jquery' ), '1.0.4', true );
	wp_enqueue_script( 'clubfashion-script', get_template_directory_uri() . '/js/script.min.js', array( 'jquery' ), '1.0.4', true );
}
add_action( 'wp_enqueue_scripts', 'clubfashion_bootstrap' );

/**
 * Google Font
 */
function clubfashion_google_fonts_url() {
	$font_families = array( 'Playfair Display:400,400i,700,700i,900,900i', 'Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i' );
	$query_args    = array(
		'family' => rawurlencode( implode( '|', $font_families ) ),
		'subset' => rawurlencode( 'latin,latin-ext' ),
	);
	$fonts_url     = add_query_arg( $query_args, '//fonts.googleapis.com/css' );
	return apply_filters( 'clubfashion_google_fonts_url', $fonts_url );
}

/**
 * Navigation
 */
function clubfashion_register_menu() {
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Top Primary Menu', 'clubfashion' ),
		)
	);
}
add_action( 'init', 'clubfashion_register_menu' );

/**
 * Bootstrap Navigation
 */
function clubfashion_bootstrap_menu() {
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'depth'          => 2,
			'menu_class'     => 'nav navbar-nav',
			'fallback_cb'    => 'WP_Bootstrap_Navwalker::fallback',
			'walker'         => new WP_Bootstrap_Navwalker(),
		)
	);
}
require get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

/**
 * WooCommerce Support
 */
function clubfashion_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'clubfashion_woocommerce_support' );

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10 );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 10 );

/**
 * WooCommerce Cart Count
 */
function clubfashion_woocommerce_cart_count() {
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		$clubfashion_count = WC()->cart->cart_contents_count;
		?>
		<a href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_attr__( 'View your shopping cart', 'clubfashion' ); ?>" class="w-100 h-100 rounded-circle d-block">
			<i class="fas fa-shopping-bag"></i>
		<?php if ( $clubfashion_count > 0 ) { ?>
			<span class="woocommerce-cart-contents-count"><?php echo esc_html( $clubfashion_count ); ?></span>
		<?php } ?>
		</a>
		<?php
	}
}
add_action( 'clubfashion_header_top', 'clubfashion_woocommerce_cart_count' );

/**
 * WooCommerce Cart Count
 *
 * @param array $clubfashion_fragments Cart Count.
 */
function clubfashions_add_to_cart_fragment( $clubfashion_fragments ) {
	ob_start();
	$clubfashion_count = WC()->cart->cart_contents_count;
	?>
	<a href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_attr( 'View your shopping cart', 'clubfashion' ); ?>" class="w-100 h-100 rounded-circle d-block">
		<i class="fas fa-shopping-bag"></i>
	<?php if ( $clubfashion_count > 0 ) { ?>
		<span class="woocommerce-cart-contents-count"><?php echo esc_html( $clubfashion_count ); ?></span>
	<?php } ?>
	</a>
	<?php
	$clubfashion_fragments['.woocommerce-cart-contents a'] = ob_get_clean();
	return $clubfashion_fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'clubfashions_add_to_cart_fragment' );

/**
 * Header Style
 */
function clubfashion_header_style() {
	if ( ! display_header_text() ) {
		echo '
		<style type="text/css">
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px 1px 1px 1px);
				clip: rect(1px, 1px, 1px, 1px);
			}
		</style>
		';
	}
}
add_action( 'wp_head', 'clubfashion_header_style' );

/**
 * Custom Colors
 */
function clubfashion_customizer_css() {
	$clubfashion_default_color      = get_theme_mod( 'default_color' );
	$clubfashion_header_text_color  = get_header_textcolor();
	$clubfashion_contact_background = get_theme_mod( 'contact_bg' );
	$clubfashion_contact_color      = get_theme_mod( 'contact_widget_color' );
	?>
	<style type="text/css">
	<?php if ( has_header_image() ) : ?>
		.site-header,
		.site-page {
			background-image: url('<?php header_image(); ?>');
		}
	<?php endif; ?>
		.site-header h1,
		.site-header p {
			color: #<?php echo esc_attr( $clubfashion_header_text_color ); ?>;
		}
	<?php if ( $clubfashion_contact_background || $clubfashion_contact_color ) : ?>
		.sidebar-contact {
		<?php if ( $clubfashion_contact_background ) : ?>
			background-image: url('<?php echo esc_attr( $clubfashion_contact_background ); ?>');
		<?php endif; ?>
		<?php if ( $clubfashion_contact_color ) : ?>
			color: <?php echo esc_attr( $clubfashion_contact_color ); ?>;
		<?php endif; ?>
		}
	<?php endif; ?>
	<?php if ( $clubfashion_contact_color ) : ?>
		.sidebar-contact h1,
		.sidebar-contact h2,
		.sidebar-contact h3,
		.sidebar-contact h4,
		.sidebar-contact h5,
		.sidebar-contact h6 {
			color: <?php echo esc_attr( $clubfashion_contact_color ); ?>;
		}
		.sidebar-contact a {
			color: <?php echo esc_attr( $clubfashion_contact_color ); ?>;
		}
	<?php endif; ?>
	<?php if ( $clubfashion_default_color ) : ?>
		.btn-search,
		.btn-submit,
		.navbar-toggler .navbar-toggler-line,
		.sidebar-contact:after,
		.button,
		.post-message .more-link,
		.post-tags .tags a,
		.nav-links .page-numbers.current,
		.nav-links .page-numbers:hover,
		.nav-links .page-numbers:focus,
		.nav-links .prev.page-numbers,
		.nav-links .next.page-numbers,
		.comment .reply a,
		.woocommerce-cart-contents,
		.woocommerce span.onsale,
		.woocommerce .products .product .button,
		.woocommerce .products .product a.added_to_cart,
		.woocommerce #respond input#submit.alt,
		.woocommerce a.button.alt,
		.woocommerce a.button.alt:hover,
		.woocommerce a.button.alt:focus,
		.woocommerce button.button.alt,
		.woocommerce button.button.alt:hover,
		.woocommerce button.button.alt:focus,
		.woocommerce input.button.alt,
		.woocommerce #respond input#submit,
		.woocommerce a.button,
		.woocommerce button.button,
		.woocommerce input.button,
		.sidebar .sidebar-button .btn-sidebar {
			background-color: <?php echo esc_attr( $clubfashion_default_color ); ?>;
		}

		a,
		.site-header-no-image h1,
		.post-categories-list {
			color: <?php echo esc_attr( $clubfashion_default_color ); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
add_action( 'wp_head', 'clubfashion_customizer_css' );

/**
 * Widgets
 */
function clubfashion_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Primary Sidebar', 'clubfashion' ),
			'id'            => 'primary_sidebar',
			'before_widget' => '<div class="sidebar-item">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Header', 'clubfashion' ),
			'id'            => 'header-sidebar',
			'before_widget' => '<div class="header">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1>',
			'after_title'   => '</h1>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Front page: Contact', 'clubfashion' ),
			'id'            => 'contact_sidebar',
			'before_widget' => '<div class="sidebar-contact-main position-relative py-5">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'clubfashion_widgets_init' );

/**
 * Post Read More
 *
 * @param array $link Show more link.
 */
function clubfashion_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf(
		'<p class="link-more mt-4"><a href="%1$s" class="more-link mt-3 py-2 px-3 text-uppercase">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		// translators: %s containing the title of the post or page.
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'clubfashion' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'clubfashion_excerpt_more' );

/**
 * Bootstrap Images img-fluid
 *
 * @param array $html Html code for image with Bootstrap class.
 */
function clubfashion_bootstrap_responsive_images( $html ) {
	$classes = 'img-fluid';
	if ( preg_match( '/<img.*? class="/', $html ) ) {
		$html = preg_replace( '/(<img.*? class=".*?)(".*?\/>)/', '$1 ' . $classes . ' $2', $html );
	} else {
		$html = preg_replace( '/(<img.*?)(\/>)/', '$1 class="' . $classes . '" $2', $html );
	}
	return $html;
}
add_filter( 'the_content', 'clubfashion_bootstrap_responsive_images', 10 );
add_filter( 'post_thumbnail_html', 'clubfashion_bootstrap_responsive_images', 10 );

/**
 * Comment Reply
 */
function clubfashion_comment_reply() {
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'clubfashion_comment_reply' );

/**
 * Bootstrap Comment Form
 *
 * @param array $clubfashion_fields Comment Form Fields.
 */
function clubfashion_comment_form_fields( $clubfashion_fields ) {
	$clubfashion_commenter = wp_get_current_commenter();
	$clubfashion_req       = get_option( 'require_name_email' );
	$clubfashion_aria_req  = ( $clubfashion_req ? " aria-required='true'" : '' );
	$clubfashion_html5     = current_theme_supports( 'html5', 'comment-form' ) ? 1 : 0;
	$clubfashion_fields    = array(
		'author' => '<div class="form-group comment-form-author"><input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $clubfashion_commenter['comment_author'] ) . '" placeholder="' . ( $clubfashion_req ? '* ' : '' ) . __( 'Name', 'clubfashion' ) . '..." size="30"' . $clubfashion_aria_req . ' /></div>',
		'email'  => '<div class="form-group comment-form-email"><input class="form-control" id="email" name="email" ' . ( $clubfashion_html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $clubfashion_commenter['comment_author_email'] ) . '" placeholder="' . ( $clubfashion_req ? '* ' : '' ) . __( 'Email', 'clubfashion' ) . '..." size="30"' . $clubfashion_aria_req . ' /></div>',
		'url'    => '<div class="form-group comment-form-url"><input class="form-control" id="url" name="url" ' . ( $clubfashion_html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $clubfashion_commenter['comment_author_url'] ) . '" placeholder="' . __( 'Website', 'clubfashion' ) . '..." size="30" /></div>',
	);
	return $clubfashion_fields;
}
add_filter( 'comment_form_default_fields', 'clubfashion_comment_form_fields' );

/**
 * Bootstrap Comment Form
 *
 * @param array $clubfashion_args Comment Form Fields.
 */
function clubfashion_comment_form( $clubfashion_args ) {
	$clubfashion_args['comment_field'] = '<div class="form-group comment-form-comment"><textarea class="form-control" id="comment" name="comment" cols="45" rows="8" placeholder="' . esc_attr__( 'Comment', 'clubfashion' ) . '..." aria-required="true"></textarea></div>';
	$clubfashion_args['class_submit']  = 'btn btn-default btn-submit';
	return $clubfashion_args;
}
add_filter( 'comment_form_defaults', 'clubfashion_comment_form' );

?>
