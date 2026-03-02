<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Bt_Sync_Shipment_Tracking_Ithink {

    private const API_BASE_URL = 'https://my.ithinklogistics.com/api_v3';

    private $access_token;
    private $secret_key;
    private $warehouse_id;
    private $pickup_pincode;
    private $store_id;

    public function __construct() {
    }

    public function init_params() {
        $settings = get_option('ithink_logistics_settings');
        $this->access_token = trim($settings['api_key']);
        $this->secret_key = trim($settings['api_secret']);
        $this->warehouse_id = trim($settings['warehouse_id']);
        $this->pickup_pincode = trim($settings['pickup_pincode']);
        $this->shipping_mode = trim($settings['shipping_mode']);
        $this->store_id = trim($settings['store_id']);
    }

    public function ithink_test_connection(){
        $this->init_params();
        if(empty($this->access_token) || empty($this->secret_key)){
            return false;
        }

        $payload = array(
            'data' => array(
                'warehouse_id' => (string)$this->warehouse_id,
                'access_token' => $this->access_token,
                'secret_key' => $this->secret_key,
            )
        );


        $args = array(
            'body' => wp_json_encode($payload),
            'headers' => array(
                'Content-Type' => 'application/json'
            )
        );

        $response = wp_remote_post(self::API_BASE_URL . "/warehouse/get.json" , $args);
        $body = wp_remote_retrieve_body($response);
        $resp = json_decode($body, true);

        if($resp && isset($resp['status']) && $resp['status'] === "success"){
            return true;
        }

        return false;
    }

    public function create_forward_order($order_id){
        $this->init_params();
        if(empty($this->access_token) || empty($this->secret_key)){
            return false;
        }

        $order = wc_get_order($order_id);

        $payload = $this->get_ithink_order_object($order_id);

        if (!$payload) return false;

        $args = array(
            'body'    => wp_json_encode($payload),
            'headers' => array('Content-Type' => 'application/json')
        );

        $response = wp_remote_post(self::API_BASE_URL . "/order/add.json", $args);
        $body     = wp_remote_retrieve_body($response);
        $resp     = json_decode($body, true);

        if (
            !empty($resp) &&
            isset($resp['status']) &&
            strtolower($resp['status']) === 'success' &&
            !empty($resp['data'])
        ) {
            $shipment = reset($resp['data']["1"]);

            if (!empty($shipment['waybill'])) {
                return $shipment;
            }
        }
        
        if ($order) {
            $order->add_order_note(
                'iThink Logistics Order Creation Failed. Response: ' . $body
            );
        }

        return false;
    }

    private function get_ithink_order_object($order_id){

        if(false == $order = wc_get_order($order_id)){
            return false;
        }

        $shipping_mode = $this->shipping_mode ?: 'Surface';
        $phoneNumber   = $this->extractPhoneNumber($order->get_billing_phone());

        $products = [];
        foreach ($order->get_items() as $item) {

            $product = $item->get_product();
            $product_id = $item->get_product_id();

            $line_total    = (float)$item->get_total();
            $line_subtotal = (float)$item->get_subtotal();
            $discount      = $line_subtotal - $line_total;

            $products[] = [
                "product_name"      => $item->get_name(),
                "product_sku"       => $product ? $product->get_sku() : '',
                "product_quantity"  => (int)$item->get_quantity(),
                "product_price"     => (float)$line_total,
                "product_tax_rate"  => (float)$item->get_total_tax(),
                "product_hsn_code"  => $product ? $product->get_meta('_hsn_code') : '',
                "product_discount"  => (float)$discount,
            ];
        }

        $weight_unit = get_option('woocommerce_weight_unit');
        $computed    = Bt_Sync_Shipment_Tracking::bt_sst_get_package_dimensions($order_id);

        $length = !empty($computed['length']) ? (float)$computed['length'] : 10;
        $width  = !empty($computed['width'])  ? (float)$computed['width']  : 10;
        $height = !empty($computed['height']) ? (float)$computed['height'] : 10;
        $weight = !empty($computed['weight']) ? (float)$computed['weight'] : 0.5;

        if ($weight_unit !== 'kg') {
            $weight = wc_get_weight($weight, 'kg', $weight_unit);
        }

        $shipping_total     = (float)$order->get_shipping_total();
        $discount_total     = (float)$order->get_discount_total();
        $fee_total          = (float)$order->get_total_fees();
        $total_tax          = (float)$order->get_total_tax();
        $cod_amount         = strtoupper($order->get_payment_method()) == "COD" ? (float)$order->get_total() : 0;

        $is_same = (
            $order->get_shipping_address_1() == $order->get_billing_address_1() &&
            $order->get_shipping_postcode()  == $order->get_billing_postcode()
        ) ? "Yes" : "No";

        $gst_number       = $order->get_meta('_gst_number');
        $eway_bill_number = $order->get_meta('_eway_bill_number');
        $reseller_name    = $order->get_meta('_reseller_name');
        $what3words       = $order->get_meta('_what3words');

        $postData = [
            "data" => [
                "shipments" => [
                    [
                        "order"             => (string)$order->get_order_number(),
                        "sub_order"         => "",
                        "order_date"        => $order->get_date_created()->format("Y-m-d H:i:s"),
                        "total_amount"      => (float)$order->get_total(),

                        "name"              => $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(),
                        "company_name"      => $order->get_shipping_company(),
                        "add"               => $order->get_shipping_address_1(),
                        "add2"              => $order->get_shipping_address_2(),
                        "add3"              => "",
                        "pin"               => $order->get_shipping_postcode(),
                        "city"              => $order->get_shipping_city(),
                        "state"             => $order->get_shipping_state(),
                        "country"           => $order->get_shipping_country() ?: 'India',
                        "phone"             => $phoneNumber,
                        "alt_phone"         => $phoneNumber,
                        "email"             => $order->get_billing_email(),

                        "is_billing_same_as_shipping" => $is_same,

                        "billing_name"          => $order->get_billing_first_name().' '.$order->get_billing_last_name(),
                        "billing_company_name"  => $order->get_billing_company(),
                        "billing_add"           => $order->get_billing_address_1(),
                        "billing_add2"          => $order->get_billing_address_2(),
                        "billing_add3"          => "",
                        "billing_pin"           => $order->get_billing_postcode(),
                        "billing_city"          => $order->get_billing_city(),
                        "billing_state"         => $order->get_billing_state(),
                        "billing_country"       => $order->get_billing_country(),
                        "billing_phone"         => $phoneNumber,
                        "billing_alt_phone"     => $phoneNumber,
                        "billing_email"         => $order->get_billing_email(),

                        "products"          => $products,

                        "shipment_length"   => $length,
                        "shipment_width"    => $width,
                        "shipment_height"   => $height,
                        "weight"            => $weight,

                        "shipping_charges"      => $shipping_total,
                        "giftwrap_charges"      => 0,
                        "transaction_charges"   => $fee_total,
                        "total_discount"        => $discount_total,
                        "first_attemp_discount" => 0,
                        "cod_charges"           => $fee_total,
                        "advance_amount"        => 0,
                        "cod_amount"            => $cod_amount,

                        "payment_mode"      => strtoupper($order->get_payment_method()) == "COD" ? "COD" : "Prepaid",

                        "reseller_name"     => $reseller_name,
                        "eway_bill_number"  => $eway_bill_number,
                        "gst_number"        => $gst_number,
                        "what3words"        => $what3words,

                        "return_address_id" => $this->warehouse_id,
                        "store_id"            => (String)$this->store_id,
                    ]
                ],

                "pickup_address_id" => $this->warehouse_id,
                "access_token"      => $this->access_token,
                "secret_key"        => $this->secret_key,
                "logistics"         => "",
                "order_type"        => "forward",
                "s_type"            => strtolower($shipping_mode),
            ]
        ];

        return $postData;
    }

    private function extractPhoneNumber( $billing_phone) {
        $digitsOnly = preg_replace('/\D/', '', $billing_phone);
        
        if (strlen($digitsOnly) > 10) {
            $phoneNumber = substr($digitsOnly, -10);
        } else {
            $phoneNumber = str_pad($digitsOnly, 10, '1', STR_PAD_LEFT);
        }

        return $phoneNumber;
    }

    public function update_order_shipment_status($order_id){
        $resp=null;
        if(!empty($awb_number = Bt_Sync_Shipment_Tracking_Shipment_Model::get_awb_by_order_id($order_id))){
            $resp= $this->get_order_tracking_by_awb_number($awb_number);
        }

        if($resp!=null && isset($resp[$awb_number])){
            $shipment_obj = $this->init_model($resp[$awb_number], $order_id);
            Bt_Sync_Shipment_Tracking_Shipment_Model::save_tracking($order_id,$shipment_obj);                           
            return $shipment_obj;
        }
        return null;
    }

    public function get_order_tracking_by_awb_number($awb_number){
        $this->init_params();
        if (empty($this->access_token) || empty($this->secret_key)) {
            return false;
        }
        $order = wc_get_order($order_id);
        if (empty($awb_number)) {
            if ($order) {
                $order->add_order_note('iThink Tracking Failed: AWB number missing.');
            }
            return false;
        }

        $awb_numbers = is_array($awb_number) ? $awb_number : [$awb_number];

        $payload = array(
            'data' => array(
                'access_token'     => $this->access_token,
                'secret_key'       => $this->secret_key,
                'awb_number_list'  => $awb_numbers
            )
        );
        $args = array(
            'body'    => wp_json_encode($payload),
            'headers' => array(
                'Content-Type' => 'application/json'
            )
        );
        $response = wp_remote_post(
            self::API_BASE_URL . "/order/track.json",
            $args
        );
        if (is_wp_error($response)) {
            if ($order) {
                $order->add_order_note(
                    'iThink Tracking API Error: ' . $response->get_error_message()
                );
            }
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $resp = json_decode($body, true);

        if (!empty($resp) && isset($resp['status_code']) && $resp['status_code'] == 200) {
            if (!empty($resp['data'])) {
                if ($order) {
                    $order->add_order_note(
                        'iThink Tracking Response: ' . $body
                    );
                }
                return $resp['data'];
            }
        }
        if ($order) {
            $order->add_order_note(
                'iThink Tracking Failed. Response: ' . $body
            );
        }

        return false;
    }

    public function init_model($data, $order_id){
        $obj = new Bt_Sync_Shipment_Tracking_Shipment_Model();
        $obj->order_id = $order_id;
        $obj->shipping_provider = "iThink Logistics";
        $obj->courier_name = $data['logistic'];
        
        if (sizeof($data) > 0) {
            $obj->awb = $data['awb_no'];
            $obj->current_status = Bt_Sync_Shipment_Tracking_Shipment_Model::convert_string_to_slug($data['current_status']);
            $obj->etd = $data['expected_delivery_date'];
            $obj->scans = $data['scan_details'];

            if (strtolower($obj->current_status) == "delivered" && empty($obj->delivery_date)) {
                $obj->delivery_date = date('Y-m-d');
            }        
            
        }
        return $obj;

    }

    public function check_shipping_rate(
            $from_pincode,
            $to_pincode,
            $length,
            $width,
            $height,
            $weight,
            $product_mrp,
            $order_type,
            $payment_method,
        ) {
        $this->init_params();
        if (empty($this->access_token) || empty($this->secret_key)) {
            return false;
        }

        $payload = array(
            'data' => array(
                'from_pincode'        => (string) $from_pincode,
                'to_pincode'          => (string) $to_pincode,
                'shipping_length_cms' => (string) $length,
                'shipping_width_cms'  => (string) $width,
                'shipping_height_cms' => (string) $height,
                'shipping_weight_kg'  => (string) $weight,
                'order_type'          => $order_type,
                'payment_method'      => $payment_method,
                'product_mrp'         => (string) $product_mrp,
                'access_token'        => $this->access_token,
                'secret_key'          => $this->secret_key
            )
        );
        $response = wp_remote_post(
            self::API_BASE_URL ."/rate/check.json",
            array(
                'body'    => wp_json_encode($payload),
                'headers' => array('Content-Type' => 'application/json')
            )
        );


        if (is_wp_error($response)) {
            return false;
        }
        $body = wp_remote_retrieve_body($response);
        $resp = json_decode($body, true);
        if (
            !empty($resp) &&
            isset($resp['status']) &&
            $resp['status'] === 'success' &&
            isset($resp['status_code']) &&
            $resp['status_code'] == 200
        ) {
            $rates = $resp ?? array();
            return $rates;
        }

        return false;
    }

    public function generate_shipping_label(
        $awb_numbers,
        $page_size = 'A4',
        $display_cod_prepaid = '',
        $display_shipper_mobile = '',
        $display_shipper_address = ''
    ) {
        $this->init_params();
        if (empty($this->access_token) || empty($this->secret_key)) {
            return false;
        }
        if (empty($awb_numbers)) {
            return false;
        }
        if (is_array($awb_numbers)) {
            $awb_numbers = implode(',', $awb_numbers);
        }
        $payload = array(
            'data' => array(
                'access_token'            => $this->access_token,
                'secret_key'              => $this->secret_key,
                'awb_numbers'             => $awb_numbers,
                'page_size'               => $page_size,
                'display_cod_prepaid'     => $display_cod_prepaid,
                'display_shipper_mobile'  => $display_shipper_mobile,
                'display_shipper_address' => $display_shipper_address,
            )
        );

        $response = wp_remote_post(
            self::API_BASE_URL . "/shipping/label.json",
            array(
                'body'    => wp_json_encode($payload),
                'headers' => array(
                    'Content-Type' => 'application/json'
                )
            )
        );

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $resp = json_decode($body, true);

        if (
            !empty($resp) &&
            isset($resp['status']) &&
            $resp['status'] === 'success' &&
            isset($resp['status_code']) &&
            $resp['status_code'] == 200 &&
            !empty($resp['file_name'])
        ) {
            return array($resp['file_name']);
        }

        return "Please enter a valid AWB number to generate the shipping label.";
    }

}
