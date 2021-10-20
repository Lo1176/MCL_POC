<?php

class WC_Revolut_Settings_API extends WC_Settings_API
{

    use WC_Revolut_Settings_Trait;

    public function __construct()
    {
        $this->id = 'revolut';
        $this->tab_title = __('API Settings', 'revolut-gateway-for-woocommerce');
        $this->init_form_fields();
        $this->init_settings();
        $this->hooks();
    }

    public function hooks()
    {
        add_filter('wc_revolut_settings_nav_tabs', array($this, 'admin_nav_tab'));
        add_action('woocommerce_settings_checkout', array($this, 'output_settings_nav'));
        add_action('woocommerce_settings_checkout', array($this, 'admin_options'));
        add_action('admin_notices', array($this, 'add_revolut_description'));
        add_action('admin_notices', array($this, 'check_api_key'));
        add_action('woocommerce_update_options_checkout_' . $this->id, array($this, 'process_admin_options'));
        add_action('admin_head', array($this, 'check_exist_webhook'));
    }

    public function init_form_fields()
    {
        $mode = $this->get_option('mode') == 'live' ? 'live' : 'sandbox';
        $api_key_sandbox = $this->get_option('api_key_sandbox');
        $api_key_live = $this->get_option('api_key');
        $this->form_fields = array(
            'title' => array(
                'type' => 'title',
                'title' => __('Revolut Gateway - API Settings', 'revolut-gateway-for-woocommerce'),
            ),
            'mode' => array(
                'title' => __('Select Mode', 'revolut-gateway-for-woocommerce'),
                'description' => __('Select mode between live mode and sandbox.', 'revolut-gateway-for-woocommerce'),
                'desc_tip' => true,
                'type' => 'select',
                'default' => $mode,
                'options' => array(
                    'sandbox' => __('Sandbox', 'revolut-gateway-for-woocommerce'),
                    'live' => __('Live', 'revolut-gateway-for-woocommerce')
                )
            ),
            'api_key_sandbox' => array(
                'title' => __('API Key Sandbox'),
                'description' => __('API Key from your Merchant settings on Revolut.', 'revolut-gateway-for-woocommerce'),
                'desc_tip' => true,
                'default' => $api_key_sandbox,
                'type' => 'password',
                'class' => 'enabled-sandbox'
            ),
            'api_key' => array(
                'title' => __('API Key Live', 'revolut-gateway-for-woocommerce'),
                'type' => 'password',
                'description' => __('API Key from your Merchant settings on Revolut.', 'revolut-gateway-for-woocommerce'),
                'desc_tip' => true,
                'default' => $api_key_live,
                'class' => 'enabled-live'
            ),
            'setup_webhook_sandbox' => array(
                'title' => __('Setup Webhook Sandbox', 'revolut-gateway-for-woocommerce'),
                'type' => 'text',
                'description' => '<button class="setup-webhook" style="min-height: 30px;"><span id="span-for-active-button-sandbox">Setup</span></button>
                                <p id="text_for_isset_webhook_sandbox"><i>Webhook has been set</i></p>
            					<p>Setup the Webhook to sync your Woocommerce orders when an order is completed on Revolut\'s side</p>'
            ),
            'setup_webhook_live' => array(
                'title' => __('Setup Webhook Live', 'revolut-gateway-for-woocommerce'),
                'type' => 'text',
                'description' => '<button class="setup-webhook" style="min-height: 30px;"><span id="span-for-active-button-live">Setup</span></button>
                                <p id="text_for_isset_webhook_live"><i>Webhook has been set</i></p>
            					<p>Setup the Webhook to sync your Woocommerce orders when an order is completed on Revolut\'s side</p>'
            ),
            'payment_action' => array(
                'title' => __('Payment Action', 'revolut-gateway-for-woocommerce'),
                'type' => 'select',
                'default' => 'authorize_and_capture',
                'options' => array(
                    'authorize' => __('Authorize Only', 'revolut-gateway-for-woocommerce'),
                    'authorize_and_capture' => __('Authorize and Capture', 'revolut-gateway-for-woocommerce')
                ),
                'description' => __('Select "Authorize Only" mode. This allows the payment to be captured up to 7 days after the user has placed the order (e.g. when the goods are shipped or received). 
                If not selected, Revolut will try to authorize and capture all payments.', 'woocommece-gateway-revolut'),
                'desc_tip' => true,
            ),
            'accept_capture' => array(
                'title' => __('', 'revolut-gateway-for-woocommerce'),
                'label' => __('Automatically capture order in Revolut', 'revolut-gateway-for-woocommerce'),
                'type' => 'checkbox',
                'description' => __('Automatically try to capture orders when their status is changed to Processing or Completed.', 'revolut-gateway-for-woocommerce'),
                'default' => 'yes'
            ),
        );
    }

    public function admin_options()
    {
        if (isset($_GET['page']) && isset($_GET['section'])) {
            $is_revolut_api_section = $_GET['page'] == 'wc-settings' && ($_GET['section'] == 'revolut');

            if ($is_revolut_api_section) {
                echo '<table class="form-table">' . $this->generate_settings_html($this->get_form_fields(), false) . '</table>';
            }
        }
    }

    /**
     * Output Revolut description.
     *
     * @since 2.0.0
     */
    function add_revolut_description()
    {
        if (isset($_GET['page']) && isset($_GET['section'])) {
            $is_revolut_section = $_GET['page'] == 'wc-settings' && ($_GET['section'] == 'revolut' || $_GET['section'] == 'revolut_cc' || $_GET['section'] == 'revolut_pay');

            if ($is_revolut_section) {
                if ($this->settings['api_key'] == "") {
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
                                    Once your Revolut Business account has been approved, <a target="_blank"
                                            href="https://business.revolut.com/merchant">apply for a Merchant
                                        Account</a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://business.revolut.com/settings/merchant-api">Get your Production API key</a>
                                    and
                                    paste it in the corresponding field below
                                </li>
                            </ul>
                            <p>
                                <a target="_blank" href="https://www.revolut.com/business/online-payments">Find out more</a> about why
                                accepting
                                payments through Revolut is the right decision for your business.
                            </p>
                            <p>
                                If you'd like to know more about how to configure this plugin for your needs, <a target="_blank"
                                        href="https://developer.revolut.com/docs/accept-payments/plugins/woocommerce/configuration">check
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
     * Add admin notice when set up failed
     */
    public function check_api_key()
    {
        if (isset($_GET['page']) && isset($_GET['section'])) {
            $is_revolut_section = $_GET['page'] == 'wc-settings' && ($_GET['section'] == 'revolut' || $_GET['section'] == 'revolut_cc' || $_GET['section'] == 'revolut_pay');

            if ($is_revolut_section) {
                $message = get_option("revolut_message");

                if ($message) {
                    echo '<div class="error revolut-passphrase-message"><p>'
                        . __('Set up failed. Please re-check your API and action mode', 'revolut-gateway-for-woocommerce')
                        . '</p></div>';
                }
            }
        }
    }

    /**
     * Check webhook exist
     * @throws Exception
     */
    public function check_exist_webhook()
    {
        $list_webhook = $this->revolut_api_get_request('/webhooks');
        $latest_webhook_url = end($list_webhook);
        $current_webhook_url = get_option('siteurl') . '/wp-json/wc/v3/revolut';

        $mode = $this->get_option('mode');

        if (isset($latest_webhook_url['url']) && $latest_webhook_url['url'] == $current_webhook_url) {
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
     * Send GET request to API
     *
     * @param $path
     * @param null $body
     *
     * @return mixed
     * @throws Exception
     */
    private function revolut_api_get_request($path)
    {
        $api_client = new WC_Revolut_API_Client($this);
        $result = $api_client->get($path);
        if (is_array($result) && isset($result['message'])) {
            update_option("revolut_message", $result['message']);
            return [];
        }
        update_option("revolut_message", "");
        return $result;
    }
}