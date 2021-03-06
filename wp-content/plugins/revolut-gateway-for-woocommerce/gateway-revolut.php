<?php
/**
 * Plugin Name: Revolut Gateway for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/revolut-gateway-for-woocommerce/
 * Description: Accept card payments easily and securely via Revolut.
 * Author: Revolut
 * Author URI: https://www.revolut.com/business/online-payments
 * Text Domain: revolut-gateway-for-woocommerce
 * Version: 2.4.2
 * Requires at least: 4.4
 * Tested up to: 5.8.1
 * WC tested up to: 5.8
 * WC requires at least: 2.6
 *
 */
defined('ABSPATH') || exit;
define('REVOLUT_PATH', plugin_dir_path(__FILE__));
define('WC_GATEWAY_REVOLUT_VERSION', '2.4.2');
define('WC_REVOLUT_WAIT_FOR_ORDER_TIME', 2);
define('WC_REVOLUT_FETCH_API_ORDER_ATTEMPTS', 10);

/**
 * Manage all dependencies
 */
require_once(REVOLUT_PATH . 'includes/class-revolut-manager.php');

/**
 * Init revolut
 */
function woocommerce_revolut_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    //be sure that plugin activation hook gets triggered
    if (WC_GATEWAY_REVOLUT_VERSION != get_option('WC_GATEWAY_REVOLUT_VERSION')) {
        woocommerce_revolut_install(is_network_admin());
    }

    define('WC_REVOLUT_MAIN_FILE', __FILE__);
    define('WC_REVOLUT_CARD_WIDGET_BG_COLOR', '#ffffff');
    define('WC_REVOLUT_CARD_WIDGET_TEXT_COLOR', '#848484');
    define('WC_REVOLUT_PLUGIN_URL', untrailingslashit(plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__))));
    add_action('admin_enqueue_scripts', 'load_admin_scripts');
    load_plugin_textdomain('revolut-gateway-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');

    add_action('wp_ajax_wc_revolut_set_webhook', 'wc_revolut_set_webhook');
    add_filter('woocommerce_payment_gateways', 'woocommerce_revolut_add_gateways');
    add_action('wc_ajax_wc_revolut_validate_checkout_field', 'wc_revolut_validate_checkout_field');
    add_action('wc_ajax_wc_revolut_get_order_pay_billing_info', 'wc_revolut_get_order_pay_billing_info');
    add_action('wc_ajax_wc_revolut_get_customer_info', 'wc_revolut_get_customer_info');
    add_action('wc_ajax_wc_revolut_refresh_checkout_token', 'wc_revolut_refresh_checkout_token');
    add_action('init', 'woocommerce_revolut_load_rest_api');
}

/**
 * Load API function
 */
function woocommerce_revolut_load_rest_api()
{
    add_action('rest_api_init', 'woocommerce_revolut_create_callback_api', 99);
}

/**
 * Create API to accept setup Webhook
 */
function woocommerce_revolut_create_callback_api()
{
    $api = new RevolutController();
    $api->register_routes();
}

add_action('plugins_loaded', 'woocommerce_revolut_init', 0);

/**
 * Set up Revolut plugin links
 *
 * @param $links
 *
 * @return array
 */
function woocommerce_revolut_plugin_links($links)
{
    $settings_url = add_query_arg(
        array(
            'page' => 'wc-settings',
            'tab' => 'checkout',
            'section' => 'revolut',
        ),
        admin_url('admin.php')
    );

    $plugin_links = array(
        '<a href="' . esc_url($settings_url) . '">' . __('Settings', 'revolut-gateway-for-woocommerce') . '</a>',
        '<a href="https://business.revolut.com/help-centre">' . __('Support', 'revolut-gateway-for-woocommerce') . '</a>',
        '<a href="https://developer.revolut.com/docs/accept-payments/plugins/woocommerce/configuration">' . __('Docs', 'revolut-gateway-for-woocommerce') . '</a>',
    );

    return array_merge($plugin_links, $links);
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'woocommerce_revolut_plugin_links');

/**
 * Add Revolut payment gateways
 *
 * @param $methods
 *
 * @return mixed
 */
function woocommerce_revolut_add_gateways($gateways)
{
    return array_merge($gateways, woocommerce_revolut_payment_gateways());
}

function woocommerce_revolut_payment_gateways()
{
    return array(
        'WC_Gateway_Revolut_CC',
        'WC_Gateway_Revolut_Pay'
    );
}

/**
 * Create table to save Revolut Order
 */
register_activation_hook(__FILE__, 'woocommerce_revolut_install');

function woocommerce_revolut_install($network_wide)
{
    global $wpdb;


    // Check if the plugin is being network-activated or not.
    if ($network_wide) {
        // Retrieve all site IDs from this network (WordPress >= 4.6 provides easy to use functions for that).
        if (function_exists('get_sites') && function_exists('get_current_network_id')) {
            $site_ids = get_sites(array('fields' => 'ids', 'network_id' => get_current_network_id()));
        } else {
            $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;");
        }

        // Install the plugin for all these sites.
        foreach ($site_ids as $site_id) {
            switch_to_blog($site_id);
            woocommerce_revolut_install_single_site();
            restore_current_blog();
        }
    } else {
        woocommerce_revolut_install_single_site();
    }
}

function woocommerce_revolut_install_single_site()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $orders_table_name = $wpdb->prefix . 'wc_revolut_orders';
    $customer_table_name = $wpdb->prefix . 'wc_revolut_customer';

    try {
        $ordersTableSql = "CREATE TABLE IF NOT EXISTS $orders_table_name (
		order_id BINARY(16) NOT NULL,
		public_id BINARY(16) NOT NULL UNIQUE,
		wc_order_id INTEGER NULL UNIQUE,
    	PRIMARY KEY  (order_id)
	    ) $charset_collate;";

        $customersTableSql = "CREATE TABLE IF NOT EXISTS $customer_table_name (
		wc_customer_id INTEGER NOT NULL UNIQUE,
		revolut_customer_id VARCHAR (50) NOT NULL UNIQUE,
    	PRIMARY KEY  (wc_customer_id)
	    ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($ordersTableSql);
        dbDelta($customersTableSql);

        // update plugin version on DB
        update_option('WC_GATEWAY_REVOLUT_VERSION', WC_GATEWAY_REVOLUT_VERSION);
    } catch (Exception $exception) {
        error_log(print_r($exception, true));
    }
}

/**
 * Add script to setup Webhook using ajax
 */
function load_admin_scripts()
{
    wp_register_script('revolut-settings', plugins_url('assets/js/revolut-setting.js', WC_REVOLUT_MAIN_FILE));
    wp_localize_script('revolut-settings',
        'default_options',
        array(
            'default_bg_color' => WC_REVOLUT_CARD_WIDGET_BG_COLOR,
            'default_text_color' => WC_REVOLUT_CARD_WIDGET_TEXT_COLOR,
        ));
    wp_enqueue_script('revolut-settings');
}

/**
 * Setup webhook
 * @throws Exception
 */
function wc_revolut_set_webhook()
{
    global $wp_version;
    global $woocommerce;

    if (!isset($_POST['apiKey']) || !$_POST['apiKey']) {
        wp_die(false);
    }

    $request = array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $_POST['apiKey'],
            'User-Agent' => 'Revolut Payment Gateway/' . WC_GATEWAY_REVOLUT_VERSION
                . ' WooCommerce/' . $woocommerce->version
                . ' Wordpress/' . $wp_version
                . ' PHP/' . PHP_VERSION,
            "Content-Type" => "application/json"
        ),
        'method' => "POST"
    );
    $url = get_option('siteurl');
    $body = array(
        "url" => $url . '/wp-json/wc/v3/revolut',
        "events" => [
            "ORDER_COMPLETED",
            "ORDER_AUTHORISED"
        ]
    );
    if ($body != null) {
        $request['body'] = json_encode($body);
    }
    $curl = $_POST['mode'] == "live" ? 'https://merchant.revolut.com/api/1.0/webhooks' : 'https://sandbox-merchant.revolut.com/api/1.0/webhooks';

    $response = wp_remote_request($curl, $request);
    $response_body = json_decode(wp_remote_retrieve_body($response));
    if ($response_body->url == $body['url'] && isset($response_body->id)) {
        wp_die(true);
    } else {
        wp_die(false);
    }
}

/**
 * Validate checkout fields
 */
function wc_revolut_validate_checkout_field()
{
    $json = $_POST['json'];
    $validate_checkout = new Revolut_Validate_Checkout();

    if (!isset($json['ship_to_different_address'])) {
        $json['ship_to_different_address'] = "";
    }

    $new_error = new WP_Error();
    $errors = $validate_checkout->validate_checkout_shipping($json, $new_error);

    foreach ($errors->errors as $code => $messages) {
        $data = $errors->get_error_data($code);
        foreach ($messages as $message) {
            wc_add_notice($message, 'error', $data);
        }
    }
    $messages = wc_print_notices(true);
    $response = array(
        'result' => !!$messages ? 'failure' : 'success',
        'messages' => $messages,
    );

    if ($response['result'] == "success") {
        wp_die('<div class="revolut-result">success</div>');
    } else {
        wp_die('<div class="revolut-result">' . $messages . '</div>');
    }
}

/**
 * Get billing info for manual order payments
 */
function wc_revolut_get_order_pay_billing_info()
{
    check_ajax_referer('wc-revolut-get-billing-info', 'security');

    $order_id = $_POST['order_id'];
    $order_key = $_POST['order_key'];
    $order = wc_get_order($order_id);
    // validate order key
    if ($order && $order_key === $order->get_order_key()) {
        $billing_address = $order->get_address('billing');
        $billing_info = array(
            "name" => $billing_address['first_name'] . " " . $billing_address["last_name"],
            "email" => $billing_address["email"],
            "phone" => $billing_address["phone"],
            "billingAddress" => array(
                "countryCode" => $billing_address["country"],
                "region" => $billing_address["state"],
                "city" => $billing_address["city"],
                "streetLine1" => $billing_address["address_1"],
                "streetLine2" => $billing_address["address_2"],
                "postcode" => $billing_address["postcode"],
            )
        );
        wp_send_json($billing_info);
    }
    wp_die();
}


/**
 * Get billing info for payment method save
 */
function wc_revolut_get_customer_info()
{
    check_ajax_referer('wc-revolut-get-customer-info', 'security');

    $customer_id = get_current_user_id();
    $customer = new WC_Customer($customer_id);
    // validate order key
    if ($customer_id) {
        $billing_info = array(
            "name" => $customer->get_first_name() . " " . $customer->get_last_name(),
            "email" => $customer->get_email(),
            "phone" => $customer->get_billing_phone(),
        );
        wp_send_json($billing_info);
    } else {
        wp_send_json([
            'error' => true,
            'msg' => "Can not find customer address",
        ]);
    }
    wp_die();
}

function wc_revolut_refresh_checkout_token()
{
    wp_send_json(
        array(
            "refresh-checkout-token" => wp_create_nonce('woocommerce-process_checkout'),
            'current-user' => get_current_user_id(),
        )
    );
}