<?php

class RevolutController extends \WC_REST_Data_Controller
{
    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'wc/v3';

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'revolut';

    /**
     * Register routes.
     *
     * @since 3.5.0
     */
    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,

            array(
                'methods' => \WP_REST_Server::ALLMETHODS,
                'callback' => array($this, 'get_items'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            )
        );
    }

    /**
     * Check API request
     *
     * @param WP_REST_Request $request
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request)
    {
        $parameters = $request->get_params();
        $order_id = '';
        $event = '';
        if (isset($parameters['order_id']) && isset($parameters['event'])) {
            $order_id = $parameters['order_id'];
            $event = $parameters['event'];
        }

        if (empty($order_id)) {
            $parameters = $request->get_body();
            $parameters = json_decode($parameters, true);
            if (isset($parameters['order_id']) && isset($parameters['event'])) {
                $order_id = $parameters['order_id'];
                $event = $parameters['event'];
            }
        }

        if (empty($order_id)) {
            return new WP_REST_Response(['status' => 'Failed'], 200);
        }

        $wc_order_id = $this->get_wc_order_id($order_id);

        if (empty($wc_order_id) || empty($wc_order_id['wc_order_id'])) {
            return new WP_REST_Response(['status' => 'Failed'], 404);
        }

        // force webhook callback to wait, in order to be sure that the main payment process has ended
        $wait_for_main_process_time = (WC_REVOLUT_WAIT_FOR_ORDER_TIME * 3);
        sleep($wait_for_main_process_time);

        $wc_order = wc_get_order($wc_order_id['wc_order_id']);

        if (!$wc_order) {
            return new WP_REST_Response(['status' => 'Failed'], 404);
        }

        $wc_order_status = empty($wc_order->get_status()) ? "" : $wc_order->get_status();
        $check_wc_status = $wc_order_status == "processing" || $wc_order_status == "completed";
        $check_capture = isset(get_post_meta($wc_order_id['wc_order_id'], "revolut_capture")[0]) ? get_post_meta($wc_order_id['wc_order_id'], "revolut_capture")[0] : "";

        $data = [];
        if (!empty($order_id) && $check_capture != "yes") {
            if (!empty($wc_order) && empty($wc_order->get_transaction_id()) && !$check_wc_status) {
                if ($event == "ORDER_COMPLETED") {
                    $wc_order->add_order_note(__('Payment has been successfully captured (Order ID: ' . $order_id . ')', 'revolut-gateway-for-woocommerce'));
                    $wc_order->payment_complete($order_id);
                    update_post_meta($wc_order_id['wc_order_id'], 'revolut_capture', "yes");
                    $data = [
                        'status' => 'OK',
                        'response' => 'Completed'
                    ];
                } else {
                    $data = [
                        'status' => 'Failed',
                    ];

                }
            }
        } else {
            $data = [
                'status' => 'Failed',
            ];
        }

        return new WP_REST_Response($data, 200);
    }

    /**
     * Get items permissions check
     *
     * @param null $request
     *
     * @return bool|WP_Error
     */
    public function get_items_permissions_check($request = null)
    {
        return true;
    }

    /**
     * Get Woocommerce Order ID
     *
     * @param $order_id
     *
     * @return array|object|void|null
     */
    public function get_wc_order_id($order_id)
    {
        global $wpdb;

        $query = 'SELECT wc_order_id FROM ' . $wpdb->prefix . "wc_revolut_orders
                WHERE order_id=UNHEX(REPLACE('" . $order_id . "', '-', ''))";
        $order = $wpdb->get_row($query, ARRAY_A);

        return $order;
    }
}