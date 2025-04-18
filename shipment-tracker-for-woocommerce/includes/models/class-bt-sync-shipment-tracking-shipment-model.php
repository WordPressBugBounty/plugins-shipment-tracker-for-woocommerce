<?php

class Bt_Sync_Shipment_Tracking_Shipment_Model{

    public $shipping_provider;
    public $order_id;
    public $awb;
    public $current_status;
    public $current_address;
    public $current_country;
    public $current_pincode;
    public $courier_name;
    public $etd;
    public $scans;
    public $tracking_url;
    public $delivery_date;

    public function __construct() {
    }

    public static function get_tracking_by_order_id($order_id) {
        $bt_shipment_tracking = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta( $order_id, '_bt_shipment_tracking', true );
        // echo "<pre>"; print_r($bt_shipment_tracking); die;

        if(!empty($bt_shipment_tracking) && $bt_shipment_tracking instanceof Bt_Sync_Shipment_Tracking_Shipment_Model){
            $bt_shipping_provider = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta( $order_id, '_bt_shipping_provider', true );
            $bt_shipping_awb_number = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta( $order_id, '_bt_shipping_awb', true );
            if(!empty($bt_shipping_awb_number)){
                $bt_shipment_tracking->awb = $bt_shipping_awb_number;//for backward compatibility
            }            
            $bt_shipment_tracking->shipping_provider = $bt_shipping_provider;           
            $bt_shipment_tracking->tracking_url = $bt_shipment_tracking->get_tracking_link();   
        }else{
            $bt_shipment_tracking = new Bt_Sync_Shipment_Tracking_Shipment_Model();
            $bt_shipping_provider = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta( $order_id, '_bt_shipping_provider', true );
            $bt_shipping_awb_number = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta( $order_id, '_bt_shipping_awb', true );
            $bt_shipment_tracking->shipping_provider = $bt_shipping_provider==false?"":$bt_shipping_provider;
            $bt_shipment_tracking->awb = $bt_shipping_awb_number==false?"":$bt_shipping_awb_number;
        }
        
        return $bt_shipment_tracking;
		   
    }

    public static function save_tracking($order_id,$shipment_obj) {

        if(empty($shipment_obj)){
            return;
        }
        //important to add as wc_email classes are not available before this call.
        $mailer = WC()->mailer();
        $bt_shipment_tracking_old = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($order_id);

        Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipment_tracking', $shipment_obj);
        Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipping_provider', $shipment_obj->shipping_provider );
        Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipping_awb', $shipment_obj->awb  );
        do_action( 'bt_shipment_status_changed',$order_id,$shipment_obj,$bt_shipment_tracking_old);      
    }

    public static function get_awb_by_order_id($order_id) {
        $bt_shipment_tracking_old = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($order_id);
		return $bt_shipment_tracking_old->awb;        
    }

    //returns array of order ids associated with an awb number.
    public static function get_orders_by_awb_number($awb){  
        $orderids = wc_get_orders( array(
            'limit'        => 50,//set max for performance
            'meta_key'     => '_bt_shipping_awb',
            'meta_value'     => $awb,
            'meta_compare' => '==',
            'return' => 'ids',
        ));        
        return $orderids;        
    }


    public function get_tracking_link($WebsiteOrCourier='courier'){
      

        if($WebsiteOrCourier=='courier'){
            //try to get it from current object since courier tracking urls are store in current object
            if(!empty($this->tracking_url)){
                $manual_tracking_url ="";
                if (strpos($this->tracking_url, "http") === 0) {
                    $manual_tracking_url = $this->tracking_url;
                } else{
                    $manual_tracking_url = 'https://' . $this->tracking_url;
                }
               // $manual_tracking_url = strtolower($manual_tracking_url); do not lower case, causes issue for some couriers.
               $manual_tracking_url = isset($manual_tracking_url) ? $manual_tracking_url : '';
               $awb = isset($this->awb) ? $this->awb : '';
               $order_id = isset($this->order_id) ? $this->order_id : '';
               
               //Uses arrays to replace all placeholders in a single str_ireplace() call.
               $search = array('#awb#', '#orderid#', '{awb}', '{orderid}');
               $replace = array($awb, $order_id, $awb, $order_id);
               
               $manual_tracking_url = str_ireplace($search, $replace, $manual_tracking_url);

               
                return $manual_tracking_url;
            }

            //if came this far, try to get it based on provider
            if($this->shipping_provider=="shyplite"){
                return "https://tracklite.in/track/" . $this->awb;
            }else if($this->shipping_provider=="shiprocket"){
                return "https://shiprocket.co/tracking/" . $this->awb; 
            }else if($this->shipping_provider=="nimbuspost"){
                return "https://ship.nimbuspost.com/shipping/tracking/" . $this->awb;
            }else if($this->shipping_provider=="nimbuspost_new"){
                return "https://ship.nimbuspost.com/shipping/tracking/" . $this->awb;
            }else if($this->shipping_provider === "manual"){  
                $manual_tracking_url = "#";
                $shipping_mode_is_manual_or_ship24 = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($this->order_id, '_bt_sst_custom_shipping_mode', true);
    
                if ($shipping_mode_is_manual_or_ship24 == "ship24") {
                    $tracking_page_id = get_option('_bt_sst_tracking_page');
                    $page_url = get_permalink($tracking_page_id);
                    if ($page_url) {
                        $manual_tracking_url = $page_url . '?order=' . $this->order_id;
                        return $manual_tracking_url;
                    } else {
                        return '#';
                    }
                }

                if(!empty($this->tracking_url)){
                    if (strpos($this->tracking_url, "http") === 0) {
                        $manual_tracking_url = $this->tracking_url;
                    } else{
                        $manual_tracking_url = 'https://' . $this->tracking_url;
                    }
                    $manual_tracking_url = str_ireplace('#awb#', $this->awb, $manual_tracking_url);

                }
                
                return $manual_tracking_url;
            }else if($this->shipping_provider=="xpressbees"){
                return "https://shipment.xpressbees.com/shipping/tracking/" . $this->awb;
            }else if($this->shipping_provider=="shipmozo"){
                return "https://app.shipmozo.com/track-order?awb=" . $this->awb;
            }else if($this->shipping_provider=="delhivery"){
                return "https://www.delhivery.com/track/package/" . $this->awb;
            }

        }


        //if came this far, try to get it from tracking page.
        $tracking_page_id = get_option( '_bt_sst_tracking_page' );
        if($tracking_page_id ){
            $link = get_permalink( $tracking_page_id );
            $separator = (strpos($link, '?') !== false) ? '&' : '?';
            return $link . $separator . 'order=' . $this->order_id;
        }

      return "#";


    }

    public static function convert_string_to_slug($text){
        if($text){
            $text = (string)$text;
            $text = strtolower($text);
            $text = trim($text);
            $text = preg_replace('/[\s_]+/', '-', $text);
            $text = preg_replace('/[^\w\-]/', '', $text);
            $text = preg_replace('/\-+/', '-', $text);
        }
        return $text;
    }
}
