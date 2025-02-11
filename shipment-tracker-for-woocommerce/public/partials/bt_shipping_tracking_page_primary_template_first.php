<div class="fl-module-subscribe-form snipcss-oLzae obscure-5eL1eW3kd obscure-1wJ5wR9d8" data-node="krpof3agj2mn">
    <div class="fl-node-content obscure-9aVJaXpd0">
        <div class="fl-subscribe-form-name-show fl-form obscure-n0E90mPMb obscure-avzkvjx5A obscure-r3av3mwX0">
            <!-- <form class="bt_tracking_form" action="" method="post" class="bt_track_order_form"> -->
                <div class="">
                    <?php
                        if($message){
                            echo '<div class="bt_sst_error_message">'.$message.'</div>';
                        }
                    ?>
                </div>
                <div class="v3931_251">
                    <form class="bt_tracking_form" action="" method="post" class="bt_track_order_form">
                        <div class="v3931_252">
                            <div class="v3931_253">Track Your Order</div>
                            <div class="v3931_254">
                                <div class="v3931_266"></div>
                                <div class="v3931_255">
                                    <!-- <form class="bt_tracking_form" action="" method="post" class="bt_track_order_form"> -->
                                    <input type="hidden" name="bt_tracking_form_nonce" value="<?php echo esc_attr(wp_create_nonce('bt_shipping_tracking_form_2')); ?>">
                                        
                                        <div class="v3931_259">
                                            <!-- <div class="v3931_260"></div> -->
                                            <span class="v3931_261">Order Id/ AWB No</span>
                                            <input required 
                                            style="background: rgba(255,255,255,1); height: 52px; border-radius: 10px; border: 1px solid rgba(241,241,241,1); top: 30px;" 
                                            type="text" value="<?php echo esc_attr($bt_track_order_id) ?>" name="bt_track_order_id" id="bt_track_order_id" placeholder="Your order id/ AWB No" >
                                        </div>
                                        
                                        <?php if ($last_four_digit) { ?>
                                                <!-- <div class="v3931_263"></div> -->
                                                <div class="v3931_262">
                                                    <span class="v3931_261">Mobile No (last 4 digits)</span>
                                                    <input required style="background: rgba(255,255,255,1); height: 52px; border-radius: 10px; border: 1px solid rgba(241,241,241,1); top: 30px;" type="text" value="<?php echo esc_attr($bt_last_four_digit) ?>" name="bt_track_order_phone" placeholder="Last 4 digits of mobile number" id="bt_last_four_digit_no">                              
                                                </div>
                                        <?php } ?>
                                        
                                        <div class="obscure-69pk9aPdz" data-wait-text="Please Wait...">
                                            <div class="v3931_256">
                                                <button type="submit" class="v3931_257" role="button">
                                                    <span class="fl-button-text">Track Now</span>
                                                </button>
                                            </div>
                                        </div>
                                    <!-- </form> -->
                                </div>
                            </div>
                        
                        </div>
                    </form>    
                </div>
            <!-- </form>     -->
        </div>
    </div>
</div>
                    