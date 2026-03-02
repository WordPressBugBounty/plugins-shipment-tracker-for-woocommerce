<?php
if ( ! defined( 'ABSPATH' ) ) exit;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
/**
 * Proship B2C API shipping provider.
 * API docs: https://documenter.getpostman.com/view/27749698/2s93mBwyZ1
 */

if (!defined('ABSPATH')) {
    exit;
}

class Bt_Sync_Shipment_Tracking_Proship {

    private $username;
    private $password;


    public function __construct() {}

    public function init_params() {
        $this->username   = trim(carbon_get_theme_option('bt_sst_proship_user_email'));
        $this->password   = trim(carbon_get_theme_option('bt_sst_proship_user_password'));
        $this->api_base_url = 'https://proship.prozo.com';
    }

    public function test_proship() {
        $this->init_params();

        $url = $this->api_base_url.'/api/auth/signin';

        $args = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => wp_json_encode([
                'username' => $this->username,
                'password' => $this->password,
            ]),
            'timeout' => 15,
            'method'  => 'POST',
        ];

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            return false;
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if ($code === 200 && !empty($data['accessToken'])) {
            return $data['accessToken'];
   
        }

        return false;
    }

    private function get_headers() {
        return [
            'Authorization' => 'Bearer ' . $this->test_proship(),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }

    public function create_order($order_id)
    {
        $this->init_params();

        $url = $this->api_base_url . '/api/order/create';
        $order_payload = $this->get_proship_order_object($order_id);

        $args = [
            'headers' => $this->get_headers(),
            'body'    => wp_json_encode($order_payload),
            'timeout' => 30,
            'method'  => 'POST',
        ];

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => $response->get_error_message(),
            ];
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        return $body;
    }

    private function get_proship_order_object($order_id) {

        if (false == $order = wc_get_order($order_id)) {
            return false;
        }


        $phone = $this->extractPhoneNumber($order->get_billing_phone());

        $shipping_postcode = $order->get_shipping_postcode();
        $use_billing = empty($shipping_postcode);

        $first_name = $use_billing ? $order->get_billing_first_name() : $order->get_shipping_first_name();
        $last_name  = $use_billing ? $order->get_billing_last_name()  : $order->get_shipping_last_name();
        $address_1  = $use_billing ? $order->get_billing_address_1()  : $order->get_shipping_address_1();
        $address_2  = $use_billing ? $order->get_billing_address_2()  : $order->get_shipping_address_2();
        $city       = $use_billing ? $order->get_billing_city()       : $order->get_shipping_city();
        $state      = $use_billing ? $order->get_billing_state()      : $order->get_shipping_state();
        $postcode   = $use_billing ? $order->get_billing_postcode()   : $order->get_shipping_postcode();
        $country    = $use_billing ? $order->get_billing_country()    : $order->get_shipping_country();

        $pickup_details = [
            "from_name"         => carbon_get_theme_option('bt_sst_proship_from_name'),
            "from_phone_number" => carbon_get_theme_option('bt_sst_proship_from_phone'),
            "from_address"      => carbon_get_theme_option('bt_sst_proship_from_address'),
            "from_email"        => carbon_get_theme_option('bt_sst_proship_from_email'),
            "from_pincode"      => carbon_get_theme_option('bt_sst_proship_from_pincode'),
            "from_city"         => carbon_get_theme_option('bt_sst_proship_from_city'),
            "from_country"         => 'IN',
            "from_addressline"  => carbon_get_theme_option('bt_sst_proship_from_address'),
            "from_state"        => carbon_get_theme_option('bt_sst_proship_from_state'),
            "gstin"             => carbon_get_theme_option('bt_sst_proship_from_gstin'),
        ];

        $item_list = [];
        $total_weight = 0;
        $max_length = 0;
        $max_width  = 0;
        $max_height = 0;

        foreach ($order->get_items() as $item) {
            if (!is_a($item, 'WC_Order_Item_Product')) {
                continue;
            }

            $product = $item->get_product();
            if (!$product) {
                continue;
            }

            $qty = $item->get_quantity();

            $item_list[] = [
                "units"         => $qty,
                "hsn"           => $product->get_meta('_hsn_code'),
                "item_name"     => $product->get_name(),
                "sku_id"        => $product->get_sku() ?: $product->get_name(),
                "selling_price" => (float) $product->get_price(),
            ];

            if ($product->get_weight()) {
                $total_weight += ($product->get_weight() * $qty);
            }

            $max_length = max($max_length, (float) $product->get_length());
            $max_width  = max($max_width,  (float) $product->get_width());
            $max_height = max($max_height, (float) $product->get_height());
        }

        $weight_unit    = get_option('woocommerce_weight_unit');
        $dimension_unit = get_option('woocommerce_dimension_unit');

        $weight = (new Mass($total_weight ?: 0.2, $weight_unit))->toUnit('g');
        $length = (new Length($max_length ?: 5, $dimension_unit))->toUnit('cm');
        $width  = (new Length($max_width  ?: 5, $dimension_unit))->toUnit('cm');
        $height = (new Length($max_height ?: 5, $dimension_unit))->toUnit('cm');

        return [
            "reverse"        => false,
            "order_type"     => "Forward Shipment",

            "item_list"      => $item_list,

            "pickup_details" => $pickup_details,

            "delivery_details" => [
                "to_name"        => trim($first_name . ' ' . $last_name),
                "to_phone_number"=> $phone,
                "to_address"     => $address_1 . ' ' . $address_2,
                "to_country"     => $country,
                "to_email"       => $order->get_billing_email(),
                "to_pincode"     => $postcode,
                "to_city"        => $city,
                "to_addressline" => $address_1 . ' ' . $address_2,
                "to_state"       => $state,
            ],

            "customer_detail" => [
                "to_email"   => $order->get_billing_email(),
                "to_address" => $address_1 . ' ' . $address_2,
                "to_city"    => $city,
                "to_country" => $country,
                "to_state"   => $state,
            ],

            "shipment_detail" => [
                [
                    "item_breadth" => (float) $width,
                    "item_length"  => (float) $length,
                    "item_height"  => (float) $height,
                    "item_weight"  => (float) $weight,
                ]
            ],

            "invoice_value"     => (float) $order->get_total(),
            "cod_amount"        => $order->get_payment_method() === 'cod' ? (float) $order->get_total() : 0,
            "client_order_id"   => (string) $order->get_id(),
            "is_reverse"        => false,
            "invoice_number"    => $order->get_order_number(),
            "payment_mode"      => $order->get_payment_method() === 'cod' ? 'COD' : 'PREPAID',
            "reference"         => 'WC-' . $order->get_id(),
        ];
    }

    private function extractPhoneNumber( $billing_phone) {
        $digitsOnly = preg_replace('/\D/', '', $billing_phone);
        
        if (strlen($digitsOnly) > 10) {
            $phoneNumber = substr($digitsOnly, -10);
        } else {
            $phoneNumber = str_pad($digitsOnly, 10, '9', STR_PAD_LEFT);
        }

        return $phoneNumber;
    }

    public function get_order_tracking_by_awb_number($awb_numbers) {
        $this->init_params();

        if (empty($awb_numbers)) {
            return null;
        }

        if (is_array($awb_numbers)) {
            $awb_numbers = implode(',', $awb_numbers);
        }

        $url = $this->api_base_url . '/api/order/track_waybill?waybills=' . urlencode($awb_numbers);

        $args = [
            'headers' => $this->get_headers(),
            'timeout' => 30,
            'method'  => 'GET',
        ];

        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $resp = json_decode($body, true);

        return is_array($resp) ? $resp : null;
    }

    public function update_order_shipment_status($order_id) {
        $resp = null;
        $awb  = Bt_Sync_Shipment_Tracking_Shipment_Model::get_awb_by_order_id($order_id);
        if (!empty($awb)) {
            $resp = $this->get_order_tracking_by_awb_number($awb);
        }
        
        if ($resp !== null && is_array($resp) && !empty($resp)) {
            $shipment_obj = $this->init_model($resp, $order_id, $awb);
            if ($shipment_obj) {
                Bt_Sync_Shipment_Tracking_Shipment_Model::save_tracking($order_id, $shipment_obj);
                return $shipment_obj;
            }
        }
        return null;
    }

    public function init_model($data, $order_id, $awb)
    {
        $obj = new Bt_Sync_Shipment_Tracking_Shipment_Model();
        $obj->order_id = $order_id;
        $obj->shipping_provider = 'proship';

        if (!empty($data['waybillDetails']) && isset($data['waybillDetails'][0])) {

            $details = $data['waybillDetails'][0];

            $obj->courier_name   = $details['courierPartner'] ?? '';
            $obj->awb            = $details['waybill'] ?? $awb;
            $obj->current_status = Bt_Sync_Shipment_Tracking_Shipment_Model::convert_string_to_slug(
                $details['currentStatus'] ?? ''
            );
            $obj->etd   = $details['edd'] ?? null;
            $obj->scans = $details['order_history'] ?? [];

            if (strtolower($obj->current_status) === 'delivered' && empty($obj->delivery_date)) {
                $obj->delivery_date = date('Y-m-d');
            }

        }
        else {
            $obj->awb = $awb;
            $obj->current_status = '';
            $obj->scans = [];
        }

        return $obj;
    }

    public function get_proship_pricing(
        $from_pincode,
        $to_pincode,
        $payment_type,
        $length,
        $breadth,
        $height,
        $weight,
        $dispatch_mode,
        $cod_amount,
        $order_type
    )
    {
        $this->init_params();
        $params = [];
        $url = $this->api_base_url . '/api/tools/list/ext/pricing';

        $payload = wp_parse_args($params, [
            'from'         => (int) $from_pincode,
            'to'           => (int) $to_pincode,
            'paymentType'  => $payment_type,
            'length'       => $length,
            'breadth'      => $breadth,
            'height'       => $height,
            'weight'       => $weight,
            'dispatchMode' => $dispatch_mode,
            'codAmount'    => $cod_amount,
            'orderType'    => $order_type,
        ]);

        $args = [
            'headers' => $this->get_headers(),
            'body'    => wp_json_encode($payload),
            'timeout' => 30,
            'method'  => 'POST',
        ];

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => $response->get_error_message(),
            ];
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    public function proship_webhook_receiver($request){

        update_option( 'proship_webhook_called', time() );
        $json = $request->get_json_params();

        if ( empty( $json['waybill'] ) ) {
            return 'Invalid payload';
        }

        $shipment = $json;

        $enabled_shipping_providers = carbon_get_theme_option( 'bt_sst_enabled_shipping_providers' );

        if ( ! is_array( $enabled_shipping_providers ) || ! in_array( 'proship', $enabled_shipping_providers ) ) {
            return 'Proship not enabled';
        }

        $order_ids = [];

        if ( ! empty( $shipment['waybill'] ) ) {
            $awb_number = $shipment['waybill'];

            $awb_order_ids = Bt_Sync_Shipment_Tracking_Shipment_Model::get_orders_by_awb_number( $awb_number );

            if ( ! empty( $awb_order_ids ) && is_array( $awb_order_ids ) ) {
                foreach ( $awb_order_ids as $awb_order_id ) {
                    if ( ! in_array( $awb_order_id, $order_ids ) ) {
                        $order_ids[] = $awb_order_id;
                    }
                }
            }
        }

        if ( empty( $order_ids ) ) {
            return 'No order found';
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
                                    $shipment_obj = $this->webhook_init_model($shipment, $order_id);
                            
                                    Bt_Sync_Shipment_Tracking_Shipment_Model::save_tracking($order_id,$shipment_obj);                           
                                    return "Thanks Proship! Record updated.";
                                }else{
                                    return "Thanks Proship! Order too old.";
                                }
                        }
                    }
                }                    
            }

            
       
        return "Thanks Proship!";
    }

      public function webhook_init_model( $data, $order_id ) {
        $obj = new Bt_Sync_Shipment_Tracking_Shipment_Model();
        $obj->order_id          = $order_id;
        $obj->shipping_provider = 'proship';
        $obj->courier_name      = $data['courierPartnerId'];

        if ( ! empty( $data ) && is_array( $data ) ) {

            $shipment = $data;

            $obj->awb = ! empty( $shipment['waybill'] )
                ? $shipment['waybill']
                : '';

            $obj->current_status = ! empty( $shipment['orderStatusDescription'] )
                ? Bt_Sync_Shipment_Tracking_Shipment_Model::convert_string_to_slug(
                    $shipment['orderStatusDescription']
                )
                : '';

            $obj->etd = ! empty( $shipment['courierPartnerEdd'] )
                ? $shipment['courierPartnerEdd']
                : '';

            $obj->scans = [];

            if (
                strtolower( $obj->current_status ) === 'delivered'
                && empty( $obj->delivery_date )
            ) {
                $obj->delivery_date = date( 'Y-m-d' );
            }
        }

        return $obj;
    }

}
