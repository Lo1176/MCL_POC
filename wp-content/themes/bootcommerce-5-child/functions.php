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

/* Supprimer le fil d'Ariane de WooCommerce */

// add_action('init', 'wpm_remove_wc_breadcrumbs');
// function wpm_remove_wc_breadcrumbs()
// {
//     remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
// }

// remove_action('woocommerce_before_main_content',
//     'woocommerce_breadcrumb',
//     20,
//     0
// );
// Remove breadcrumbs from shop & categories
// add_filter('woocommerce_before_main_content', 'remove_breadcrumbs');
// function remove_breadcrumbs()
// {
//     if (!is_product()) {
//         remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
//     }
// }


// Remove breadcrumbs only from shop page
add_filter('woocommerce_before_main_content', 'remove_breadcrumbs');
function remove_breadcrumbs()
{
    if (!is_product() && !is_product_category()) {
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
    }
}

// for my images ...
function homeURLshortcode()
{
    return home_url();
}
add_shortcode('homeurl', 'homeURLshortcode');
