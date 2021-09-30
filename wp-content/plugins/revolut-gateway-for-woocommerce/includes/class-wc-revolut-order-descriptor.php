<?php

class Revolut_Order_Descriptor
{
    public $amount;
    public $currency;
    public $merchant_order_ext_ref = null;
    public $customer_email = null;
    public $revolut_customer_id;

    /**
     * OrderDescriptor constructor.
     *
     * @param float $amount
     * @param string $currency
     * @param string $merchant_order_ext_ref
     * @param string $customer_email
     * @param string $revolut_customer_id
     */
    public function __construct($amount, $currency, $revolut_customer_id)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->revolut_customer_id = $revolut_customer_id;
    }
}