<?php
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;
if ( ! defined( 'ABSPATH' ) ) exit;
class Bt_Sync_Shipment_Tracking_Fship
{

    //Swagger URL – https://capi-qc.fship.in/swagger/index.html
    private $public_key;
    private const API_BASE_URL = "https://capi-qc.fship.in";
    private const API_GET_ALL_COURIER = "/api/getallcourier";
    private const API_RATE_CALCULATOR = "/api/ratecalculator";
    private const API_PINCODE_SERVICEABILITY = "/api/pincodeserviceability";
    private const API_SYNC_SHIPMENT_TRACKING = "/api/trackinghistory";
    private const API_PUSH_ORDER = "/api/createforwardorder_new";
    public function __construct()
    {
    }

    public function init_params()
    {
        $public_key = carbon_get_theme_option('bt_sst_fship_apitoken');
        $this->public_key = trim($public_key);
    }

public function test_fship()
{
    $this->init_params();

    if (!empty($this->public_key)) {

        $args = array(
            'timeout' => 20,
            'headers' => array(
                'signature' => $this->public_key,
            )
        );

        $url = self::API_BASE_URL . self::API_GET_ALL_COURIER;
        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            return false; // Request failed
        }

        $body = wp_remote_retrieve_body($response);
        $resp = json_decode($body, true);


        if (isset($resp) && is_array($resp) && count($resp) > 0) {
            return true;
        }
    }

    return false;
}



    public function get_rate_calculator(
        $payment_Mode,
        $source_Pincode,
        $destination_Pincode,
        $amount,
        $express_Type,
        $shipment_Weight,
        $shipment_Length,
        $shipment_Width,
        $shipment_Height,
        $volumetric_Weight
    ) {
        $this->init_params();

        if (empty($this->public_key)) {
            return array('error' => 'Authorization token is missing.');
        }

        $body = array(
            "source_Pincode" => $source_Pincode,
            "destination_Pincode" => $destination_Pincode,
            "payment_Mode" => $payment_Mode,
            "amount" => $amount,
            "express_Type" => $express_Type,
            "shipment_Weight" => $shipment_Weight,
            "shipment_Length" => $shipment_Length,
            "shipment_Width" => $shipment_Width,
            "shipment_Height" => $shipment_Height,
            "volumetric_Weight" => $volumetric_Weight
        );

        $args = array(
            'headers' => array(
                'signature' => $this->public_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($body),
            'timeout' => 20
        );

        $url = self::API_BASE_URL . self::API_RATE_CALCULATOR;

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            return array('error' => $response->get_error_message());
        }

        $status_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        $decoded_body = json_decode($response_body, true);

        return $decoded_body["shipment_rates"] ?? array(); // Return only the shipment rates

        // {
        //     "status": true,
        //     "response": "",
        //     "shipment_rates": [
        //         {
        //         "courier_name": "Surface Xpressbees",
        //         "shipping_charge": 23,
        //         "cod_charge": 20.35,
        //         "rto_charge": 20.5,
        //         "service_mode": "surface"
        //         }
        //     ]
        // }
    }

    public function get_pincode_serviceability($source_Pincode, $destination_Pincode)
    {
        $this->init_params();

        if (empty($this->public_key)) {
            return array('error' => 'Authorization token is missing.');
        }

        $body = array(
            "source_Pincode" => (string)$source_Pincode,
            "destination_Pincode" => (string)$destination_Pincode
        );

        $args = array(
            'headers' => array(
                'signature' => $this->public_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($body),
        );

        $url = self::API_BASE_URL . self::API_PINCODE_SERVICEABILITY;

        $response = wp_remote_post($url, $args);

        $response_body = wp_remote_retrieve_body($response);
        $decoded_body = json_decode($response_body, true);

        if ($decoded_body["status"] !== true) {
            return array('error' => 'Serviceability check failed: ' . $decoded_body["response"]);
        }

        return $decoded_body ?? array(); // Return only the serviceability info


    }

    public function push_order_on_fship($order_id)
    {
        $this->init_params();

        if (empty($this->public_key)) {
            return array('error' => 'Authorization token is missing.');
        }

        $order_data = $this->get_fship_order_object($order_id);
        $args = array(
            'headers' => array(
                'signature' => $this->public_key,
                'Content-Type' => 'application/json',
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

    private function get_fship_order_object($order_id)
    {
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
                "productDetailId" => (string) $item->get_id(),
                "productId" => (string) $product->get_id(),
                "productName" => $product->get_name(),
                "unitPrice" => round($item->get_total() / max(1, $item->get_quantity()), 2),
                "quantity" => (int) $item->get_quantity(),
                "productCategory" => $product->get_categories(),
                "hsnCode" => $product->get_meta('hsn_code'),
                "sku" => $product_sku,
                "taxRate" => (float) $item->get_total_tax(),
                "productDiscount" => (float) ($item->get_subtotal() - $item->get_total())
            ];
        }

        // Convert to appropriate units
        $weight_unit = get_option('woocommerce_weight_unit');
        $dimension_unit = get_option('woocommerce_dimension_unit');

        $total_weight = new Mass($total_weight, $weight_unit);
        $weight_kg = $total_weight->toUnit('kg');

        $length = new Length($total_length, $dimension_unit);
        $length_cm = $length->toUnit('cm');

        $width = new Length($total_width, $dimension_unit);
        $width_cm = $width->toUnit('cm');

        $height = new Length($total_height, $dimension_unit);
        $height_cm = $height->toUnit('cm');

        $payment_method = $order->get_payment_method();
        $cod_amount = ($payment_method === 'cod') ? $order->get_total() : 0;

        $pickup_id = (int) carbon_get_theme_option('bt_sst_fship_pincodepickup');
        $bt_sst_fship_pick_address_id = (int) carbon_get_theme_option('bt_sst_fship_pick_address_id');

        $order_data = [
            "customer_Name" => trim($billing['first_name'] . ' ' . $billing['last_name']),
            "customer_Mobile" => (int) $billing['phone'],
            "customer_Emailid" => $billing['email'],
            "customer_Address" => trim($shipping['address_1'] . ' ' . $shipping['address_2']),
            "landMark" => $shipping['address_2'],
            "customer_Address_Type" => "Home",
            "customer_PinCode" => $shipping['postcode'],
            "customer_City" => $shipping['city'],
            "orderId" => $order->get_order_number(),
            "invoice_Number" => $order->get_order_number(),
            "payment_Mode" => ($payment_method === 'cod') ? 1 : 0,
            // "express_Type" => "Standard",
            // "is_Ndd" => 0,
            "order_Amount" => (float) $order->get_subtotal(),
            "tax_Amount" => (float) $order->get_total_tax(),
            // "extra_Charges" => 0,
            "total_Amount" => (float) $order->get_total(),
            "cod_Amount" => (float) $cod_amount,
            "shipment_Weight" => $weight_kg > 0 ? $weight_kg : 0.1,
            "shipment_Length" => $length_cm > 0 ? $length_cm : 10,
            "shipment_Width" => $width_cm > 0 ? $width_cm : 8,
            "shipment_Height" => $height_cm > 0 ? $height_cm : 6,
            "volumetric_Weight" => 1.5,
            // "latitude" => 0,
            // "longitude" => 0,
            "pick_Address_ID" => $bt_sst_fship_pick_address_id,
            "return_Address_ID" => $bt_sst_fship_pick_address_id,
            "products" => $products,
            // "courierId" => 0,
            // "dropshipperOrderId" => "DROPSHIP-" . $order->get_order_number(),
            "isTaxIncluded" => true
        ];

        return $order_data;
    }

    public function get_order_tracking_by_awb_number($awb)
    {
        $this->init_params();

        if (empty($this->public_key)) {
            return array('error' => 'Authorization token is missing.');
        }
        $body = array(
            "waybill" => $awb,
        );

        $args = array(
            'headers' => array(
                'signature' => $this->public_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($body),
        );

        $url = self::API_BASE_URL . self::API_SYNC_SHIPMENT_TRACKING;

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
    public function update_order_shipment_status($order_id)
    {
        $resp = null;
        if (!empty($awb_number = Bt_Sync_Shipment_Tracking_Shipment_Model::get_awb_by_order_id($order_id))) {
            $resp = $this->get_order_tracking_by_awb_number($awb_number);
        }
        if (!empty($resp)) {
            $shipment_obj = $this->init_model($resp, $order_id);
            Bt_Sync_Shipment_Tracking_Shipment_Model::save_tracking($order_id, $shipment_obj);
            return $shipment_obj;
        }
        return null;
    }
    public function init_model($data, $order_id)
    {
        $obj = new Bt_Sync_Shipment_Tracking_Shipment_Model();
        $obj->order_id = $order_id;
        $obj->shipping_provider = 'fship';
        $obj->courier_name = 'Fship';

        if (sizeof($data) > 0) {
            $shipment = 'delhivery';
            $obj->awb = '143455210101006';
            $obj->current_status = 'in transit';
            $obj->etd = date('Y-m-d', strtotime('+2 days'));
            $obj->scans = [];

            if (strtolower($obj->current_status) == "delivered" && empty($obj->delivery_date)) {
                $obj->delivery_date = date('Y-m-d');
            }

        }
        return $obj;
    }
}