<?php
defined('ABSPATH') || exit();

/**
 * Revolut API Client
 *
 * @since 2.0
 * @author Revolut
 *
 */
class WC_Revolut_API_Client
{

    use WC_Revolut_Logger_Trait;

    /**
     * Version
     *
     * @var string
     */
    public $version;

    private $api_key;
    private $base_url;
    private $api_url;

    /**
     * API settings
     *
     * @var WC_Revolut_Settings_API
     */
    private $api_settings;

    /**
     * Constructor
     */
    public function __construct(WC_Revolut_Settings_API $api_settings)
    {
        $this->api_settings = $api_settings;
        $this->version = WC_GATEWAY_REVOLUT_VERSION;

        $this->api_key = $this->api_settings->get_option('mode') == "sandbox" ? $this->api_settings->get_option('api_key_sandbox') : $this->api_settings->get_option('api_key');
        $this->base_url = $this->api_settings->get_option('mode') == "sandbox" ? 'https://sandbox-merchant.revolut.com' : 'https://merchant.revolut.com';

        $this->api_url = $this->base_url . '/api/1.0';
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
    private function request($path, $method, $body = null)
    {
        global $wp_version;
        global $woocommerce;

        if (empty($this->api_key)) {
            return [];
        }

        $request = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'User-Agent' => 'Revolut Payment Gateway/' . WC_GATEWAY_REVOLUT_VERSION
                    . ' WooCommerce/' . $woocommerce->version
                    . ' Wordpress/' . $wp_version
                    . ' PHP/' . PHP_VERSION,
                "Content-Type" => "application/json"
            ),
            'method' => $method
        );

        if ($body != null) {
            $request['body'] = json_encode($body);
        }

        $url = $this->api_url . $path;
        $response = wp_remote_request($url, $request);
        $response_body = wp_remote_retrieve_body($response);

        if (wp_remote_retrieve_response_code($response) >= 400 && wp_remote_retrieve_response_code($response) < 500 && $method != "GET") {
            $this->logError("Failed request to URL $method $url");
            $this->logError($response_body);
            throw new Exception("Something went wrong: $method $url\n" . $response_body);
        }

        return json_decode($response_body, true);
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
    public function post($path, $body = null)
    {
        return $this->request($path, 'POST', $body);
    }

    /**
     * Send GET request to API
     *
     * @param $path
     *
     * @return mixed
     * @throws Exception
     */
    public function get($path)
    {
        return $this->request($path, 'GET');
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
    public function patch($path, $body)
    {
        return $this->request($path, 'PATCH', $body);
    }

    /**
     * Revolut API delete
     *
     * @param $path
     *
     * @return mixed
     * @throws Exception
     */
    public function delete($path)
    {
        return $this->request($path, 'DELETE');
    }
}