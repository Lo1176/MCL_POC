<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles and process WC payment tokens API.
 * Seen in checkout page and my account->add payment method page.
 *
 */
class WC_Revolut_Payment_Tokens
{
    public function __construct()
    {
        add_action('woocommerce_payment_token_deleted', [$this, 'woocommerce_payment_token_deleted'], 10, 2);
        add_filter('woocommerce_payment_methods_list_item', [$this, 'get_account_saved_payment_methods_list_item'], 10, 2);
    }

    public function get_account_saved_payment_methods_list_item($item, $payment_token)
    {
        return $item;
    }

    public function woocommerce_payment_token_deleted($token_id, $token)
    {
        $gateway_revolut = new WC_Gateway_Revolut_CC();
        $revolut_customer_id = $gateway_revolut->get_revolut_customer_id(get_current_user_id());
        if (empty($revolut_customer_id)) {
            wc_add_notice('Can not find customer ID', 'error');
        }

        if ($token->get_gateway_id() == $gateway_revolut->id) {

            $payment_method_id = $token->get_token();
            try {
                $gateway_revolut->api_client->delete("/customers/$revolut_customer_id/payment-methods/$payment_method_id");
            } catch (Exception $e) {
                wc_add_notice($e->getMessage(), 'error');
            }
        }
    }
}

new WC_Revolut_Payment_Tokens();
