<?php

/**
 * Revolut Payment Gateway
 *
 * Provides a Revolut Payment Gateway.
 *
 * @class  woocommerce_revolut
 * @package WooCommerce
 * @category Payment Gateways
 * @author Revolut
 */

include_once REVOLUT_PATH . '/api/RevolutController.php';
define( 'FAILED_CARD', 2005 );

class WC_Gateway_Revolut extends WC_Payment_Gateway_CC {

	/**
	 * Version
	 *
	 * @var string
	 */
	public $version;

	private $api_key;
	private $api_key_sandbox;
	private $base_url;
	private $api_url;

	private $enable_logging = true;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->version      = WC_GATEWAY_REVOLUT_VERSION;
		$this->id           = 'revolut';
		$this->method_title = __( 'Revolut', 'woocommerce-gateway-revolut' );
		$this->has_fields   = true;

		$this->method_description = sprintf( __( 'Accept card payments easily and securely via %1$sRevolut%2$s.', 'woocommerce-gateway-revolut' ), '<a href="https://www.revolut.com/business/online-payments">', '</a>' );
		$this->icon               = $this->get_icon();

		// Supported functionality
		$this->supports = array(
			'products',
			'refunds',
			'tokenization',
			'add_payment_method'
		);

		$this->title           = $this->get_option( 'title' );
		$this->api_key_sandbox = $this->get_option( 'api_key_sandbox' );

		//get setting from old version
		if ( 'yes' === $this->get_option( 'sandbox' ) && $this->get_option( 'mode' ) == "" ) {
			$this->update_option( 'mode', 'sandbox' );
			$this->update_option( 'api_key_sandbox', $this->get_option( 'api_key' ) );
			$this->update_option( 'api_key', '' );
		} else if ( 'yes' != $this->get_option( 'sandbox' ) && $this->get_option( 'mode' ) == "" ) {
			$this->update_option( 'mode', 'live' );
			$this->update_option( 'api_key', $this->get_option( 'api_key' ) );
		}

		$this->api_key  = $this->get_option( 'mode' ) == "sandbox" ? $this->api_key_sandbox : $this->get_option( 'api_key' );
		$this->base_url = $this->get_option( 'mode' ) == "sandbox" ? 'https://sandbox-merchant.revolut.com' : 'https://merchant.revolut.com';

		$this->api_url = $this->base_url . '/api/1.0';

		$this->init_form_fields();
		$this->init_settings();

		$this->description = $this->get_option( 'description' );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
			$this,
			'process_admin_options'
		) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'revolut_enqueue_scripts' ) );
		add_action( 'admin_head', array( $this, 'check_exist_webhook' ) );
		add_action( 'init', array( $this, 'load_rest_api' ) );
		add_action( 'woocommerce_order_status_changed', array( $this, 'order_action_from_woocommerce' ), 10, 3 );
		add_action( 'woocommerce_pay_order_before_submit', array( $this, 'add_shipping_information' ) );
		add_action( 'admin_notices', array( $this, 'add_revolut_description' ) );
		add_action( 'admin_notices', array( $this, 'check_api_key' ) );
	}

	/**
	 * Output Revolut description.
	 *
	 * @since 2.2.0
	 */
	function add_revolut_description() {
		if ( isset( $_GET['page'] ) && isset( $_GET['section'] ) ) {
			$is_revolut_section = $_GET['page'] == 'wc-settings' && ( $_GET['section'] == 'revolut' || $_GET['section'] == 'wc_gateway_revolut' );

			if ( $is_revolut_section ) {
				if ( $this->api_key == "" ) {
					?>
                    <div class="notice notice-info sf-notice-nux is-dismissible" xmlns="" id="revolut_notice">
                        <div class="notice-content">
                            <p>
                                Welcome to the <b>Revolut Gateway for Woocommerce plugin!</b>
                            </p>
                            <p>
                                To start accepting payments from your customers at great rates, you'll need to follow
                                three
                                simple steps:
                            </p>
                            <ul style="list-style-type: disc; margin-left: 50px;">
                                <li>
                                    <a href="https://business.revolut.com/signup">Sign up for Revolut Business</a> if
                                    you
                                    don't
                                    have an account already.
                                </li>
                                <li>
                                    Once your Revolut Business account has been approved, <a
                                            href="https://business.revolut.com/merchant">apply for a Merchant
                                        Account</a>
                                </li>
                                <li>
                                    <a href="https://business.revolut.com/merchant/api">Get your Production API key</a>
                                    and
                                    paste it in the corresponding field below
                                </li>
                            </ul>
                            <p>
                                <a href="https://www.revolut.com/business/online-payments">Find out more</a> about why
                                accepting
                                payments through Revolut is the right decision for your business.
                            </p>
                            <p>
                                If you'd like to know more about how to configure this plugin for your needs, <a
                                        href="https://developer.revolut.com/docs/accept-payments/#plugins-plugins-woocommerce-configure-the-woocommerce-plugin">check
                                    out
                                    our documentation.</a>
                            </p>
                        </div>
                    </div>
					<?php
				} else {
					?>
                    <script>
                        jQuery(document).ready(function ($) {
                            $("#revolut_notice").hide();
                        });
                    </script>
					<?php
				}
			}
		}
	}

	/**
	 * Renders hidden inputs on the "Pay for Order" page in order to let Revolut handle PaymentIntents.
	 */
	function add_shipping_information() {
		if ( is_wc_endpoint_url( 'order-pay' ) ) {
			$order_id = wc_get_order_id_by_order_key( $_GET['key'] );
			$wc_order = wc_get_order( $order_id );
			?>
            <div style="display: none">
				<?php
				if ( $wc_order->get_billing_first_name() != "" ) {
					?>
                    <input type="text" class="input-text " name="billing_first_name" id="billing_first_name"
                           placeholder="" value="<?= $wc_order->get_billing_first_name() ?>"
                           autocomplete="given-name" readonly/>
					<?php
				}
				?>
				<?php
				if ( $wc_order->get_billing_last_name() != "" ) {
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
	 * Load API function
	 */
	public function load_rest_api() {
		add_action( 'rest_api_init', 'createApi', 99 );
	}

	/**
	 * Create API to accept setup Webhook
	 */
	public function createApi() {
		$api = new RevolutController();
		$api->register_routes();
	}

	/**
	 * Display card type
	 */
	public function get_icon() {
		$icons_str = '';

		$icons_str .= '<img src="' . WC_REVOLUT_PLUGIN_URL . '/assets/images/visa.svg" style="max-width: 40px" alt="Visa" />';
		$icons_str .= '<img src="' . WC_REVOLUT_PLUGIN_URL . '/assets/images/mastercard.svg" style="max-width: 40px" alt="MasterCard" />';

		return apply_filters( 'woocommerce_gateway_icon', $icons_str, $this->id );
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		$mode              = $this->get_option( 'mode' ) == 'live' ? 'live' : 'sandbox';
		$api_key_sandbox   = $this->get_option( 'api_key_sandbox' );
		$api_key_live      = $this->get_option( 'api_key' );
		$this->form_fields = array(
			'enabled'               => array(
				'title'       => __( 'Enable/Disable', 'woocommerce-gateway-revolut' ),
				'label'       => __( 'Enable Revolut', 'woocommerce-gateway-revolut' ),
				'type'        => 'checkbox',
				'description' => __( 'This controls whether or not this gateway is enabled within WooCommerce.', 'woocommerce-gateway-revolut' ),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'title'                 => array(
				'title'       => __( 'Title', 'woocommerce-gateway-revolut' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-gateway-revolut' ),
				'default'     => __( 'Pay with card', 'woocommerce-gateway-revolut' ),
				'desc_tip'    => true,
			),
			'payment_action'        => array(
				'title'       => __( 'Payment Action', 'woocommerce-gateway-revolut' ),
				'type'        => 'select',
				'default'     => 'authorize_and_capture',
				'options'     => array(
					'authorize'             => __( 'Authorize Only', 'woocommerce-gateway-revolut' ),
					'authorize_and_capture' => __( 'Authorize and Capture', 'woocommerce-gateway-revolut' )
				),
				'description' => __( 'Select "Authorize Only" mode. This allows the payment to be captured up to 7 days after the user has placed the order (e.g. when the goods are shipped or received). 
                If not selected, Revolut will try to authorize and capture all payments.', 'woocommece-gateway-revolut' ),
				'desc_tip'    => true,
			),
			'accept_capture'        => array(
				'title'       => __( '', 'woocommerce-gateway-revolut' ),
				'label'       => __( 'Automatically capture order in Revolut', 'woocommerce-gateway-revolut' ),
				'type'        => 'checkbox',
				'description' => __( 'Automatically try to capture orders when their status is changed to Processing or Completed.', 'woocommerce-gateway-revolut' ),
				'default'     => 'yes'
			),
			'mode'                  => array(
				'title'       => __( 'Select Mode', 'woocommerce-gateway-revolut' ),
				'description' => __( 'Select mode between live mode and sandbox.', 'woocommerce-gateway-revolut' ),
				'desc_tip'    => true,
				'type'        => 'select',
				'default'     => $mode,
				'options'     => array(
					'sandbox' => __( 'Sandbox', 'woocommerce-gateway-revolut' ),
					'live'    => __( 'Live', 'woocommerce-gateway-revolut' )
				)
			),
			'api_key_sandbox'       => array(
				'title'       => __( 'API Key Sandbox' ),
				'description' => __( 'API Key from your Merchant settings on Revolut.', 'woocommerce-gateway-revolut' ),
				'desc_tip'    => true,
				'default'     => $api_key_sandbox,
				'type'        => 'password',
				'class'       => 'enabled-sandbox'
			),
			'api_key'               => array(
				'title'       => __( 'API Key Live', 'woocommerce-gateway-revolut' ),
				'type'        => 'password',
				'description' => __( 'API Key from your Merchant settings on Revolut.', 'woocommerce-gateway-revolut' ),
				'desc_tip'    => true,
				'default'     => $api_key_live,
				'class'       => 'enabled-live'
			),
			'setup_webhook_sandbox' => array(
				'title'       => __( 'Setup Webhook Sandbox', 'woocommerce-gateway-revolut' ),
				'type'        => 'text',
				'description' => '<button class="setup-webhook" style="min-height: 30px;"><span id="span-for-active-button-sandbox">Setup</span></button>
                                <p id="text_for_isset_webhook_sandbox"><i>Webhook has been set</i></p>
            					<p>Setup the Webhook to sync your Woocommerce orders when an order is completed on Revolut\'s side</p>'
			),
			'setup_webhook_live'    => array(
				'title'       => __( 'Setup Webhook Live', 'woocommerce-gateway-revolut' ),
				'type'        => 'text',
				'description' => '<button class="setup-webhook" style="min-height: 30px;"><span id="span-for-active-button-live">Setup</span></button>
                                <p id="text_for_isset_webhook_live"><i>Webhook has been set</i></p>
            					<p>Setup the Webhook to sync your Woocommerce orders when an order is completed on Revolut\'s side</p>'
			)
		);
	}

	/**
	 * Add script to load card form
	 */
	public function revolut_enqueue_scripts() {
		wp_enqueue_script( 'revolut-core', $this->base_url . '/embed.js', false, WC_GATEWAY_REVOLUT_VERSION, true );

		wp_enqueue_script( "jquery" );
		wp_enqueue_script( 'revolut-woocommerce', plugins_url( 'assets/js/revolut.js', WC_REVOLUT_MAIN_FILE ), array(
			'revolut-core',
			'jquery'
		), WC_GATEWAY_REVOLUT_VERSION, true );

	}

	/**
	 * Build payment fields area - including fields for logged
	 * in users, and the payment fields.
	 */
	public function payment_fields() {
		if ( ! isset( $_POST['post_data'] ) ) {
			$this->unset_revolut_public_id();
		}

		$public_id  = $this->get_revolut_public_id();
		$descriptor = new RevolutOrderDescriptor( WC()->cart->get_total( '' ), get_woocommerce_currency(), null, null );
		try {
			if ( $public_id === null ) {
				$public_id = $this->create_revolut_order( $descriptor );
			} else {
				$this->update_revolut_order( $descriptor, $public_id );
			}

			$this->set_revolut_public_id( $public_id );
			echo $this->generate_inline_revolut_form( $public_id );
		} catch ( Exception $e ) {
			$this->logError( $e );
			echo 'To receive payments using the Revolut Gateway for WooCommerce plugin, please <a href="https://developer.revolut.com/docs/accept-payments/#plugins-plugins-woocommerce-configure-the-woocommerce-plugin" target="_blank">configure your API key</a>.<br><br>If you are still seeing this message after the configuration of your API key, please reach out via the support chat in your Revolut Business account.';
		}
	}

	/**
	 * Return error message
	 *
	 * @param $message
	 */
	public function logError( $message ) {
		error_log( $message );
		if ( 'yes' === $this->get_option( 'sandbox' ) || $this->enable_logging ) {
			if ( empty( $this->logger ) ) {
				$this->logger = new WC_Logger();
			}
			$this->logger->add( 'revolut', $message );
		}
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
	public function update_revolut_order_metadata( $revolut_order_id, $wc_order_id ) {
		if ( empty( $revolut_order_id ) || empty( $wc_order_id ) ) {
			$this->logError( 'Something went wrong: Params is empty' );
		}
		$body = array(
			'merchant_order_ext_ref' => $wc_order_id
		);

		$json = $this->revolut_api_patch( "/orders/$revolut_order_id", $body );

		if ( ! isset( $json['public_id'] ) || ! isset( $json['id'] ) ) {
			throw new Exception( 'Something went wrong: ' . json_encode( $json, JSON_PRETTY_PRINT ) );
		}
	}

	/**
	 * Send post request to API
	 *
	 * @param $path
	 * @param null $body
	 *
	 * @return mixed
	 * @throws Exception
	 */
	private function revolut_api_post_request( $path, $body = null ) {
		global $wp_version;
		global $woocommerce;

		if ( empty( $this->api_key ) ) {
			return [];
		}
		$request = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $this->api_key,
				'User-Agent'    => 'Revolut Payment Gateway/' . WC_GATEWAY_REVOLUT_VERSION
				                   . ' WooCommerce/' . $woocommerce->version
				                   . ' Wordpress/' . $wp_version
				                   . ' PHP/' . PHP_VERSION,
				"Content-Type"  => "application/json"
			),
			'method'  => 'POST'
		);

		if ( $body != null ) {
			$request['body'] = json_encode( $body );
		}

		$url           = $this->api_url . $path;
		$response      = wp_remote_request( $url, $request );
		$response_body = wp_remote_retrieve_body( $response );

		if ( is_wp_error( $response ) ) {
			throw new Exception( "Something went wrong: $url\n" . $response->get_error_message() );
		}

		return json_decode( $response_body, true );
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
	public function action_revolut_order( $revolut_order_id, $action, $body = null ) {
		if ( empty( $revolut_order_id ) ) {
			return [];
		}
		$json = $this->revolut_api_post_request( "/orders/$revolut_order_id/$action", $body );

		if ( ! isset( $json['id'] ) ) {
			if ( $json['code'] == FAILED_CARD ) {
				throw new Exception( __( 'Customer will not be able to get a ' . $action . ' using this card!', 'woocommerce-gateway-revolut' ) );
			}
			throw new Exception( __( 'Cannot ' . $action . ' Order - Error Id: ' . $json['errorId'] . '.', 'woocommerce-gateway-revolut' ) );
		}

		return $json;
	}

	/**
	 * Create Revolut Order
	 *
	 * @param RevolutOrderDescriptor $order_descriptor
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function create_revolut_order( RevolutOrderDescriptor $order_descriptor ) {
		global $wp;
		if ( isset( $_GET['pay_for_order'] ) && ! empty( $_GET['key'] ) ) {
			$order = wc_get_order( wc_clean( $wp->query_vars['order-pay'] ) );
			$total = $order->get_total();
		} else {
			$total = $order_descriptor->amount;
		}
		$capture = $this->get_option( 'payment_action' ) == 'authorize' ? 'MANUAL' : 'AUTOMATIC';

		if ( ! $this->isZeroDecimal( $order_descriptor->currency ) ) {
			$total = $total * 100;
		}
		$body = array(
			'amount'                 => round( $total ),
			'currency'               => $order_descriptor->currency,
			'merchant_order_ext_ref' => $order_descriptor->merchant_order_ext_ref,
			'customer_email'         => $order_descriptor->customer_email,
			'capture_mode'           => $capture
		);
		$json = $this->revolut_api_post( '/orders', $body );

		if ( empty( $json['id'] ) || empty( $json['public_id'] ) ) {
			throw new Exception( 'Something went wrong: ' . json_encode( $json, JSON_PRETTY_PRINT ) );
		}

		global $wpdb;
		$insert_query = $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . "wc_revolut_orders (order_id, public_id)
            VALUES (UNHEX(REPLACE(%s, '-', '')), UNHEX(REPLACE(%s, '-', '')))", array(
			$json['id'],
			$json['public_id']
		) );
		$this->query( $insert_query );

		return $json['public_id'];
	}

	/**
	 * Send post to API
	 *
	 * @param $path
	 * @param null $body
	 *
	 * @return mixed
	 * @throws Exception
	 */
	private function revolut_api_post( $path, $body = null ) {
		return $this->revolut_api_request( $path, 'POST', $body );
	}

	/**
	 * Send request to API
	 *
	 * @param $path
	 * @param $method
	 * @param null $body
	 *
	 * @return mixed
	 * @throws Exception
	 */
	private function revolut_api_request( $path, $method, $body = null ) {
		global $wp_version;
		global $woocommerce;

		if ( empty( $this->api_key ) ) {
			return [];
		}

		$request = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $this->api_key,
				'User-Agent'    => 'Revolut Payment Gateway/' . WC_GATEWAY_REVOLUT_VERSION
				                   . ' WooCommerce/' . $woocommerce->version
				                   . ' Wordpress/' . $wp_version
				                   . ' PHP/' . PHP_VERSION,
				"Content-Type"  => "application/json"
			),
			'method'  => $method
		);

		if ( $body != null ) {
			$request['body'] = json_encode( $body );
		}

		$url           = $this->api_url . $path;
		$response      = wp_remote_request( $url, $request );
		$response_body = wp_remote_retrieve_body( $response );

		if ( wp_remote_retrieve_response_code( $response ) >= 400 && wp_remote_retrieve_response_code( $response ) < 500 && $method != "GET" ) {
			$this->logError( "Failed request to URL $method $url" );
			$this->logError( $response_body );
			throw new Exception( "Something went wrong: $method $url\n" . $response_body );
		}

		return json_decode( $response_body, true );
	}

	/**
	 * Request query to database
	 *
	 * @param $query
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	private function query( $query ) {
		global $wpdb;
		$result = $wpdb->query( $query );
		if ( $result === false ) {
			throw new Exception( $wpdb->last_error );
		}

		return $result;
	}

	/**
	 * Update Revolut Order
	 *
	 * @param RevolutOrderDescriptor $order_descriptor
	 * @param $public_id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function update_revolut_order( RevolutOrderDescriptor $order_descriptor, $public_id ) {
		global $wpdb;

		$order_query = $wpdb->prepare( 'SELECT HEX(order_id) FROM ' . $wpdb->prefix . "wc_revolut_orders
                WHERE public_id=UNHEX(REPLACE(%s, '-', ''))", array( $public_id ) );
		$order_id    = $this->uuid_dashes( $wpdb->get_col( $order_query ) );

		if ( ! $this->isZeroDecimal( $order_descriptor->currency ) ) {
			$order_descriptor->amount = $order_descriptor->amount * 100;
		}
		$body = array(
			'amount'                 => round( $order_descriptor->amount ),
			'currency'               => $order_descriptor->currency,
			'merchant_order_ext_ref' => $order_descriptor->merchant_order_ext_ref,
			'customer_email'         => $order_descriptor->customer_email
		);

		if ( empty( $order_id ) ) {
			return "";
		}

		$json = $this->revolut_api_patch( "/orders/$order_id", $body );

		if ( ! isset( $json['public_id'] ) || ! isset( $json['id'] ) ) {
			throw new Exception( 'Something went wrong: ' . json_encode( $json, JSON_PRETTY_PRINT ) );
		}

		return $json['public_id'];
	}

	/**
	 * Replace dashes
	 *
	 * @param $uuid
	 *
	 * @return string|string[]|null
	 */
	private function uuid_dashes( $uuid ) {
		if ( is_array( $uuid ) ) {
			if ( isset( $uuid[0] ) ) {
				$uuid = $uuid[0];
			}
		}

		$result = preg_replace( '/(\w{8})(\w{4})(\w{4})(\w{4})(\w{12})/i', '$1-$2-$3-$4-$5', $uuid );

		return $result;
	}

	/**
	 * Revolut API patch
	 *
	 * @param $path
	 * @param $body
	 *
	 * @return mixed
	 * @throws Exception
	 */
	private function revolut_api_patch( $path, $body ) {
		return $this->revolut_api_request( $path, 'PATCH', $body );
	}

	/**
	 * Unset Revolut public_id
	 */
	private function unset_revolut_public_id() {
		WC()->session->__unset( 'revolut_public_id' );
	}

	/**
	 * Set Revolut public_id
	 *
	 * @param $value
	 */
	private function set_revolut_public_id( $value ) {
		WC()->session->set( 'revolut_public_id', $value );
	}

	/**
	 * Get Revolut public_id
	 * @return array|string|null
	 */
	private function get_revolut_public_id() {
		return WC()->session->get( 'revolut_public_id' );
	}

	/**
	 * Add public_id field and logo on card form
	 *
	 * @param $public_id
	 *
	 * @return string
	 */
	public function generate_inline_revolut_form( $public_id ) {
		return '<input type="hidden" name="revolut_public_id" value="' . $public_id . '"/>'
		       . '<div id="woocommerce-revolut-card-element" data-public-id="' . $public_id . '" style="height: 40px;"></div>'
		       . '<img src="' . WC_REVOLUT_PLUGIN_URL . '/assets/images/Powered_by_Revolut.svg" style="float: left;" alt="power_by_revolut" />';
	}

	/**
	 * Process the payment and return the result.
	 */
	public function process_payment( $wc_order_id ) {
		$wc_order = wc_get_order( $wc_order_id );

		try {
			global $wpdb;

			// find public_id
			$public_id = $this->get_revolut_public_id();
			if ( $public_id == null ) {
				throw new Exception( 'Missing revolut_public_id parameter' );
			}

			// resolve into order_id
			$order_query = $wpdb->prepare( 'SELECT HEX(order_id) FROM ' . $wpdb->prefix . "wc_revolut_orders
                WHERE public_id=UNHEX(REPLACE(%s, '-', ''))", array( $public_id ) );
			$order_id    = $this->uuid_dashes( $wpdb->get_col( $order_query ) );

			// update internal table to avoid piggybacking on already paid order
			$update_query      = $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . "wc_revolut_orders
                SET wc_order_id=%d
                WHERE public_id=UNHEX(REPLACE(%s, '-', '')) AND wc_order_id IS NULL",
				array( $wc_order_id, $public_id ) );
			$isset_wc_order_id = $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . "wc_revolut_orders
                WHERE wc_order_id=%d
                ",
				array( $wc_order_id ) );
			if ( $this->query( $isset_wc_order_id ) !== 1 ) {
				$updated_rows = $this->query( $update_query );

				if ( $updated_rows !== 1 ) {
					throw new Exception( "Invalid revolut_public_id, updated rows: $updated_rows" );
				}
			}

			$wc_order->update_status( 'on-hold' );
			$wc_order->add_order_note( "Payment has been successfully authorized (Order ID: " . $order_id . ")." );

			// verify that the order was paid
			$check_state = false;
			$mode        = $this->get_option( 'payment_action' );

			try {
				$this->update_revolut_order_metadata( $order_id, $wc_order_id );
			} catch ( Exception $e ) {
				$patch_false = true;
			}

			for ( $i = 0; $i <= 9; $i ++ ) {
				if ( isset( $order_id ) && ! empty( $order_id ) ) {
					$order           = $this->revolut_api_get( '/orders/' . $order_id );
					$wc_order_status = empty( $wc_order->get_status() ) ? "" : $wc_order->get_status();
					$check_wc_status = $wc_order_status == "processing" || $wc_order_status == "completed";
					if ( isset( $order['state'] ) && ! $check_wc_status ) {
						if ( $order['state'] == 'COMPLETED' && $mode == "authorize_and_capture" ) {
							update_post_meta( $wc_order_id, 'revolut_capture', "yes" );
							$wc_order->payment_complete( $order_id );
							$wc_order->add_order_note( 'Payment has been successfully captured (Order ID: ' . $order_id . ').' );
							$check_state = true;
							return $this->checkout_return( $wc_order );

						} else if ( $order['state'] == 'AUTHORISED' && $mode == "authorize" ) {
							return $this->checkout_return( $wc_order );
						} else if ( $order['state'] == 'PENDING' ) {
							throw new Exception( 'Something went wrong while completing this payment. Please reach out to your customer and ask them to try again.' );
						} else if ( $i == 9 && ($order['state'] == "PROCESSING" || $order['state'] == "IN_SETTLEMENT")) {
							if ( $mode == "authorize_and_capture" ) {
								$wc_order->add_order_note( 'Payment is taking a bit longer than expected to be completed. 
							                If the order is not moved to the “Processing” state after 24h, please check your Revolut account to verify that this payment was taken. 
							                You might need to contact your customer if it wasn’t.' );
							}

							return $this->checkout_return( $wc_order );
						}

						sleep( 2 );
					} else if ( $check_wc_status ) {
						return $this->checkout_return( $wc_order );
					}
				}
			}
		} catch ( Exception $e ) {
			$this->logError( $e );
			$wc_order->update_status( 'failed' );
			$wc_order->add_order_note( "Customer attempted to pay, but the payment failed or got declined. You can check your Revolut account to find out more. (Error: " . get_option( 'revolut_message' ) . ")" );
			wc_add_notice( get_option( 'revolut_message' ), 'error' );

			return array(
				'result'   => 'fail',
				'redirect' => ''
			);
		}
	}

	/**
	 * Return after checkout successfully
	 *
	 * @param $wc_order
	 *
	 * @return array
	 */
	public function checkout_return( $wc_order ) {
		$this->unset_revolut_public_id();
		if ( isset( WC()->cart ) ) {
			WC()->cart->empty_cart();
		}

		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $wc_order ),
		);
	}

	/**
	 * Send GET request to API
	 *
	 * @param $path
	 *
	 * @return mixed
	 * @throws Exception
	 */
	private function revolut_api_get( $path ) {
		return $this->revolut_api_request( $path, 'GET' );
	}

	/**
	 * Add admin notice when use revolut payment without API key
	 */

	public function admin_notices() {
		if ( 'yes' !== $this->get_option( 'enabled' )
		     || ! empty( $this->api_key ) ) {
			return;
		}

		if ( empty( $this->api_key ) && empty( $this->api_key_sandbox ) ) {
			echo '<div class="error revolut-passphrase-message"><p>'
			     . __( 'Revolut requires a API Key to work.', 'woocommerce-gateway-revolut' )
			     . '</p></div>';
		}
	}

	/**
	 * Add admin notice when set up failed
	 */
	public function check_api_key() {
		if ( isset( $_GET['page'] ) && isset( $_GET['section'] ) ) {
			$is_revolut_section = $_GET['page'] == 'wc-settings' && ( $_GET['section'] == 'revolut' || $_GET['section'] == 'wc_gateway_revolut' );

			if ( $is_revolut_section ) {
				$message = get_option( "revolut_message" );

				if ( $message ) {
					echo '<div class="error revolut-passphrase-message"><p>'
					     . __( 'Set up failed. Please re-check your API and action mode', 'woocommerce-gateway-revolut' )
					     . '</p></div>';
				}
			}
		}
	}

	/**
	 * Send GET request to API
	 *
	 * @param $path
	 * @param null $body
	 *
	 * @return mixed
	 * @throws Exception
	 */
	private function revolut_api_get_request( $path, $body = null ) {
		global $wp_version;
		global $woocommerce;

		if ( isset( $_POST['woocommerce_revolut_mode'] ) ) {
			$get_api_key = $_POST['woocommerce_revolut_mode'] == 'sandbox' ? $_POST['woocommerce_revolut_api_key_sandbox'] : $_POST['woocommerce_revolut_api_key'];
		} else {
			$get_api_key = $this->api_key;
		}

		if ( ! $get_api_key ) {
			return [];
		}

		$request = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $get_api_key,
				'User-Agent'    => 'Revolut Payment Gateway/' . WC_GATEWAY_REVOLUT_VERSION
				                   . ' WooCommerce/' . $woocommerce->version
				                   . ' Wordpress/' . $wp_version
				                   . ' PHP/' . PHP_VERSION,
				"Content-Type"  => "application/json"
			),
			'method'  => 'GET'
		);

		if ( ! is_null( $body ) ) {
			$request['body'] = json_encode( $body );
		}

		$url           = $this->api_url . $path;
		$response      = wp_remote_request( $url, $request );
		$response_body = wp_remote_retrieve_body( $response );
		$result        = json_decode( $response_body, true );
		if ( is_wp_error( $response ) || ( is_array( $result ) && isset( $result['message'] ) ) ) {
			update_option( "revolut_message", $result['message'] );

			return [];
		}
		update_option( "revolut_message", "" );

		return $result;
	}

	/**
	 * Check webhook exist
	 * @throws Exception
	 */
	public function check_exist_webhook() {
		$list_webhook        = $this->revolut_api_get_request( '/webhooks' );
		$latest_webhook_url  = end( $list_webhook );
		$current_webhook_url = get_option( 'siteurl' ) . '/wp-json/wc/v3/revolut';

		$mode = $this->get_option( 'mode' );

		if ( isset( $latest_webhook_url['url'] ) && $latest_webhook_url['url'] == $current_webhook_url ) {
			?>
            <script>
                jQuery(document).ready(function ($) {
                    $("#span-for-active-button-<?= $mode ?>").text("Reset");
                    $('#text_for_isset_webhook_<?= $mode ?>').removeAttr('style', 'display: none');
                });
            </script>
			<?php
		} else {
			?>
            <script>
                jQuery(document).ready(function ($) {
                    $('#text_for_isset_webhook_<?= $mode ?>').attr('style', 'display: none');
                });
            </script>
			<?php
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
	public function order_action_from_woocommerce( $order_id, $old_status, $new_status ) {
		$wc_order = wc_get_order( $order_id );
		if ( $wc_order->get_payment_method() == 'revolut' ) {
			$revolut_order_id = $this->get_revolut_order( $order_id );

			if ( ! isset( $revolut_order_id ) ) {
				throw new Exception( __( 'Can\'t retrieve Revolut Order ID right now. Try again later or contact support via the Revolut Business app or web portal.', 'woocommerce-gateway-revolut' ) );
			} else if ( $this->get_option( 'accept_capture' ) == 'yes' && $revolut_order_id != "" ) {
				$order = $this->revolut_api_get( '/orders/' . $revolut_order_id );
				$state = isset( $order['state'] ) ? $order['state'] : "";
				if ( $this->get_option( 'payment_action' ) == 'authorize' ) {
					//capture order
					if ( $new_status == 'processing' || $new_status == 'completed' ) {
						// check fraud order
						$order_amount = isset( $order['order_amount']['value'] ) ? (float) $order['order_amount']['value'] : 0;
						$currency     = isset( $order['order_amount']['currency'] ) ? $order['order_amount']['currency'] : "";
						$total        = $this->isZeroDecimal( $currency ) ? $wc_order->get_total() : $wc_order->get_total() * 100;
						if ( $total != $order_amount ) {
							$wc_order->add_order_note( __( 'Order amount can\'t be partially captured. Please try again or capture this payment from your Revolut Business web portal.', 'woocommerce-gateway-revolut' ) );
						}

						if ( $state == "AUTHORISED" ) {
							$response       = $this->action_revolut_order( $revolut_order_id, 'capture' );
							$order_response = $this->revolut_api_get( '/orders/' . $revolut_order_id );

							if ( $order_response['state'] == 'COMPLETED' || $order_response['state'] == "IN_SETTLEMENT" ) {
								$wc_order->payment_complete( $revolut_order_id );
								$wc_order->add_order_note( __( 'Payment amount has been captured successfully.', 'woocommerce-gateway-revolut' ) );
								update_post_meta( $order_id, 'revolut_capture', "yes" );
							} else {
								$wc_order->add_order_note( __( 'Order capture wasn\'t successful. Please try again or check your Revolut Business web portal for more information', 'woocommerce-gateway-revolut' ) );
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
	public function get_revolut_order( $order_id ) {
		global $wpdb;
		$order_query      = $wpdb->prepare( 'SELECT HEX(order_id) FROM ' . $wpdb->prefix . 'wc_revolut_orders
                WHERE wc_order_id=' . $order_id );
		$revolut_order_id = $this->uuid_dashes( $wpdb->get_col( $order_query ) );

		return $revolut_order_id;
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
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$wc_order = wc_get_order( $order_id );

		if ( ! $this->can_refund_order( $wc_order ) ) {
			return new WP_Error( 'error', __( 'Order can\'t be refunded.', 'woocommerce' ) );
		}

		$revolut_order_id = $this->get_revolut_order( $order_id );

		if ( ! isset( $revolut_order_id ) ) {
			throw new Exception( __( 'Can\'t retrieve order information right now. Please try again later or process the refund via your Revolut Business account.', 'woocommerce-gateway-revolut' ) );
		} else {
			$order = $this->revolut_api_get( '/orders/' . $revolut_order_id );
			if ( $order['type'] == "PAYMENT" && $order['state'] == "COMPLETED" || $order['state'] == "IN_SETTLEMENT" ) {
				if ( $order['refunded_amount']['value'] == $order['order_amount']['value'] ) {
					throw new Exception( __( 'The amount remaining for this order is less than the amount being refunded. Please check your Revolut Business account.', 'woocommerce-gateway-revolut' ) );
				}

				$amount   = round( $amount, 2 );
				$currency = isset( $order['order_amount']['currency'] ) ? $order['order_amount']['currency'] : "";
				if ( $this->isZeroDecimal( $currency ) && ( $amount - floor( $amount ) ) > 0 ) {
					throw new Exception( __( 'Revolut: Can\'t refund this amount for this order. Please check your Revolut Business account.', 'woocommerce-gateway-revolut' ) );
				}
				$refund_amount     = $this->isZeroDecimal( $currency ) ? $amount : $amount * 100;
				$refund_amount_api = (float) $order['refunded_amount']['value'];
				$order_amount_api  = (float) $order['order_amount']['value'];

				if ( $refund_amount_api < $order_amount_api && $refund_amount <= $order_amount_api - $refund_amount_api ) {
					$body     = array(
						'amount'      => $refund_amount,
						'currency'    => $wc_order->get_currency(),
						"description" => $reason
					);
					$response = $this->action_revolut_order( $revolut_order_id, 'refund', $body );
					if ( $response['type'] == "REFUND" && $response['state'] == "COMPLETED" ) {
						$wc_order->add_order_note( __( 'Order has been successfully refunded (Refund ID: ' . $response['id'] . ').', 'woocommerce-gateway-revolut' ) );

						return true;
					}
				} else {
					throw new Exception( __( 'Revolut: This amount can\'t be refunded for this order. Please check your Revolut Business account.', 'woocommerce-gateway-revolut' ) );
				}
			} else {
				throw new Exception( __( 'Revolut: Incomplete order can\'t be refunded', 'woocommerce-gateway-revolut' ) );
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
	public function isZeroDecimal( $currency ) {
		return strtolower( $currency ) == 'jpy';
	}
}

class RevolutOrderDescriptor {
	public $amount;
	public $currency;
	public $merchant_order_ext_ref;
	public $customer_email;

	/**
	 * OrderDescriptor constructor.
	 *
	 * @param float $amount
	 * @param string $currency
	 * @param string $merchant_order_ext_ref
	 * @param string $customer_email
	 */
	public function __construct( $amount, $currency, $merchant_order_ext_ref, $customer_email ) {
		$this->amount                 = $amount;
		$this->currency               = $currency;
		$this->merchant_order_ext_ref = $merchant_order_ext_ref;
		$this->customer_email         = $customer_email;
	}
}

/**
 * Revolut Order Descriptor
 *
 * @param WC_Order $order
 *
 * @return RevolutOrderDescriptor
 */
function RevolutOrderDescriptor( WC_Order $order ) {
	return new RevolutOrderDescriptor( $order->get_total(), $order->get_currency(), $order->get_id(), $order->get_billing_email() );
}