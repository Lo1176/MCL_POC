<?php
defined('ABSPATH') || exit();

/**
 * Singleton class that handles class loading.
 *
 * @since 2.0
 * @author Revolut
 *
 */
class WC_Revolut_Manager
{

    public static $_instance;

    public static function instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct()
    {
        add_action('woocommerce_init', array($this, 'woocommerce_dependencies'));
    }

    public function woocommerce_dependencies()
    {

        // traits
        include_once REVOLUT_PATH . 'includes/traits/wc-revolut-settings-trait.php';
        include_once REVOLUT_PATH . 'includes/traits/wc-revolut-logger-trait.php';

        // load gateways
        include_once REVOLUT_PATH . 'includes/abstract/abstract-wc-gateway-revolut.php';
        include_once REVOLUT_PATH . 'includes/gateways/class-wc-gateway-revolut-cc.php';
        include_once REVOLUT_PATH . 'includes/gateways/class-wc-gateway-revolut-pay.php';

        // main classes
        include_once REVOLUT_PATH . 'includes/class-wc-gateway-revolut-privacy.php';
        include_once REVOLUT_PATH . 'includes/class-wc-revolut-validate-checkout.php';
        include_once REVOLUT_PATH . 'includes/class-wc-revolut-order-descriptor.php';
        include_once REVOLUT_PATH . 'includes/api/class-wc-revolut-api-client.php';
        include_once REVOLUT_PATH . '/api/RevolutController.php';

        // settings
        include_once REVOLUT_PATH . 'includes/settings/class-wc-gateway-revolut-api-settings.php';
        include_once REVOLUT_PATH . '/includes/class-wc-revolut-payment-tokens.php';

        // allow other plugins to provide their own settings class
        $setting_classes = apply_filters('wc_revolut_setting_classes', array(
            'api_settings' => 'WC_Revolut_Settings_API',
        ));
        foreach ($setting_classes as $id => $class_name) {
            if (class_exists($class_name)) {
                $this->{$id} = new $class_name();
            }
        }
    }
}

/**
 * Returns the global instance of the WC_Revolut_Manager.
 *
 * @return WC_Revolut_Manager
 * @since 2.0.0
 */
function revolut_wc()
{
    return WC_Revolut_Manager::instance();
}

// load singleton
revolut_wc();
