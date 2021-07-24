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

                <nav id="nav-main" class="navbar navbar-expand-lg navbar-expand-md sticky-top bg-light navbar-light">

                    <div class="container-fluid">
                        <!-- SideNav Menu -->
                        <!-- Sidenav -->
                        <div class="collapse" id="navbarToggleExternalContent">
                            <div class="bg-dark p-4">
                                <h5 class="text-white h4">Collapsed content</h5>
                                <span class="text-muted">Toggleable via the navbar brand.</span>
                            </div>
                        </div>
                        <nav class="navbar navbar-dark bg-dark">
                            <div class="container-fluid">
                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                            </div>
                        </nav>
                        <!-- Sidenav -->
                        <!-- END SideNav Menu -->

                        <!-- Navbar Brand -->
                        <a class="navbar-brand xs d-md-none" href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/logo/logo-sm.svg" alt="logo" class="logo xs"></a>
                        <a class="navbar-brand md d-none d-md-block" href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/logo/logo.svg" alt="logo" class="logo md"></a>

                        <!-- Offcanvas Navbar -->
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-navbar">
                            <div class="offcanvas-header hover cursor-pointer bg-light text-primary" data-bs-dismiss="offcanvas">
                                <i class="fas fa-chevron-left"></i> <?php esc_html_e('Close menu', 'bootscore'); ?>
                            </div>
                            <div class="offcanvas-body">
                                <!-- Bootstrap 5 Nav Walker Main Menu -->
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
                                <!-- Bootstrap 5 Nav Walker Main Menu End -->
                            </div>
                        </div>

                        <div class="header-actions d-flex align-items-center">

                            <!-- Top Nav Widget -->
                            <div class="top-nav-widget">
                                <?php if (is_active_sidebar('top-nav')) : ?>
                                    <div class="d-flex">
                                        <?php dynamic_sidebar('top-nav'); ?>
                                    </div>
                                <?php endif; ?>


                            </div>

                            <!-- Search Toggler -->
                            <!-- <button class="btn btn-outline-secondary ms-1 ms-md-2 top-nav-search-md" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-search" aria-expanded="false" aria-controls="collapse-search">
                                <i class="fas fa-search"></i>
                            </button> -->
                            <!-- loop de recherche toute seule et inutile permet d'ouvrir le widget search... -->

                            <!-- User Toggler -->
                            <button class="btn btn-outline-secondary ms-1 ms-md-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-user" aria-controls="offcanvas-user">
                                <i class="fas fa-user"></i>
                            </button>

                            <!-- Mini Cart Toggler -->
                            <button class="btn btn-outline-secondary ms-1 ms-md-2 position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-cart" aria-controls="offcanvas-cart">
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

                            <!-- Navbar Toggler -->
                            <button class="btn btn-outline-secondary d-lg-none ms-1 ms-md-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-navbar" aria-controls="offcanvas-navbar">
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
            <nav class="offcanvas-body">
                <!-- Bootstrap 5 Nav Walker Main Menu -->
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'my-navigation-menu',
                    'container' => false,
                    //'container_class' => 'custom-menu-class',
                    'menu_class' => '',
                    'fallback_cb' => '__return_false',
                    'items_wrap' => '<ul id="bootscore-navbar" class="navbar ms-auto %2$s">%3$s</ul>',
                    'depth' => 2,
                    'container_class' => 'custom-menu-class',
                    'walker' => new bootstrap_5_wp_nav_menu_walker()
                ));
                ?>
                <!-- 'items_wrap' => '<ul id="bootscore-navbar" class="navbar navbar-nav navbar-expand-lg navbar-light bg-light ms-auto %2$s">%3$s</ul>', -->
                <!-- Bootstrap 5 Nav Walker Main Menu End -->
            </nav>

            <!-- offcanvas user -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-user">
                <div class="offcanvas-header cursor-pointer hover bg-light text-primary" data-bs-dismiss="offcanvas">
                    <?php esc_html_e('Close account', 'bootscore'); ?> <i class="fas fa-chevron-right"></i>
                </div>
                <div class="offcanvas-body">
                    <div class="my-offcancas-account">
                        <?php include get_template_directory() . '/woocommerce/myaccount/my-account-offcanvas.php'; ?>
                    </div>
                </div>
            </div>

            <!-- offcanvas cart -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-cart">
                <div class="offcanvas-header cursor-pointer hover bg-light text-primary" data-bs-dismiss="offcanvas">
                    <i class="fas fa-chevron-left"></i> <?php esc_html_e('Continue shopping', 'bootscore'); ?>
                </div>
                <div class="offcanvas-body p-0">
                    <div class="cart-loader bg-white position-absolute end-0 bottom-0 start-0 d-flex align-items-center justify-content-center">
                        <div class="loader-icon ">
                            <div class="spinner-border text-primary"></div>
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