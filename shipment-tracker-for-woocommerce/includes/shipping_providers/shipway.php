<?php
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;

if ( ! defined( 'ABSPATH' ) ) exit;

class Bt_Sync_Shipment_Tracking_Shipway {

    private $email;
    private $license_key;
    private const API_BASE_URL = "https://app.shipway.com";
    private const API_PUSH_ORDER = "/api/v2orders";
    private const API_TEST_CONNECTION = "/api/getcarrier";
    private const API_TRACKING = "/api/tracking";

    public function __construct() {
    }

    public function init_params() {
        $email = carbon_get_theme_option('bt_sst_shipway_email');
        $license_key = carbon_get_theme_option('bt_sst_shipway_licencekey');
        $this->email = trim($email);
        $this->license_key = trim($license_key);
    }

    public function test_shipway() {
        $this->init_params();

        if (!empty($this->email) && !empty($this->license_key)) {
            $token = base64_encode($this->email . ":" . $this->license_key);
            $args = array(
                'timeout' => 20,
                'headers' => array(
                    'Authorization' => 'Basic ' . $token,
                )
            );

            $url = self::API_BASE_URL . self::API_TEST_CONNECTION;
            $response = wp_remote_get($url, $args);

            if (is_wp_error($response)) {
                return false;
            }

            $status_code = wp_remote_retrieve_response_code($response);
            if ($status_code === 200) {
                $body = wp_remote_retrieve_body($response);
                $resp = json_decode($body, true);
                // The API returns carriers list on success
                if (isset($resp) && is_array($resp)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function push_order_on_shipway($order_id) {
        $this->init_params();

        if (empty($this->email) || empty($this->license_key)) {
            return array('error' => 'Authorization credentials are missing.');
        }

        $order_data = $this->get_shipway_order_object($order_id);
        if (isset($order_data['error'])) {
            return $order_data;
        }

        $token = base64_encode($this->email . ":" . $this->license_key);
        $args = array(
            'headers' => array(
                'Authorization' => 'Basic ' . $token,
                'Content-Type'  => 'application/json',
            ),
            'body' => json_encode($order_data),
            'timeout' => 30
        );

        $url = self::API_BASE_URL . self::API_PUSH_ORDER;
        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            return array('error' => $response->get_error_message());
        }

        $status_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        $decoded_body = json_decode($response_body, true);

        return array(
            'status_code' => $status_code,
            'response' => $decoded_body
        );
    }

    private function get_shipway_order_object($order_id) {
        $order = wc_get_order($order_id);
        if (!$order) {
            return ['error' => 'Invalid WooCommerce order ID.'];
        }

        $shipping = $order->get_address('shipping');
        $billing = $order->get_address('billing');
        if (empty($shipping['address_1'])) {
            $shipping = $billing;
        }

        $products = [];
        $total_weight = 0;
        $total_width = 0;
        $total_length = 0;
        $total_height = 0;

        $sku_count = 1;
        $sku_count_map = [];

        foreach ($order->get_items() as $item) {
            $product = $item->get_product();
            if (!$product)
                continue;

            $product_sku = $product->get_sku();
            if (empty($product_sku)) {
                $product_sku = urldecode(substr(get_post($product->get_id())->post_name, 0, 40)) . '_' . $sku_count;
                $sku_count++;
            }

            if (isset($sku_count_map[$product_sku])) {
                $sku_count_map[$product_sku]++;
                $product_sku .= '_' . $sku_count_map[$product_sku];
            } else {
                $sku_count_map[$product_sku] = 1;
            }

            // Weight and dimensions calculation
            if (!empty($product->get_weight())) {
                $total_weight += $product->get_weight() * $item->get_quantity();
            }
            if (!empty($product->get_width())) {
                $total_width += $product->get_width() * $item->get_quantity();
                if ($product->get_length() > $total_length) {
                    $total_length = $product->get_length();
                }
                if ($product->get_height() > $total_height) {
                    $total_height = $product->get_height();
                }
            }

            $products[] = [
                "product" => $product->get_name(),
                "price" => (string)round($item->get_total() / max(1, $item->get_quantity()), 2),
                "product_code" => $product_sku,
                "product_quantity" => (string)$item->get_quantity(),
                "discount" => "0",
                "tax_rate" => (string)$item->get_total_tax(),
                "tax_title" => "GST"
            ];
        }

        // Convert to appropriate units
        $weight_unit = get_option('woocommerce_weight_unit');
        $dimension_unit = get_option('woocommerce_dimension_unit');

        // Convert weight to grams
        $total_weight_g = 100; // default 100g fallback
        if ($total_weight > 0) {
            try {
                $weight_obj = new Mass($total_weight, $weight_unit);
                $total_weight_g = round($weight_obj->toUnit('g'));
            } catch (Exception $e) {
                // fallback if unit conversion fails
                $total_weight_g = round($total_weight * 1000);
            }
        }

        // Convert dimensions to cm
        $length_cm = 10;
        $width_cm = 10;
        $height_cm = 10;
        try {
            if ($total_length > 0) {
                $len_obj = new Length($total_length, $dimension_unit);
                $length_cm = round($len_obj->toUnit('cm'));
            }
            if ($total_width > 0) {
                $wid_obj = new Length($total_width, $dimension_unit);
                $width_cm = round($wid_obj->toUnit('cm'));
            }
            if ($total_height > 0) {
                $hei_obj = new Length($total_height, $dimension_unit);
                $height_cm = round($hei_obj->toUnit('cm'));
            }
        } catch (Exception $e) {
            // dimensions fallback
        }

        $payment_method = $order->get_payment_method();
        $payment_type = ($payment_method === 'cod') ? 'C' : 'P';

        $order_data = [
            "order_id" => apply_filters( 'bt_sst_push_order_number', $order->get_order_number(), $order ),
            "ewaybill" => "",
            "products" => $products,
            "discount" => (string)round($order->get_total_discount(), 2),
            "shipping" => (string)round($order->get_total_shipping(), 2),
            "order_total" => (string)round($order->get_total(), 2),
            "gift_card_amt" => "0",
            "taxes" => (string)round($order->get_total_tax(), 2),
            "payment_type" => $payment_type,
            "email" => $billing['email'],
            "billing_address" => $billing['address_1'],
            "billing_address2" => $billing['address_2'],
            "billing_city" => $billing['city'],
            "billing_state" => $billing['state'],
            "billing_country" => $billing['country'],
            "billing_firstname" => $billing['first_name'],
            "billing_lastname" => $billing['last_name'],
            "billing_phone" => $billing['phone'],
            "billing_zipcode" => $billing['postcode'],
            "shipping_address" => $shipping['address_1'],
            "shipping_address2" => $shipping['address_2'],
            "shipping_city" => $shipping['city'],
            "shipping_state" => $shipping['state'],
            "shipping_country" => $shipping['country'],
            "shipping_firstname" => $shipping['first_name'],
            "shipping_lastname" => $shipping['last_name'],
            "shipping_phone" => $shipping['phone'],
            "shipping_zipcode" => $shipping['postcode'],
            "order_weight" => (string)$total_weight_g,
            "box_length" => (string)$length_cm,
            "box_breadth" => (string)$width_cm,
            "box_height" => (string)$height_cm,
            "order_date" => $order->get_date_created()->date("Y-m-d H:i:s")
        ];

        return $order_data;
    }

    public function get_order_tracking_by_awb_number($awb) {
        $this->init_params();

        if (empty($this->email) || empty($this->license_key)) {
            return array('error' => 'Authorization credentials are missing.');
        }

        $token = base64_encode($this->email . ":" . $this->license_key);
        $args = array(
            'headers' => array(
                'Authorization' => 'Basic ' . $token,
            ),
            'timeout' => 25
        );

        $url = self::API_BASE_URL . self::API_TRACKING . "?awb_numbers=" . urlencode($awb) . "&tracking_history=1";

        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            return array('error' => $response->get_error_message());
        }

        $status_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        $decoded_body = json_decode($response_body, true);

        return array(
            'status_code' => $status_code,
            'response' => $decoded_body
        );
    }

    public function update_order_shipment_status($order_id) {
        $resp = null;
        if (!empty($awb_number = Bt_Sync_Shipment_Tracking_Shipment_Model::get_awb_by_order_id($order_id))) {
            $resp = $this->get_order_tracking_by_awb_number($awb_number);
        }
        if (!empty($resp) && isset($resp['response']) && is_array($resp['response'])) {
            $shipment_obj = $this->init_model($resp['response'], $order_id);
            Bt_Sync_Shipment_Tracking_Shipment_Model::save_tracking($order_id, $shipment_obj);
            return $shipment_obj;
        }
        return null;
    }

    public function init_model($data, $order_id) {
        $obj = new Bt_Sync_Shipment_Tracking_Shipment_Model();
        $obj->order_id = $order_id;
        $obj->shipping_provider = 'shipway';
        $obj->courier_name = 'Shipway';

        if (is_array($data) && !empty($data)) {
            $tracking = isset($data[0]) ? $data[0] : $data;
            if (isset($tracking['tracking_details'])) {
                $details = $tracking['tracking_details'];
                $obj->awb = sanitize_text_field($tracking['awb'] ?? '');
                
                $status = $details['shipment_status'] ?? 'unknown';
                $obj->current_status = Bt_Sync_Shipment_Tracking_Shipment_Model::convert_string_to_slug(sanitize_text_field($status));

                if (isset($details['shipment_details']) && is_array($details['shipment_details']) && !empty($details['shipment_details'])) {
                    $ship_detail = $details['shipment_details'][0];
                    $obj->courier_name = sanitize_text_field($ship_detail['courier_name'] ?? 'Shipway');
                    $obj->etd = sanitize_text_field($ship_detail['delivered_date'] ?? ($ship_detail['pickup_date'] ?? ''));
                }

                if (isset($details['shipment_track_activities']) && is_array($details['shipment_track_activities'])) {
                    $scans = [];
                    foreach ($details['shipment_track_activities'] as $act) {
                        $scans[] = [
                            'date' => sanitize_text_field($act['date'] ?? ''),
                            'activity' => sanitize_text_field($act['activity'] ?? ''),
                            'location' => sanitize_text_field($act['location'] ?? ''),
                        ];
                    }
                    $obj->scans = $scans;
                } else {
                    $obj->scans = [];
                }

                if (strtolower($obj->current_status) == "delivered" && empty($obj->delivery_date)) {
                    $obj->delivery_date = date('Y-m-d');
                }
            }
        }
        return $obj;
    }

    public function shipway_webhook_receiver($request){
        update_option( "shipway_webhook_called", time() );
        $enabled_shipping_providers = carbon_get_theme_option( 'bt_sst_enabled_shipping_providers' );
        if(is_array($enabled_shipping_providers) && in_array('shipway',$enabled_shipping_providers)){
            $data = array();
            if (is_object($request) && method_exists($request, 'get_json_params')) {
                $data = $request->get_json_params();
            } elseif (is_array($request)) {
                $data = $request;
            }
                    
            $order_ids = array();
            if (!empty($data['order_id'])) {
                $order_ids[] = $data['order_id'];
            }
            if (!empty($data['api_input']['order_id'])) {
                $order_ids[] = $data['api_input']['order_id'];
            }
            if (!empty($data['api_input']['extra_fields']['ReferenceNo'])){
                $order_ids[] = $data['api_input']['extra_fields']['ReferenceNo'];
            }
            if (isset($data["awbno"]) && !empty($data["awbno"])){
                $awb_number = $data["awbno"];
                if (!empty($awb_order_ids = Bt_Sync_Shipment_Tracking_Shipment_Model::get_orders_by_awb_number($awb_number))){
                    foreach ($awb_order_ids as $awb_order_id) {
                        if (!in_array($awb_order_id, $order_ids)){
                            $order_ids[] = $awb_order_id;
                        }
                    }
                }
            }
            if (!empty($order_ids) && is_array($order_ids)){
                foreach ($order_ids as $order_id) {
                    if (!empty($order_id)){
                        if (false !== $order = wc_get_order( $order_id )){
                            $bt_sst_sync_orders_date = carbon_get_theme_option( 'bt_sst_sync_orders_date' );
                            $date_created_dt = $order->get_date_created();
                            $timezone        = $date_created_dt->getTimezone();
                            $date_created_ts = $date_created_dt->getTimestamp();
                            $now_dt = new WC_DateTime();
                            $now_dt->setTimezone( $timezone );
                            $now_ts = $now_dt->getTimestamp();
                            $allowed_seconds = $bt_sst_sync_orders_date * 24 * 60 * 60;
                            $diff_in_seconds = $now_ts - $date_created_ts;
                            if ( $diff_in_seconds <= $allowed_seconds ) {
                                $shipment_obj = $this->init_model_from_webhook($data, $order_id);
                                Bt_Sync_Shipment_Tracking_Shipment_Model::save_tracking($order_id,$shipment_obj);
                                return "Thanks Shipway! Record updated.";
                            }else{
                                return "Thanks Shipway! Order too old.";
                            }
                        }
                    }
                }
            }
            return "Thanks Shipway, but nothing got updated.";
        }
        return "Shipway is not enabled.";
    }

    public function init_model_from_webhook($data, $order_id) {
        $obj = new Bt_Sync_Shipment_Tracking_Shipment_Model();
        $obj->order_id = $order_id;
        $obj->shipping_provider = 'shipway';
        $obj->courier_name = 'Shipway';
        $obj->awb = sanitize_text_field($data['awbno'] ?? $data['api_input']['awbno'] ?? '');

        if (!empty($data['carrier'])) {
            $obj->courier_name = sanitize_text_field($data['carrier']);
        } elseif (!empty($data['api_input']['carrier'])) {
            $obj->courier_name = sanitize_text_field($data['api_input']['carrier']);
        }
        
        $obj->etd = sanitize_text_field($data['expected_delivery_date'] ?? $data['api_input']['expected_delivery_date'] ?? '');

        $status = $data['api_input']['current_status_desc'] ?? $data['api_input']['current_status'] ?? $data['scans_current_status'] ?? $data['current_status'] ?? 'unknown';
        $obj->current_status = Bt_Sync_Shipment_Tracking_Shipment_Model::convert_string_to_slug(sanitize_text_field($status));

        $scans = [];
        $raw_scans = $data['api_input']['scans'] ?? [];
        if (is_array($raw_scans)) {
            // Normalize associative arrays with numeric-string keys into a sequential array
            $raw_scans_norm = array_values($raw_scans);
            foreach ($raw_scans_norm as $act) {
                $scans[] = [
                    'date' => sanitize_text_field($act['time'] ?? ''),
                    'activity' => sanitize_text_field($act['status'] ?? ''),
                    'location' => sanitize_text_field($act['location'] ?? ''),
                ];
            }
        }
        $obj->scans = $scans;

        if (strtolower($obj->current_status) == "delivered" && empty($obj->delivery_date)) {
            $obj->delivery_date = date('Y-m-d');
        }

        return $obj;
    }

    public function get_rate_calcultor($pickup_pincode, $delivery_pincode, $pm, $declared_value, $cod, $weight_in_kg, $length_in_cms = '', $breadth_in_cms = '', $height_in_cms = '') {
        $this->init_params();

        if (empty($this->email) || empty($this->license_key)) {
            return null;
        }

        $token = base64_encode($this->email . ":" . $this->license_key);
        $args = array(
            'headers' => array(
                'Authorization' => 'Basic ' . $token,
            ),
            'timeout' => 20
        );

        $params = array(
            'fromPincode' => $pickup_pincode,
            'toPincode'   => $delivery_pincode,
            'paymentType' =>'prepaid',
        );

        if ($length_in_cms !== '') $params['length'] = $length_in_cms;
        if ($breadth_in_cms !== '') $params['breadth'] = $breadth_in_cms;
        if ($height_in_cms !== '') $params['height'] = $height_in_cms;
        if ($weight_in_kg !== '') $params['weight'] = $weight_in_kg;
        if (strtolower($pm) === 'cod') {
            $params['cummulativePrice'] = $declared_value;
        }

        $url = add_query_arg($params, self::API_BASE_URL . '/api/getshipwaycarrierrates');
        $response = wp_remote_get($url, $args);



        if (is_wp_error($response)) {
            return null;
        }

        $status_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $resp = json_decode($body, true);

        if ($status_code === 200 && isset($resp['success']) && $resp['success'] === 'success') {
            return $resp;
        }

        return null;
    }
}
