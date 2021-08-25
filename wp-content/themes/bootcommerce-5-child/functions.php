<?php

// style and scripts
add_action('wp_enqueue_scripts', 'bootscore_5_child_enqueue_styles');
function bootscore_5_child_enqueue_styles()
{

    // style.css
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // custom.js
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', false, '', true);
}

// WooCommerce
require get_template_directory() . '/woocommerce/woocommerce-functions.php';

##### Menu #####
/* 
* add Custom Navigation Menu
*/

function lbi_custom_new_menu()
{
    register_nav_menu('my-navigation-menu', __('Navigation menu'));
}
add_action('init', 'lbi_custom_new_menu');

/* end Custom Navigation Menu*/

##### CONTENT-PRODUCT #####
// Archives pages: Additional button linked to the product
// add_action('woocommerce_after_shop_loop_item', 'loop_continue_button', 15);
// function loop_continue_button()
// {
//     global $product;

//     if ($product->is_type('simple')) {
//         $link = $product->get_permalink();
//         $text = __("Continue", "woocommerce");

//         echo '<a href="' . $link . '" class="button alt" style="margin-top:10px;">' . $text . '</a>';
//     }
// }
add_action('woocommerce_after_shop_loop_item', 'custom_3D_button', 21);

##### CONTENT-SINGLE-PRODUCT #####
/* Single product pages: Additional button linked to checkout */
add_action('woocommerce_single_product_summary', 'product_additional_3D_button', 1);
function product_additional_3D_button()
{
    global $product;
    // For variable product types
    if ($product->is_type('variable')) {
        add_action('woocommerce_single_product_summary', 'custom_3D_button', 21);
    }
    // For all other product types
    else {
        add_action('woocommerce_single_product_summary', 'custom_3D_button', 31);
    }
}

function custom_3D_button()
{
    global $product;
    $link = "/www/MCL_POC/wp-json/3D/v1/product/" . $product->get_id();
    $text = __("Customize with 3D app", "woocommerce");
    echo '<button id="customize3d" class="btn btn-danger my-1" style="margin-bottom:14px;" data-url="' . $link . '">' . $text . '</button>';
}

// function btn test API
add_action('rest_api_init', function () {
    register_rest_route('3D/v1', '/product/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'my_3D_func',
        // If your REST API endpoint is public, you can use __return_true as the permission callback.
        'permission_callback' => '__return_true',
    ));
});

function my_3D_func($data)
{
    $productId = $data['id'];
    $product = wc_get_product( $productId );

    
    $api3dRequest = array(
        'id' => $product->get_id(), // id du produit récupéré en base de donnée
        'produit' => $productId, // id from url
        'entretoise' => $data['entretoise'],
        'couleur' => $data['couleur']
    );
    // to do : appeler API 3D request 
    $api3dResponse = array(
        'url' => "https://lignew.clients.arkima.io/configurator/app/index.html"
    );
    return $api3dResponse;
}

/* Ne pas afficher l'UGS sur vos pages produits (content-single-product) */
add_filter('wc_product_sku_enabled', 'wpm_remove_sku');

function wpm_remove_sku($enabled)
{
    // Si on est pas dans l'admin et si on est sur la page produit
    if (!is_admin() && is_product()) {
        return false;
    }
    return $enabled;
}

##### END ----- SINGLE-PRODUCT #####


// Remove breadcrumbs only from shop page
add_filter('woocommerce_before_main_content', 'remove_breadcrumbs');
function remove_breadcrumbs()
{
    if (!is_product() && !is_product_category()) {
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
    }
}

/**
 * to prevent break links between localhost and http
 */
function homeURLshortcode()
{
    return home_url();
}
add_shortcode('homeurl', 'homeURLshortcode');

// ############ A VERIFIER CA NE SEMBLE PAS MARCHER ###############
/**
 * Change number or products per row to 3
 */
add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
    function loop_columns()
    {
        return 3; // 3 products per row
    }
}


/**
 * Change number of products that are displayed per page (shop page)
 */
add_filter('loop_shop_per_page', 'new_loop_shop_per_page', 20);

function new_loop_shop_per_page($cols)
{
    // $cols contains the current number of products per page based on the value stored on Options –> Reading
    // Return the number of products you wanna show per page.
    $cols = 9;
    return $cols;
}
