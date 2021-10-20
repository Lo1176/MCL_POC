<?php

/**
 * Class Revolut_Validate_Checkout
 *
 * Validate all checkout fields
 */
class Revolut_Validate_Checkout extends WC_Checkout
{
    public function validate_checkout_shipping($data, $error)
    {
        $_POST = $data;
        // Update session for customer and totals.
        $posted_data = $this->get_posted_data();
        $this->update_session( $posted_data );
        $this->validate_checkout($posted_data, $error);

        //additional validation check for wp-gdpr-compliance plugin
        if (in_array('wp-gdpr-compliance/wp-gdpr-compliance.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            if (empty($data['wpgdprc'])) {
                $error->add('terms', __('Please accept the privacy checkbox.', 'woocommerce'));
            }
        }

        return $error;
    }
}