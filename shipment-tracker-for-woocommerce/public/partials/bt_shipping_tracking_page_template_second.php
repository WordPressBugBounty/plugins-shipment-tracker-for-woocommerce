<?php
wp_enqueue_style('bt-sync-shipment-tracking-result-page-template-second-css');

$pickup_pincode = ''; 
$currentPin = "";
$delivery_pincode = "";

$public_dir_url = plugin_dir_url(dirname(__FILE__));
$last_four_digit = carbon_get_theme_option('bt_sst_valid_phone_no');
if(is_user_logged_in() && isset($_GET["order"])){
    $last_four_digit=false;
}

$bt_sst_review_heading_text = carbon_get_theme_option('bt_sst_heading_text');
if(empty($bt_sst_review_heading_text)){
    $bt_sst_review_heading_text = "How did we do?";
}
$bt_sst_review_subheading_text = carbon_get_theme_option('bt_sst__subheading_text');
if(empty($bt_sst_review_subheading_text)){
    $bt_sst_review_subheading_text = "Rate your experience.";
}
$shipping_provider="";
if($tracking && isset($tracking['tracking_data'])){
    $shipping_provider = strtolower($tracking['tracking_data']['shipping_provider']);
}                            
?>
<div class="snipcss0-0-0-1 snipcss-GDYrP obscure-kzaezq7Gp">
    
    <div class="fl-node-content snipcss0-1-1-2 obscure-MMkaMW9Lx obscure-JkAnk9l33" style="">
    <?php 
      if ($auto_post) {
        echo "<div style='width: 100%;'>
        <div id='bt_loader_div' class='loader' style='text-align: center; margin: auto;'></div>
        </div>";
      }
    ?>
    <?php 
      
        if ($the_order==false){
            $bt_sst_selected_tracking_template = carbon_get_theme_option('bt_sst_selected_tracking_template');
            if($bt_sst_selected_tracking_template=="classic_template" || !$is_premium){
                require_once plugin_dir_path(dirname(__FILE__)) . 'partials/bt_shipping_tracking_page_primary_template_first.php';
            }else if($bt_sst_selected_tracking_template=="prime_template"){ 
                require_once plugin_dir_path(dirname(__FILE__)) . 'partials/bt_shipping_tracking_page_primary_template_second.php';
            }
      
        } else if ($the_order==false && !empty($bt_track_order_id))
        {
            echo "<p style='text-align: center;'>". $message ."</p>";
        } 
        else if (isset($the_order) && $the_order instanceof WC_Order)
        {
            
            $name = $the_order->get_billing_first_name() ." ". $the_order->get_billing_last_name() ;
            $order_status = $the_order->get_status();      
            $order_status_name = wc_get_order_status_name( $order_status);     
            $order_number = $the_order->get_order_number();      
            $ordering_date = $the_order->get_date_created()->date(get_option('date_format'));
            $ordering_time = $the_order->get_date_created()->date(get_option('time_format'));
            $order_payment_method = $the_order->get_payment_method();
            $order_total = $the_order->get_formatted_order_total();
            $order_sjipping_method = $the_order->get_shipping_method();
            $order_delivery_address= $the_order->get_shipping_city();
            if(!$order_delivery_address){
                $order_delivery_address= $the_order->get_billing_city();
            }
            $payment_method_name = $order_payment_method; 
            $payment_gateways   = WC_Payment_Gateways::instance()->payment_gateways();
            if(isset($payment_gateways[$order_payment_method])){
                $payment_method_name = $payment_gateways[$order_payment_method]->get_title();
            }
            $order_placed_message = "We've received your order on </strong> $ordering_date at $ordering_time.";
            $estimated_delivery_date = 'NA';
            $courier_name = 'NA';
            $awb_number = 'NA';
            $shipment_status = "NA";
            $shipped_string = "Shipped";
            $shipped_message = "Your package is on its way & will reach you soon.";
            $show_delivery_states = true;
            $current_step = 2; //orderplaced=1, shipped=2, outfordelivery=3, delivered=4
            $currentPin = "";
            $currentCountry = "";

            $delivery_status = "";
            if(!empty($tracking['tracking_data']['awb']) && $order_status!='cancelled' && $order_status!='on-hold' && $order_status!='pending' && $order_status!='refunded' && $order_status!='failed' && $order_status!='checkout-draft'){
                $awb_number = $tracking['tracking_data']['awb'];
                $estimated_delivery_date = $tracking['tracking_data']['etd'];
                $shipment_status = $tracking['tracking_data']['current_status'];
                $courier_name = $tracking['tracking_data']['courier_name'];
                
        
                if (strtolower($shipment_status) != 'delivered') {
                    $delivery_status = "Arriving ";
                }else {
                    $delivery_status = " Delivered On ";
                }

                if ($estimated_delivery_date && !$estimated_delivery_date instanceof DateTime) {
                    $estimated_delivery_date = new DateTime($estimated_delivery_date);
                }

                $days_remaining ="";
                $timezone = get_option('timezone_string');

                if (!$timezone instanceof DateTimeZone) {
                    $timezone = new DateTimeZone('Asia/Kolkata');
                }

                if ($estimated_delivery_date && !$estimated_delivery_date instanceof DateTime) {
                    $estimated_delivery_date = new DateTime($estimated_delivery_date, $timezone);
                }
                $days_remaining=null;
                if($estimated_delivery_date){
                    $current_date = new DateTime('now', $timezone);
                    $interval = $current_date->diff($estimated_delivery_date);
                    $days_remaining = $interval->days;
                    if ($interval->invert) {
                        $days_remaining = null;
                    }
                    $date_format = get_option('date_format');

                    $estimated_delivery_date = $estimated_delivery_date->format($date_format);

                    if($days_remaining>0){
                        if($days_remaining==1){
                            $days_remaining = "in ".$days_remaining." day"; 
                        }else{
                            $days_remaining = "in ".$days_remaining." days"; 
                        }
                    }else if($days_remaining===0){
                        $days_remaining = " Today ";
                    }
                    
                    if ($delivery_status !="" && $shipment_status != "canceled"):    
                        $estimated_delivery_date = $delivery_status." ".$days_remaining." (".$estimated_delivery_date.")"; 
                    endif;
                }


                

                if(stripos($shipment_status,'delivered')!==false && stripos($shipment_status,'rto')===false){
                    $current_step = 4; 
                } else if(stripos($shipment_status,'out')!==false && stripos($shipment_status,'rto')===false && stripos($shipment_status,'pick')===false){
                    $current_step = 3; 
                } else if(stripos($shipment_status,'rto')!==false || stripos($shipment_status,'cancel')!==false || stripos($shipment_status,'lost')!==false || stripos($shipment_status,'dispose')!==false){
                    $show_delivery_states = false;
                    $shipped_string = $shipment_status;
                    $shipped_message = "Oops, there will be no further movement of your package.";
                } else if(stripos($shipment_status,'transit')!==false){
                    
                    //shipment is either delivered or out for delivery
                    $shipped_string = bt_format_shipment_status($shipment_status);
                    if(empty($shipped_string)){
                        $shipped_string = "Shipped";
                    }
                    $shipped_message = "Your package is on its way & will reach you soon.";
                } else{
                    $shipped_string = bt_format_shipment_status($shipment_status);
                    $shipped_message = apply_filters( 'bt_sst_shipping_status_message', "Your order has been " . $shipped_string, $shipment_status );

                    if(empty($shipped_string)){
                        $shipped_string = "Shipping Soon";
                        $shipped_message = "Your package will be shipped soon, check back later.";
                    }
                }
               

            }else{
                if($order_status =='cancelled' || $order_status=='on-hold' || $order_status=='pending' || $order_status=='refunded' || $order_status=='failed' || $order_status=='checkout-draft'){
                    //order is not yet in processing or any other equivalent state
                    $show_delivery_states = false;
                    $shipped_string = $order_status_name;
                    $shipped_message = "Your order is in " . $order_status_name . ' state.';
                }else{
                    //order is in processing or any other equivalent state, but not yet shipped or tracking data not available yet
                    $shipped_string = "Shipping soon";
                    $shipped_message = "Your package will be shipped soon, check back later.";
                }
                
            }
            // $current_url = get_permalink(); // Get the current page URL in WordPress
            $current_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $order_total_whatsapp = html_entity_decode($order_total);

            $whatsapp_url = 'Hello!' ."\n".
                            'Here’s an update on your order:' ."\n".
                            'Delivery To: ' . $order_delivery_address . "\n" .
                            'Courier Name: ' . $courier_name . "\n" .
                            'AWB NO: ' . $awb_number . "\n" .
                            'Shipment status: ' . $shipment_status . "\n" .
                            'Order No: ' . $order_number . "\n" .
                            'Order Total: ' . wp_strip_all_tags($order_total_whatsapp) . "\n" .
                            'Order Placed: ' . $ordering_date . ' at ' . $ordering_time . "\n" .
                            'Remark: ' . $shipped_message . "\n" ;
            if($order_status !='cancelled' || $order_status!='refunded' || $order_status!='failed' ){
            $whatsapp_url .= $estimated_delivery_date . "\n";
            }
            if($estimated_delivery_date=="NA" || !$estimated_delivery_date){
                if(stripos($shipment_status,'delivered')!==false && stripos($shipment_status,'rto')===false){
                    $estimated_delivery_date = "Delivered";
                }else{
                    $estimated_delivery_date = "Arriving Soon";
                }
            }
            $delivery_pincode = $the_order->get_shipping_postcode();
                                                // $delivery_pincode = "";
                                                $billing_pincode = $the_order->get_billing_postcode();
                                                // $billing_pincode = "";
                                                $delivery_country = $the_order->get_shipping_country();
                                                $billing_country = $the_order->get_billing_country();
                                                $pickup_pincode = WC()->countries->get_base_postcode() ;
                                                // $pickup_pincode = "";
                                                $base_country = WC()->countries->get_base_country() ;

                                                if($billing_pincode && !$delivery_pincode){
                                                    $delivery_country = $billing_country;
                                                    $delivery_pincode = $billing_pincode;
                                                }else if(!$delivery_pincode && $pickup_pincode){
                                                    $delivery_country = $base_country;
                                                    $delivery_pincode = $pickup_pincode;
                                                }
                                                if(!$pickup_pincode){
                                                    $pickup_pincode = $delivery_pincode;
                                                }
                            
            $whatsapp_url .= 'Track Your Shipment: ' . $current_url . "\n" .
                            'Thank you for shopping with us! If you have any questions, feel free to reach out.' ;
        ?>
        <div class="bt_sst_tmp_mster_progressbar_container" style="">
            <div class="fl-row-content fl-row-fixed-width fl-node-content">
                <div class="fl-col-group fl-node-h34zioj6ygep" data-node="h34zioj6ygep">
                    <div class="" data-node="pt7e19gljyuh" id="style-IBgKo">
                        <div class="fl-col-content fl-node-content ui-sortable">
                            <div class="fl-module fl-module-heading fl-node-0z7h1ugnboap" data-node="0z7h1ugnboap" data-parent="pt7e19gljyuh" data-type="heading" data-name="Heading">
                                <div class="fl-module-content fl-node-content" >
                                    <div class="fl-heading">
                                        <h1 class="bt-sst-edd-heading" style="margin:0"><?php echo esc_html($estimated_delivery_date); ?></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fl-col-group fl-node-huo20iqjn8rc" data-node="huo20iqjn8rc" style="display:flex; align-items: center;">
                    <div class="fl-col fl-node-fi95nkgz4uvt fl-col-small style-QHQxh" data-node="fi95nkgz4uvt" id="style-QHQxh">
                        <div class="fl-col-content fl-node-content ui-sortable">
                            <div class="fl-module fl-module-photo fl-node-j7vr109hbw3x" data-node="j7vr109hbw3x" data-parent="fi95nkgz4uvt" data-type="photo" data-name="Photo">
                                <div class="">
                                    <div class="" itemscope="" itemtype="https://schema.org/ImageObject">
                                        <div class="fl-photo-content fl-photo-img-png">
                                        <div style="display:flex; align-items: center;">
                                                <?php
                                                    $i=1;
                                                    foreach ( $the_order->get_items() as $item_id => $item ) {
                                                        $product = $item->get_product();
                                                        if ( $product ) {
                                                            $image_id = $product->get_image_id();
                                                            $image_url = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
                                                            if($i<4){
                                                                echo '<img style="height:77px; margin:5px;" src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $product->get_name() ) . '" />';
                                                            }else if($i==4){
                                                                echo "<pMore</p>";
                                                            }
                                                            $i++;
                                                        }
                                                    }
                                                
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fl-drop-target fl-col-drop-target ui-sortable" style=""></div>
                        <div class="fl-drop-target fl-drop-target-last fl-col-drop-target fl-col-drop-target-last ui-sortable" style=""></div>
                    </div>
                    <div class="fl-drop-target fl-col-group-drop-target ui-sortable" style=""></div>
                    <div class="fl-drop-target fl-drop-target-last fl-col-group-drop-target fl-col-group-drop-target-last ui-sortable" style=""></div>
                </div>
                <?php 
                    $map_dive_height="";
                    $bt_sst_navigation_map = carbon_get_theme_option('bt_sst_navigation_map');
                    if($bt_sst_navigation_map != 'yes'){
                        $map_dive_height = 'height: 100px';
                    }
                ?>
                <div class="fl-col-group fl-node-la6f3q7nhr48" data-node="la6f3q7nhr48">
                    <div class="fl-col fl-node-udyvigx8l6kb style-choB9" data-node="udyvigx8l6kb" id="style-choB9" style="<?php echo $map_dive_height ?>">
                        <div class="fl-col-content fl-node-content ui-sortable">
                            <div class="fl-module fl-module-heading fl-node-kyl50ju6rfct" data-node="kyl50ju6rfct" data-parent="udyvigx8l6kb" data-type="heading" data-name="Heading">
                                <div class="fl-module-content fl-node-content">
                                    <div class="">
                                        <?php
                                       
                                        if($is_premium && $bt_sst_navigation_map == 'yes'){
                                            // var_dump($delivery_pincode); die;
                                            if($delivery_pincode || $pickup_pincode){
                                            ?>
                                            <script>
                                                        const dropoffPin = '<?php echo esc_js($delivery_pincode); ?>';
                                                        const estimatedDate = '<?php echo esc_js($the_order->get_billing_city() . " " . $the_order->get_billing_state() . " " . $the_order->get_billing_postcode()); ?>';
                                                        const deliveryCountry = '<?php echo esc_js($delivery_country); ?>';
                                                        const pickupPin = '<?php echo esc_js($pickup_pincode); ?>';
                                                </script>
                                            <div class="">
                                                <div id="bt_sst_leaflet_map_location" class="bt_sst_leaflet_map_location_shipment" style="width:100%; border:unset;"></div>
                                            </div>
                                        <?php }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fl-drop-target fl-col-drop-target ui-sortable" style=""></div>
                        <div class="fl-drop-target fl-drop-target-last fl-col-drop-target fl-col-drop-target-last ui-sortable" style=""></div>
                    </div>
                    <div class="fl-drop-target fl-col-group-drop-target ui-sortable" style=""></div>
                    <div class="fl-drop-target fl-drop-target-last fl-col-group-drop-target fl-col-group-drop-target-last ui-sortable" style=""></div>
                </div>
                <div class="fl-col-group fl-node-2abpnsxkt394" data-node="2abpnsxkt394">
                    <div class="fl-col fl-node-gt9f8nkh6wuq style-ixo3P" data-node="gt9f8nkh6wuq" id="style-ixo3P">
                        <div class="fl-col-content fl-node-content ui-sortable">
                            <div class="fl-module fl-module-uabb-timeline fl-node-nyo8u0s1p4eh" data-node="nyo8u0s1p4eh" data-parent="gt9f8nkh6wuq" data-type="uabb-timeline" data-name="Advanced Timeline">
                                <div class="fl-module-content fl-node-content">
                                    <div style="text-align:center; font-size:20px">
                                        <div class="bt-sst-shipment-status-text">
                                            <?php    
                                                if($current_step==1){
                                                    echo "Order Placed";
                                                }else if($current_step==2){
                                                    echo  ucfirst($shipped_string);
                                                }else if($current_step==3){
                                                    echo "Out For Delivery";
                                                }else if($current_step==4){
                                                    echo "Delivered";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div style="margin: 28px 0;" class="uabb-timeline-horizontal uabb-timeline--center uabb-timeline-arrow-center uabb-timeline-wrapper uabb-timeline-node">
                                        <div class="uabb-timeline-connector slick-initialized slick-slider">
                                            <div aria-live="polite" class="slick-list draggable">
                                                <div class="slick-track style-reSfY" id="style-reSfY">
                                                    <div class="uabb-timeline-item-0 bt-sst-slick-slide slick-current slick-active style-bBNl2" data-slick-index="0" aria-hidden="false" id="style-bBNl2">
                                                        <div class="uabb-timeline-marker-wrapper">
                                                            <div class="uabb-timeline-card-date-wrapper">
                                                                <div class="uabb-timeline-card-date"> Order Placed </div>
                                                            </div>
                                                            <div class="uabb-timeline-marker">
                                                                    <i class="<?php if($current_step >0) { echo "bt_sst_selected"; }else{ echo "bt_sst_not_selected";} ?>" aria-hidden="true"><img style="display:inline" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAgUlEQVR4nO2UwQqAIBBE/Ymi/v9LuhRhpzr0Hxm7Bwc2vElYdFhBwgHxoDxnh0FjqqqKkRNpGRgZGLLACdgYEAZmVfgh0hCwBjh5v58iXYWbsmNhYGJgCc24n8VtCXvqzpcHbArgNOAPEfTqmd/dkobzt0k4R1ui/8WGpea86n+6APYsDy07b57jAAAAAElFTkSuQmCC"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="uabb-timeline-item-1 bt-sst-slick-slide slick-active style-KCZZz" data-slick-index="1" aria-hidden="false" id="style-KCZZz">
                                                        <div class="uabb-timeline-marker-wrapper">
                                                            <div class="uabb-timeline-card-date-wrapper">
                                                                <div class="uabb-timeline-card-date"><?php echo ucfirst(esc_html($shipped_string)) ?></div>
                                                            </div>
                                                            <div class="uabb-timeline-marker">
                                                            <?php if ($order_status == "cancelled" || $order_status == "canceled" ||  $order_status == "refunded" || $order_status == "failed") {
                                                                    ?><i class="<?php if($current_step >1) { echo "bt_sst_selected_cancelled"; } ?>" aria-hidden="true"><img  style="display:inline" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAADDUlEQVR4nO1Zy2oUQRRtfC1c+lj5+ABXQs+9TlYDVW1w4XZQdOnadWICBg1ksjdhIn6CqAsTQQX/QdEPMLoyiWiqZuhxHiW3cGZ0ppOu6q7uHmEuFAx00XNO1X2ce9vzpja1qaU2Va0ebXKYkQwXJMNnksMnyeG74PiLFv2WHD/qZwwXGgGW1ZJ3xCvamrOlC4LjquDwVXJUNktw+CIY1hoVPJ878P1rl88KBo8Fw5Yt8DEiDFuCY32/4p/JBbwI4JZksJcW+NhiuCvYlZuZAVe+f1xwfOIcOB9zrQ36L7fgr/snBcdXWYOXQxJb9J8uTz438HLoUm9V9dKJ1ATycBt54E1gPSV4uF0UeDkgUbqRCPxPDqclx52iCUgGe4lSLOX5wsHzQTysW4Gn6mhVpIKyat69YwxI7w3K5m7EsNUM/Ivmp89x1QZ8+/WmUt2uCmtLsfvDh/eU6nRU+90bJWdnbEjUjMCTyCKdYgW+bzEk+uD71rYgQXqLRGMsAa0qLVzhb0DaOh0VLi+Og19ejNzbtHC9BvMxloCWxBYBFj6YHwc2chOjJz/Ys3LfLpgDmDfx/+dWL40h4Qw8pwVP42+AGg/rFx9AotfVYN2AR6oJHwxcKLlUjiThCjzXayfehVI2KZrE6Knr2+ilBa8EwzB7AuTzkQTM6oRMSyCVC0UFbERgZ+pCMmkQR4Hv9aKDOCkJZhDEidLoIanSpE5Ip2nUtpAZ5HlnJBjMxRKgodPESgleAjMxx3A7sZg7JFWO3kSbxNxVM1ktGH42nuaRdE0kpw3yfJ+EDXipCcCKEfgJbWjCBiufMyagb4Fj3SrAMl3wyLO1H7PlU5PR1ONu4rkpzSqLJiAYVhOBH5DgsFGg66x5aY36UMHhRf4nD5uqUjmWmsBwuAtbObrNS2fD3ZEhbw6ZCdacnXyU0awyk+zE8FvqgLWamzJcpwLjwF1CyvOUtr28jaojyQ5j7fQv8G2SB9YVNgsjkUVDJ5rbkGanxoM6O/0BjySJ7vLg/Z9nc6QqJ+Iz69Sm5v3/9htwCyTCs1agAgAAAABJRU5ErkJggg=="></i>
                                                                <?php } else {?>
                                                                    <i class="<?php if($current_step >1) { echo "bt_sst_selected"; }else{ echo "bt_sst_not_selected";} ?>" aria-hidden="true"><img  style="display:inline" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAgUlEQVR4nO2UwQqAIBBE/Ymi/v9LuhRhpzr0Hxm7Bwc2vElYdFhBwgHxoDxnh0FjqqqKkRNpGRgZGLLACdgYEAZmVfgh0hCwBjh5v58iXYWbsmNhYGJgCc24n8VtCXvqzpcHbArgNOAPEfTqmd/dkobzt0k4R1ui/8WGpea86n+6APYsDy07b57jAAAAAElFTkSuQmCC"></i>
                                                                <?php }
                                                            ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="uabb-timeline-item-2 bt-sst-slick-slide slick-active style-l3WRx" data-slick-index="2" aria-hidden="false" id="style-l3WRx">
                                                        <div class="uabb-timeline-marker-wrapper">
                                                            <div class="uabb-timeline-card-date-wrapper">
                                                                <div class="uabb-timeline-card-date">Out for delivery</div>
                                                            </div>
                                                            <div class="uabb-timeline-marker">
                                                                    <i class="<?php if($current_step >2) { echo "bt_sst_selected"; }else{ echo "bt_sst_not_selected";} ?>" aria-hidden="true"><img style="display:inline"  src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAgUlEQVR4nO2UwQqAIBBE/Ymi/v9LuhRhpzr0Hxm7Bwc2vElYdFhBwgHxoDxnh0FjqqqKkRNpGRgZGLLACdgYEAZmVfgh0hCwBjh5v58iXYWbsmNhYGJgCc24n8VtCXvqzpcHbArgNOAPEfTqmd/dkobzt0k4R1ui/8WGpea86n+6APYsDy07b57jAAAAAElFTkSuQmCC"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="uabb-timeline-item-3 bt-sst-slick-slide slick-active style-iMPqq" data-slick-index="3" aria-hidden="false" id="style-iMPqq">
                                                        <div class="uabb-timeline-marker-wrapper">
                                                            <div class="uabb-timeline-card-date-wrapper">
                                                                <div class="uabb-timeline-card-date">Delivered</div>
                                                            </div>
                                                            <div class="uabb-timeline-marker">
                                                                    <i class="<?php if($current_step >3) { echo "bt_sst_selected"; }else{ echo "bt_sst_not_selected";} ?>" aria-hidden="true"><img style="display:inline" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAgUlEQVR4nO2UwQqAIBBE/Ymi/v9LuhRhpzr0Hxm7Bwc2vElYdFhBwgHxoDxnh0FjqqqKkRNpGRgZGLLACdgYEAZmVfgh0hCwBjh5v58iXYWbsmNhYGJgCc24n8VtCXvqzpcHbArgNOAPEfTqmd/dkobzt0k4R1ui/8WGpea86n+6APYsDy07b57jAAAAAElFTkSuQmCC"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uabb-timeline-main uabb-timeline-responsive-tablet ">
                                            <div class="uabb-days slick-initialized slick-slider">
                                                <div aria-live="polite" class="slick-list draggable">
                                                    <div class="slick-track style-wOjsN" id="style-wOjsN"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display:flex; width:100%">
                                        <div style="padding:5px; width:50%; border: 1px solid #e9e9e9; margin: 5px 15px; border-radius:10px; text-align:center;">
                                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAB+0lEQVR4nO2YOUsDQRiGHzAmWphYiGjAQksre8XSxsKjyl/wwtouXRpttLPS1s4fYOcNHo1XHVQ8SgVBdGXgi6whGXZ39sjKPPBB2G923n13vjk2YLFYLBaLGU7M8QJsAJ1pN+JIrJNyJsTIM/8ARyKSTh3DsEbSjBNFaSVp5NFDyT4BO8Cw106jnhemmu/AdKsaKWlyNYaAXbn2AYzTQuhehNMg1wZsy/UHoJeUGlFkgH3J7Ym5P8RVTo00/eYGgFfJr6TZiGIS+AY+gTESxsSIYk3aVIEeUmykHTiWdlv1N0ZdTl4f1vGoOSh7yxcwkmYjilVpu0lC6B62qtks6xmVtne0oJFygJVUrWCRlY8OnWZGzFT9bgutZsQv1kgY2BExLa0OYBk4lU1IxQmwBORMxEPQcrwaKQKXmtXiAugPyUgxgJYnIzlXx1fAFNAloT47byR3DmQNjQTV8mRkSXLXQKFBvuASmDc0ElTrt697+aG2+3oOJafeTjNmpM2BoZEgWiXXcZ6Kh11TDW8z8gGOFGFrlZF6q7hGxm/nhRiNFOraVsWEOtZoqQ237v+k2QClFbvWotx402QCdssRWrWZ89t5nFpZWbvVzbcy2fISs66Oz3wuv4loFV0CjUKt632GJmLTysnQqw/+N4kjYCGEkUhSy2KxkDJ+ANIID7KqwJZkAAAAAElFTkSuQmCC" width="25px"><div class="courier_name" style=""><?php echo esc_html($courier_name) ?></div>
                                        </div>
                                        <div style="padding:5px; width:50%; border: 1px solid #e9e9e9; margin: 5px 15px; border-radius:10px; text-align:center;">
                                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAABiklEQVR4nMWVsUrEQBCGY6do5zMoB76Dd4JYauUj2OQssv+/geuCrTa+hFernShY2V9lY+MJIjZXCFZHUIabQNzkNskZceDnZi+z8+1mZkgQ/LcZY3ZIDgHcenQJoLNQcgApgLEn+YTkF4C3xhA9+ThJkuV5MQDuST4poBlENovqxADYBPAK4D2Koq02ATckH9TvNLpJTcAJgCmAvrV2l+SxrqshAK5FvhiSqyTvpNCuqvYGg8FgXRRU25LUQG6QieSo6va/MtR4ve0CjE6sqpf9b609EpWt5Tc/dCTPwjBcKwByE/usU5tmEDgnya/VnyhA/KlACvt0Yj8B7FtrD9S/yAfGcbwhcgFSTC3sHskXklcFgAa6LZZP8igTKlI//8xtT84DjMpaDJoEwIeoBF7vBr73zFnSQ5ELcGsA4LSQ09cpAPok4+yZ+CTDsi6S5DLZpW3atqEOgOS56C8Bw6oPTpkZY1ak4Fmr+wA9HToJ9n2Tf0jjU2NMt85punKSJgCJJ7nd5NYL2zenX/kTY8xhFQAAAABJRU5ErkJggg==" width="25px"><div class="awb_number" style=""><?php echo esc_html($awb_number) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fl-drop-target fl-col-drop-target ui-sortable" style=""></div>
                        <div class="fl-drop-target fl-drop-target-last fl-col-drop-target fl-col-drop-target-last ui-sortable" style=""></div>
                    </div>
                    <div class="fl-drop-target fl-col-group-drop-target ui-sortable" style=""></div>
                    <div class="fl-drop-target fl-drop-target-last fl-col-group-drop-target fl-col-group-drop-target-last ui-sortable" style=""></div>
                </div>
                <div class="fl-col-group fl-node-ik86evf7ojdb" data-node="ik86evf7ojdb">
                    <div class="fl-col fl-node-l0va2due3kcy style-tbbFm" data-node="l0va2due3kcy" id="style-tbbFm">
                        <div class="fl-col-content fl-node-content ui-sortable">
                                        <div>
                                            
                                            <?php if($shipping_provider=="delhivery" && isset($tracking['tracking_data']['scans']) && sizeof($tracking['tracking_data']['scans'])>0){ ?>
                                                <div class="bt_sst_tracking_product_trackong">
                                                <input type="checkbox" id="toggle" class="bt_sst_toggle-checkbox">
                                                <label for="toggle" class="bt_sst_toggle-label">Show More</label>
                        
                                                <div class="bt_sst_toggle-content">
                                                    <table>
                                                        <tr>
                                                            <th>Date:</th>
                                                            <th>Scan:</th>
                                                            <th>Scanned Location:</th>
                                                        </tr>
                                                            <?php foreach ($tracking['tracking_data']['scans'] as $scan) : 
                                                            $dateandtime_obj = new DateTime($scan['ScanDetail']['ScanDateTime'])
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $dateandtime_obj->format('F j Y') ?></td>
                                                                <td><?php echo htmlspecialchars($scan['ScanDetail']['Scan']); ?></td>
                                                                <td><?php echo htmlspecialchars($scan['ScanDetail']['ScannedLocation']); ?></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </table>
                                                    </div>
                                                </div>

                                            <?php }else if($shipping_provider=="shiprocket" && isset($tracking['tracking_data']['scans']) && sizeof($tracking['tracking_data']['scans'])>0){ ?>
                                                <div class="bt_sst_tracking_product_trackong">
                                                <input type="checkbox" id="toggle" class="bt_sst_toggle-checkbox">
                                                <label for="toggle" class="bt_sst_toggle-label">Show More</label>
                        
                                                <div class="bt_sst_toggle-content">
                                                    <table>
                                                        <tr>
                                                            <th>Date:</th>
                                                            <th>Status:</th>
                                                            <th>Location:</th>
                                                        </tr>
                                                            <?php foreach ($tracking['tracking_data']['scans'] as $scan) : 
                                                            $dateandtime_obj = new DateTime($scan['date'])
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $dateandtime_obj->format('F j Y') ?></td>
                                                                <td><?php echo htmlspecialchars($scan['sr-status-label']); ?></td>
                                                                <td><?php echo htmlspecialchars($scan['location']); ?></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php }else if($shipping_provider=="nimbuspost" && isset($tracking['tracking_data']['scans']) && sizeof($tracking['tracking_data']['scans'])>0){ ?>
                                                <div class="bt_sst_tracking_product_trackong">
                                                <input type="checkbox" id="toggle" class="bt_sst_toggle-checkbox">
                                                <label for="toggle" class="bt_sst_toggle-label">Show More</label>
                        
                                                <div class="bt_sst_toggle-content">
                                                    <table>
                                                        <tr>
                                                            
                                                            <th>Status:</th>
                                                            <th>Location:</th>
                                                        </tr>
                                                            <?php foreach ($tracking['tracking_data']['scans'] as $scan) : 
                                                            $dateandtime_obj = new DateTime($scan['date'])
                                                            ?>
                                                            <tr>
                                                                
                                                                <td><?php echo htmlspecialchars($scan['message']); ?></td>
                                                                <td><?php echo htmlspecialchars($scan['location']); ?></td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php }?>
                                        </div>
                        </div>
                        <div class="fl-drop-target fl-col-drop-target ui-sortable" style=""></div>
                        <div class="fl-drop-target fl-drop-target-last fl-col-drop-target fl-col-drop-target-last ui-sortable" style=""></div>
                    </div>
                    <div class="fl-drop-target fl-col-group-drop-target ui-sortable" style=""></div>
                    <div class="fl-drop-target fl-drop-target-last fl-col-group-drop-target fl-col-group-drop-target-last ui-sortable" style=""></div>
                </div>
                <div class="fl-col-group fl-node-6pot3hxjwag4" data-node="6pot3hxjwag4">
                    <div class="fl-col fl-node-p1khazx03f9c style-38So3" data-node="p1khazx03f9c" id="style-38So3">
                        <div class="fl-col-content fl-node-content ui-sortable">
                            <div class="fl-module fl-module-heading fl-node-y2qxf7tea6o5" data-node="y2qxf7tea6o5" data-parent="p1khazx03f9c" data-type="heading" data-name="Heading">
                                <div class="">
                                    <div class="fl-heading bt_sst_rating_bar_container">
                                        <?php if($is_premium && carbon_get_theme_option('bt_sst_enable_rating')=='yes'): ?>
                                            <div class="bt_sst_tracking_product_rating">
                                                <div class="bt_sst_tracking_product_rating_text">
                                                    <strong class="bt_sst_tracking_product_rating_text_heading"><?php echo esc_html($bt_sst_review_heading_text); ?></strong>
                                                    <p class="bt_sst_tracking_product_rating_text_subheading"><?php echo esc_html($bt_sst_review_subheading_text); ?></p>
                                                </div>
                                                <div class="bt_sst_tracking_product_rating_btns">
                                                    <a style="margin:7px; text-decoration: none;" target="_blank" href="<?php echo esc_url(carbon_get_theme_option('bt_sst_rating_page_url')); ?>" class="bt_sst_tracking_rating_url_btn">
                                                    😈
                                                    </a>
                                                    <a style="margin:7px; text-decoration: none;" target="_blank" href="<?php echo esc_url(carbon_get_theme_option('bt_sst_rating_page_url')); ?>" class="bt_sst_tracking_rating_url_btn">
                                                    😐
                                                    </a>
                                                    <a style="margin:7px; text-decoration: none;" target="_blank" href="<?php echo esc_url(carbon_get_theme_option('bt_sst_rating_page_url')); ?>" class="bt_sst_tracking_rating_url_btn">
                                                    😌
                                                    </a>
                                                    <a style="margin:7px; text-decoration: none;" target="_blank" href="<?php echo esc_url(carbon_get_theme_option('bt_sst_rating_page_url_pos')); ?>" class="bt_sst_tracking_rating_url_btn">
                                                    ☺️
                                                    </a>
                                                    <a style="margin:7px; text-decoration: none;" target="_blank" href="<?php echo esc_url(carbon_get_theme_option('bt_sst_rating_page_url_pos')); ?>" class="bt_sst_tracking_rating_url_btn">
                                                    😎
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="fl-drop-target fl-col-drop-target ui-sortable" style=""></div>
                        <div class="fl-drop-target fl-drop-target-last fl-col-drop-target fl-col-drop-target-last ui-sortable" style=""></div>
                    </div>
                    <div class="fl-drop-target fl-col-group-drop-target ui-sortable" style=""></div>
                    <div class="fl-drop-target fl-drop-target-last fl-col-group-drop-target fl-col-group-drop-target-last ui-sortable" style=""></div>
                </div>
                <div class="fl-col-group fl-node-78xaz0hcqfk4" data-node="78xaz0hcqfk4">
                    <div class="fl-col fl-node-ai83xde9bp2o style-pfWUf" data-node="ai83xde9bp2o" id="style-pfWUf">
                        <div class="fl-col-content fl-node-content ui-sortable">
                            <div class="fl-module fl-module-heading fl-node-j728vexgl5yw" data-node="j728vexgl5yw" data-parent="ai83xde9bp2o" data-type="heading" data-name="Heading">
                                <div class="fl-module-content fl-node-content">
                                    <div class="fl-heading">
                                        <div class="">
                                            <div style="">
                                                <div>
                                                    <?php if(is_user_logged_in()){ ?>
                                                    <a style="margin-top:4px; text-decoration:none; width:100%; display:flex; justify-content:center; border: 1px solid #c7c7c7b0; color:#101212; border-radius:20px; padding:13px;"
                                                         href="<?php echo esc_url( $the_order->get_view_order_url() ); ?>" class="">View order details
                                                        </a>
                                                    <?php } else { ?>
                                                        <a style="margin-top:4px; text-decoration:none; width:100%; display:flex; justify-content:center; border: 1px solid #c7c7c7b0; color:#101212; border-radius:20px; padding:13px;"
                                                            href="/my-account" class="">Login to see more details
                                                        </a>
                                                    <?php } ?>
                                                    <a style="margin-top:4px; text-decoration:none; width:100%; display:flex; justify-content:center; border: 1px solid #c7c7c7b0; color:#101212; border-radius:20px; padding:13px;"
                                                         href="<?php echo esc_url(get_permalink( get_the_ID() )); ?>" class="">Track another order
                                                    </a>
                                                    <a style="margin-top:4px; text-decoration:none; width:100%; display:flex; justify-content:center; border: 1px solid #c7c7c7b0; color:#101212; border-radius:20px; padding:13px;"
                                                         href="https://api.whatsapp.com/send?text=<?php echo urlencode($whatsapp_url); ?>" target="_blank">Share on WhatsApp
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fl-drop-target fl-col-drop-target ui-sortable" style=""></div>
                        <div class="fl-drop-target fl-drop-target-last fl-col-drop-target fl-col-drop-target-last ui-sortable" style=""></div>
                    </div>
                    <div class="fl-drop-target fl-col-group-drop-target ui-sortable" style=""></div>
                    <div class="fl-drop-target fl-drop-target-last fl-col-group-drop-target fl-col-group-drop-target-last ui-sortable" style=""></div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php if($auto_post) : ?>
      <script>
        document.addEventListener("DOMContentLoaded", function(event) {
          var enable_ph = '<?php echo esc_js($last_four_digit) ?>';
          if (enable_ph) {
            var ph = prompt("Enter last 4 digits of phone number");
            if (ph!="" && ph!=null){
              document.getElementById("bt_last_four_digit_no").value = ph;            
              document.getElementsByClassName('bt_tracking_form')[0].submit();
            }
            // else if (ph="" && ph=null){
              <?php $auto_post = false ?>
              var ele = document.getElementById('bt_loader_div');
              ele.classList.remove("loader");
            // }
          }
          else {
            document.getElementsByClassName('bt_tracking_form')[0].submit();
          }
        });
      </script>
    <?php elseif($the_order==false): ?>
        <script>
             document.getElementsByClassName('bt_tracking_form')[0].addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent the default form submission
                let queryValue = document.getElementById('bt_track_order_id').value; // Set the query string value
                let formAction = new URL(this.action);
                formAction.searchParams.delete('order');
                formAction.searchParams.set('order', encodeURIComponent(queryValue));
                this.action = formAction.toString();
                this.submit();
            });
        </script>
    <?php endif ?>
    <?php
    $bt_sst_navigation_map = carbon_get_theme_option('bt_sst_navigation_map');

    if($shipping_provider == 'manual' && $pickup_pincode != $currentPin && $delivery_pincode != $currentPin){
        $currentPin = isset($tracking['tracking_data']['current_pincode']) ? $tracking['tracking_data']['current_pincode'] : "";
        $currentCountry = isset($tracking['tracking_data']['current_country']) ? $tracking['tracking_data']['current_country'] : "";
    }
    if($bt_sst_navigation_map == 'yes' && $the_order && (isset($delivery_pincode) || isset($pickup_pincode))): 
        wp_enqueue_script('bt-sync-shipment-tracking-leaflet');
        wp_enqueue_script('bt-sync-shipment-tracking-mapRender');
        wp_enqueue_style('bt-sync-shipment-tracking-leaflet-css');
    ?>
        <script> async function plotMap2() {
                // Retrieve PHP variables inside JavaScript
                var pickupPin = '<?php echo esc_js($pickup_pincode); ?>'; // Pickup PIN code
                var dropoffPin = '<?php echo esc_js($delivery_pincode); ?>'; // Delivery PIN code
                var pickupMessage = `Order placed on <?php echo esc_js($ordering_date); ?>`;
                var deliveryMessage = `<?php echo esc_js($estimated_delivery_date); ?>`;
                var base_country = '<?php echo esc_js($base_country); ?>';
                var delivery_country = '<?php echo esc_js($delivery_country); ?>';
                var currentPin = '<?php echo esc_js($currentPin); ?>';
                var current_country = '<?php echo esc_js($currentCountry); ?>';
                var currentMessage = '<?php echo esc_js($currentCountry."(".$shipment_status.")"); ?>';
                window.plotMap(dropoffPin, pickupPin, deliveryMessage,pickupMessage, base_country, delivery_country, currentPin, currentMessage, current_country);
            }
            document.addEventListener("DOMContentLoaded", function(event) {
                plotMap2(); // Call the function to plot the map
            });
        </script>
    <?php endif ?>
</div>
<style>
    @font-face {
        font-family: 'bt_sst_tracking_widget_font';
        src: url('<?php echo esc_url( $public_dir_url ); ?>/css/fonts/bt_sst_tracking_widget_font.eot?40534079');
        src: url('<?php echo esc_url( $public_dir_url ); ?>/css/fonts/bt_sst_tracking_widget_font.eot?40534079#iefix') format('embedded-opentype'),
            url('<?php echo esc_url( $public_dir_url ); ?>/css/fonts/bt_sst_tracking_widget_font.woff2?40534079') format('woff2'),
            url('<?php echo esc_url( $public_dir_url ); ?>/css/fonts/bt_sst_tracking_widget_font.woff?40534079') format('woff'),
            url('<?php echo esc_url( $public_dir_url ); ?>/css/fonts/bt_sst_tracking_widget_font.ttf?40534079') format('truetype'),
            url('<?php echo esc_url( $public_dir_url ); ?>/css/fonts/bt_sst_tracking_widget_font.svg?40534079#bt_sst_tracking_widget_font') format('svg');
        font-weight: normal;
        font-style: normal;
    }
</style>
