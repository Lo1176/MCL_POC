<?php

// style and scripts
add_action('wp_enqueue_scripts', 'bootscore_child_enqueue_styles');
function bootscore_child_enqueue_styles()
{

  // style.css
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

  // Compiled Bootstrap
  $modified_bootscoreChildCss = date('YmdHi', filemtime(get_stylesheet_directory() . '/css/lib/bootstrap.min.css'));
  wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/css/lib/bootstrap.min.css', array('parent-style'), $modified_bootscoreChildCss);

  // custom.js
  wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', false, '', true);
}

// WooCommerce
require get_template_directory() . '/woocommerce/woocommerce-functions.php';

// add your custom functions bellow //

##### start-Menu #####
// Custom-Navigation-Menu
function mcl_custom_new_menu()
{
  register_nav_menu('my-navigation-menu', __('Navigation menu'));
}
add_action('init', 'mcl_custom_new_menu');
// Custom-Navigation-Menu  END
##### end-Menu #####

##### start-CONTENT-PRODUCT #####
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
##### end-CONTENT-PRODUCT #####

##### start-CONTENT-SINGLE-PRODUCT #####
// Single product pages: Additional button linked to 3D website
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
  write_log('*****');
  write_log('my 3D func input');
  write_log($data);
  write_log('*****');
  // $productId = $data['id']; // get product id from data url
  $product = wc_get_product($data); // get product id from DB

  // test API avec l'EXUDE-API TBE
  // $urlApi = "https://exude-api.herokuapp.com/exude/stopping/data";
  // $apiTestRequest = array(
  //     "links" => ["https://en.wikipedia.org/wiki/Rama"]
  // );
  // $apiTestRequestJSON = json_encode($apiTestRequest);
  // wp_remote_post($urlApi, $apiTestRequestJSON);

  // $apiTestRequest2 = array(
  //     "data" => "Kannada is a Southern Dravidian language and according to scholar Sanford B. Steever, its history can be conventionally divided into three stages: Old Kannada (Halegannada) from 450–1200 AD, Middle Kannada (Nadugannada) from 1200–1700 and Modern Kannada from 1700 to the present.[23] Kannada is influenced to a considerable degree by Sanskrit. Influences of other languages such as Prakrit and Pali can also be found in Kannada. The scholar Iravatham Mahadevan indicated that Kannada was already a language of rich spoken tradition earlier than the 3rd century BC and based on the native Kannada words found in Prakrit inscriptions of that period, Kannada must have been spoken by a broad and stable population.[24][25] The scholar K. V. Narayana claims that many tribal languages which are now designated as Kannada dialects could be nearer to the earlier form of the language, with lesser influence from other languages"
  // );
  // $apiTestRequest2JSON = json_encode($apiTestRequest2);
  // wp_remote_post($urlApi, $apiTestRequest2JSON);

  // Get Product ID
  // global $product;
  // $productId = $product->get_id();

  $api3dRequest = array(
    // 'produit' => $productId, // id from url
    'id' => $product, // id du produit récupéré en base de donnée
    'entretoise' => $data['entretoise'],
    'couleur' => $data['couleur']
  );
  $api3dRequestJSON = json_encode($api3dRequest);

  $api3dHttpRequest = [
    'body'        => $api3dRequestJSON,
    'headers'     => [
      'Content-Type' => 'application/json',
    ], // précise qu'on lui envoie du JSON en data
    'timeout'     => 60,
    'redirection' => 5,
    'blocking'    => true,
    'httpversion' => '1.0',
    'sslverify'   => false,
    'data_format' => 'body',
  ];

  // to do : appeler API 3D request
  // test API avec l'EXUDE-API TBE
  // $urlApi = "https://exude-api.herokuapp.com/exude/stopping/data";
  $urlApi = "https://qa-lignew-lignew-configurator.preview.arkima.io/configurator/app/index.html";
  // $api3dHttpResponse = wp_remote_post($urlApi, $api3dHttpRequest);
  $api3dResponse = array(
    // 'url3d' => "https://lignew.clients.arkima.io/configurator/app/index.html"
    // 'url3d' => wp_remote_post($urlApi, $api3dHttpRequest)
    'url3d' => $urlApi
  );
  return $api3dResponse;
}
##### end-CONTENT-SINGLE-PRODUCT #####


// log errors
if (!function_exists('write_log')) {

  function write_log($log)
  {
    if (true === WP_DEBUG) {
      if (is_array($log) || is_object($log)) {
        error_log(print_r($log, true));
      } else {
        error_log($log);
      }
    }
  }
}

// Ne pas afficher l'UGS sur vos pages produits (content-single-product)
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

//to prevent break links between localhost and http

function homeURLshortcode()
{
  return home_url();
}
add_shortcode('homeurl', 'homeURLshortcode');

// ############ A VERIFIER CA NE SEMBLE PAS MARCHER ###############
// Change number or products per row to 3

add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
  function loop_columns()
  {
    return 3; // 3 products per row
  }
}


// Change number of products that are displayed per page (shop page)

add_filter('loop_shop_per_page', 'new_loop_shop_per_page', 20);

function new_loop_shop_per_page($cols)
{
  // $cols contains the current number of products per page based on the value stored on Options –> Reading
  // Return the number of products you wanna show per page.
  $cols = 9;
  return $cols;
}

// Autoriser les fichiers SVG
function wpc_mime_types($mimes)
{
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'wpc_mime_types');
/* rajouter <?xml version="1.0" encoding="utf-8"?> au début du svg */
