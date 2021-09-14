<?php
defined('ABSPATH') || exit();

/**
 * Logger trait
 *
 * Provides shared logic for logging errors
 *
 * @since 2.0
 */
trait WC_Revolut_Logger_Trait
{

    /**
     * Logger status
     *
     * @var boolean
     */
    protected $enable_logging = true;

    /**
     * Return error message
     *
     * @param $message
     */
    public function logError($message)
    {
        error_log($message);
        if ('yes' === $this->api_settings->get_option('sandbox') || $this->enable_logging) {
            if (empty($this->logger)) {
                $this->logger = new WC_Logger();
            }
            $this->logger->add('revolut', $message);
        }
    }
}