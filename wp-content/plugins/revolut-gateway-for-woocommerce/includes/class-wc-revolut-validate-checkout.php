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
        $_POST['terms-field'] = $data['terms-field'];
        $this->validate_checkout($data, $error);
        return $error;
    }
}