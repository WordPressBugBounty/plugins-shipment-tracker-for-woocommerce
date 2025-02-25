<?php
    $allowed_tags = array(
        'a'      => array(
            'href'  => array(),
            'title' => array()
        ),
        'strong' => array(),
        'em'     => array(),
        'p'      => array(),
        'br'     => array(),
    );
    $bt_tracking_form_heading_text = apply_filters( 'bt_tracking_form_heading_text', 'Take control of your deliveries<br>from the comfort of your own.');
?>

<div class="bt-sst-tracking-container bt-sst-tracking-primary-form-2">
    <div class="">
                    <?php
                        if($message){
                            echo '<div class="bt_sst_error_message">'.esc_html($message).'</div>';
                        }
                    ?>
    </div>
    <div class="tracking-image">
        <div style="background-image: url('<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/pngegg.png'; ?>');"></div>
    </div>
    
    <div class="tracking-text">
    <?php echo wp_kses( $bt_tracking_form_heading_text, $allowed_tags); ?>
    </div>

    <form class=" bt_tracking_form tracking-form-2 " action="" method="post">
        <input type="hidden" name="bt_tracking_form_nonce" value="<?php echo esc_attr(wp_create_nonce('bt_shipping_tracking_form_2')); ?>">
        
        <input class="tracking-input" required type="text" value="<?php echo esc_attr($bt_track_order_id) ?>" id="bt_track_order_id"  name="bt_track_order_id" placeholder="Your order ID / AWB No">
        
        <?php if ($last_four_digit) { ?>
            <input class="tracking-input" type="hidden" value="<?php echo esc_attr($bt_last_four_digit) ?>" id="bt_last_four_digit_no" name="bt_track_order_phone" placeholder="Last 4 digits of mobile number">
        <?php } ?>

        <button class="tracking-button" type="submit">
            <span>Track Delivery</span>
        </button>
    </form>
</div>
