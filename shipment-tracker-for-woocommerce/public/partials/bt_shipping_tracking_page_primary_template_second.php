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
<style>
    .tracking-container {
        display: flex;
        flex-direction: column;
        /* justify-content: center; */
        align-items: center;
        /* height: 60vh; */
        font-family: Arial, sans-serif;
        text-align: center;
        /* border: 1px solid; */
    }

    .tracking-text {
        font-size: 2rem;
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .tracking-form-2 {
        /* display: flex; */
        flex-direction: column;
        gap: 10px;
        align-items: center;
    }

    .tracking-input {
        padding: 15px;
        width: 50%;
        border-radius:20px 0 0 20px;
        font-size: 1rem;
        text-align: center;
        border: none;
        background: #3c3c3c2b;
    }

    .tracking-button {
        padding: 15px 20px;
        border-radius: 0px 20px 20px 0px;
        cursor: pointer;
        font-size: 1rem;
        border: none;
        background: #005e4b;
        color: white;
    }

    .tracking-image {
        margin-bottom: 20px;
    }

    .tracking-image div {
        height: 250px;
        width: 250px;
        background-size: cover;
        background-position: center;
    }
</style>

<div class="tracking-container">
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
