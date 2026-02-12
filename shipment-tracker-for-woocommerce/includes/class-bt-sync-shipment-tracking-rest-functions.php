<?php


class Bt_Sync_Shipment_Tracking_Rest_Functions{

    private $shiprocket;
    private $shipmozo;
    private $shyplite;
    private $nimbuspost;
    private $nimbuspost_new;
    private $xpressbees;
    private $manual;
    private $ship24;
    private $ekart;
    private $courierkaro;
    private $delhivery;
    private $proship;

    public function __construct($shiprocket,$shyplite, $nimbuspost, $manual, $xpressbees, $shipmozo, $nimbuspost_new, $ship24,$ekart, $courierkaro, $delhivery, $proship)  {

        $this->shiprocket = $shiprocket;
        $this->shipmozo = $shipmozo;
        $this->shyplite = $shyplite;
        $this->nimbuspost = $nimbuspost;
        $this->nimbuspost_new = $nimbuspost_new;
        $this->xpressbees = $xpressbees;
        $this->manual = $manual;
        $this->ship24 = $ship24;
        $this->ekart = $ekart;
        $this->delhivery = $delhivery;
        $this->courierkaro = $courierkaro;
        $this->proship = $proship;
    }

    public function shiprocket_webhook_receiver($request){
        return $this->shiprocket->shiprocket_webhook_receiver($request);
    }
    public function webhook_receiver_apex_create_wc_order( $request ) {

        $logger = wc_get_logger();
        $context = [ 'source' => 'apex_log' ];

        try {
            $data = $request->get_json_params();

            if ( empty($data['order']) || empty($data['amount']) || empty($data['order']['phone']) ) {
                return new WP_REST_Response([
                    'success' => false,
                    'message' => 'Invalid payload'
                ], 400);
            }


            $phone = wc_sanitize_phone_number( $data['order']['phone'] );
            $phone_value = (string) $phone;
            $args = [
                'status'        => ['wc-pending'],
                'limit'         => 1,
                'billing_phone' => $phone,
                'date_created'  => '>' .  (time() - 30 ),
            ];

            $existing_orders = wc_get_orders( $args );

            if ( ! empty( $existing_orders ) ) {
                $order = $existing_orders[0];
                $logger->info('Existing Pending/Failed order #' . $order->get_id() . ' found for phone ' . $phone, $context);
            } else {
                $order = wc_create_order([
                    'status' => 'pending' 
                ]);

                if ( is_wp_error( $order ) ) {
                    throw new Exception( $order->get_error_message() );
                }

                $order->set_billing_first_name( $data['order']['name'] ?? '' );
                $order->set_billing_phone( $phone );
                $order->set_billing_email( $data['email'] ?? '' );
                $order->set_billing_address_1( $data['order']['address'] ?? '' );
                $order->set_billing_city( $data['order']['city'] ?? '' );
                $order->set_billing_state( $data['order']['state'] ?? '' );
                $order->set_billing_postcode( $data['order']['pincode'] ?? '' );
                $order->set_billing_country( 'IN' );

                $order->set_shipping_first_name( $data['order']['name'] ?? '' );
                $order->set_shipping_address_1( $data['order']['address'] ?? '' );
                $order->set_shipping_city( $data['order']['city'] ?? '' );
                $order->set_shipping_state( $data['order']['state'] ?? '' );
                $order->set_shipping_postcode( $data['order']['pincode'] ?? '' );
                $order->set_shipping_country( 'IN' );

                $order->set_payment_method( 'apex' );
                $order->set_payment_method_title( $data['payment mode'] ?? 'APEX' );
            }

            $item = new WC_Order_Item_Product();
            $item->set_name( $data['item name'] ?? 'APEX Order Item' );
            $item->set_quantity( 1 );
            $item->set_subtotal( (float) $data['amount'] );
            $item->set_total( (float) $data['amount'] );
            $item->set_tax_class('');
            $item->set_taxes([]);

            $order->add_item( $item );

            $order->update_meta_data( 'apex_order', 'true' );
            $order->update_meta_data( '_invoice_number', $data['invoice number'] ?? '' );

            $order->add_order_note(
                "APEX Webhook: Item added.\n" .
                "Item: {$data['item name']}\n" .
                "Amount: ₹{$data['amount']}\n" .
                "Invoice: {$data['invoice number']}"
            );

            $order->calculate_totals( false );
            $order->save();

            return new WP_REST_Response([
                'success' => true,
                'woocommerce_order_id' => $order->get_id(),
                'message' => 'Item successfully added to pending order'
            ], 200);

        } catch ( Exception $e ) {
            $logger->critical( 'APEX webhook error: ' . $e->getMessage(), $context );

            return new WP_REST_Response([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ship24_webhook_receiver($request){
        return $this->ship24->ship24_webhook_receiver($request);
    }
    public function ekart_webhook_receiver($request){
        return $this->ekart->ekart_webhook_receiver($request);
    }
    public function delhivery_webhook_receiver($request){
        return $this->delhivery->delhivery_webhook_receiver($request);
    }
    public function proship_webhook_receiver($request){
        return $this->proship->proship_webhook_receiver($request);
    }

    public function shipmozo_webhook_receiver($request){
        return $this->shipmozo->shipmozo_webhook_receiver($request);
    }

    public function shiprocket_get_postcode($request){
        $resp = array(
            "status"=>false,
            "message"=>"",
            "data"=>array()
        );

        return $resp;
    }

    public function rest_shyplite($request){
        //$ob = new Bt_Sync_Shipment_Tracking_Crons();

        //return $ob->sync_shyplite_shipments();
        //$resp= $this->shyplite->get_order_tracking("3591");
       // $resp= $this->shiprocket->get_order_tracking("4569");
        // if(sizeof($resp)>0){
        //     $shipment_obj = $this->shiprocket->init_model($resp[0]);
        //     //update_post_meta($order_id, '_bt_shipment_tracking', $shipment_obj);
        //     return $shipment_obj;
        // }

        //$copyright = carbon_get_theme_option( 'crb_text' );

        return "";// $this->get_orders();
    }

    public function nimbuspost_webhook_receiver($request){
        return $this->nimbuspost->nimbuspost_webhook_receiver($request);
    }

    public function nimbuspost_webhook_receiver_new($request){
        return $this->nimbuspost_new->nimbuspost_webhook_receiver($request);
    }

    public function xpressbees_webhook_receiver($request){
        return $this->xpressbees->xpressbees_webhook_receiver($request);
    }

    public function nimbuspost_get_postcode($request){
        $resp = array(
            "status"=>false,
            "message"=>"",
            "data"=>array()
        );

        return $resp;
    }

    public function manual_webhook_receiver($request){
        return $this->manual->manual_webhook_receiver($request);
    }

    public function courierkaro_webhook_receiver($request){
        return $this->courierkaro->courierkaro_webhook_receiver($request);
    }



}
