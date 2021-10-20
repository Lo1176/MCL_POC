<?php

/**
 * Revolut Pay
 *
 * Provides a gateway to accept payments through Revolut Pay.
 *
 * @package WooCommerce
 * @category Payment Gateways
 * @author Revolut
 * @since 2.0
 */

class WC_Gateway_Revolut_Pay extends WC_Payment_Gateway_Revolut
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = 'revolut_pay';
        $this->method_title = __('Revolut Gateway - Revolut Pay', 'revolut-gateway-for-woocommerce');
        $this->tab_title = __('Revolut Pay', 'revolut-gateway-for-woocommerce');

        $this->default_title = __('Pay with Revolut', 'revolut-gateway-for-woocommerce');
        $this->method_description = sprintf(__('Accept payments easily and securely via %1$sRevolut%2$s.', 'revolut-gateway-for-woocommerce'), '<a href="https://www.revolut.com/business/online-payments">', '</a>');

        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');

        // Get settings from version 1.x
        try {
            if (get_option('woocommerce_revolut_pay_settings') === false) {
                $this->update_option('title', $this->default_title);
                $this->update_option('enabled', 'yes');
            }
        } catch (Exception $e) {
            $this->logError($e);
        }

        parent::__construct();
    }

    /**
     * Supported functionality
     */
    public function init_supports()
    {
        parent::init_supports();
        $this->supports[] = 'refunds';
    }

    /**
     * Display Revolut Pay icon
     */
    public function get_icon()
    {
        $icons_str = '';

        $icons_str .= '<img src="' . WC_REVOLUT_PLUGIN_URL . '/assets/images/Revolut_Business_Symbol_Black.svg" style="max-width: 40px" alt="Revolut Pay" />';

        return apply_filters('woocommerce_gateway_icon', $icons_str, $this->id);
    }

    /**
     * Add public_id field and logo on card form
     *
     * @param $public_id
     *
     * @return string
     */
    public function generate_inline_revolut_form($public_id)
    {
        return '<div id="woocommerce-revolut-pay-element" data-textcolor="" data-locale="' . $this->get_lang_iso_code() . '" data-public-id="' . $public_id . '" style="height: 50px;"></div>
		<input type="hidden" id="wc_' . $this->id . '_payment_nonce" name="wc_' . $this->id . '_payment_nonce" />';
    }
}