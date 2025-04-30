<?php
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
    $bt_sst_review_heading_text = "How was your experience with us?";
}
$bt_sst_review_subheading_text = carbon_get_theme_option('bt_sst__subheading_text');
if(empty($bt_sst_review_subheading_text)){
    $bt_sst_review_subheading_text = "Rate your experience.";
}
?>
<div class="snipcss0-0-0-1 snipcss-GDYrP obscure-kzaezq7Gp">
    
    <div class="fl-node-content snipcss0-1-1-2 obscure-MMkaMW9Lx obscure-JkAnk9l33">
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
            $order_shipping_method = $the_order->get_shipping_method();
            $order_delivery_address= $the_order->get_shipping_city();
            if(!$order_delivery_address){
                $order_delivery_address= $the_order->get_billing_city();
            }
            $payment_method_name = $order_payment_method; 
            $payment_gateways   = WC_Payment_Gateways::instance()->payment_gateways();
            if(isset($payment_gateways[$order_payment_method])){
                $payment_method_name = $payment_gateways[$order_payment_method]->get_title();
            }
            $order_placed_message = "We've received your order on $ordering_date at $ordering_time.";
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
                    $delivery_status = "Estimated Delivery:";
                }else {
                    $delivery_status = " Delivered On:";
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
                            $days_remaining = "(In ".$days_remaining." Day)"; 
                        }else{
                            $days_remaining = "(In ".$days_remaining." Days)"; 
                        }
                    }else if($days_remaining===0){
                        $days_remaining = "( Today )";
                    }
                    
                    if ($delivery_status !="" && $shipment_status != "canceled"):    
                        $estimated_delivery_date = $delivery_status." ".$estimated_delivery_date." ".$days_remaining; 
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
                            
            $whatsapp_url .= 'Track Your Shipment: ' . $current_url . "\n" .
                            'Thank you for shopping with us! If you have any questions, feel free to reach out.' ;
        ?>
        <div class="fl-module-subscribe-form snipcss-oLzae obscure-5eL1eW3kd obscure-1wJ5wR9d8" data-node="krpof3agj2mn">
            <div class="fl-node-s27vp51tbfmw fl-col-group-align-top snipcss0-2-2-3 obscure-P7W378aZg obscure-wgJwgZVGm" data-node="s27vp51tbfmw">
                <div class="fl-col-has-cols snipcss0-3-3-4 obscure-x03Z0E8Ga obscure-VXzbXRgla" data-node="oi8q7g9kbxfm">
                    <div class="fl-node-content snipcss0-4-4-5 obscure-ZWy0WPMnz">
                        <div class="fl-node-l93oa6izcf70 fl-col-group-nested snipcss0-5-5-6 obscure-P7W378aZg" data-node="l93oa6izcf70">
                            <div class="snipcss0-6-6-7 obscure-x03Z0E8Ga obscure-BEzVENp13" data-node="lmkze9s734bq">
                                <div class="fl-node-content snipcss0-7-7-8 obscure-ZWy0WPMnz">
                                    <div class="fl-module-rich-text snipcss0-8-8-9 obscure-LzqyzR5x7 obscure-ZWy0WPMR0" data-node="512uy9gq7pib">
                                        <div class="fl-node-content snipcss0-9-9-10 obscure-p7an7J3vr">
                                            <div class="bt_sst_location_del_bar_icone snipcss0-10-10-11 obscure-69p19MbZm">
                                                <!-- <p class="bt_sst_location_del_bar_icone snipcss0-11-11-12 bt_sst snipcss0-0-0-1 tether-element-attached-top tether-element-attached-center tether-target-attached-top tether-target-attached-center"> -->
                                                    <div class="bt_sst_location_del_bar_icone_child">
                                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAADuElEQVR4nO2ZWYiOURjHfzRkmbHPZOJCWYZCiNwoW7JEGtkaV24VuZG9xnZBcodsxYWILJESLiQiDLIklEhqLFnGvoxPR/+3TtN8531f3znffCO/euvr/f7vc/bnOec58J9G6QBUAbuAq8BL4Luel3q3E5gLlFCAVAB7gM9AJuHzCdgN9KUAaAdsBn6ocr/U6yuB0Wpgez0VercKuCZtRqO1CWjTVI0wPXlHlakH9gO9U3zfBzhgNegyUE6eGao5byrwABiWg63hwEPZegYMJo8jETXiDNDZg80uwFmrMeX5WBN3rEYUebTdCjhnTbOga2azNZ18jERjI/NIZawhEBXyTvU5rok4RsgBfAS6hyhgj3rKeKfQHFRZ20JE7E/qqSQutgxYB9xUz5rnBrAWKE3oUH7pu2I8UqUeMsEujplAnSOivwdmJLBzXfo5eGSXjJqIHdeIKMAdUySPIvsY4LgVQCtjbK2WdofHdvwZiYwq5ppO0UgsceiWSvMO6ObQjZXuCh55JaP9HJp11kjEcSKBi+0nzQs88k1GXVvvWwlGrWFvGweQjRJpvuKRLzLqirYfpEniZaJKmqno2kVkdDTwRq2MugJUXYqGdLA8WDZ6SPMcj9yX0YEOzU1pjHeKY5y0NQ7NEGnu4pGTMjrboVkrzfEU9qodmnnSHMUjG2R0vUNTqqmSkYvNxnJp3gJdHbqNCRqbmtkyarbZLmYo2GXkYsdqzRRrOkUjYTTTY2xdlDZOl4oyFf5V3sRFpYJdti3K2wSV66Sdtnk64pkbqsiUBNpuCnY1cst12jtVx0yniFkqy4yKd6LIvZfwHEmw1v6a/pbvj5teudBRAdhsPnuFKqRGjTGuMRQLVcb5gGWwwEoOhKAIeKwyzBkoGMWWRzJna9/MtbYlJqsSlC0q7HDAqbuCPFBuLcZBHu1OsA5cJo7kha0BRsUs7qD5rMboaWVVRnmwV6lGvA6U+HNSbZ3yWuZgp7WVxF5AE9AWeKIKzM/BzhLZuOc5l/xX+a5anfrSUmq584k0IS2AS6qISXCnZZ++Ndv7Jmeors9+AiNTfDfJuktMc9MVlOiYez/hvYbJojzVN4soIFoDtxMchyO2W+eNXDxeEIZoitXHZFImKf6Y0+YACpQ16uknWbYZZUqBGs1iCpgi4IKVxjFeLcJModP671SD/wqSnlbSe2UjR+XaUFdqIZimdWDWy2Rgqn4bFz2eZka1RuCNUkDm9zKaIS2AQ1Y+q+GaaVaU6Mbpsu9LTf5VfgNttDSN+ivn8AAAAABJRU5ErkJggg=="><p> <?php echo esc_html($order_delivery_address) ?> </p>
                                                    </div>
                                                    <div class="bt_sst_location_del_bar_icone_child">
                                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAB+0lEQVR4nO2YOUsDQRiGHzAmWphYiGjAQksre8XSxsKjyl/wwtouXRpttLPS1s4fYOcNHo1XHVQ8SgVBdGXgi6whGXZ39sjKPPBB2G923n13vjk2YLFYLBaLGU7M8QJsAJ1pN+JIrJNyJsTIM/8ARyKSTh3DsEbSjBNFaSVp5NFDyT4BO8Cw106jnhemmu/AdKsaKWlyNYaAXbn2AYzTQuhehNMg1wZsy/UHoJeUGlFkgH3J7Ym5P8RVTo00/eYGgFfJr6TZiGIS+AY+gTESxsSIYk3aVIEeUmykHTiWdlv1N0ZdTl4f1vGoOSh7yxcwkmYjilVpu0lC6B62qtks6xmVtne0oJFygJVUrWCRlY8OnWZGzFT9bgutZsQv1kgY2BExLa0OYBk4lU1IxQmwBORMxEPQcrwaKQKXmtXiAugPyUgxgJYnIzlXx1fAFNAloT47byR3DmQNjQTV8mRkSXLXQKFBvuASmDc0ElTrt697+aG2+3oOJafeTjNmpM2BoZEgWiXXcZ6Kh11TDW8z8gGOFGFrlZF6q7hGxm/nhRiNFOraVsWEOtZoqQ237v+k2QClFbvWotx402QCdssRWrWZ89t5nFpZWbvVzbcy2fISs66Oz3wuv4loFV0CjUKt632GJmLTysnQqw/+N4kjYCGEkUhSy2KxkDJ+ANIID7KqwJZkAAAAAElFTkSuQmCC"><p> <?php echo esc_html($courier_name) ?> </p>
                                                    </div>
                                                    <div class="bt_sst_location_del_bar_icone_child">
                                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAABiklEQVR4nMWVsUrEQBCGY6do5zMoB76Dd4JYauUj2OQssv+/geuCrTa+hFernShY2V9lY+MJIjZXCFZHUIabQNzkNskZceDnZi+z8+1mZkgQ/LcZY3ZIDgHcenQJoLNQcgApgLEn+YTkF4C3xhA9+ThJkuV5MQDuST4poBlENovqxADYBPAK4D2Koq02ATckH9TvNLpJTcAJgCmAvrV2l+SxrqshAK5FvhiSqyTvpNCuqvYGg8FgXRRU25LUQG6QieSo6va/MtR4ve0CjE6sqpf9b609EpWt5Tc/dCTPwjBcKwByE/usU5tmEDgnya/VnyhA/KlACvt0Yj8B7FtrD9S/yAfGcbwhcgFSTC3sHskXklcFgAa6LZZP8igTKlI//8xtT84DjMpaDJoEwIeoBF7vBr73zFnSQ5ELcGsA4LSQ09cpAPok4+yZ+CTDsi6S5DLZpW3atqEOgOS56C8Bw6oPTpkZY1ak4Fmr+wA9HToJ9n2Tf0jjU2NMt85punKSJgCJJ7nd5NYL2zenX/kTY8xhFQAAAABJRU5ErkJggg=="><p> <?php echo esc_html($awb_number) ?></p>
                                                    </div>
                                                <!-- </p> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fl-node-xb3alogzj8qv fl-col-group-nested snipcss0-5-5-14 obscure-P7W378aZg delhivery_processing" data-node="xb3alogzj8qv">
                            <!-- <div class="fl-col-small snipcss0-6-14-15 obscure-x03Z0E8Ga obscure-4BxzBaqZN" data-node="zuia8xpo0fg3">
                                <div class="fl-node-content snipcss0-7-15-16 obscure-ZWy0WPMnz delhivery_status">
                                    <div class="snipcss0-8-16-17 obscure-LzqyzR5x7 obscure-VXzbXRgBj obscure-n0EW0n7er" data-node="4358g7wtiu2f">
                                        <div class="fl-node-content snipcss0-9-17-18 obscure-p7an7J3vr">
                                            <h2 class="snipcss0-10-18-19 obscure-Wb9zbk749 bt_sst">
                                                <span class="snipcss0-11-19-20 obscure-0w5ewyE3G"><?=  $order_status_name ?></span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <div class="fl-node-j70sgf5izo6c fl-col-group-nested snipcss0-5-5-27 obscure-P7W378aZg" data-node="j70sgf5izo6c">
                            <div class="snipcss0-6-27-28 obscure-x03Z0E8Ga obscure-38P18aqw4" data-node="k3gphtsnqfw2">
                                <div class="fl-node-content snipcss0-7-28-29 obscure-ZWy0WPMnz">
                                    <div class="fl-module-info-list snipcss0-8-29-30 obscure-LzqyzR5x7 obscure-E1qj1WVXX" data-node="9au81dr5xjz6">
                                        <div class="fl-node-content snipcss0-9-30-31 obscure-p7an7J3vr">
                                            <div class="snipcss0-10-31-32 obscure-qJaRJ3PVg obscure-BEzVENpmn">
                                                <ul class="snipcss0-11-32-33 obscure-jzEBz173V obscure-AdqVd1LWL bt_sst">
                                                    <li class="info-list-item-dynamic0 snipcss0-12-33-34 obscure-z0xQ0E4nw">
                                                        <div class="snipcss0-13-34-35 obscure-jzEBz173V obscure-MMkaMW93B obscure-vMapMwZlb">
                                                            <div class="snipcss0-14-35-36 obscure-38P18aqL4 obscure-P7W378aLr obscure-x03Z0E85r obscure-VXzbXRgej">
                                                                <div class="snipcss0-15-36-37 obscure-8zZkzlygz obscure-BEzVENpmn"> <span class="snipcss0-16-37-38 obscure-5eLBea7qK">
                                                                        <span class="snipcss0-17-38-39 obscure-Wb9zbk739">
                                                                            <i class="snipcss0-18-39-40 obscure-geN8ev7qP obscure-avzpv6m4P <?php echo esc_attr($current_step >= 1 ? "bt_sst_step_completed" : "") ?>"></i>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="info-list-content-dynamic0 snipcss0-14-35-41 obscure-jzEBz173V obscure-LzqyzR5gW">
                                                                <h4 class="uabb-info-list-title bt_sst snipcss0-15-41-42">Order Placed</h4>
                                                                <div class="info-list-description-dynamic0 snipcss0-15-41-43 obscure-9aVkarqgw obscure-n0EW0n7or">
                                                                    <p class="snipcss0-16-43-44 bt_sst"><?php echo esc_html($order_placed_message )?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="snipcss0-13-34-45 obscure-jzEBz173V obscure-kzaezq7PL <?php echo esc_attr($current_step > 1 ? "bt_sst_step_completed" : ""); ?>"></div>
                                                    </li>
                                                    <li class="info-list-item-dynamic1 snipcss0-12-33-46 obscure-z0xQ0E4nw">
                                                        <div class="snipcss0-13-46-47 obscure-jzEBz173V obscure-MMkaMW93B obscure-vMapMwZlb">
                                                            <div class="snipcss0-14-47-48 obscure-38P18aqL4 obscure-P7W378aLr obscure-x03Z0E85r obscure-4BxzBaq0J">
                                                                <div class="snipcss0-15-48-49 obscure-8zZkzlygz obscure-BEzVENpmn"> <span class="snipcss0-16-49-50 obscure-5eLBea7qK">
                                                                        <span class="snipcss0-17-50-51 obscure-Wb9zbk739">
                                                                        <?php
                                                                                if ($order_status == "cancelled" || $shipped_string == "canceled" ||  $order_status == "refunded" || $order_status == "failed") {
                                                                                    echo '<span class="bt_st_order_canceled_icone">⚠</span>';
                                                                                } else {
                                                                                    echo '<i class="snipcss0-18-51-52 obscure-geN8ev7qP obscure-avzpv6m4P ' . ($current_step >= 2 ? "bt_sst_step_completed" : "") . '"></i>';
                                                                                }
                                                                            ?>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="info-list-content-dynamic1 snipcss0-14-47-53 obscure-jzEBz173V obscure-LzqyzR5gW">
                                                                <h4 class="uabb-info-list-title bt_sst snipcss0-15-53-54"><?php echo esc_html($shipped_string)  ?></h4>
                                                                <div class="info-list-description-dynamic1 snipcss0-15-53-55 obscure-9aVkarqgw obscure-n0EW0n7or">
                                                                    <p class="snipcss0-16-55-56 bt_sst"><?php echo esc_html($shipped_message); ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="snipcss0-13-46-57 obscure-jzEBz173V obscure-kzaezq7PL <?php echo esc_attr($current_step > 2 ? "bt_sst_step_completed" : ""); ?>"></div>
                                                    </li>
                                                    <li style="<?php echo esc_attr($show_delivery_states ? '' : 'display:none') ?>" class="info-list-item-dynamic2 snipcss0-12-33-58 obscure-z0xQ0E4nw">
                                                        <div class="snipcss0-13-58-59 obscure-jzEBz173V obscure-MMkaMW93B obscure-vMapMwZlb">
                                                            <div class="snipcss0-14-59-60 obscure-38P18aqL4 obscure-P7W378aLr obscure-x03Z0E85r obscure-KEqMElRvl">
                                                                <div class="snipcss0-15-60-61 obscure-8zZkzlygz obscure-BEzVENpmn"> <span class="snipcss0-16-61-62 obscure-5eLBea7qK">
                                                                        <span class="snipcss0-17-62-63 obscure-Wb9zbk739">
                                                                            <i class="snipcss0-18-63-64 obscure-geN8ev7qP obscure-avzpv6m4P <?php echo esc_attr($current_step >= 3 ? "bt_sst_step_completed" : ""); ?>"></i>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="info-list-content-dynamic2 snipcss0-14-59-65 obscure-jzEBz173V obscure-LzqyzR5gW">
                                                                <h4 class="uabb-info-list-title bt_sst snipcss0-15-65-66">Out for delivery</h4>
                                                                <div class="info-list-description-dynamic2 snipcss0-15-65-67 obscure-9aVkarqgw obscure-n0EW0n7or">
                                                                    <p class="snipcss0-16-67-68 bt_sst">
                                                                    <?php if($current_step>=3){
                                                                        echo "Package is going to arrive anytime now.";
                                                                    }       
                                                                    ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="snipcss0-13-58-69 obscure-jzEBz173V obscure-kzaezq7PL <?php echo esc_attr($current_step > 3 ? "bt_sst_step_completed" : ""); ?>"></div>
                                                    </li>
                                                    <li style="<?php echo esc_attr($show_delivery_states ? '' : 'display:none') ?>"  class="info-list-item-dynamic3 snipcss0-12-33-70 obscure-z0xQ0E4nw">
                                                        <div class="snipcss0-13-70-71 obscure-jzEBz173V obscure-MMkaMW93B obscure-vMapMwZlb">
                                                            <div class="snipcss0-14-71-72 obscure-38P18aqL4 obscure-P7W378aLr obscure-x03Z0E85r obscure-l0E90NdXQ">
                                                                <div class="snipcss0-15-72-73 obscure-8zZkzlygz obscure-BEzVENpmn"> <span class="snipcss0-16-73-74 obscure-5eLBea7qK">
                                                                        <span class="snipcss0-17-74-75 obscure-Wb9zbk739">
                                                                            <i class="snipcss0-18-75-76 obscure-geN8ev7qP obscure-avzpv6m4P <?php echo esc_attr($current_step >= 4 ? "bt_sst_step_completed" : ""); ?>"></i>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="info-list-content-dynamic3 snipcss0-14-71-77 obscure-jzEBz173V obscure-LzqyzR5gW">
                                                                <h4 class="uabb-info-list-title bt_sst snipcss0-15-77-78">Delivered</h4>
                                                                <div class="info-list-description-dynamic3 snipcss0-15-77-79 obscure-9aVkarqgw obscure-n0EW0n7or">
                                                                    <p class="snipcss0-16-79-80 bt_sst">
                                                                        <?php if($current_step>=4){
                                                                                echo 'Yay! You should have already received your package.';
                                                                            }   
                                                                            echo esc_html($estimated_delivery_date);
                                                                        ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="snipcss0-13-70-81 obscure-jzEBz173V obscure-kzaezq7PL <?php echo esc_attr($current_step > 4 ? "bt_sst_step_completed" : ""); ?>"></div>
                                                    </li>
                                                </ul>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if($is_premium && carbon_get_theme_option('bt_sst_enable_rating')=='yes'): ?>
                        <div class="bt_sst_tracking_product_rating">
                            <div>
                                <strong><?php echo esc_html($bt_sst_review_heading_text); ?></strong>
                                <p><?php echo esc_html($bt_sst_review_subheading_text); ?></p>
                            </div>
                            <div>
                             
                                    <a target="_blank" href="<?php echo esc_url(carbon_get_theme_option('bt_sst_rating_page_url')); ?>" class="bt_sst_tracking_rating_url_btn">
                                        <button>Rate Us </button>
                                    </a>
                               
                            </div>
                        </div>
                        <?php endif;?>
                        <div class="fl-node-njehum1dxzwr fl-col-group-nested snipcss0-5-5-82 obscure-P7W378aZg" data-node="njehum1dxzwr">
                        <?php

                            $bt_sst_navigation_map = carbon_get_theme_option('bt_sst_navigation_map');
                            if($is_premium && $bt_sst_navigation_map == 'yes'){ 
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
                                // var_dump($delivery_pincode); die;
                                if($delivery_pincode || $pickup_pincode){
                                ?>
                                <div class="">
                                    <div id="bt_sst_leaflet_map_location" class="bt_sst_leaflet_map_location_shipment"></div>
                                </div>
                            <?php }
                            }
                            ?>
                        </div>
                        <?php
                        $shipping_provider = strtolower($tracking['tracking_data']['shipping_provider']);
                        ?>
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
                <div class="fl-col-small fl-col-has-cols snipcss0-3-3-97 obscure-x03Z0E8Ga obscure-8zZkzly4Q" data-node="ykwfsiqu7zdh">
                    <div class="fl-node-content snipcss0-4-97-98 obscure-ZWy0WPMnz">
                        <div class="fl-node-n5bgt7xqmypu fl-col-group-nested snipcss0-5-98-99 obscure-P7W378aZg" data-node="n5bgt7xqmypu">
                            <div class="snipcss0-6-99-100 obscure-x03Z0E8Ga obscure-LzqyzR5JW" data-node="cp8dn9rx16bz">
                                <div class="fl-node-content snipcss0-7-100-101 obscure-ZWy0WPMnz">
                                    <div class="snipcss0-8-101-102 obscure-LzqyzR5x7 obscure-BEzVENpan obscure-n0EW0n7er" data-node="m5r9xvwk1nhu">
                                        <div class="fl-node-content snipcss0-9-102-103 obscure-p7an7J3vr">
                                            <h2 class="snipcss0-10-103-104 obscure-Wb9zbk749 bt_sst">
                                                <span class="snipcss0-11-104-105 obscure-0w5ewyE3G">ORDER SUMMARY</span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fl-node-xqgvb6cr4f2y fl-col-group-nested snipcss0-5-98-106 obscure-P7W378aZg" data-node="xqgvb6cr4f2y">
                            <div class="snipcss0-6-106-107 obscure-x03Z0E8Ga obscure-p7an7J3Zk" data-node="xcfaj63eqln7">
                                <div class="fl-node-content snipcss0-7-107-108 obscure-ZWy0WPMnz">
                                    <!-- <div class="fl-module-rich-text snipcss0-8-108-109 obscure-LzqyzR5x7 obscure-4BxzBaqpJ" data-node="1d5gj37mkwel">
                                        <div class="fl-node-content snipcss0-9-109-110 obscure-p7an7J3vr">
                                            <div class="snipcss0-10-110-111 obscure-69p19MbZm">
                                                <p class="snipcss0-11-111-112 bt_sst"><strong class="snipcss0-12-112-113">Order #:</strong> <?= $order_number ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fl-module-rich-text snipcss0-8-108-114 obscure-LzqyzR5x7 obscure-KEqMElRjl" data-node="f73z49e2tahd">
                                        <div class="fl-node-content snipcss0-9-114-115 obscure-p7an7J3vr">
                                            <div class="snipcss0-10-115-116 obscure-69p19MbZm">
                                                <p class="snipcss0-11-116-117 bt_sst"><strong class="snipcss0-12-117-118">Order Date:</strong> <?=  $ordering_date ?> <strong class="snipcss0-12-117-119">at</strong> <?=  $ordering_time ?></p>
                                            </div>
                                        </div>
                                    </div> -->
                                    <!-- <div class="fl-module-button snipcss0-8-108-120 obscure-LzqyzR5x7 obscure-l0E90NdxQ" data-node="k0yzrxscgtih">
                                        <div class="fl-node-content snipcss0-9-120-121 obscure-p7an7J3vr">
                                            <div class="snipcss0-10-121-122 obscure-X89y8J7Kw obscure-N7917enPm obscure-GAqEAWBoV obscure-yzP5zEB93">
                                                <a href="https://google.com" target="_blank" class="snipcss0-11-122-123 obscure-7XwkXrqVp" role="button" rel="noopener">
                                                    <i class="fl-button-icon fl-button-icon-before snipcss0-12-123-124 obscure-geN8ev7qP obscure-mzq5zn7LG" aria-hidden="true"></i>
                                                    <span class="snipcss0-12-123-125 obscure-b1Ny1BQXA">Contact Support</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="snipcss0-8-108-126 obscure-LzqyzR5x7 obscure-Rzy6z41w7 obscure-oka0knJwe" data-node="crxjating2sb">
                                        <div class="fl-node-content snipcss0-9-126-127 obscure-p7an7J3vr">
                                            <div class="fl-list-regular snipcss0-10-127-128 obscure-r3ak3Px8d" role="list">
                                            <div role="listitem" class="fl-list-item-0 snipcss0-11-128-129 obscure-dvNWveyoq">
                                                    <div class="snipcss0-12-129-130 obscure-QzbPzM6pr">
                                                        <h3 class="snipcss0-13-130-131 obscure-e8bX8verE bt_sst"> <span class="snipcss0-14-131-132 obscure-p7an7J30k">Order #</span></h3>
                                                        <div class="snipcss0-13-130-133 obscure-JkAnk9lqR"><span class="snipcss0-14-133-134 obscure-wgJwgZVx0"><span class="snipcss0-15-134-135 obscure-Rzy6z41e7"></span></span>
                                                            <div class="snipcss0-14-133-136 obscure-ZWy0WPML0">
                                                                <p class="snipcss0-15-136-137 bt_sst"><?php echo esc_html($order_number) ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div role="listitem" class="fl-list-item-0 snipcss0-11-128-129 obscure-dvNWveyoq">
                                                    <div class="snipcss0-12-129-130 obscure-QzbPzM6pr">
                                                        <h3 class="snipcss0-13-130-131 obscure-e8bX8verE bt_sst"> <span class="snipcss0-14-131-132 obscure-p7an7J30k">Placed on</span></h3>
                                                        <div class="snipcss0-13-130-133 obscure-JkAnk9lqR"><span class="snipcss0-14-133-134 obscure-wgJwgZVx0"><span class="snipcss0-15-134-135 obscure-Rzy6z41e7"></span></span>
                                                            <div class="snipcss0-14-133-136 obscure-ZWy0WPML0">
                                                                <p class="snipcss0-15-136-137 bt_sst"> <?php echo esc_html($ordering_date) ?> <strong class="snipcss0-12-117-119">at</strong> <?php echo esc_html($ordering_time) ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="listitem" class="fl-list-item-0 snipcss0-11-128-129 obscure-dvNWveyoq">
                                                    <div class="snipcss0-12-129-130 obscure-QzbPzM6pr">
                                                        <h3 class="snipcss0-13-130-131 obscure-e8bX8verE bt_sst"> <span class="snipcss0-14-131-132 obscure-p7an7J30k">Order Total</span></h3>
                                                        <div class="snipcss0-13-130-133 obscure-JkAnk9lqR"><span class="snipcss0-14-133-134 obscure-wgJwgZVx0"><span class="snipcss0-15-134-135 obscure-Rzy6z41e7"></span></span>
                                                            <div class="snipcss0-14-133-136 obscure-ZWy0WPML0">
                                                                <p class="snipcss0-15-136-137 bt_sst"><?php echo $order_total; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="listitem" class="fl-list-item-1 snipcss0-11-128-138 obscure-dvNWveyoq">
                                                    <div class="snipcss0-12-138-139 obscure-QzbPzM6pr">
                                                        <h3 class="snipcss0-13-139-140 obscure-e8bX8verE bt_sst"> <span class="snipcss0-14-140-141 obscure-p7an7J30k">Payment via</span></h3>
                                                        <div class="snipcss0-13-139-142 obscure-JkAnk9lqR"><span class="snipcss0-14-142-143 obscure-wgJwgZVx0"><span class="snipcss0-15-143-144 obscure-Rzy6z41e7"></span></span>
                                                            <div class="snipcss0-14-142-145 obscure-ZWy0WPML0">
                                                                <p class="snipcss0-15-145-146 bt_sst"><?php echo esc_html($payment_method_name) ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="listitem" class="fl-list-item-2 snipcss0-11-128-147 obscure-dvNWveyoq">
                                                    <div class="snipcss0-12-147-148 obscure-QzbPzM6pr">
                                                        <h3 class="snipcss0-13-148-149 obscure-e8bX8verE bt_sst"> <span class="snipcss0-14-149-150 obscure-p7an7J30k">Shipping</span></h3>
                                                        <div class="snipcss0-13-148-151 obscure-JkAnk9lqR"><span class="snipcss0-14-151-152 obscure-wgJwgZVx0"><span class="snipcss0-15-152-153 obscure-Rzy6z41e7"></span></span>
                                                            <div class="snipcss0-14-151-154 obscure-ZWy0WPML0">
                                                                <p class="snipcss0-15-154-155 bt_sst"><?php echo esc_html($order_shipping_method)  ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fl-node-p4u02q3angrh fl-col-group-nested snipcss0-5-98-156 obscure-P7W378aZg" data-node="p4u02q3angrh">
                            <div class="snipcss0-6-156-157 obscure-x03Z0E8Ga obscure-wgJwgZVR0" data-node="935aktuywvbf">
                                <div class="fl-node-content snipcss0-7-157-158 obscure-ZWy0WPMnz">
                                    <div class="fl-module-rich-text snipcss0-8-158-159 obscure-LzqyzR5x7 obscure-5eLBea7wK" data-node="lgx6c3o0ejz2">
                                        <div class="fl-node-content snipcss0-9-159-160 obscure-p7an7J3vr">
                                            <div class="snipcss0-10-160-161 obscure-69p19MbZm">
                                                <?php if(is_user_logged_in()){ ?>
                                                    <p class="snipcss0-11-161-162 bt_sst"><a href="<?php echo esc_url( $the_order->get_view_order_url() ); ?>" class="snipcss0-12-162-163">View</a> order details.</p>
                                                <?php } else { ?>
                                                    <p class="snipcss0-11-161-162 bt_sst"><a href="/my-account" class="snipcss0-12-162-163">Login</a> to see more details.</p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fl-module-rich-text snipcss0-8-158-164 obscure-LzqyzR5x7 obscure-9aVkarqBw" data-node="b5kjxqdpzleu">
                                        <div class="fl-node-content snipcss0-9-164-165 obscure-p7an7J3vr">
                                            <div class="snipcss0-10-165-166 obscure-69p19MbZm">
                                                <p class="snipcss0-11-166-167 bt_sst"><a href="<?php echo esc_url(get_permalink( get_the_ID() )); ?>" class="snipcss0-12-167-168">Track</a> another order.</p>
                                                <div class="">
                                                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($whatsapp_url); ?>" target="_blank"> Share 
                                                     </a> on WhatsApp
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
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
    if($pickup_pincode != $currentPin && $delivery_pincode != $currentPin){
        $currentPin = isset($tracking['tracking_data']['current_pincode']) ? $tracking['tracking_data']['current_pincode'] : "";
        $currentCountry = isset($tracking['tracking_data']['current_country']) ? $tracking['tracking_data']['current_country'] : "";
    }
    if($bt_sst_navigation_map == 'yes' && $the_order && (isset($delivery_pincode) || isset($pickup_pincode))): 
        wp_enqueue_script('bt-sync-shipment-tracking-leaflet');
        wp_enqueue_script('bt-sync-shipment-tracking-mapRender');
        wp_enqueue_style('bt-sync-shipment-tracking-leaflet-css');
    ?>
    <script>
    async function plotMap2() {
            // Retrieve PHP variables inside JavaScript
            var pickupPin = '<?php echo esc_js($pickup_pincode); ?>'; // Pickup PIN code
            var dropoffPin = '<?php echo esc_js($delivery_pincode); ?>'; // Delivery PIN code
            var pickupMessage = `Order placed on <?php echo esc_js($ordering_date); ?>`;
            var deliveryMessage = `<?php echo esc_js($estimated_delivery_date); ?>`;
            var base_country = '<?php echo esc_js($base_country); ?>';
            var delivery_country = '<?php echo esc_js($delivery_country); ?>';
            var currentPin = '<?php echo esc_js($currentPin); ?>';
            var current_country = '<?php echo esc_js($currentCountry); ?>';
            var currentMessage = '<?php echo esc_js($currentCountry." (".$shipment_status.")"); ?>';
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
        src: url('<?php echo esc_url( $public_dir_url ) ?>/css/fonts/bt_sst_tracking_widget_font.eot?40534079');
        src: url('<?php echo esc_url( $public_dir_url ) ?>/css/fonts/bt_sst_tracking_widget_font.eot?40534079#iefix') format('embedded-opentype'),
            url('<?php echo esc_url( $public_dir_url ) ?>/css/fonts/bt_sst_tracking_widget_font.woff2?40534079') format('woff2'),
            url('<?php echo esc_url( $public_dir_url ) ?>/css/fonts/bt_sst_tracking_widget_font.woff?40534079') format('woff'),
            url('<?php echo esc_url( $public_dir_url ) ?>/css/fonts/bt_sst_tracking_widget_font.ttf?40534079') format('truetype'),
            url('<?php echo esc_url( $public_dir_url ) ?>/css/fonts/bt_sst_tracking_widget_font.svg?40534079#bt_sst_tracking_widget_font') format('svg');
        font-weight: normal;
        font-style: normal;
    }
</style>