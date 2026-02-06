<?php 
if(isset($order_id)){
    $shipping_mode_is_manual_or_ship24 = carbon_get_theme_option( 'bt_sst_enabled_custom_shipping_mode' );
    // $shipping_mode_is_manual_or_ship24 = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_sst_custom_shipping_mode', true);

    $bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($order_id);
    $bt_shipping_provider = $bt_shipment_tracking->shipping_provider;
    //$bt_enable_ud_shipment_details = carbon_get_theme_option( 'bt_sst_manual_ud_shipment_details' );
    $edit = '';
    //if ($bt_enable_ud_shipment_details) {
        $get_awb_no = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta( $order_id, '_bt_shipping_awb', true );

        if ((!$bt_shipping_provider) || ($bt_shipping_provider == "manual" && $shipping_mode_is_manual_or_ship24 == "manual")) {
            if (isset( $_GET['action'] ) ) {
                $action = $_GET['action'];
                if ( $action != 'edit' ) {
                    $edit = "<br><a data-order-id='".$order_id."' class='show_st_popup' href='#'>Update Tracking</a>";
                }
            }else{
                $edit = "<br><a data-order-id='".$order_id."' class='show_st_popup' href='#'>Update Tracking</a>";
            }
        }else if($bt_shipping_provider == "manual" && $shipping_mode_is_manual_or_ship24=="ship24") {
            if($get_awb_no){
                $edit =  "<br><a class='bt_sync_order_tracking' data-order-id='$order_id' href='#'>Sync Now</a>";
            }else{
                $edit =  "<br><a class='' data-order-id='$order_id' id='bt_sst_sync_now_awb' href='#'>Add AWB No.</a>";
            }
        }else if($bt_shipping_provider == "shiprocket") {
            $edit =  "<br><a class='bt_sync_order_tracking' data-order-id='$order_id' href='#'>Sync Now</a>";
        }
        else if ($bt_shipping_provider == "shipmozo"){
            $edit =  "<br><a class='bt_sync_order_tracking' data-order-id='$order_id' href='#'>Sync Now</a>";
        }
        else if ($bt_shipping_provider == "nimbuspost_new"){
            $edit =  "<br><a class='bt_sync_order_tracking' data-order-id='$order_id' href='#'>Sync Now</a>";
        }else if ($bt_shipping_provider == "delhivery"){
            $edit =  "<br><a class='bt_sync_order_tracking' data-order-id='$order_id' href='#'>Sync Now</a>";
        }else if ($bt_shipping_provider == "fship"){
            $edit =  "<br><a class='bt_sync_order_tracking' data-order-id='$order_id' href='#'>Sync Now</a>";
        }
        else if ($bt_shipping_provider == "ekart"){
            $edit =  "<br><a class='bt_sync_order_tracking' data-order-id='$order_id' href='#'>Sync Now</a>";
        }
        else if ($bt_shipping_provider == "courierkaro"){
            $edit =  "<br><a class='bt_sync_order_tracking' data-order-id='$order_id' href='#'>Sync Now</a>";
        }
   // }
}
?>
<div>
    <?php
        if(!empty($bt_shipment_tracking) && $bt_shipment_tracking instanceof Bt_Sync_Shipment_Tracking_Shipment_Model &&!empty($bt_shipment_tracking->awb)){
            try{
                echo esc_html(bt_format_shipment_status($bt_shipment_tracking->current_status)) . "<br>" .
                "ETD: " . esc_html($bt_shipment_tracking->etd) . "<br>" .
                "Courier: " . esc_html($bt_shipment_tracking->courier_name) . "<br>" .
                "Awb: " . esc_html($bt_shipment_tracking->awb) . "<br>" .
                "<a target='_blank' href='" . esc_url($bt_shipment_tracking->get_tracking_link()) . "'>Track</a>" . $edit;
                

            }catch(Exception $e){
                echo '<small>NA</small>';
            }
        }					
        else {
            echo '<small>NA</small>' . $edit;
        }

    ?>
     
</div>