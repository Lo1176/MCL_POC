<?php

defined('ABSPATH') || exit();

if (!class_exists('WC_Payment_Gateway')) {
    return;
}

define('FAILED_CARD', 2005);

class RevolutPaymentErrorException extends Exception
{
}

/**
 * Abstract Revolut Payment Gateway
 * @since 2.0
 * @author Revolut
 */
abstract class WC_Payment_Gateway_Revolut extends WC_Payment_Gateway_CC
{

    use WC_Revolut_Settings_Trait;
    use WC_Revolut_Logger_Trait;

    protected $api_key;
    protected $api_key_sandbox;
    protected $base_url;
    public $api_client;

    protected $default_title;

    protected $user_friendly_error_message_code = 1000;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->api_settings = revolut_wc()->api_settings;
        $this->has_fields = true;
        $this->icon = $this->get_icon();
        $this->available_currency_list = array('AED', 'AUD', 'BHD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HRK', 'ILS', 'ISK', 'JPY', 'KWD', 'MXN', 'NOK', 'NZD', 'OMR', 'PLN', 'QAR', 'RON', 'RUB', 'SAR', 'SEK', 'SGD', 'THB', 'TRY', 'UAH', 'USD', 'ZAR');
        $this->card_payments_currency_list = array('AED', 'AUD', 'BHD', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'ISK', 'JPY', 'KWD', 'NOK', 'NZD', 'OMR', 'PLN', 'QAR', 'RON', 'SAR', 'SEK', 'SGD', 'TRY', 'UAH', 'USD', 'ZAR');
        $this->api_key_sandbox = $this->api_settings->get_option('api_key_sandbox');
        $this->revolut_saved_cards = false;

        //get setting from old version
        if ('yes' === $this->api_settings->get_option('sandbox') && $this->api_settings->get_option('mode') == "") {
            $this->update_option('mode', 'sandbox');
            $this->update_option('api_key_sandbox', $this->api_settings->get_option('api_key'));
            $this->update_option('api_key', '');
        } else if ('yes' != $this->api_settings->get_option('sandbox') && $this->api_settings->get_option('mode') == "") {
            $this->update_option('mode', 'live');
            $this->update_option('api_key', $this->api_settings->get_option('api_key'));
        }

        $this->api_key = $this->api_settings->get_option('mode') == "sandbox" ? $this->api_key_sandbox : $this->api_settings->get_option('api_key');
        $this->base_url = $this->api_settings->get_option('mode') == "sandbox" ? 'https://sandbox-merchant.revolut.com' : 'https://merchant.revolut.com';

        $this->init_supports();
        $this->init_form_fields();
        $this->init_settings();

        $this->api_client = new WC_Revolut_API_Client($this->api_settings);

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(
            $this,
            'process_admin_options'
        ));
        add_action('admin_notices', array($this, 'admin_notices'));
        add_action('wp_enqueue_scripts', array($this, 'wc_revolut_enqueue_scripts'));
        add_action('woocommerce_order_status_changed', array($this, 'order_action_from_woocommerce'), 10, 3);
        add_action('woocommerce_pay_order_before_submit', array($this, 'add_shipping_information'));
        add_filter('wc_revolut_settings_nav_tabs', array($this, 'admin_nav_tab'));
        add_action('woocommerce_checkout_order_processed', array($this, 'woocommerce_checkout_revolut_order_processed'), 10, 3);
    }

    public function woocommerce_checkout_revolut_order_processed($order_id, $posted_data, $order)
    {
        if (!isset($_REQUEST['revolut_create_wc_order']) || $posted_data['payment_method'] !== $this->id) {
            return;
        }

        WC()->session->set('order_awaiting_payment', $order_id);

        $order_total = $order->get_total();
        $order_currency = $order->get_currency();
        $revolut_payment_public_id = $this->get_revolut_public_id();

        $update_revolut_order_result = false;
        // update payment amount and currency after order creation in order to be sure that the payment will be exactly same with order
        try {
            $update_revolut_order_result = $this->update_revolut_order_total($revolut_payment_public_id, $order_total, $order_currency);
        } catch (Exception $e) {
            $this->logError($e->getMessage());
        }

        if (!$update_revolut_order_result) {
            wp_send_json(array(
                "refresh-checkout" => true,
                "refresh-checkout-token" => false,
                "result" => "revolut_wc_order_created",
            ));
        }

        wp_send_json(array(
            "refresh-checkout" => false,
            "refresh-checkout-token" => isset($posted_data['createaccount']) && !empty($posted_data['createaccount']),
            "result" => "revolut_wc_order_created",
        ));
    }

    /**
     * Supported functionality
     */
    public function init_supports()
    {
        $this->supports = array(
            'products',
        );
    }

    /**
     * Renders hidden inputs on the "Pay for Order" page in order to let Revolut handle PaymentIntents.
     */
    function add_shipping_information()
    {
        if (is_wc_endpoint_url('order-pay')) {
            $order_id = wc_get_order_id_by_order_key($_GET['key']);
            $wc_order = wc_get_order($order_id);
            ?>
            <div style="display: none">
                <?php
                if ($wc_order->get_billing_first_name() != "") {
                    ?>
                    <input type="text" class="input-text " name="billing_first_name" id="billing_first_name"
                           placeholder="" value="<?= $wc_order->get_billing_first_name() ?>"
                           autocomplete="given-name" readonly/>
                    <?php
                }
                ?>
                <?php
                if ($wc_order->get_billing_last_name() != "") {
                    ?>
                    <input type="text" class="input-text " name="billing_last_name" id="billing_last_name"
                           placeholder="" value="<?= $wc_order->get_billing_last_name() ?>"
                           autocomplete="family-name" readonly/>
                    <?php
                }
                ?>
                <input type="email" class="input-text "
                       name="billing_email" id="billing_email"
                       placeholder=""
                       value="<?= $wc_order->get_billing_email() == "" ? wp_get_current_user()->user_email : $wc_order->get_billing_email() ?>"
                       autocomplete="email username"/>
            </div>

            <?php
        }
    }

    /**
     * Display icon in checkout
     * @abstract
     */
    public function get_icon()
    {
    }

    /**
     * Initialise Gateway Settings Form Fields
     */
    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'revolut-gateway-for-woocommerce'),
                'label' => __('Enable ', 'revolut-gateway-for-woocommerce') . $this->method_title,
                'type' => 'checkbox',
                'description' => __('This controls whether or not this gateway is enabled within WooCommerce.', 'revolut-gateway-for-woocommerce'),
                'default' => 'yes',
                'desc_tip' => true,
            ),
            'title' => array(
                'title' => __('Title', 'revolut-gateway-for-woocommerce'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'revolut-gateway-for-woocommerce'),
                'default' => $this->default_title,
                'desc_tip' => true,
            ),
        );
    }

    /**
     * Add script to load card form
     */
    public function wc_revolut_enqueue_scripts()
    {
        wp_register_style('revolut-custom-style', plugins_url('assets/css/style.css', WC_REVOLUT_MAIN_FILE));
        wp_enqueue_style('revolut-custom-style');

        wp_enqueue_script('revolut-core', $this->base_url . '/embed.js', false, WC_GATEWAY_REVOLUT_VERSION, true);

        wp_enqueue_script("jquery");
        wp_enqueue_script('revolut-woocommerce', plugins_url('assets/js/revolut.js', WC_REVOLUT_MAIN_FILE), array(
            'revolut-core',
            'jquery'
        ), WC_GATEWAY_REVOLUT_VERSION, true);

        wp_localize_script('revolut-woocommerce',
            'wc_revolut',
            [
                'ajax_url' => WC_AJAX::get_endpoint(''),
                'page' => $this->wc_revolut_get_current_page(),
                'order_id' => $this->wc_revolut_get_current_order_id(),
                'order_key' => $this->wc_revolut_get_current_order_key(),
                'checkout_reload_message' => __('Something went wrong while checking out. Payment was not taken. Please try again', 'revolut-gateway-for-woocommerce'),
                'nonce' => [
                    'billing_info' => wp_create_nonce('wc-revolut-get-billing-info'),
                    'customer_info' => wp_create_nonce('wc-revolut-get-customer-info'),
                    'refresh_checkout_token' => wp_create_nonce('wc-revolut-refresh-checkout-token'),
                ],
            ]);
    }

    /**
     * Check the current page
     */
    public function wc_revolut_get_current_page()
    {
        global $wp;
        if (is_product()) {
            return 'product';
        }
        if (is_cart()) {
            return 'cart';
        }
        if (is_checkout()) {
            if (!empty($wp->query_vars['order-pay'])) {
                return 'order_pay';
            }

            return 'checkout';
        }
        if (is_add_payment_method_page()) {
            return 'add_payment_method';
        }

        return '';
    }

    /**
     * Get current order id on 'order_pay' page
     */
    public function wc_revolut_get_current_order_id()
    {
        global $wp;
        if (is_checkout()) {
            if (!empty($wp->query_vars['order-pay']) && absint($wp->query_vars['order-pay']) > 0) {
                return absint($wp->query_vars['order-pay']);;
            }
        }
        return '';
    }

    /**
     * Get current order key
     */
    public function wc_revolut_get_current_order_key()
    {
        $order_id = $this->wc_revolut_get_current_order_id();
        if ($order_id) {
            $order = wc_get_order($order_id);
            $order_key = $order->get_order_key();
            return $order_key;
        }
        return '';
    }

    /**
     * Update Revolut order metadata
     *
     * @param $revolut_order_id
     * @param $wc_order_id
     * @param null $customer_id
     *
     * @throws Exception
     */
    public function update_revolut_order_metadata($revolut_order_id, $wc_order_id)
    {
        if (empty($revolut_order_id) || empty($wc_order_id)) {
            $this->logError('Something went wrong: Params is empty');
        }
        $body = array(
            'merchant_order_ext_ref' => $wc_order_id
        );

        $json = $this->api_client->patch("/orders/$revolut_order_id", $body);

        if (!isset($json['public_id']) || !isset($json['id'])) {
            throw new Exception('Something went wrong: ' . json_encode($json, JSON_PRETTY_PRINT));
        }
    }

    /**
     * Send order action request from Woocommerce to API
     *
     * @param $revolut_order_id
     * @param $action
     * @param null $body
     *
     * @return mixed
     * @throws Exception
     */
    public function action_revolut_order($revolut_order_id, $action, $body = null)
    {
        if (empty($revolut_order_id)) {
            return [];
        }
        $json = $this->api_client->post("/orders/$revolut_order_id/$action", $body);

        if (!isset($json['id'])) {
            if ($json['code'] == FAILED_CARD) {
                throw new Exception(__('Customer will not be able to get a ' . $action . ' using this card!', 'revolut-gateway-for-woocommerce'));
            }
            throw new Exception(__('Cannot ' . $action . ' Order - Error Id: ' . $json['errorId'] . '.', 'revolut-gateway-for-woocommerce'));
        }

        return $json;
    }

    /**
     * Create Revolut Order
     *
     * @param Revolut_Order_Descriptor $order_descriptor
     *
     * @return mixed
     * @throws Exception
     */
    public function create_revolut_order(Revolut_Order_Descriptor $order_descriptor)
    {
        global $wp;
        if (isset($_GET['pay_for_order']) && !empty($_GET['key'])) {
            $order = wc_get_order(wc_clean($wp->query_vars['order-pay']));
            $total = $order->get_total();
        } else {
            $total = $order_descriptor->amount;
        }

        if (is_add_payment_method_page() || $this->is_subs_change_payment()) {
            $total = 0;
        }

        $capture = $this->api_settings->get_option('payment_action') == 'authorize' ? 'MANUAL' : 'AUTOMATIC';

        if (!$this->isZeroDecimal($order_descriptor->currency)) {
            $total = $total * 100;
        }
        $body = array(
            'amount' => round($total),
            'currency' => $order_descriptor->currency,
            'customer_id' => $order_descriptor->revolut_customer_id,
            'capture_mode' => $capture
        );
        $json = $this->api_client->post('/orders', $body);

        if (empty($json['id']) || empty($json['public_id'])) {
            throw new Exception('Something went wrong: ' . json_encode($json, JSON_PRETTY_PRINT));
        }

        global $wpdb;
        $insert_query = $wpdb->prepare('INSERT INTO ' . $wpdb->prefix . "wc_revolut_orders (order_id, public_id)
            VALUES (UNHEX(REPLACE(%s, '-', '')), UNHEX(REPLACE(%s, '-', '')))", array(
            $json['id'],
            $json['public_id']
        ));

        if ($this->query($insert_query) !== 1) {
            throw new Exception("Can not save Revolut order record on DB");
        }

        return $json['public_id'];
    }

    /**
     * Request query to database
     *
     * @param $query
     *
     * @return bool|int
     * @throws Exception
     */
    protected function query($query)
    {
        global $wpdb;
        $result = $wpdb->query($query);
        if ($result === false) {
            throw new Exception($wpdb->last_error);
        }

        return $result;
    }

    /**
     * Update Revolut Order
     *
     * @param Revolut_Order_Descriptor $order_descriptor
     * @param $public_id
     *
     * @return mixed
     * @throws Exception
     */
    public function update_revolut_order(Revolut_Order_Descriptor $order_descriptor, $public_id)
    {
        global $wp;
        $order_id = $this->get_revolut_order_by_public_id($public_id);

        if (isset($_GET['pay_for_order']) && !empty($_GET['key'])) {
            $order = wc_get_order(wc_clean($wp->query_vars['order-pay']));
            $total = $order->get_total();
        } else {
            $total = $order_descriptor->amount;
        }

        if (is_add_payment_method_page() || $this->is_subs_change_payment()) {
            $total = 0;
        }

        if (!$this->isZeroDecimal($order_descriptor->currency)) {
            $total = $total * 100;
        }

        $body = array(
            'amount' => round($total),
            'currency' => $order_descriptor->currency
        );

        if (empty($order_id)) {
            return "";
        }

        $revolut_order = $this->api_client->get("/orders/$order_id");

        if (!isset($revolut_order['public_id']) || !isset($revolut_order['id']) || $revolut_order['state'] != "PENDING") {
            return $this->create_revolut_order($order_descriptor);
        }

        $revolut_order = $this->api_client->patch("/orders/$order_id", $body);

        if (!isset($revolut_order['public_id']) || !isset($revolut_order['id'])) {
            return $this->create_revolut_order($order_descriptor);
        }

        return $revolut_order['public_id'];
    }

    /**
     * Update Revolut Order Total
     *
     * @param $public_id
     * @param $order_total
     * @param $currency
     *
     * @return bool
     * @throws Exception
     */
    public function update_revolut_order_total($public_id, $order_total, $currency)
    {
        $order_id = $this->get_revolut_order_by_public_id($public_id);

        if (!$this->isZeroDecimal($currency)) {
            $order_total = $order_total * 100;
        }

        $body = array(
            'amount' => round($order_total),
            'currency' => $currency
        );

        if (empty($order_id)) {
            return false;
        }

        $revolut_order = $this->api_client->get("/orders/$order_id");

        if (!isset($revolut_order['public_id']) || !isset($revolut_order['id']) || $revolut_order['state'] != "PENDING") {
            return false;
        }

        $revolut_order = $this->api_client->patch("/orders/$order_id", $body);

        if (!isset($revolut_order['public_id']) || !isset($revolut_order['id'])) {
            return false;
        }

        return true;
    }

    /**
     * Replace dashes
     *
     * @param $uuid
     *
     * @return string|string[]|null
     */
    protected function uuid_dashes($uuid)
    {
        if (is_array($uuid)) {
            if (isset($uuid[0])) {
                $uuid = $uuid[0];
            }
        }

        $result = preg_replace('/(\w{8})(\w{4})(\w{4})(\w{4})(\w{12})/i', '$1-$2-$3-$4-$5', $uuid);

        return $result;
    }

    /**
     * Unset Revolut public_id
     */
    protected function unset_revolut_public_id()
    {
        WC()->session->__unset('revolut_public_id');
    }

    /**
     * Set Revolut public_id
     *
     * @param $value
     */
    protected function set_revolut_public_id($value)
    {
        WC()->session->set('revolut_public_id', $value);
    }

    /**
     * Get Revolut public_id
     * @return array|string|null
     */
    protected function get_revolut_public_id()
    {
        return WC()->session->get('revolut_public_id');
    }

    /**
     * Add public_id field and logo on card form
     * @abstract
     * @param $public_id
     *
     * @return string
     */
    public function generate_inline_revolut_form($public_id)
    {
        return '';
    }

    /**
     * Add save checkbox on payment form
     * @abstract
     *
     * @return string
     */
    public function save_payment_method_checkbox()
    {
        return '';
    }

    /**
     * check if save action requested for the payment method
     * @abstract
     *
     * @return bool
     */
    public function save_payment_method_requested()
    {
        return false;
    }

    /**
     * check if saved payment method requested for making the payment
     * @abstract
     *
     * @return bool
     */
    public function is_using_saved_payment_method()
    {
        return false;
    }

    /**
     * Add update checkbox on payment form
     * @abstract
     *
     * @return string
     */
    public function display_update_subs_payment_checkout()
    {
        return '';
    }

    /**
     * check if request for changing payment method
     * @abstract
     *
     * @return bool
     */
    public function is_subs_change_payment()
    {
        return false;
    }

    public function save_payment_method($order_id)
    {
        //get revolut customer ID from Revolut order
        $revolut_order = null;
        for ($i = 0; $i <= 9; $i++) {
            $revolut_order = $this->api_client->get('/orders/' . $order_id);
            if (isset($revolut_order['customer_id']) && !empty($revolut_order['customer_id']) && $revolut_order['state'] != 'PROCESSING') {
                $revolut_customer_id = $revolut_order['customer_id'];
                break;
            }
            sleep(2);
        }

        if (empty($revolut_customer_id)) {
            throw new Exception('An error occurred while saving the card');
        }

        if (!$this->get_revolut_customer_id(get_current_user_id())) {
            $this->insert_revolut_customer_id($revolut_customer_id);
        }

        $revolut_customer = $this->api_client->get('/customers/' . $revolut_customer_id);

        if (empty($revolut_customer['payment_methods']) || count($revolut_customer['payment_methods']) == 0) {
            throw new Exception("Can not save Payment Methods through API");
        }

        $payment_methods = $revolut_customer['payment_methods'];
        $exist_tokens = WC_Payment_Tokens::get_customer_tokens(get_current_user_id(), $this->id);
        $stored_tokens = array();

        foreach ($exist_tokens as $token) {
            $stored_tokens[$token->get_token()] = $token;
        }

        if (empty($revolut_order)) {
            $revolut_order = $this->api_client->get('/orders/' . $order_id);
        }

        $current_payment_list = isset($revolut_order['payments']) && !empty($revolut_order['payments']) ? $revolut_order['payments'] : [];
        $current_token = null;

        foreach ($payment_methods as $payment_method) {

            if (in_array($payment_method['id'], array_keys($stored_tokens))) {
                continue;
            }

            $token = new WC_Payment_Token_CC();
            $token->set_token($payment_method['id']);
            $token->set_gateway_id($this->id);
            $method_details = $payment_method['method_details'];
            $card_type = $payment_method['type'];
            $current_payment = self::searchListKeyValue($current_payment_list, 'id', $payment_method['id']);

            if (isset($current_payment['payment_method'])
                && isset($current_payment['payment_method']['card'])
                && isset($current_payment['payment_method']['card']['card_brand'])) {
                $card_type = $current_payment['payment_method']['card']['card_brand'];
            }

            $token->set_card_type($card_type);
            $token->set_last4($method_details['last4']);
            $token->set_expiry_month($method_details['expiry_month']);
            $token->set_expiry_year($method_details['expiry_year']);
            $token->set_user_id(get_current_user_id());
            $token->save();
            $current_token = $token;
        }

        return $current_token;
    }

    public function add_payment_method()
    {
        try {
            // find public_id
            $revolut_payment_public_id = $_POST['revolut_public_id'];
            if (empty($revolut_payment_public_id)) {
                throw new Exception('Missing revolut_public_id parameter');
            }

            // resolve revolut_public_id into revolut_order_id
            $revolut_order_id = $this->get_revolut_order_by_public_id($revolut_payment_public_id);
            if (empty($revolut_order_id)) {
                throw new Exception('Missing revolut order id parameter');
            }

            $wc_token = $this->save_payment_method($revolut_order_id);
            if ($wc_token == null) {
                throw new Exception('An error occured while saving payment method');
            }

            $this->handle_add_payment_method(null, $wc_token, get_current_user_id());

            return [
                'result' => 'success',
                'redirect' => wc_get_endpoint_url('payment-methods'),
            ];

        } catch (Exception $e) {
            $this->logError($e);

            wc_add_notice($e->getMessage(), 'error');
            return;
        }
    }

    /**
     * Process the payment and return the result.
     */
    public function process_payment($wc_order_id)
    {
        //if request contains create key, that means request is for validating order
        if (isset($_REQUEST['revolut_create_wc_order'])) {
            return false;
        }

        $wc_order = wc_get_order($wc_order_id);

        try {
            // find public_id
            $revolut_payment_public_id = $_POST['revolut_public_id'];
            if (empty($revolut_payment_public_id)) {
                throw new Exception('Missing revolut_public_id parameter');
            }

            //check payment errors
            if (!empty($_POST['revolut_payment_error'])) {
                throw new Exception($_POST['revolut_payment_error'], $this->user_friendly_error_message_code);
            }

            // resolve revolut_public_id into revolut_order_id
            $revolut_order_id = $this->get_revolut_order_by_public_id($revolut_payment_public_id);
            if (empty($revolut_order_id)) {
                throw new Exception('Can not find Revolut order ID');
            }

            //check if it needs to process payment with previously saved method
            $previously_saved_wc_token = $this->maybe_pay_by_saved_method($revolut_order_id);

            // update internal table to avoid piggybacking on already paid order
            //just log the error for the future investigations
            try {
                $this->save_wc_order_id($revolut_payment_public_id, $wc_order_id);
            } catch (Exception $e) {
                $this->logError($e->getMessage());
            }

            //payment should be processed until this point, if not throw an error
            $this->check_payment_processed($revolut_order_id);
            //payment process began...
            $wc_order->update_status('on-hold');
            $wc_order->add_order_note("Payment has been successfully authorized (Order ID: " . $revolut_order_id . ").");
            //there is no need to stop order process if can not update order ID on Revolut account
            //just log the error for the future investigations
            try {
                $this->update_revolut_order_metadata($revolut_order_id, $wc_order_id);
            } catch (Exception $e) {
                $this->logError($e->getMessage());
            }

            //check payment result and update order status
            $this->handle_revolut_order_result($wc_order, $revolut_order_id);
            //check save method requested
            $newly_saved_wc_token = $this->maybe_save_payment_method($revolut_order_id, $wc_order);
            //check if there is any saved or used payment token
            $wc_token = null;
            if ($previously_saved_wc_token != null) {
                $wc_token = $previously_saved_wc_token;
            } else {
                $wc_token = $newly_saved_wc_token;
            }

            $this->save_payment_token_to_order($wc_order, $wc_token, get_current_user_id());
            return $this->checkout_return($wc_order);
        } catch (Exception $e) {
            $this->logError($e->getMessage());
            $wc_order->update_status('failed');
            $wc_order->add_order_note("Customer attempted to pay, but the payment failed or got declined. (Error: " . $e->getMessage() . ")");
            $error_message_for_user = 'Something went wrong';
            if ($e->getCode() === $this->user_friendly_error_message_code) {
                $error_message_for_user = $e->getMessage();
            }

            wc_add_notice($error_message_for_user, 'error');

            return array(
                'messages' => $error_message_for_user,
                'result' => 'fail',
                'redirect' => ''
            );
        }
    }

    protected function save_wc_order_id($public_id, $wc_order_id)
    {
        global $wpdb;
        // update internal table to avoid piggybacking on already paid order
        $update_query = $wpdb->prepare('UPDATE ' . $wpdb->prefix . "wc_revolut_orders
                SET wc_order_id=%d
                WHERE public_id=UNHEX(REPLACE(%s, '-', ''))",
            array($wc_order_id, $public_id));

        $updated_rows = $this->query($update_query);

        if ($updated_rows !== 1) {
            throw new Exception("Can not update wc_order_id for Revolut order record on DB");
        }
    }

    protected function check_payment_processed($revolut_order_id)
    {
        $revolut_order = $this->api_client->get('/orders/' . $revolut_order_id);
        if (!isset($revolut_order['state']) || (isset($revolut_order['state']) && $revolut_order['state'] == 'PENDING')) {
            throw new Exception('Something went wrong while completing this payment. Please try again.');
        }
    }

    protected function handle_revolut_order_result($wc_order, $revolut_order_id)
    {
        $wc_order_id = $wc_order->get_id();

        // verify that the order was paid
        $mode = $this->api_settings->get_option('payment_action');

        for ($i = 0; $i < WC_REVOLUT_FETCH_API_ORDER_ATTEMPTS; $i++) {
            if (isset($revolut_order_id) && !empty($revolut_order_id)) {
                $order = $this->api_client->get('/orders/' . $revolut_order_id);
                $wc_order_status = empty($wc_order->get_status()) ? "" : $wc_order->get_status();
                $check_wc_status = $wc_order_status == "processing" || $wc_order_status == "completed";
                if (isset($order['state']) && !$check_wc_status) {
                    if ($order['state'] == 'COMPLETED' && $mode == "authorize_and_capture") {
                        update_post_meta($wc_order_id, 'revolut_capture', "yes");
                        $wc_order->payment_complete($revolut_order_id);
                        $wc_order->add_order_note('Payment has been successfully captured (Order ID: ' . $revolut_order_id . ').');
                        return true;
                    } else if ($order['state'] == 'AUTHORISED' && $mode == "authorize") {
                        return true;
                    } else if ($order['state'] == 'PENDING') {
                        $wc_order->add_order_note('Something went wrong while completing this payment. Please reach out to your customer and ask them to try again.');
                        throw new Exception('Something went wrong while completing this payment.');
                    } else if ($i == 9 && ($order['state'] == "PROCESSING" || $order['state'] == "IN_SETTLEMENT")) {
                        if ($mode == "authorize_and_capture") {
                            $wc_order->add_order_note('Payment is taking a bit longer than expected to be completed. 
							                If the order is not moved to the “Processing” state after 24h, please check your Revolut account to verify that this payment was taken. 
							                You might need to contact your customer if it wasn’t.');
                        }

                        return true;
                    }

                    sleep(WC_REVOLUT_WAIT_FOR_ORDER_TIME);
                } else if ($check_wc_status) {
                    return true;
                }
            } else {
                throw new Exception('Revolut order ID is missing');
            }
        }

        return true;
    }

    /**
     * Build payment fields area - including fields for logged
     * in users, and the payment fields.
     */
    public function payment_fields()
    {
        if ($this->api_settings->get_option('mode') == "sandbox") {
            if ($this->id == 'revolut_cc') {
                echo "<p style='color:red'>The payment gateway is in Sandbox Mode. You can use our <a href='https://developer.revolut.com/docs/accept-payments/tutorials/test-in-the-sandbox-environment/test-cards' target='_blank'>test cards</a> to simulate different payment scenarios.";
            } else if ($this->id == 'revolut_pay') {
                echo "<p style='color:red'>The payment gateway is in Sandbox Mode.";
            }
        }

        if (!$this->check_currency_support()) {
            $this->currency_support_error();
            return false;
        }

        $public_id = $this->get_revolut_public_id();
        $revolut_customer_id = $this->get_revolut_customer_id(get_current_user_id());
        $descriptor = new Revolut_Order_Descriptor(WC()->cart->get_total(''), get_woocommerce_currency(), $revolut_customer_id);
        $display_tokenization = !empty($revolut_customer_id) && $this->supports('tokenization') && (is_checkout() || isset($_GET['pay_for_order'])) && $this->revolut_saved_cards;

        if ($display_tokenization) {
            try {
                $this->normalise_payment_methods($revolut_customer_id);
            } catch (Exception $e) {
                $display_tokenization = false;
                $this->logError($e->getMessage());
            }
        }

        try {
            if ($public_id === null || is_add_payment_method_page()) {
                $public_id = $this->create_revolut_order($descriptor);
            } else {
                $public_id = $this->update_revolut_order($descriptor, $public_id);
            }

            $this->set_revolut_public_id($public_id);

            if ($display_tokenization) {
                $this->tokenization_script();
                $this->saved_payment_methods();
            }

            echo $this->generate_inline_revolut_form($public_id);
            echo $this->save_payment_method_checkbox();
            echo $this->display_update_subs_payment_checkout();
        } catch (Exception $e) {
            $this->logError($e->getMessage());
            echo 'To receive payments using the Revolut Gateway for WooCommerce plugin, please <a href="https://developer.revolut.com/docs/accept-payments/#plugins-plugins-woocommerce-configure-the-woocommerce-plugin" target="_blank">configure your API key</a>.<br><br>If you are still seeing this message after the configuration of your API key, please reach out via the support chat in your Revolut Business account.';
        }
    }

    protected function normalise_payment_methods($revolut_customer_id)
    {
        $exist_tokens = WC_Payment_Tokens::get_customer_tokens(get_current_user_id(), $this->id);

        if (empty($exist_tokens)) {
            return [];
        }

        $revolut_customer = $this->api_client->get('/customers/' . $revolut_customer_id);

        if (!isset($revolut_customer['id']) || empty($revolut_customer['id'])) {
            $this->remove_all_payment_tokens($exist_tokens);
            throw new Exception("Can not find Revolut Customer");
        }

        if (!isset($revolut_customer['payment_methods']) || empty($revolut_customer['payment_methods'])) {
            $this->remove_all_payment_tokens($exist_tokens);
            throw new Exception("Revolut Customer does not have any saved payment methods");
        }

        $saved_revolut_payment_tokens = array_column($revolut_customer['payment_methods'], 'id');

        foreach ($exist_tokens as $wc_token) {
            $wc_token_id = $wc_token->get_id();
            $wc_payment_token = $wc_token->get_token();
            if (!in_array($wc_payment_token, $saved_revolut_payment_tokens)) {
                WC_Payment_Tokens::delete($wc_token_id);
            }
        }
    }

    public function remove_all_payment_tokens($exist_tokens)
    {
        if (empty($exist_tokens)) {
            return;
        }

        foreach ($exist_tokens as $wc_token) {
            $wc_token_id = $wc_token->get_id();
            WC_Payment_Tokens::delete($wc_token_id);
        }
    }

    protected function insert_revolut_customer_id($revolut_customer_id)
    {
        global $wpdb;
        $result = $wpdb->insert($wpdb->prefix . 'wc_revolut_customer', array(
            'wc_customer_id' => get_current_user_id(),
            'revolut_customer_id' => $revolut_customer_id,
        ));

        if (!$result) {
            throw new Exception('Something went wrong: while saving customer on db');
        }
    }

    public function get_revolut_customer_id($wc_customer_id)
    {
        global $wpdb;
        $order_query = $wpdb->prepare('SELECT revolut_customer_id FROM ' . $wpdb->prefix . "wc_revolut_customer
                WHERE wc_customer_id=%s", array($wc_customer_id));
        $revolut_customer_id = $wpdb->get_col($order_query);
        $revolut_customer_id = reset($revolut_customer_id);
        if (empty($revolut_customer_id)) {
            $revolut_customer_id = null;
        }

        return $revolut_customer_id;
    }

    protected function maybe_pay_by_saved_method($revolut_order_id)
    {
        if ($this->is_using_saved_payment_method()) {
            $wc_token = $this->get_selected_payment_token();
            return $this->pay_by_saved_method($revolut_order_id, $wc_token);
        }

        return null;
    }

    protected function pay_by_saved_method($revolut_order_id, $wc_token)
    {
        $payment_method_id = $wc_token->get_token();

        $body = array(
            'payment_method_id' => $payment_method_id
        );

        $this->action_revolut_order($revolut_order_id, 'confirm', $body);
        return $wc_token;
    }

    protected function maybe_save_payment_method($revolut_order_id, $wc_order)
    {
        if ($this->save_payment_method_requested() && !$this->is_using_saved_payment_method()) {
            try {
                return $this->save_payment_method($revolut_order_id);
            } catch (Exception $e) {
                $wc_order->add_order_note("Card save process failed. (Error: " . $e->getMessage() . ")");
            }
        }
        return null;
    }

    protected function save_payment_token_to_order($order, $wc_token, $wc_customer_id)
    {
        if ($wc_token != null && !empty($wc_token->get_id())) {
            $id_payment_token = $wc_token->get_id();
            $order_id = $order->get_id();

            if (empty($id_payment_token) || empty($order_id)) {
                throw new Exception('Can not save payment into order meta');
            }

            $order->update_meta_data('_payment_token', $wc_token->get_token());
            $order->update_meta_data('_payment_token_id', $id_payment_token);
            $order->update_meta_data('_wc_customer_id', $wc_customer_id);

            if (is_callable([$order, 'save'])) {
                $order->save();
            }

            // Also store it on the subscriptions being purchased or paid for in the order
            if (function_exists('wcs_order_contains_subscription') && wcs_order_contains_subscription($order_id)) {
                $subscriptions = wcs_get_subscriptions_for_order($order_id);
            } elseif (function_exists('wcs_order_contains_renewal') && wcs_order_contains_renewal($order_id)) {
                $subscriptions = wcs_get_subscriptions_for_renewal_order($order_id);
            } else {
                $subscriptions = [];
            }

            foreach ($subscriptions as $subscription) {
                $subscription_id = $subscription->get_id();
                update_post_meta($subscription_id, '_payment_token', $wc_token->get_token());
                update_post_meta($subscription_id, '_payment_token_id', $id_payment_token);
                update_post_meta($subscription_id, '_wc_customer_id', $wc_customer_id);
            }
        }
    }

    /**
     * Updates all active subscriptions payment method.
     * @abstract
     *
     * @param int $id_payment_token
     * @param int $wc_customer_id
     * @return bool
     */
    public function handle_add_payment_method($current_subscription, $wc_token, $wc_customer_id)
    {
        return false;
    }

    /**
     * Grab selected payment token from Request
     * @abstract
     *
     * @return String
     */
    public function get_selected_payment_token()
    {
        return '';
    }

    /**
     * Return after checkout successfully
     *
     * @param $wc_order
     *
     * @return array
     */
    public function checkout_return($wc_order)
    {
        $this->unset_revolut_public_id();
        if (isset(WC()->cart)) {
            WC()->cart->empty_cart();
        }

        return array(
            'result' => 'success',
            'redirect' => $this->get_return_url($wc_order),
        );
    }

    /**
     * Add admin notice when use revolut payment without API key
     */
    public function admin_notices()
    {
        if ('yes' !== $this->get_option('enabled')
            || !empty($this->api_key)) {
            return;
        }

        if (empty($this->api_key) && empty($this->api_key_sandbox)) {
            echo '<div class="error revolut-passphrase-message"><p>'
                . __('Revolut requires an API Key to work.', 'revolut-gateway-for-woocommerce')
                . '</p></div>';
        }
    }

    /**
     * Handle Order action from Woocommerce to API
     *
     * @param $order_id
     * @param $old_status
     * @param $new_status
     *
     * @throws Exception
     */
    public function order_action_from_woocommerce($order_id, $old_status, $new_status)
    {
        $wc_order = wc_get_order($order_id);
        if ($wc_order->get_payment_method() == 'revolut' || $wc_order->get_payment_method() == 'revolut_cc' || $wc_order->get_payment_method() == 'revolut_pay') {
            $revolut_order_id = $this->get_revolut_order($order_id);

            if (!empty($revolut_order_id) && $this->api_settings->get_option('accept_capture') == 'yes' && $revolut_order_id != "") {
                $order = $this->api_client->get('/orders/' . $revolut_order_id);
                $state = isset($order['state']) ? $order['state'] : "";
                if ($this->api_settings->get_option('payment_action') == 'authorize') {
                    //capture order
                    if ($new_status == 'processing' || $new_status == 'completed') {
                        // check fraud order
                        $order_amount = isset($order['order_amount']['value']) ? (float)$order['order_amount']['value'] : 0;
                        $currency = isset($order['order_amount']['currency']) ? $order['order_amount']['currency'] : "";
                        $total = $this->isZeroDecimal($currency) ? $wc_order->get_total() : $wc_order->get_total() * 100;
                        if ($total != $order_amount) {
                            $wc_order->add_order_note(__('Order amount can\'t be partially captured. Please try again or capture this payment from your Revolut Business web portal.', 'revolut-gateway-for-woocommerce'));
                        }

                        if ($state == "AUTHORISED") {
                            $response = $this->action_revolut_order($revolut_order_id, 'capture');
                            $order_response = $this->api_client->get('/orders/' . $revolut_order_id);

                            if ($order_response['state'] == 'COMPLETED' || $order_response['state'] == "IN_SETTLEMENT") {
                                $wc_order->payment_complete($revolut_order_id);
                                $wc_order->add_order_note(__('Payment amount has been captured successfully.', 'revolut-gateway-for-woocommerce'));
                                update_post_meta($order_id, 'revolut_capture', "yes");
                            } else {
                                $wc_order->add_order_note(__('Order capture wasn\'t successful. Please try again or check your Revolut Business web portal for more information', 'revolut-gateway-for-woocommerce'));
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $order_id
     *
     * @return string|string[]|null
     */
    public function get_revolut_order($order_id)
    {
        global $wpdb;
        $order_query = $wpdb->prepare('SELECT HEX(order_id) FROM ' . $wpdb->prefix . 'wc_revolut_orders
                WHERE wc_order_id=%s', array($order_id));
        $revolut_order_id = $this->uuid_dashes($wpdb->get_col($order_query));

        return $revolut_order_id;
    }

    /**
     * @param $public_id
     *
     * @return string|null
     */
    public function get_revolut_order_by_public_id($public_id)
    {
        global $wpdb;
        // resolve into order_id
        $order_query = $wpdb->prepare('SELECT HEX(order_id) FROM ' . $wpdb->prefix . "wc_revolut_orders
                WHERE public_id=UNHEX(REPLACE(%s, '-', ''))", array($public_id));
        return $this->uuid_dashes($wpdb->get_col($order_query));
    }

    /**
     * Process a refund if supported.
     *
     * @param int $order_id Order ID.
     * @param float $amount Refund amount.
     * @param string $reason Refund reason.
     *
     * @return bool|WP_Error
     * @throws Exception
     */
    public function process_refund($order_id, $amount = null, $reason = '')
    {
        $wc_order = wc_get_order($order_id);

        if (!$this->can_refund_order($wc_order)) {
            return new WP_Error('error', __('Order can\'t be refunded.', 'woocommerce'));
        }

        $revolut_order_id = $this->get_revolut_order($order_id);

        if (!isset($revolut_order_id)) {
            throw new Exception(__('Can\'t retrieve order information right now. Please try again later or process the refund via your Revolut Business account.', 'revolut-gateway-for-woocommerce'));
        } else {
            $order = $this->api_client->get('/orders/' . $revolut_order_id);
            if ($order['type'] == "PAYMENT" && $order['state'] == "COMPLETED" || $order['state'] == "IN_SETTLEMENT") {
                if ($order['refunded_amount']['value'] == $order['order_amount']['value']) {
                    throw new Exception(__('The amount remaining for this order is less than the amount being refunded. Please check your Revolut Business account.', 'revolut-gateway-for-woocommerce'));
                }

                $amount = round($amount, 2);
                $currency = isset($order['order_amount']['currency']) ? $order['order_amount']['currency'] : "";
                if ($this->isZeroDecimal($currency) && ($amount - floor($amount)) > 0) {
                    throw new Exception(__('Revolut: Can\'t refund this amount for this order. Please check your Revolut Business account.', 'revolut-gateway-for-woocommerce'));
                }
                $refund_amount = $this->isZeroDecimal($currency) ? $amount : $amount * 100;
                $refund_amount_api = (float)$order['refunded_amount']['value'];
                $order_amount_api = (float)$order['order_amount']['value'];

                if ($refund_amount_api < $order_amount_api && $refund_amount <= $order_amount_api - $refund_amount_api) {
                    $body = array(
                        'amount' => $refund_amount,
                        'currency' => $wc_order->get_currency(),
                        "description" => $reason
                    );
                    $response = $this->action_revolut_order($revolut_order_id, 'refund', $body);
                    if (isset($response['state']) && isset($response['id'])) {
                        $wc_order->add_order_note(__('Order has been successfully refunded (Refund State: ' . $response['state'] . ', Refund ID: ' . $response['id'] . ').', 'revolut-gateway-for-woocommerce'));

                        return true;
                    }
                } else {
                    throw new Exception(__('Revolut: This amount can\'t be refunded for this order. Please check your Revolut Business account.', 'revolut-gateway-for-woocommerce'));
                }
            } else {
                throw new Exception(__('Revolut: Incomplete order can\'t be refunded', 'revolut-gateway-for-woocommerce'));
            }
        }

        return false;
    }

    /**
     * Check if is not minor currency
     *
     * @param $currency
     *
     * @return bool
     */
    public function isZeroDecimal($currency)
    {
        return strtolower($currency) == 'jpy';
    }

    public function admin_nav_tab($tabs)
    {
        $tabs[$this->id] = $this->tab_title;

        return $tabs;
    }

    public function check_currency_support()
    {
        if (!in_array(get_woocommerce_currency(), $this->available_currency_list)) {
            return false;
        }
        return true;
    }

    public function currency_support_error()
    {
        echo get_woocommerce_currency() . ' currency is not supported, please use a different currency to check out. You can check the supported currencies in the <a href="https://www.revolut.com/en-HR/business/help/merchant-accounts/payments/in-which-currencies-can-i-accept-payments" target="_blank">[following link]</a>';
    }

    public function get_lang_iso_code()
    {
        return substr(get_locale(), 0, 2);
    }

    public function searchListKeyValue($list, $skey, $svalue)
    {
        foreach ($list as $element) {
            if (isset($element['payment_method'])) {
                foreach ($element['payment_method'] as $key => $value) {
                    if ($key == $skey && $svalue == $value) {
                        return $element;
                    }
                }
            }
        }

        return null;
    }
}