<?php

/**
 * The header for our WooCommerce theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/safari-pinned-tab.svg" color="#0d6efd">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- Loads the internal WP jQuery. Required if a 3rd party plugin loads jQuery in header instead in footer -->
    <?php wp_enqueue_script('jquery'); ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <div id="to-top"></div>

    <div id="page" class="site">

        <header id="masthead" class="site-header">

            <div class="">

                <nav id="nav-main" class="navbar navbar-expand-lg navbar-expand-md sticky-top bg-white navbar-white">

                    <div class="container-fluid">
                        <!-- ## SideNavBar Menu ## -->
                        <!-- Navbar Toggler LEFT for large devise-->
                        <button class="btn btn-outline-primary md d-none d-md-block mx-5 ms-md-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-sidenavbar" aria-controls="offcanvas-sidenavbar">
                            <i class="fas fa-bars"></i>
                        </button>
                        <!-- END SideNav Menu -->

                        <!-- ## Logo Brand ## -->
                        <a class="navbar-brand xs d-md-none" href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/logo/logo-sm.svg" alt="logo" class="logo xs"></a>
                        <a class="navbar-brand md d-none d-md-block" href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/logo/logo.svg" alt="logo" class="logo md"></a>

                        <!-- Offcanvas Navbar -->
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-navbar">
                            <div class="offcanvas-header hover cursor-pointer bg-light" data-bs-dismiss="offcanvas">
                                <i class="fas fa-chevron-left"></i> <?php esc_html_e('Close menu', 'bootscore'); ?>
                            </div>
                        </div>

                        <div class="header-actions d-flex align-items-center">
                            <!-- ### dropdown menu for collection ###  -->
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle ms-1 ms-md-2" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    Collections
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="<?php echo do_shortcode('[homeurl]/etiquette-produit/couteaux-sommeliers/'); ?>">Couteaux sommeliers</a></li>
                                    <li><a class=" dropdown-item" href="<?php echo do_shortcode('[homeurl]/etiquette-produit/couteaux-pliants/'); ?>">Couteaux pliants</a></li>
                                    <li><a class="dropdown-item" href="<?php echo do_shortcode('[homeurl]/etiquette-produit/couteaux-de-table/'); ?>">Couteaux de table</a></li>
                                </ul>
                            </div>
                            <!-- end dropdown collection menu  -->
                            <!-- Top Nav Widget select the one you prefere-->
                            <div class="top-nav-widget">
                                <?php if (is_active_sidebar('top-nav')) : ?>
                                    <div class="d-flex">
                                        <?php dynamic_sidebar('top-nav'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- User Toggler -->
                            <button class="btn btn-outline-primary ms-1 ms-md-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-user" aria-controls="offcanvas-user">
                                <i class="fas fa-user"></i>
                            </button>

                            <!-- Mini Cart Toggler -->
                            <button class="btn btn-outline-primary ms-1 ms-md-2 position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-cart" aria-controls="offcanvas-cart">
                                <i class="fas fa-shopping-bag"></i>
                                <?php if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                                    $count = WC()->cart->cart_contents_count;
                                ?>
                                    <span class="cart-content">
                                        <?php if ($count > 0) { ?>
                                            <?php echo esc_html($count); ?>
                                        <?php
                                        }
                                        ?></span>
                                <?php } ?>
                            </button>

                            <!-- Navbar Toggler Right for small devise-->
                            <button class="btn btn-outline-primary d-lg-none ms-1 ms-md-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-sidenavbar" aria-controls="offcanvas-sidenavbar">
                                <i class="fas fa-bars"></i>
                            </button>

                        </div><!-- .header-actions -->

                    </div><!-- .container -->

                </nav><!-- .navbar -->


                <!-- Top Nav Search Collapse -->
                <div class="collapse container" id="collapse-search">
                    <?php if (is_active_sidebar('top-nav-search')) : ?>
                        <div class="mb-2">
                            <?php dynamic_sidebar('top-nav-search'); ?>
                        </div>
                    <?php endif; ?>
                </div>


            </div><!-- .fixed-top .bg-light -->

            <!-- offcanvas sideNavBar  -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-sidenavbar">
                <div class="offcanvas-header cursor-pointer hover bg-white text-dark" data-bs-dismiss="offcanvas">
                    <?php esc_html_e('Close menu', 'bootscore'); ?> <i class="fas fa-chevron-left"></i>
                </div>
                <div class="offcanvas-body">
                    <div class="my-offcancas-sidenavbar">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'my-navigation-menu',
                            'container' => false,
                            'menu_class' => '',
                            'fallback_cb' => '__return_false',
                            'items_wrap' => '<ul id="bootscore-navbar" class="navbar-nav ms-auto %2$s">%3$s</ul>',
                            'depth' => 2,
                            'walker' => new bootstrap_5_wp_nav_menu_walker()
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <!-- offcanvas user -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-user">
                <div class="offcanvas-header cursor-pointer hover bg-light text-dark" data-bs-dismiss="offcanvas">
                    <?php esc_html_e('Close account', 'bootscore'); ?> <i class="fas fa-chevron-left"></i>
                </div>
                <div class="offcanvas-body">
                    <div class="my-offcancas-account">
                        <?php include get_template_directory() . '/woocommerce/myaccount/my-account-offcanvas.php'; ?>
                    </div>
                </div>
            </div>

            <!-- offcanvas cart -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-cart">
                <div class="offcanvas-header cursor-pointer hover bg-light text-dark" data-bs-dismiss="offcanvas">
                    <i class="fas fa-chevron-left"></i> <?php esc_html_e('Continue shopping', 'bootscore'); ?>
                </div>
                <div class="offcanvas-body p-0">
                    <div class="cart-loader bg-white position-absolute end-0 bottom-0 start-0 d-flex align-items-center justify-content-center">
                        <div class="loader-icon ">
                            <div class="spinner-border text-dark"></div>
                        </div>
                    </div>
                    <div class="cart-list">
                        <h2 class="p-3"><?php esc_html_e('Cart', 'bootscore'); ?></h2>
                        <div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>
                    </div>
                </div>
            </div>

        </header><!-- #masthead -->

        <?php bootscore_ie_alert(); ?>