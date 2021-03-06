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
/**
 * Set WooCommerce image dimensions upon theme activation
 */
// Remove each style one by one (e.i. woocommerce-page css)
add_filter('woocommerce_enqueue_styles', 'mcl_dequeue_styles');
function mcl_dequeue_styles($enqueue_styles)
{
  unset($enqueue_styles['woocommerce-layout']); //Remove woocommerce-page css
  return $enqueue_styles;
}

// WooCommerce
require get_template_directory() . '/woocommerce/woocommerce-functions.php';

// ** add your custom functions bellow ** //


##### start - Menu #####
// Custom-Navigation-Menu
function mcl_custom_new_menu()
{
  register_nav_menu('my-navigation-menu', __('Navigation menu'));
  register_nav_menu('rigt-menu', 'Right menu');

}
add_action('init', 'mcl_custom_new_menu');
// Custom-Navigation-Menu  END

##### end - Menu #####

/**
 * add wishlist button
 */
// do_shortcode("[ti_wishlists_addtowishlist loop=yes]");
function mcl_whishlist_icon()
{
  echo do_shortcode("[ti_wishlists_addtowishlist loop=yes]");
};
// add wish-icon on content-single-product.php
add_action('woocommerce_single_product_summary', 'mcl_whishlist_icon', 11);
// add wish-icon on content-product.php
add_action('woocommerce_before_shop_loop_item_title', 'mcl_whishlist_icon', 10);
// wishlist button END

// Minicart Header
remove_filter('woocommerce_add_to_cart_fragments', 'bs_mini_cart', 10);
function bs_mini_cart($fragments)
{

  ob_start();
  $count = WC()->cart->cart_contents_count; ?>
  <span class="cart-content">
    <?php if ($count > 0) { ?>
      <span class="cart-content-count position-absolute start-100 translate-middle badge rounded-pill bg-black"><?php echo esc_html($count); ?></span><span class="cart-total ms-1 d-none d-md-inline"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
    <?php } ?>
  </span>

<?php
  $fragments['span.cart-content'] = ob_get_clean();

  return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'bs_mini_cart', 11);
// Minicart Header End


##### start - CONTENT-SINGLE-PRODUCT #####
/** remove title */
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);

/** change order of description (move the description on the top) */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 6);

/** remove price */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 16);
/** remove add-to-cart */
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

// add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 6);

/** Remove product data tabs */
add_filter('woocommerce_product_tabs', 'woo_remove_product_tabs', 98);
/** remove product meta */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
/** whishes logo product */
// custom text after description product summary
// add_action('woocommerce_before_add_to_cart_form', 'add_text_after_excerpt_single_product', 30);
// function add_text_after_excerpt_single_product()
// {
//   global $product;

//   // Output your custom text
//   echo '<hr class="line-separator"><div class="custom-mcl-text-in-function red">
//     <p>Current Delivery Times: Pink Equine - 4 - 6 Weeks, all other products 4 Weeks</p>
//     </div>';
// }

/** Add line-separator after description */
add_action('woocommerce_single_product_summary', 'add_line_separator_after_excerpt_single_product', 15);
function add_line_separator_after_excerpt_single_product()
{
  // global $product;

  // Output your custom text
  echo '<hr class="line-separator">';
}

/** add <div> to have btn inline */
add_action('woocommerce_single_product_summary', 'mcl_add_div_btn_inline', 19);
function mcl_add_div_btn_inline()
{
  echo '<div class="btn-inline row">';
};

function mcl_add_div_product_result_count()
{
  echo '<div class="row"><div class="col-sm-2"></div>';
};

add_action('woocommerce_single_product_summary', 'mcl_add_div_end', 60);
function mcl_add_div_end()
{
  echo '</div>';
};
/** function to remove or change order for tabs */
function woo_remove_product_tabs($tabs)
{
  // unset($tabs['additional_information']);    // Remove the additional information tab
  // unset($tabs['description']);    // Remove the description tab
  // $tabs['additional_information']['priority'] = 5;	// move Additional information at the beginning

  return $tabs;
}


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
/** */
function custom_3D_button()
{
  global $product;
  $theme_link = get_stylesheet_directory_uri();
  $svg_link = "/img/svg/logo_3d.svg";
  $link = "/www/MCL_POC/wp-json/3D/v1/product/" . $product->get_id();
  // $text = __("PERSONNALISER mon mod??le", "woocommerce");
  $text1 = nl2br(__("PERSONNALISER\n", "woocommerce"));
  $text2 = __("mon mod??le", "woocommerce");
  echo '<div id="customize3d" class="col-sm-12"><img src="' . $theme_link . $svg_link . '" alt="3D logo"/><button class="btn btn3d my-1" style="margin-bottom:14px;" data-url="' . $link . '"><strong>' . $text1 . '</strong>' . $text2 . '</button></div>';
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
  //     "data" => "Kannada is a Southern Dravidian language and according to scholar Sanford B. Steever, its history can be conventionally divided into three stages: Old Kannada (Halegannada) from 450???1200 AD, Middle Kannada (Nadugannada) from 1200???1700 and Modern Kannada from 1700 to the present.[23] Kannada is influenced to a considerable degree by Sanskrit. Influences of other languages such as Prakrit and Pali can also be found in Kannada. The scholar Iravatham Mahadevan indicated that Kannada was already a language of rich spoken tradition earlier than the 3rd century BC and based on the native Kannada words found in Prakrit inscriptions of that period, Kannada must have been spoken by a broad and stable population.[24][25] The scholar K. V. Narayana claims that many tribal languages which are now designated as Kannada dialects could be nearer to the earlier form of the language, with lesser influence from other languages"
  // );
  // $apiTestRequest2JSON = json_encode($apiTestRequest2);
  // wp_remote_post($urlApi, $apiTestRequest2JSON);

  // Get Product ID
  // global $product;
  // $productId = $product->get_id();

  $api3dRequest = array(
    // 'produit' => $productId, // id from url
    'id' => $product, // id du produit r??cup??r?? en base de donn??e
    'entretoise' => $data['entretoise'],
    'couleur' => $data['couleur']
  );
  $api3dRequestJSON = json_encode($api3dRequest);

  $api3dHttpRequest = [
    'body'        => $api3dRequestJSON,
    'headers'     => [
      'Content-Type' => 'application/json',
    ], // pr??cise qu'on lui envoie du JSON en data
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
/** test API END */

/** 
 * Hide Price Range for WooCommerce Variable Products
 */
function mcl_wc_varb_price_range($wcv_price, $product)
{

  // $prefix = sprintf('%s: ', __('From', 'wcvp_range'));
  $prefix = sprintf('%s:', __('From', 'wcvp_range'));

  $wcv_reg_min_price = $product->get_variation_regular_price('min', true);
  $wcv_min_sale_price    = $product->get_variation_sale_price('min', true);
  $wcv_max_price = $product->get_variation_price('max', true);
  $wcv_min_price = $product->get_variation_price('min', true);

  $wcv_price = ($wcv_min_sale_price == $wcv_reg_min_price) ?
    wc_price($wcv_reg_min_price) :
    '<del>' . wc_price($wcv_reg_min_price) . '</del>' . '<ins>' . wc_price($wcv_min_sale_price) . '</ins>';

  return ($wcv_min_price == $wcv_max_price) ?
    $wcv_price :
    sprintf('%s%s', $prefix, $wcv_price);
}

add_filter('woocommerce_variable_sale_price_html', 'mcl_wc_varb_price_range', 10, 2);
add_filter('woocommerce_variable_price_html', 'mcl_wc_varb_price_range', 10, 2);

//Hide "From:$X" 
add_filter('woocommerce_get_price_html', 'mcl_hide_variation_price', 10, 2);
function mcl_hide_variation_price($v_price, $v_product)
{
  $v_product_types = array('variable');
  if (in_array($v_product->get_type(), $v_product_types)) {
    return '';
  }
  // return regular price 
  return $v_price;
}
/* Hide Price Range for WooCommerce Variable Products END */

##### end - CONTENT-SINGLE-PRODUCT #####


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



// ############ A VERIFIER CA NE SEMBLE PAS MARCHER ###############
// Change number or products per row to 3

add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
  function loop_columns()
  {
    return 3; // 3 products per row
  }
}

/** 
 * content-product
 */
// test
// add_filter('woocommerce_get_stock_html', '__return_empty_string', 10, 2);
// add_filter('woocommerce_get_stock_html', 'my_wc_hide_in_stock_message', 10, 2);
function my_wc_hide_in_stock_message($html, $product)
{
  if ($product->is_in_stock()) {
    return '';
  }

  return $html;
}
// test end
// remove woocommerce-result-count
add_action('after_setup_theme', 'mcl_remove_product_result_count', 99);
function mcl_remove_product_result_count()
{
  remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
  remove_action('woocommerce_after_shop_loop', 'woocommerce_result_count', 20);
  add_action('woocommerce_before_shop_loop', 'mcl_add_div_product_result_count', 10);
}

// define the woocommerce_before_shop_loop_item_title callback
// function to add '<hr class="line-separator">'
add_action('woocommerce_before_shop_loop_item_title', 'action_woocommerce_before_shop_loop_item', 10, 0);
add_action('woocommerce_before_subcategory_title', 'action_woocommerce_before_shop_loop_item', 10, 0);
function action_woocommerce_before_shop_loop_item()
{
  echo '<hr class="line-separator">';
};

// fonction pour tester les loop
// add_action('woocommerce_before_shop_loop_item', 'mcl_test', 2);
// function mcl_test()
// {
//   echo 'hello my text';
// }

// add 3D_button after shop loop item
add_action('woocommerce_after_shop_loop_item', 'custom_3D_button', 21);

// Override loop template and show quantities next to add to cart buttons
add_filter('woocommerce_loop_add_to_cart_link', 'quantity_inputs_for_woocommerce_loop_add_to_cart_link', 10, 2);
function quantity_inputs_for_woocommerce_loop_add_to_cart_link($html, $product)
{
  if ($product && $product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock() && !$product->is_sold_individually()) {
    $html = wc_get_template_html('single-product/add-to-cart/simple.php');
  }
  return $html;
}

// when product is out of stock "choix des options"
// instead of "Lire la suite"
// add_filter( 'woocommerce_is_purchasable', 'vna_is_purchasable', 10, 2 );
// function vna_is_purchasable( $purchasable, $product ){
//   return true || false; // depending on your condition
// }

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


/* content-product END */

// Change number of products that are displayed per page (shop page)
add_filter('loop_shop_per_page', 'new_loop_shop_per_page', 20);
function new_loop_shop_per_page($cols)
{
  // $cols contains the current number of products per page based on the value stored on Options ???> Reading
  // Return the number of products you wanna show per page.
  $cols = 9;
  return $cols;
}

// Autoriser l'ajout de fichiers SVG
function mcl_mime_types($mimes)
{
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
/* penser ?? rajouter <?xml version="1.0" encoding="utf-8"?> au d??but du svg */
add_filter('upload_mimes', 'mcl_mime_types');


// Verifier si la page est parent/child/grandchild en utilisant le slug
// function is_tree( $page_id, $use_slug = false ) {

//   if ( $use_slog === true && !is_string( $page_id )) {
//     # code...
//   }

// }

/**
 * Hide some product-categories
 */
// add_filter('get_terms', 'ts_get_subcategory_terms', 10, 3);
function ts_get_subcategory_terms($terms, $taxonomies, $args)
{
  $new_terms = array();
  // if it is a product category and on the shop page
  #if (in_array('product_cat', $taxonomies) && !is_admin() && is_shop()) {
  if (in_array('product_cat', $taxonomies) && !is_admin()) {
    foreach ($terms as $key => $term) {
      if (!in_array($term->slug, array('boite', 'display', 'etui', 'mcl', 'packaging'))) { //pass the slug name here
        $new_terms[] = $term;
      }
    }
    $terms = $new_terms;
  }
  return $terms;
}


/**
 * Redirect URL
 */
// redirect main product-category to his brand page
function mcl_template_redirect()
{
  if (function_exists('is_product_category')) {
    if (is_product_category('ligne-w')) {
      $redirect_page_id = 299;
      wp_safe_redirect(get_permalink($redirect_page_id));
      exit();
    } elseif (is_product_category('chateau-laguiole')) {
      $redirect_page_id = 245;
      wp_safe_redirect(get_permalink($redirect_page_id));
      exit();
    } elseif (is_product_category('mateo-gallud')) {
      $redirect_page_id = 2330; // mateo-gallud page_id
      wp_redirect(get_permalink($redirect_page_id));
      exit();
    }
  } elseif (is_product_category('guy-vialis')) {
    $redirect_page_id = 2330; // guy-vialis page_id
    wp_redirect(get_permalink($redirect_page_id));
    exit();
  } else {
    return home_url();
  }
}
add_action('template_redirect', 'mcl_template_redirect');

// redirect function for Guy-Vialis and Mateo Gallud to 'soon'
function mcl_gv_redirect()
{
  if (is_page(1704)) {
    $redirect_page_id = 2330; // 'soon' page_id
    wp_redirect(get_permalink($redirect_page_id));
    exit();
  } elseif (is_page(3415)) {
    $redirect_page_id = 2330; // 'soon' page_id
    wp_redirect(get_permalink($redirect_page_id));
    exit();
  }
}
add_action('template_redirect', 'mcl_gv_redirect');
// remove_action('template_redirect', 'mcl_gv_redirect');

// change 'return-btn' to home URL
function mcl_change_return_shop_url()
{
  return home_url();
  // return wp_safe_redirect(home_url(), 302);
  // exit;
}
add_filter('woocommerce_return_to_shop_redirect', 'mcl_change_return_shop_url');

// change 'shop' breadcrumb to home URL
add_action('template_redirect', 'custom_shop_page_redirect');
function custom_shop_page_redirect()
{
  $search = array('s', 'taxonomy');
  if (is_shop() && !isset($_GET['s'])) {

    wp_safe_redirect(home_url(), 302);
    exit();
  }
}
/** redirect URL END */


/**
 * BREADCRUMB
 * WooCommerce Breadcrumb custom
 */
remove_filter('woocommerce_breadcrumb_defaults', 'bs_woocommerce_breadcrumbs', 10);
function mcl_woocommerce_breadcrumbs()
{
  return array(
    'delimiter'   => '<div class"delimiter style="font-size: 1.25rem;">&nbsp;&gt;&nbsp;</div> ',
    'wrap_before' => '<nav class="breadcrumb d-flex align-items-center justify-content-end mb-1 mb-sm-3 mt-2 py-sm-2 px-3 text-uppercase small" itemprop="breadcrumb">',
    'wrap_after'  => '</nav>',
    'before'      => '',
    'after'       => '',
    // 'home'        => _x('Home', 'breadcrumb', 'woocommerce'), => BOUTIQUE SHOP
  );
}
add_filter('woocommerce_breadcrumb_defaults', 'mcl_woocommerce_breadcrumbs', 11);

// WooCommerce Breadcrumb End


/**
 * @snippet       Bulk (Dynamic) Pricing - WooCommerce
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.8
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

add_action('woocommerce_before_calculate_totals', 'bbloomer_quantity_based_pricing', 9999);

function bbloomer_quantity_based_pricing($cart)
{

  if (is_admin() && !defined('DOING_AJAX')) return;

  if (did_action('woocommerce_before_calculate_totals') >= 2) return;

  // Define discount rules and thresholds
  $threshold1 = 100; // Change price if items > 100
  $discount1 = 0.05; // Reduce unit price by 5%
  $threshold2 = 1000; // Change price if items > 1000
  $discount2 = 0.1; // Reduce unit price by 10%

  foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
    if ($cart_item['quantity'] >= $threshold1 && $cart_item['quantity'] < $threshold2) {
      $price = round($cart_item['data']->get_price() * (1 - $discount1), 2);
      $cart_item['data']->set_price($price);
    } elseif ($cart_item['quantity'] >= $threshold2) {
      $price = round($cart_item['data']->get_price() * (1 - $discount2), 2);
      $cart_item['data']->set_price($price);
    }
  }
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bootscore_widgets_init()
{
  // Top Nav
  register_sidebar(array(
    'name' => esc_html__('Top Nav', 'bootscore'),
    'id' => 'top-nav',
    'description' => esc_html__('Add widgets here.', 'bootscore'),
    'before_widget' => '<div class="ms-3">',
    'after_widget' => '</div>',
    'before_title' => '<div class="widget-title d-none">',
    'after_title' => '</div>'
  ));
  // Top Nav End

  // Top Nav Search
  register_sidebar(array(
    'name' => esc_html__('Top Nav Search', 'bootscore'),
    'id' => 'top-nav-search',
    'description' => esc_html__('Add widgets here.', 'bootscore'),
    'before_widget' => '<div class="top-nav-search">',
    'after_widget' => '</div>',
    'before_title' => '<div class="widget-title d-none">',
    'after_title' => '</div>'
  ));
  // Top Nav Search End

  // Sidebar
  register_sidebar(array(
    'name'          => esc_html__('Sidebar', 'bootscore'),
    'id'            => 'sidebar-1',
    'description'   => esc_html__('Add widgets here.', 'bootscore'),
    'before_widget' => '<section id="%1$s" class="widget %2$s card card-body mb-4 bg-white">',
    'after_widget'  => '</section>',
    'before_title'  => '<h2 class="widget-title card-title border-bottom py-2">',
    'after_title'   => '</h2>',
  ));
  // Sidebar End

  // Top Footer
  register_sidebar(array(
    'name' => esc_html__('Top Footer', 'bootscore'),
    'id' => 'top-footer',
    'description' => esc_html__('Add widgets here.', 'bootscore'),
    'before_widget' => '<div class="footer_widget mb-5">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>'
  ));
  // Top Footer End

  // Footer 1
  register_sidebar(array(
    'name' => esc_html__('Footer 1', 'bootscore'),
    'id' => 'footer-1',
    'description' => esc_html__('Add widgets here.', 'bootscore'),
    'before_widget' => '<div class="footer_widget mb-4">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="widget-title h4">',
    'after_title' => '</h2>'
  ));
  // Footer 1 End

  // Footer 2
  register_sidebar(array(
    'name' => esc_html__('Footer 2', 'bootscore'),
    'id' => 'footer-2',
    'description' => esc_html__('Add widgets here.', 'bootscore'),
    'before_widget' => '<div class="footer_widget mb-4">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="widget-title h4">',
    'after_title' => '</h2>'
  ));
  // Footer 2 End

  // Footer 3
  register_sidebar(array(
    'name' => esc_html__('Footer 3', 'bootscore'),
    'id' => 'footer-3',
    'description' => esc_html__('Add widgets here.', 'bootscore'),
    'before_widget' => '<div class="footer_widget mb-4">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="widget-title h4">',
    'after_title' => '</h2>'
  ));
  // Footer 3 End

  // Footer 4
  register_sidebar(array(
    'name' => esc_html__('Footer 4', 'bootscore'),
    'id' => 'footer-4',
    'description' => esc_html__('Add widgets here.', 'bootscore'),
    'before_widget' => '<div class="footer_widget mb-4">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="widget-title h4">',
    'after_title' => '</h2>'
  ));
  // Footer 4 End

  /** 
   * 404 Page
   */
  register_sidebar(array(
    'name' => esc_html__('404 Page', 'bootscore'),
    'id' => '404-page',
    'description' => esc_html__('Add widgets here.', 'bootscore'),
    'before_widget' => '<div class="mb-4">',
    'after_widget' => '</div>',
    'before_title' => '<h1 class="widget-title">',
    'after_title' => '</h1>'
  ));
  // 404 Page End

}
add_action('widgets_init', 'bootscore_widgets_init');
// Register Widgets END


/**
 * email customization
 */
// add brands logo in email footer
add_action('woocommerce_email_footer', 'mcl_footer_email', 9, 1);
function mcl_footer_email()
{ ?>
  <div class="link-footer-email" style="">

    <div class="link-footer-logo"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/svg/phone.svg " alt="Enveloppe Logo" style="width: 32px; margin:0;">Email Us</div>
    <div class="link-footer-logo"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/svg/phone.svg " alt="Phone Logo" style="width: 32px; margin:0;">Call Us</div>
    <div class="link-footer-logo"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/svg/phone.svg " alt="Facebook Logo" style="width: 32px; margin:0;">Visit Us</div>
    <div class="link-footer-logo"><i class="fa-brands fa-whatsapp" alt="Whatsapp Logo" style="width: 32px; margin:0;">Whatsapp</i></div>


  </div>
<?php
}
// email customization END
