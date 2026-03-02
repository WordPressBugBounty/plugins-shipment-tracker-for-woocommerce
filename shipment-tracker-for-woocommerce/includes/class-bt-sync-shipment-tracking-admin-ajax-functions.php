<?php

if ( ! defined( 'ABSPATH' ) ) exit;


class Bt_Sync_Shipment_Tracking_Admin_Ajax_Functions{
    private $shiprocket;
    private $shyplite;
    private $crons;
    private $nimbuspost;
    private $manual;
    private $fship;
    
    public function __construct( $crons,$shiprocket,$shyplite,$nimbuspost,$manual,$licenser,$fship ) {
        $this->crons = $crons;
        $this->shiprocket = $shiprocket;
        $this->shyplite = $shyplite;
        $this->nimbuspost = $nimbuspost;
        $this->manual = $manual;
        $this->fship = $fship;
    }

    public function save_package_dimensions(){
        $resp = array(
            "status" => false,
            "message" => ''
        );

        if(empty($order_id = sanitize_text_field($_POST['order_id']))){
            $resp['message'] = 'Invalid order id.';
            wp_send_json($resp);
            wp_die();
        }

        try {
            if (isset($_POST['package_length'])) {
                Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_package_length', sanitize_text_field($_POST['package_length']));
            }
            if (isset($_POST['package_width'])) {
                Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_package_width', sanitize_text_field($_POST['package_width']));
            }
            if (isset($_POST['package_height'])) {
                Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_package_height', sanitize_text_field($_POST['package_height']));
            }
            if (isset($_POST['package_weight'])) {
                Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_package_weight', sanitize_text_field($_POST['package_weight']));
            }
            $resp['status'] = true;
            $resp['message'] = 'Package dimensions saved.';
        }
        catch(Exception $e) {
            $resp["message"] = $e->getMessage();
            $resp["status"] = false;
        }

        wp_send_json($resp);
        wp_die();
    }

    public function bt_sync_now_shyplite(){
        $obj = $this->crons->sync_shyplite_shipments();

        $resp = array(
            "status"=>true,
            "orders_count"=>sizeof($obj)
        );
        echo json_encode($resp);
        wp_die();
    }

    public function force_sync_tracking(){
        $resp = array(
            "status" => false,
            "response" => ''
        );

        if(empty($order_id = sanitize_text_field($_POST['order_id']))){
            $resp = array(
                "status" => false,
                "response" => 'Invalid order id.'
            );
            wp_send_json($resp);
            wp_die();
        }
        
        try {
            $tracking_resp = bt_force_sync_order_tracking(sanitize_text_field($order_id));
            if(!empty($tracking_resp)) {
                $resp['status'] = true;
                $resp['response'] = $tracking_resp;
            }else{
                $resp['status'] = false;
                $resp['response'] = "Unable to get latest shipment data, please check plugin settings or contact plugin support for help.";
            }
        }
        catch(Exception $e) {
            $resp["response"] = $e->getMessage();
            $resp["status"] = false;
        }


        wp_send_json($resp);
        wp_die();
    }

    public function save_order_awb_number(){
        $resp = array(
            "status" => false,
            "response" => ''
        );

        if(empty($order_id = sanitize_text_field($_POST['order_id']))){
            $resp = array(
                "status" => false,
                "response" => 'Invalid order id.'
            );
            wp_send_json($resp);
            wp_die();
        }

        if(empty($awb_number = $_POST['awb_number'])){
            $resp = array(
                "status" => false,
                "response" => 'Invalid AWB number.'
            );
            wp_send_json($resp);
            wp_die();
        }

        $shipping_mode_is_manual_or_ship24 = carbon_get_theme_option( 'bt_sst_enabled_custom_shipping_mode' );
		// $shipping_mode_is_manual_or_ship24 = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_sst_custom_shipping_mode', true);
		$shipping_provider = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_shipping_provider', true);

        if($shipping_mode_is_manual_or_ship24=='ship24' && $shipping_provider == "manual"){
            if(empty($corier_code = $_POST['corier_code'])){
                $resp = array(
                    "status" => false,
                    "response" => 'Invalid Courier code.'
                );
                wp_send_json($resp);
                wp_die();
            }
            if(empty($corier_name = $_POST['corier_name'])){
                $resp = array(
                    "status" => false,
                    "response" => 'Invalid Courier name.'
                );
                wp_send_json($resp);
                wp_die();
            }
            Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipping_ship24_corier_name', sanitize_text_field($corier_name ));
            Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipping_ship24_corier_code', sanitize_text_field($corier_code ));
        }

        try {
            // Save optional package dimensions/weight if provided (stored in store units)
            if (isset($_POST['package_length'])) {
                Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_package_length', sanitize_text_field($_POST['package_length']));
            }
            if (isset($_POST['package_width'])) {
                Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_package_width', sanitize_text_field($_POST['package_width']));
            }
            if (isset($_POST['package_height'])) {
                Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_package_height', sanitize_text_field($_POST['package_height']));
            }
            if (isset($_POST['package_weight'])) {
                Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_package_weight', sanitize_text_field($_POST['package_weight']));
            }

            Bt_Sync_Shipment_Tracking::bt_sst_delete_order_meta($order_id, '_bt_shipment_tracking' );//fix to delete old shipment data so that new awb number can take effect.
            Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipping_awb', sanitize_text_field($awb_number ));
            bt_force_sync_order_tracking($order_id);
            $resp['status'] = true;
            $resp['response'] = 'Success';
        }
        catch(Exception $e) {
            $resp["response"] = $e->getMessage();
            $resp["status"] = false;
        }


        wp_send_json($resp);
        wp_die();
    }

    public function get_tracking_data_from_db(){

        $resp = array(
            "status" => false,
            "message" => '',
            'data'  => [],
            'has_tracking' => false
        );

        if (empty($_POST) || !wp_verify_nonce($_POST['bt_get_tracking_form_nonce'],'bt_get_tracking_data') )
        {
            $resp['message'] = 'Sorry, you are not allowed.';
            wp_send_json($resp);
            wp_die();
        }


       $the_order = wc_get_order(sanitize_text_field($_POST['order_id']));
        if(empty($the_order)){
            $resp['message'] = 'Order not found!';
            wp_send_json($resp);
            wp_die();
        }

        $resp['status'] = true;
        $resp['data']['order_status'] = $the_order->get_status();

        $bt_shipment_tracking =Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id(sanitize_text_field($_POST['order_id']));

        if(!empty($bt_shipment_tracking)) {
            $resp['has_tracking'] = isset($bt_shipment_tracking->awb)&&!empty($bt_shipment_tracking->awb);
            $resp['data']['obj'] = $bt_shipment_tracking;
            $resp['data']['tracking_link'] = $bt_shipment_tracking->get_tracking_link();
        } else {
            $resp['message'] = 'Tracking of this order is not available yet.';
        }

        wp_send_json($resp);
        wp_die();
    }

    public function bt_tracking_manual(){
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_send_json( array( 'status' => false, 'response' => 'Permission denied.' ) );
            wp_die();
        }

        $order_id = isset($_POST['order_id']) ? sanitize_text_field($_POST['order_id']) : '';
        $fields = array(
            'awb_number',
            'courier_name',
            'etd',
            'shipping_status',
            'current_pincode',
            'current_address',
            'current_country',
            'tracking_link',
        );
        $sanitized = array();
        foreach ($fields as $field) {
            $sanitized[$field] = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
        }

        $resp = array(
            "status" => false,
            "response" => ''
        );

        try {
            $resp["response"] = $this->manual->update_data($order_id, $sanitized);
            $resp["status"] = true;
        }
        catch(Exception $e) {
            $resp["response"] = $e->getMessage();
            $resp["status"] = false;
        }

        wp_send_json($resp);
        wp_die();
    }

    public function post_customer_feedback_to_sever() {
        $current_user = wp_get_current_user();
        $body = array(
            'your-message'    => sanitize_text_field($_POST['feedback']),
            'your-name'    => esc_html( $current_user->display_name ),
            'your-subject'    => "Plugin Feedback from " . get_site_url(),
            'your-email'    => esc_html(get_bloginfo('admin_email')),
            '_wpcf7_unit_tag' => 'wpcf7-fe078c3f-o4'
        );

        $base_url="https://shipment-tracker-for-woocommerce.bitss.tech";
        $cfid=4;
        $resp = $this->post_cf7_data($body,$cfid,$base_url );

        return;
    }

    private function post_cf7_data($body,$cfid,$base_url ){
        // Same user agent as in regular wp_remote_post().
        $userAgent = 'WordPress/' . get_bloginfo('version') . '; ' . get_bloginfo('url');
        // Note that Content-Type wrote in a bit different way.
        $header = ['Content-Type: multipart/form-data'];
    
        $apiUrl = "$base_url/wp-json/contact-form-7/v1/contact-forms/$cfid/feedback";

        $curlOpts = [
            // Send as POST
            CURLOPT_POST => 1,
            // Get a response data instead of true
            CURLOPT_RETURNTRANSFER => 1,
            // CF7 will reject your request as spam without it.
            CURLOPT_USERAGENT => $userAgent,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $body,
        ];

        $ch = curl_init($apiUrl);          // Create a new cURL resource.
        curl_setopt_array($ch, $curlOpts); // Set options.
        $response = curl_exec($ch);        // Grab response.

        if (!$response) {
            // Do something if an error occurred.
        } else {
            $response = json_decode($response);
            // Do something with the response data.
        }

        // Close cURL resource, and free up system resources.
        curl_close($ch);

        return  $response ;

    }


    function bt_shipment_data_callback() {
        // Initialize response array
        $response = array(
            'orders_received_last_7_days' => 0,
            'orders_waiting_to_be_shipped' => 0,
            'orders_not_delivered_after_10_days' => 0,
            'orders_marked_as_rto' => 0,
            'get_delayed_orders_list' => 0,
        );

        $days = 30;

        $orders_received_last_x_days = $this->orders_received_last_x_days($days);
        $orders_waiting_to_be_shipped = $this->get_orders_waiting_to_be_shipped($days);
        //$orders_not_delivered_after_10_days = $this->get_orders_not_delivered_after_10_days($days);
        $orders_in_transit = $this->get_orders_in_transit($days);
        //$orders_marked_as_rto = $this->get_orders_marked_as_rto($days);
        $get_delayed_orders_list = $this->get_delayed_orders_list($days);
        $get_delivered_orders_count = $this->get_delivered_orders_count($days);

        $response['orders_received_last_x_days'] = $orders_received_last_x_days;
        $response['orders_waiting_to_be_shipped'] = $orders_waiting_to_be_shipped;
        //$response['orders_not_delivered_after_10_days'] = $orders_not_delivered_after_10_days;
        $response['orders_in_transit'] = $orders_in_transit;
        //$response['orders_marked_as_rto'] = $orders_marked_as_rto;
        $response['get_delayed_orders_list'] = $get_delayed_orders_list;
        $response['get_delivered_orders_count'] = $get_delivered_orders_count;

    
       
        // Return the response as JSON
        wp_send_json_success($response);
    }

    private function orders_received_last_x_days($last_days=30) {
        // Ensure WooCommerce is loaded
        if ( ! class_exists( 'WC_Order_Query' ) ) {
            return array(
                'status'  => false,
                'message' => 'WooCommerce is not available.',
                'data'    => array(),
            );
        }
    
        // Calculate the date 7 days ago
        $date_7_days_ago = date( 'Y-m-d H:i:s', strtotime( "-$last_days days" ) );
    
        // Get all registered order statuses
        $all_statuses = array_keys( wc_get_order_statuses() );
    
        // Define unsuccessful statuses to exclude
        $unsuccessful_statuses = array( 'wc-failed', 'wc-cancelled', 'wc-refunded' );
    
        // Filter out unsuccessful statuses
        $successful_statuses = array_diff( $all_statuses, $unsuccessful_statuses );
    
        // Query WooCommerce orders
        $query = new WC_Order_Query( array(
            'status'        => $successful_statuses, // Include only successful statuses
            'date_created'  => '>=' . $date_7_days_ago,
            'return'        => 'ids', // Return only order IDs
            'limit' => -1, // No limit on the number of orders
        ) );
    
        $orders = $query->get_orders();
    
        // Return the count of orders received in the last 7 days
        return count( $orders );
    }

    public function get_orders_waiting_to_be_shipped($last_days=30) {
        // Ensure WooCommerce is loaded
        if ( ! class_exists( 'WC_Order_Query' ) ) {
            return array(
                'status'  => false,
                'message' => 'WooCommerce is not available.',
                'data'    => array(),
            );
        }
        $date_7_days_ago = date( 'Y-m-d H:i:s', strtotime( "-$last_days days" ) );
        // Get all registered order statuses
        $all_statuses = array_keys( wc_get_order_statuses() );
    
        // Define unsuccessful statuses to exclude
        $unsuccessful_statuses = array( 'wc-failed', 'wc-cancelled', 'wc-refunded' );
    
        // Filter out unsuccessful statuses
        $successful_statuses = array_diff( $all_statuses, $unsuccessful_statuses );
    
        // Query WooCommerce orders that do not have a shipping AWB (not shipped yet)
        $query = new WC_Order_Query( array(
            'status'        => $successful_statuses, // Include only successful statuses
            'date_created'  => '>=' . $date_7_days_ago,
            'meta_key'      => '_bt_shipping_awb',
            'meta_compare'  => 'NOT EXISTS', // Orders without the AWB meta key
            'return'        => 'ids', // Return only order IDs
            'limit'         => -1, // No limit on the number of orders
        ) );
    
        $orders = $query->get_orders();
    
        // Generate the URL to filter orders in the WooCommerce admin
        $filter_url = "#"; //to do
    
        // Return the count of orders waiting to be shipped along with the filter URL
        return array(
            'orders_waiting_to_be_shipped' => count( $orders ),
            'filter_url'                   => $filter_url,
        );
    }

    public function get_orders_in_transit($last_days=30) {
        // Ensure WooCommerce is loaded
        if ( ! class_exists( 'WC_Order_Query' ) ) {
            return array(
                'status'  => false,
                'message' => 'WooCommerce is not available.',
                'data'    => array(),
            );
        }
        $date_7_days_ago = date( 'Y-m-d H:i:s', strtotime( "-$last_days days" ) );
        // Get all registered order statuses
        $all_statuses = array_keys( wc_get_order_statuses() );
    
        // Define unsuccessful statuses to exclude
        $unsuccessful_statuses = array( 'wc-failed', 'wc-cancelled', 'wc-refunded' );
    
        // Filter out unsuccessful statuses
        $successful_statuses = array_diff( $all_statuses, $unsuccessful_statuses );
    
        // Query WooCommerce orders that do not have a shipping AWB (not shipped yet)
        // First, get orders with _bt_shipping_awb meta key EXISTS
        $query = new WC_Order_Query( array(
            'status'        => $successful_statuses, // Include only successful statuses
            'date_created'  => '>=' . $date_7_days_ago,
            'meta_key'      => '_bt_shipping_awb',
            'meta_compare'  => 'EXISTS', // Orders with the AWB meta key
            'return'        => 'ids', // Return only order IDs
            'limit'         => -1, // No limit on the number of orders
        ) );

        $orders = $query->get_orders();

        // Now filter orders where _bt_shipment_tracking meta's current_status is 'in-transit'
        $in_transit_orders = array();
        foreach ( $orders as $order_id ) {
            $shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id( $order_id );
            if ( ! empty( $shipment_tracking->current_status ) && ($shipment_tracking->current_status === 'in-transit' || $shipment_tracking->current_status === 'in transit') ) {
                $in_transit_orders[] = $order_id;
            }
        }
        // Overwrite $orders with filtered list for further processing
        $orders = $in_transit_orders;
        
        // Generate the URL to filter orders in the WooCommerce admin
        $filter_url = "#"; //to do
    
        // Return the count of orders waiting to be shipped along with the filter URL
        return array(
            'count' => count( $orders ),
            'filter_url'                   => $filter_url,
        );
    }

    
    private function get_orders_not_delivered_after_10_days($last_days=30) {
        // Ensure WooCommerce is loaded
        if ( ! class_exists( 'WC_Order_Query' ) ) {
            return array(
                'status'  => false,
                'message' => 'WooCommerce is not available.',
                'data'    => array(),
            );
        }

        $date_7_days_ago = date( 'Y-m-d H:i:s', strtotime( "-$last_days days" ) );
        // Get all registered order statuses
        $all_statuses = array_keys( wc_get_order_statuses() );

        // Define unsuccessful statuses to exclude
        $unsuccessful_statuses = array( 'wc-failed', 'wc-cancelled', 'wc-refunded' );

        // Filter out unsuccessful statuses
        $successful_statuses = array_diff( $all_statuses, $unsuccessful_statuses );

        // Query WooCommerce orders with successful statuses
        $query = new WC_Order_Query( array(
            'status'        => $successful_statuses,
            'date_created'  => '>=' . $date_7_days_ago,
            'return'        => 'ids', // Return only order IDs
            'limit'         => -1, // No limit on the number of orders
        ) );

        $orders = $query->get_orders();
        $delayed_orders_count = 0;

        // Loop through orders to check for delays
        foreach ( $orders as $order_id ) {
            // Get the shipment tracking object from order meta
            $shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id( $order_id );

            if ( ! empty( $shipment_tracking->etd ) ) {
                $etd_timestamp = strtotime( $shipment_tracking->etd );
                $current_timestamp = time();

                // Check if the order is delayed by more than 10 days
                if ( $current_timestamp > $etd_timestamp + ( 1 * DAY_IN_SECONDS ) ) {
                    $delayed_orders_count++;
                }
            }
        }
        $filter_url = "#";//to do
        // Return the count of delayed orders
        return array(
            'orders_not_delivered_after_10_days' => $delayed_orders_count,
            'filter_url'                   => $filter_url,
        );
    }

    
    private function get_orders_marked_as_rto($last_days=30) {
        // Ensure WooCommerce is loaded
        if ( ! class_exists( 'WC_Order_Query' ) ) {
            return array(
                'status'  => false,
                'message' => 'WooCommerce is not available.',
                'data'    => array(),
            );
        }

        // Calculate the date 30 days ago
        $date_7_days_ago = date( 'Y-m-d H:i:s', strtotime( "-$last_days days" ) );

        // Get all registered order statuses
        $all_statuses = array_keys( wc_get_order_statuses() );

        // Define unsuccessful statuses to exclude
        $unsuccessful_statuses = array( 'wc-failed', 'wc-cancelled', 'wc-refunded' );

        // Filter out unsuccessful statuses
        $successful_statuses = array_diff( $all_statuses, $unsuccessful_statuses );

        // Query WooCommerce orders created in the last 30 days with successful statuses
        $query = new WC_Order_Query( array(
            'status'        => $successful_statuses,
            'date_created'  => '>=' . $date_7_days_ago,
            'return'        => 'ids', // Return only order IDs
            'limit'         => -1, // No limit on the number of orders
        ) );

        $orders = $query->get_orders();
        $rto_orders_count = 0;

        // Loop through orders to check for RTO status
        foreach ( $orders as $order_id ) {
            // Get the shipment tracking object from order meta
            $shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id( $order_id );

            if ( ! empty( $shipment_tracking->current_status ) ) {
                // Check if the current status contains "rto" or "return" (case-insensitive)
                if ( stripos( $shipment_tracking->current_status, 'rto' ) !== false || 
                    stripos( $shipment_tracking->current_status, 'return' ) !== false ) {
                    $rto_orders_count++;
                }
            }
        }
        $filter_url = "";//to do
        // Return the count of RTO orders
        return array(
            'orders_marked_as_rto' => $rto_orders_count,
            'filter_url'                   => $filter_url
        );
    }

    private function get_delayed_orders_list($last_days=30) {
        // Ensure WooCommerce is loaded
        if ( ! class_exists( 'WC_Order_Query' ) ) {
            return array(
                'status'  => false,
                'message' => 'WooCommerce is not available.',
                'data'    => array(),
            );
        }

        // Calculate the date X days ago
        $date_x_days_ago = date( 'Y-m-d H:i:s', strtotime( "-$last_days days" ) );

        // Get all registered order statuses
        $all_statuses = array_keys( wc_get_order_statuses() );

        // Define unsuccessful statuses to exclude
        $unsuccessful_statuses = array( 'wc-failed', 'wc-cancelled', 'wc-refunded','wc-completed' );

        // Filter out unsuccessful statuses
        $successful_statuses = array_diff( $all_statuses, $unsuccessful_statuses );

        // Query WooCommerce orders created in the last X days with successful statuses
        $query = new WC_Order_Query( array(
            'status'        => $successful_statuses,
            'date_created'  => '>=' . $date_x_days_ago,
            'return'        => 'ids', // Return only order IDs
            'limit'         => -1, // No limit on the number of orders
        ) );

        $orders = $query->get_orders();
        $delayed_orders = array();
        $today = strtotime(date('Y-m-d'));

        // Loop through orders to check for delayed ETD
        foreach ( $orders as $order_id ) {
            // Get the shipment tracking object from order meta
            $shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id( $order_id );

            if ( ! empty( $shipment_tracking->etd ) ) {
                $etd_timestamp = strtotime( $shipment_tracking->etd );
                // If ETD is before today, add to delayed list
                if ( $etd_timestamp < $today ) {
                    // echo $shipment_tracking->current_status;
                    if($shipment_tracking->current_status == 'out-for-delivery' || $shipment_tracking->current_status == 'in-transit'){
                        $delayed_orders[] = array(
                            'order_id' => $order_id,
                            'etd'      => $shipment_tracking->etd,
                        );
                    }
                }
            }
        }

        return array(
            'status' => true,
            'delayed_orders' =>count($delayed_orders),
        );
    }

    public function get_delivered_orders_count($last_days=30) {
        // Ensure WooCommerce is loaded
        if ( ! class_exists( 'WC_Order_Query' ) ) {
            return array(
                'status'  => false,
                'message' => 'WooCommerce is not available.',
                'data'    => array(),
            );
        }
        $date_7_days_ago = date( 'Y-m-d H:i:s', strtotime( "-$last_days days" ) );
        // Get all registered order statuses
        $all_statuses = array_keys( wc_get_order_statuses() );
    
        // Define unsuccessful statuses to exclude
        $unsuccessful_statuses = array( 'wc-failed', 'wc-cancelled', 'wc-refunded' );
    
        // Filter out unsuccessful statuses
        $successful_statuses = array_diff( $all_statuses, $unsuccessful_statuses );
    
        // Query WooCommerce orders that do not have a shipping AWB (not shipped yet)
        // First, get orders with _bt_shipping_awb meta key EXISTS
        $query = new WC_Order_Query( array(
            'status'        => $successful_statuses, // Include only successful statuses
            'date_created'  => '>=' . $date_7_days_ago,
            'meta_key'      => '_bt_shipping_awb',
            'meta_compare'  => 'EXISTS', // Orders with the AWB meta key
            'return'        => 'ids', // Return only order IDs
            'limit'         => -1, // No limit on the number of orders
        ) );

        $orders = $query->get_orders();

        // Now filter orders where _bt_shipment_tracking meta's current_status is 'in-transit'
        $in_transit_orders = array();
        foreach ( $orders as $order_id ) {
            $shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id( $order_id );
            if ( ! empty( $shipment_tracking->current_status ) && ($shipment_tracking->current_status === 'delivered')) {
                $in_transit_orders[] = $order_id;
            }
        }
        // Overwrite $orders with filtered list for further processing
        $orders = $in_transit_orders;
        
        // Generate the URL to filter orders in the WooCommerce admin
        $filter_url = "#"; //to do
    
        // Return the count of orders waiting to be shipped along with the filter URL
        return array(
            'count' => count( $orders ),
            'filter_url'                   => $filter_url,
        );
    }


}