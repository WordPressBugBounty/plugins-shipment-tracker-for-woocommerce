<?php
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;

if (!defined('ABSPATH')) {
    exit;
}

class Bt_Sync_Shipment_Tracking_CourierKaro {

    private $api_key;
    private $store_url;
    private const API_BASE_URL = "https://courierkaro.com/callback/store";

    public function __construct() {}

    public function init_params() {
        $this->api_key   = trim(carbon_get_theme_option('bt_sst_courier_karo_api_key'));
        $this->store_url = trim(carbon_get_theme_option('bt_sst_courier_karo_store_url'));
    }

    private function get_headers() {
        return [
            'api-key'      => $this->api_key,
            'store-url'    => $this->store_url,
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json'
        ];
    }

    public function push_order_to_courierkaro($order_id) {
        $order = wc_get_order($order_id);
        if (!$order || $order->get_meta('has_sub_order') == 1) {
            return null;
        }

        $this->init_params();

        if (empty($this->api_key) || empty($this->store_url)) {
            return null;
        }

        $payload = $this->get_courierkaro_order_object($order_id);
        if (!$payload) return null;

        $args = [
            'headers' => $this->get_headers(),
            'body'    => wp_json_encode($payload),
            'timeout' => 60,
            'method'  => 'POST'
        ];

        $response = wp_remote_post(self::API_BASE_URL . '/create-order', $args);

        if (is_wp_error($response)) {
            error_log("Courier Karo push_order WP error: " . $response->get_error_message());
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $resp = json_decode($body, true);

        if (is_array($resp) && isset($resp['status']) && $resp['status']) {
            Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_courierkaro_awb', $order_id);
            $shipment_obj = $this->init_model_from_create_response($resp, $order_id);
            if ($shipment_obj) {
                Bt_Sync_Shipment_Tracking_Shipment_Model::save_tracking($order_id, $shipment_obj);
            }
        }

        return $resp;
    }

    public function get_order_tracking_by_awb($awb_number) {
        $this->init_params();

        if (empty($this->api_key) || empty($this->store_url)) {
            return null;
        }

        $args = [
            'headers' => $this->get_headers(),
            'body'    => wp_json_encode(['awb_no' => $awb_number]),
            'timeout' => 30,
            'method'  => 'POST'
        ];

        $response = wp_remote_post(self::API_BASE_URL . '/track-order', $args);

        if (is_wp_error($response)) {
            error_log("Courier Karo track_order WP error: " . $response->get_error_message());
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $resp = json_decode($body, true);

        return is_array($resp) ? $resp : null;
    }

    public function get_order_tracking_by_order_id($order_id) {
        $this->init_params();

        if (empty($this->api_key) || empty($this->store_url)) {
            return null;
        }

        $args = [
            'headers' => $this->get_headers(),
            'body'    => wp_json_encode(['a_order_id' => (string)$order_id]),
            'timeout' => 30,
            'method'  => 'POST'
        ];

        $response = wp_remote_post(self::API_BASE_URL . '/track-order', $args);

        if (is_wp_error($response)) {
            error_log("Courier Karo track_order WP error: " . $response->get_error_message());
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $resp = json_decode($body, true);

        return is_array($resp) ? $resp : null;
    }

    private function get_courierkaro_order_object($order_id) {
        $order = wc_get_order($order_id);
        if (!$order) return false;

        $shipping_postcode = $order->get_shipping_postcode();
        $shipping_first_name = $order->get_shipping_first_name();
        $shipping_last_name = $order->get_shipping_last_name();
        $shipping_address_1 = $order->get_shipping_address_1();
        $shipping_address_2 = $order->get_shipping_address_2();
        $shipping_city = $order->get_shipping_city();
        $shipping_state = $order->get_shipping_state();
        $shipping_country = $order->get_shipping_country();

        if (!$shipping_postcode) {
            $shipping_postcode = $order->get_billing_postcode();
            $shipping_first_name = $order->get_billing_first_name();
            $shipping_last_name = $order->get_billing_last_name();
            $shipping_address_1 = $order->get_billing_address_1();
            $shipping_address_2 = $order->get_billing_address_2();
            $shipping_city = $order->get_billing_city();
            $shipping_state = $order->get_billing_state();
            $shipping_country = $order->get_billing_country();
        }

        $items = [];
        $total_weight = 0;
        $total_length = 0;
        $total_width = 0;
        $total_height = 0;

        foreach ($order->get_items() as $item_id => $item) {
            if (!is_a($item, 'WC_Order_Item_Product')) continue;

            $product = $item->get_product();
            if (!$product) continue;

            $product_weight = $product->get_weight() ?: 0.1;
            $product_length = $product->get_length() ?: 1;
            $product_width = $product->get_width() ?: 1;
            $product_height = $product->get_height() ?: 1;

            $quantity = $item->get_quantity();
            $item_total = $order->get_item_total($item, true);
            $item_tax = $item->get_total_tax();

            // Convert weight and dimensions
            $weight_unit = get_option('woocommerce_weight_unit');
            $dimension_unit = get_option('woocommerce_dimension_unit');

            if ($weight_unit !== 'kg') {
                $weight_obj = new Mass($product_weight, $weight_unit);
                $product_weight = $weight_obj->toUnit('kg');
            }

            if ($dimension_unit !== 'cm') {
                $length_obj = new Length($product_length, $dimension_unit);
                $product_length = $length_obj->toUnit('cm');
                $width_obj = new Length($product_width, $dimension_unit);
                $product_width = $width_obj->toUnit('cm');
                $height_obj = new Length($product_height, $dimension_unit);
                $product_height = $height_obj->toUnit('cm');
            }

            $items[] = [
                "a_product_id" => (string)$product->get_id(),
                "a_order_id"   => (string)$order_id,
                "name"         => $item->get_name(),
                "qty"          => (int)$quantity,
                "product_cost" => (float)$item_total,
                "tax"          => (float)$item_tax,
                "weight"       => (float)$product_weight,
                "d_length"     => (float)$product_length,
                "d_breadth"    => (float)$product_width,
                "d_height"     => (float)$product_height,
            ];

            $total_weight += ($product_weight * $quantity);
            if ($product_length > $total_length) $total_length = $product_length;
            if ($product_width > $total_width) $total_width = $product_width;
            if ($product_height > $total_height) $total_height = $product_height;
        }

        $phone = $this->extract_phone($order->get_billing_phone());

        return [
            "a_order_id"        => (string)$order_id,
            "t_name"            => trim($shipping_first_name . ' ' . $shipping_last_name),
            "t_email"           => $order->get_billing_email(),
            "t_phone"           => $phone,
            "t_pincode"         => $shipping_postcode,
            "t_address"         => trim($shipping_address_1 . ' ' . $shipping_address_2),
            "t_city"            => $shipping_city,
            "t_state"           => $shipping_state,
            "invoice_number"    => (string)$order_id,
            "payment_option"    => $order->get_payment_method() === 'cod' ? 'cod' : 'prepaid',
            "order_created_date"=> $order->get_date_created()->date('Y-m-d H:i:s'),
            "items"             => $items
        ];
    }

    private function extract_phone($phone) {
        $digits = preg_replace('/\D/', '', $phone);
        return strlen($digits) > 10 ? substr($digits, -10) : str_pad($digits, 10, '0', STR_PAD_LEFT);
    }

    public function update_order_shipment_status($order_id) {
        $resp = null;
        
        $awb_number = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_courierkaro_awb');
        if (!empty($awb_number)) {
            $resp = $this->get_order_tracking_by_awb($awb_number);
        } else {
            $resp = $this->get_order_tracking_by_order_id($order_id);
        }

        if ($resp && is_array($resp) && !empty($resp)) {
            $shipment_obj = $this->init_model($resp, $order_id);
            if ($shipment_obj) {
                Bt_Sync_Shipment_Tracking_Shipment_Model::save_tracking($order_id, $shipment_obj);
                return $shipment_obj;
            }
        }
        
        return null;
    }

    public function courierkaro_webhook_receiver($request) {
        update_option("courierkaro_webhook_called", time());
        
        $enabled_shipping_providers = carbon_get_theme_option('bt_sst_enabled_shipping_providers');
        if (!is_array($enabled_shipping_providers) || !in_array('courierkaro', $enabled_shipping_providers)) {
            return "Thanks Courier Karo! Provider not enabled.";
        }

        $order_ids = [];
        
        // Get order ID from request
        if (isset($request['order_id']) && !empty($request['order_id'])) {
            $order_ids[] = $request['order_id'];
        }
        
        // Get order IDs by AWB
        if (isset($request['awb_no']) && !empty($request['awb_no'])) {
            $awb_number = $request['awb_no'];
            $awb_order_ids = Bt_Sync_Shipment_Tracking_Shipment_Model::get_orders_by_awb_number($awb_number);
            if (!empty($awb_order_ids)) {
                foreach ($awb_order_ids as $awb_order_id) {
                    if (!in_array($awb_order_id, $order_ids)) {
                        $order_ids[] = $awb_order_id;
                    }
                }
            }
        }

        if (!empty($order_ids) && is_array($order_ids)) {
            foreach ($order_ids as $order_id) {
                if (!empty($order_id)) {
                    $order = wc_get_order($order_id);
                    if ($order) {
                        $bt_sst_sync_orders_date = carbon_get_theme_option('bt_sst_sync_orders_date');
                        
                        $date_created_dt = $order->get_date_created();
                        $timezone = $date_created_dt->getTimezone();
                        $date_created_ts = $date_created_dt->getTimestamp();
                        
                        $now_dt = new WC_DateTime();
                        $now_dt->setTimezone($timezone);
                        $now_ts = $now_dt->getTimestamp();
                        
                        $allowed_seconds = $bt_sst_sync_orders_date * 24 * 60 * 60;
                        $diff_in_seconds = $now_ts - $date_created_ts;
                        
                        if ($diff_in_seconds <= $allowed_seconds) {
                            $shipment_obj = $this->init_model($request, $order_id);
                            if ($shipment_obj) {
                                Bt_Sync_Shipment_Tracking_Shipment_Model::save_tracking($order_id, $shipment_obj);
                                return "Thanks Courier Karo! Record updated.";
                            }
                        } else {
                            return "Thanks Courier Karo! Order too old.";
                        }
                    }
                }
            }
        }

        return "Thanks Courier Karo!";
    }

    public function init_model($data, $order_id) {
        $obj = new Bt_Sync_Shipment_Tracking_Shipment_Model();
        $obj->shipping_provider = 'courierkaro';
        $obj->order_id = $order_id;
        $obj->courier_name = 'Courier Karo';

        if (is_array($data) && !empty($data)) {
            if (isset($data['awb_no']) && !empty($data['awb_no'])) {
                $obj->awb = sanitize_text_field($data['awb_no']);
            }
            if (isset($data['awb_no']) && !empty($data['awb_no'])) {
                $obj->awb = sanitize_text_field($data['awb_no']);
            }

            $status = '';
            if (isset($data['status'])) {
                $status = sanitize_text_field($data['status']);
            } elseif (isset($data['current_status'])) {
                $status = sanitize_text_field($data['current_status']);
            }

            if (!empty($status)) {
                $obj->current_status = Bt_Sync_Shipment_Tracking_Shipment_Model::convert_string_to_slug($status);
            }

            if (isset($data['expected_delivery']) && !empty($data['expected_delivery'])) {
                $obj->etd = sanitize_text_field($data['expected_delivery']);
            }

            if (isset($data['delivered_on']) && !empty($data['delivered_on'])) {
                $obj->delivery_date = sanitize_text_field($data['delivered_on']);
            }

            $obj->scans = [];

            if (strtolower($obj->current_status) == "delivered" && empty($obj->delivery_date)) {
                $obj->delivery_date = isset($data['delivered_on']) ? sanitize_text_field($data['delivered_on']) : date('Y-m-d');
            }
        }

        return $obj;
    }

    private function init_model_from_create_response($data, $order_id) {
        $obj = new Bt_Sync_Shipment_Tracking_Shipment_Model();
        $obj->shipping_provider = 'courierkaro';
        $obj->order_id = $order_id;
        $obj->courier_name = 'Courier Karo';

        if (isset($data['awb_no']) && !empty($data['awb_no'])) {
            $obj->awb = sanitize_text_field($data['awb_no']);
        }else{
            $obj->awb = sanitize_text_field($order_id);
        }

        $obj->current_status = $resp["current_status"] ?? '';
        $obj->scans = [];

        return $obj;
    }

    public function get_products() {
        $this->init_params();

        if (empty($this->api_key) || empty($this->store_url)) {
            return null;
        }

        $args = [
            'headers' => $this->get_headers(),
            'timeout' => 30,
            'method'  => 'POST'
        ];

        $response = wp_remote_post(self::API_BASE_URL . '/fetch-products', $args);

        if (is_wp_error($response)) {
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }

    public function get_warehouses() {
        $this->init_params();

        if (empty($this->api_key) || empty($this->store_url)) {
            return null;
        }

        $args = [
            'headers' => $this->get_headers(),
            'timeout' => 30,
            'method'  => 'POST'
        ];

        $response = wp_remote_post(self::API_BASE_URL . '/fetch-warehouse', $args);

        if (is_wp_error($response)) {
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }
}
