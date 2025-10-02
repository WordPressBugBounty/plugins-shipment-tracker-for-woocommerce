<?php
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;

if ( ! defined( 'ABSPATH' ) ) exit;
class Bt_Sync_Shipment_Tracking_Ekart
{
    private $client_id;
    private $username;
    private $password;
    private $shipping_mode;
    private $pickup_pin;
    private const API_BASE_URL = "https://app.elite.ekartlogistics.in";

    public function __construct()
    {
    }

    public function init_params()
    {
        $client_id = carbon_get_theme_option('bt_sst_ekart_client_id');
        $username = carbon_get_theme_option('bt_sst_ekart_username');
        $password = carbon_get_theme_option('bt_sst_ekart_password');
        $this->shipping_mode = carbon_get_theme_option('bt_sst_ekart_shipping_mode');
        $this->pickup_pin = carbon_get_theme_option('bt_sst_ekart_pickup_pin');
        $this->client_id = trim($client_id);
        $this->username  = trim($username);
        $this->password  = trim($password);
    }

    public function generate_access_token_object()
    {
        $this->init_params();

        $url = self::API_BASE_URL . '/integrations/v2/auth/token/' . $this->client_id;

        $args = array(
            'body'    => json_encode(array(
                'username' => $this->username,
                'password' => $this->password,
            )),
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'method'  => 'POST',
            'timeout' => 60,
        );

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            return false;
        }

        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            return false;
        }

        $response_body = wp_remote_retrieve_body($response);
        $response_data = json_decode($response_body, true);

        if (isset($response_data['access_token'])) {
            $response_data['expires_at'] = time() + intval($response_data['expires_in']);
            update_option("generated_access_token_object", $response_data);
            return $response_data['access_token'];
        }

        return false;
    }

    public function get_access_token()
    {
        $token_data = get_option("generated_access_token_object", false);

        if ($token_data && isset($token_data['access_token'], $token_data['expires_at'])) {
            if ($token_data['expires_at'] > (time() + 120)) {
                return $token_data['access_token'];
            }
        }
        $token = $this->generate_access_token_object();
   
        return $token;
    }

    public function push_order_to_ekart($order_id)
    {
        $token = $this->get_access_token();
        if (! $token) {
            return null;
        }

        $order_payload = $this->get_ekart_order_object($order_id);
        if (! $order_payload) {
            return null;
        }

        $url = self::API_BASE_URL . '/api/v1/package/create';

        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ],
            'body'    => wp_json_encode($order_payload),
            'timeout' => 60,
        ];

        $args['method'] = 'PUT';
        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            error_log("Ekart push_order WP error: " . $response->get_error_message());
            return null;
        }

        $body_raw = wp_remote_retrieve_body($response);

        $resp = json_decode($body_raw, true);
        if (! is_array($resp)) {
            error_log("Ekart push_order JSON parse error: " . json_last_error_msg());
            return null;
        }

        return $resp;
    }

    private function get_ekart_order_object($order_id)
    {
        $this->init_params();
        if (! $order = wc_get_order($order_id)) {
            return false;
        }

        $pick_up_location = carbon_get_theme_option('bt_sst_ekart_pick_up_location');

        $order_number = (String)$order->get_id();
        $invoice_number = (String)$order->get_id();
        $invoice_date = $order->get_date_created()->date('Y-m-d');

        $consignee_name = $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name();
        $products_desc = [];
        $items = [];
        $total_quantity =0;
        foreach ($order->get_items() as $item) {
            if (! is_a($item, 'WC_Order_Item_Product')) {
                continue;
            }
            $total_quantity += $item->get_quantity();
            $product = $item->get_product();
            $name = $item->get_name();
            $item_count = $order->get_item_count();
            $price = $order->get_item_total($item, true);

            $products_desc[] = $name;

            $items[] = [
                'product_name' => $name,
                'quantity' => $item_count,
                'unit_price' => $price,
            ];
        }

        $name    = $order->get_billing_first_name() || $order->get_billing_last_name() 
            ? trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() )
            : trim( $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name() );

        $phone =  $this->extractPhoneNumber($order->get_billing_phone());

        $address = $order->get_billing_address_1() 
            ? $order->get_billing_address_1() 
            : $order->get_shipping_address_1();

        $city    = $order->get_billing_city() 
            ? $order->get_billing_city() 
            : $order->get_shipping_city();

        $state   = $order->get_billing_state() 
            ? $order->get_billing_state() 
            : $order->get_shipping_state();

        $country = $order->get_billing_country() 
            ? $order->get_billing_country() 
            : $order->get_shipping_country();

        $pin     = $order->get_billing_postcode() 
            ? $order->get_billing_postcode() 
            : $order->get_shipping_postcode();

         $total_weight = 0;
        $total_width = 0;
        $total_height = 0;
        $total_length = 0;
        $total_products = 0;
        $product_description = "";
        foreach ($order->get_items() as $item_id => $a) {
            if (is_a($a, 'WC_Order_Item_Product')) {
                $product = $a->get_product();
               
                if(!empty($product->get_weight()) && $product->get_weight()>0){
                    $total_weight = $total_weight + ($product->get_weight() * $a->get_quantity());
                }

                if(!empty($product->get_width()) && $product->get_width()>0){
                    $total_width = $total_width + ($product->get_width() * $a->get_quantity());
                    if($product->get_height()>$total_height){
                        $total_height =$product->get_height();
                    }
                    if($product->get_length()>$total_length){
                        $total_length =$product->get_length();
                    }
                }
                if(empty($product_description )){
                    $product_description = $product->get_name();
                }
                $total_products++;
            }
        }

        $weight_unit = get_option('woocommerce_weight_unit');
        $dimension_unit = get_option('woocommerce_dimension_unit');

        $total_weight = new Mass($total_weight,  $weight_unit );
        $total_weight_kg = $total_weight->toUnit('g');
        if($total_weight_kg<0.1){
            $total_weight_kg = 0.1;
        }

        $total_width = new Length($total_width, $dimension_unit);
        $total_width_cm = $total_width->toUnit('cm');
        if($total_width_cm<0.1){
            $total_width_cm = 10;
        }

        $total_height = new Length($total_height, $dimension_unit);
        $total_height_cm = $total_height->toUnit('cm');
        if($total_height_cm<0.1){
            $total_height_cm = 10;
        }
        $total_length = new Length($total_length, $dimension_unit);
        $total_length_cm = $total_length->toUnit('cm');
        if($total_length_cm<0.1){
            $total_length_cm = 10;
        }
        
        $payload = [
            // "seller_name" => "Durvient",
            "consignee_gst_amount" => 0,
            "commodity_value" => "",
            "quantity" => $total_quantity,
            // "seller_address" => "Address: Shop 210, Agarwal Business Hub, Baif Road, Near Talhati Office, Wagholi , Pune, Maharashtra - (412207)",
            "seller_gst_tin" => get_option('bt_ekart_seller_gst', ''),
            "order_number" => $order_number,
            "invoice_number" => $invoice_number,
            "invoice_date" => $invoice_date,
            "consignee_name" => $consignee_name,
            "products_desc" => implode(', ', $products_desc),
            "payment_mode" => $order->get_payment_method()=="cod"?"COD":"PREPAID",
            "total_amount" => (int)$order->get_total(),
            "tax_value" => floatval($order->get_total_tax()),
            "taxable_amount" => floatval($order->get_subtotal()),
            "cod_amount" => (int)$order->get_total(),
            "weight" => $total_weight_kg,
            "length" => $total_length_cm,
            "height" => $total_height_cm,
            "width" => $total_width_cm,
            "drop_location" => [
                "name"    => $name,
                "phone"   => (int)$phone,
                "address" => $address,
                "city"    => $city,
                "state"   => $state,
                "country" => $country,
                "pin"     => (int)$pin,
            ],
            "pickup_location" => [
                "name" => $pick_up_location,
            ],
            "return_location" => [
                "name" => $pick_up_location,
            ],
            "items" => $items,
        ];

        return apply_filters('bt_ekart_order_object', $payload, $order_id);
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

    public function get_order_tracking_by_tracking_id($tracking_id)
    {
        $token = $this->get_access_token();
        if (! $token) {
            return null;
        }

        $url = self::API_BASE_URL . '/api/v1/track/' . urlencode($tracking_id);
        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'method' => 'GET',
            'timeout' => 30,
        ];

        $response = wp_remote_get($url, $args);
        if (is_wp_error($response)) {
            error_log("Ekart get_shipment_status_by_awb WP error: " . $response->get_error_message());
            return null;
        }

        $body_raw = wp_remote_retrieve_body($response);

        $resp = json_decode($body_raw, true);
        if (! is_array($resp)) {
            error_log("Ekart shipment status JSON parse error: " . json_last_error_msg());
            return null;
        }

        return $resp;
    }

    public function init_model($data, $order_id){
       
        $obj = new Bt_Sync_Shipment_Tracking_Shipment_Model();
        $obj->order_id = $order_id;
        $obj->shipping_provider = 'ekart';
        $obj->courier_name = 'Ekart';
        
        if (!empty($data)) {
            $track = $data['track'];

            $obj->awb = $data['wbn'] ?? '';
            $obj->current_status = Bt_Sync_Shipment_Tracking_Shipment_Model::convert_string_to_slug($track['status'] ?? '');
            $obj->etd = "";
            $obj->scans = $track['details'] ?? [];

            if (strtolower($obj->current_status) == "delivered" && empty($obj->delivery_date)) {
                $obj->delivery_date = date('Y-m-d');
            }
        }
        return $obj;

    }

    public function update_order_shipment_status($order_id){
        $resp=null;
        if(!empty($tracking_id = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, "_bt_ekart_tracking_id"))){
            $resp= $this->get_order_tracking_by_tracking_id($tracking_id);
        }

        if($resp!=null && isset($resp)){
            $shipment_obj = $this->init_model($resp, $order_id);
            Bt_Sync_Shipment_Tracking_Shipment_Model::save_tracking($order_id,$shipment_obj);                           
            return $shipment_obj;
        }
        return null;
    }

    public function get_addresses_list(){

        $token = $this->get_access_token();
        if (! $token) {
            return null;
        }

        $url = self::API_BASE_URL . '/api/v2/addresses';
        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'method' => 'GET',
            'timeout' => 30,
        ];

        $response = wp_remote_get($url, $args);

        $body_raw = wp_remote_retrieve_body($response);

        $resp = json_decode($body_raw, true);
        return $resp;
    }

    public function get_rate_calcultor(
        $drop_pincode, 
        $weight, 
        $length, 
        $height, 
        $width,
        $invoiceAmount
    ){
        $body_express = $this->get_rate_calcultor_by_mode(
                            $drop_pincode, 
                            $weight, 
                            $length, 
                            $height, 
                            $width,
                            $invoiceAmount,
                            "EXPRESS",
                        );
        $body_surface = $this->get_rate_calcultor_by_mode(
                                $drop_pincode, 
                                $weight, 
                                $length, 
                                $height, 
                                $width,
                                $invoiceAmount,
                                "SURFACE",
                            );
        $value = [];
        $value[]=$body_express;
        $value[]=$body_surface;
        return $value;

    }

    public function get_rate_calcultor_by_mode(
        $drop_pincode, 
        $weight, 
        $length, 
        $height, 
        $width,
        $invoiceAmount,
        $shipping_mode
    ) {
        $token = $this->get_access_token();
        if (! $token) {
            return null;
        }

        $this->init_params();

        $payload = [
            "pickupPincode"      => (int) $this->pickup_pin,
            "dropPincode"        => (int) $drop_pincode,
            "weight"             => (float) $weight,
            "length"             => (float) $length,
            "height"             => (float) $height,
            "width"              => (float) $width,
            "serviceType"        => $shipping_mode,
            "invoiceAmount"      => $invoiceAmount,
        ];

        $url = self::API_BASE_URL . '/data/v3/serviceability';

        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ],
            'body'    => wp_json_encode($payload),
            'timeout' => 60,
        ];

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            return null;
        }

        $response_body = wp_remote_retrieve_body($response);
        $response_data = json_decode($response_body, true);
        $final_data = [
            'mod'   => $shipping_mode,
            'tat'   => isset($response_data[0]["tat"]["max"]) 
                        ? $response_data[0]["tat"]["max"] 
                        : null,
            'total' => isset($response_data[0]["forwardDeliveredCharges"]["totalForwardDeliveredEstimate"]) 
                        ? $response_data[0]["forwardDeliveredCharges"]["totalForwardDeliveredEstimate"] 
                        : null,
        ];

        return $final_data;
    }

    public function get_locality($postcode) {
        $token = $this->get_access_token();
        if (! $token) {
            return null;
        }

        $this->init_params();

        $url = self::API_BASE_URL . '/api/v2/serviceability/' . $postcode;

        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ]
        ];

        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            return null;
        }

        $response_body = wp_remote_retrieve_body($response);
        $response_data = json_decode($response_body, true);

        if (isset($response_data["details"])) {
            $details = $response_data["details"];

            $data = [
                "postcode"   => $response_data["pincode"] ?? null,
                "city"       => $details["city"] ?? null,
                "state_code" => $details["state"] ?? null,
                "country"    => "IN"
            ];

            return $data;
        }

        return null;
    }

    public function ekart_webhook_receiver($request){

        update_option( "ekart_webhook_called", time() );
        $enabled_shipping_providers = carbon_get_theme_option( 'bt_sst_enabled_shipping_providers' );
        if(is_array($enabled_shipping_providers) && in_array('ekart',$enabled_shipping_providers)){
            $order_ids=array();
            if(isset($request["body"]["id"]) && !empty($request["body"]["id"])){
                $order_ids[]=$request["body"]["id"];
            }
            if(isset($request["body"]["wbn"]) && !empty($request["body"]["wbn"])){
                $awb_number = $request["body"]["wbn"];
                if(!empty($awb_order_ids = Bt_Sync_Shipment_Tracking_Shipment_Model::get_orders_by_awb_number($awb_number))){
                    foreach ($awb_order_ids as $awb_order_id) {
                        if(!in_array($awb_order_id,$order_ids)){
                            $order_ids[] = $awb_order_id;
                        }
                    }                    
                }
            }

            if(!empty($order_ids) && is_array($order_ids)){
                foreach ($order_ids as $order_id) {
                    if(!empty($order_id)){
                        if(false !== $order = wc_get_order( $order_id )){
        
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
                                    $data = [
                                        'track' => [
                                            'status'  => $request['body']['status'] ?? '',
                                            'details' => $request['body'] ?? [],
                                        ],
                                        'wbn' => $request['body']['wbn'] ?? ''
                                    ];
                                    $shipment_obj = $this->init_model($request, $order_id);
                                    Bt_Sync_Shipment_Tracking_Shipment_Model::save_tracking($order_id,$shipment_obj);                           
                                    return "Thanks Shiprocket! Record updated.";
                                }else{
                                    return "Thanks Shiprocket! Order too old.";
                                }
                        }
                    }
                }                    
            }

            
        }
        return "Thanks Shiprocket!";
    }

}