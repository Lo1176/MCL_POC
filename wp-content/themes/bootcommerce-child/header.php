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

            <div class="sticky-top bg-white">
            <!-- <div class=""> -->

                <nav id="nav-main" class="navbar navbar-expand-lg navbar-expand-md navbar-white">

                    <div class="header-height container-fluid smart-scroll py-0 pt-sm-3 ps-1 ps-md-2 bg-white border-bottom d-flex flex-nowrap justify-content-sm-between align-items-end">

                        <!-- test burger btn  -->
                        <!-- Collapse button "FROMAGE..." -->
                        <button class="btn d-none animated-burger" type="button" data-toggle="collapse" data-target="#navbarSupportedContent23" aria-controls="navbarSupportedContent23" aria-expanded="false" aria-label="Toggle navigation">
                            <div class="stripes nav-icon-5">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </button>
                        <!-- test burger btn  END-->

                        <!-- Burger-btn toggler LEFT for large devise "...OU DESSERT" -->
                        <button class="btn btn-outline-primary md d-none d-md-block mx-5 ms-md-2 burger-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-sidenavbar" aria-controls="offcanvas-sidenavbar">
                            <!-- <i class="fas fa-bars"></i> -->
                            <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/svg/bars.svg" alt="bars logo" style="width: 48px;">
                        </button>




                        <!-- btn retour en arrière -->
                        <!-- <button class="btn btn-outline-primary md d-none d-md-block mx-5 ms-md-2"  onclick="history.go(-1);">RETOUR</button> -->

                        <!-- Logo Brand -->
                        <a class="navbar-brand align-center xs d-md-none" href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/logo/mcl-black.svg" alt="logo" class="logo xs"></a>
                        <!-- logo brand disable for large devise class="... md d-md-block" -->
                        <a class="navbar-brand d-none" href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/logo/mcl-black.svg" alt="logo" class="logo md"></a>



                        <div class="header-actions d-flex align-items-end">

                            <!-- Top Nav Widget select the one you prefere-->
                            <div class="top-nav-widget">
                                <?php if (is_active_sidebar('top-nav')) : ?>
                                    <div class="d-flex">
                                        <?php dynamic_sidebar('top-nav'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- contact -->
                            <div class="contact-link d-none d-md-block me-3">
                                <a href="<?php echo esc_url(get_permalink(get_page_by_title('contact'))); ?>" class="d-inline-flex align-items-baseline">

                                    <img class="phone-logo" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/svg/phone.svg" alt="phone logo" class="">
                                    <p class="my-0 mx-1">Contactez-nous</p>
                                </a>
                            </div>

                            <!-- flag -->
                            <div class="flag-link d-none d-md-block me-3">
                                <a href="<?php #echo esc_url(get_permalink(get_page_by_title('contact'))); 
                                            ?>" class="d-inline-flex align-items-center">

                                    <img class="flag-logo" src="<?php echo esc_url(get_stylesheet_directory_uri());
                                                                ?>/img/logo/france.png" alt="flag logo" class="">
                                    <p class="my-0 mx-1"></p>
                                </a>
                            </div>

                            <!-- need help -->
                            <div class="help-link d-none d-md-block me-3">
                                <a href="<?php echo esc_url(get_permalink(get_page_by_title('aide'))); ?>" class="d-inline-flex align-items-center">

                                    <img class="help-logo" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/svg/help.svg" alt="help logo" class="">
                                    <p class="my-0 mx-1">Besoin d'aide ?</p>
                                </a>
                            </div>

                            <!-- vertical separator  -->
                            <div class="vertical-separator d-none d-md-block me-3">
                                <h2 class="mb-0">|</h2>
                            </div>


                            <!-- wishlist Toggler -->
                            <div class=" wishlist-link"><?php echo do_shortcode('[ti_wishlist_products_counter]'); ?>
                            </div>

                            <!-- User Toggler -->
                            <button class="btn btn-outline-primary ms-1 ms-md-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-user" aria-controls="offcanvas-user">
                                <!-- <i class="fas fa-user"></i> -->
                                <img class="user-logo" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/svg/user.svg" alt="user logo">
                            </button>

                            <!-- Mini Cart Toggler -->
                            <button class="btn btn-outline-primary ms-1 me-md-4 position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-cart" aria-controls="offcanvas-cart">
                                <!-- <i class="fas fa-shopping-bag"></i> -->
                                <img class="cart-logo" src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/svg/cart.svg" alt="cart logo">

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
                            <button class="btn btn-outline-primary d-md-none ms-1 ms-md-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-sidenavbar" aria-controls="offcanvas-sidenavbar">
                                <!-- <i class="fas fa-bars"></i> -->
                                <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/svg/bars.svg" alt="bars logo" style="width: 48px;">
                            </button>


                        </div><!-- .header-actions -->

                    </div><!-- .container-fluid -->

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
            <div class="navbar">
                <!-- offcanvas sideNavBar on the left -->
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-sidenavbar">
                    <!-- <div class="offcanvas-header cursor-pointer hover bg-white text-dark" data-bs-dismiss="offcanvas">
                        <?php #esc_html_e('Close menu', 'bootscore'); 
                        ?> <i class="fas fa-chevron-left"></i>
                    </div> -->
                    <div class="offcanvas-body">
                        <div class="my-offcanvas-sidenavbar">
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
                </div>
            </div>

            <!-- offcanvas user -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-user">
                <div class="offcanvas-header cursor-pointer hover bg-light text-dark" data-bs-dismiss="offcanvas">
                    <?php esc_html_e('Close account', 'bootscore'); ?> <i class="fas fa-chevron-left"></i>
                </div>
                <div class="offcanvas-body">
                    <div class="my-offcanvas-account">
                        <?php include get_stylesheet_directory() . '/woocommerce/myaccount/my-account-offcanvas.php'; ?>
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