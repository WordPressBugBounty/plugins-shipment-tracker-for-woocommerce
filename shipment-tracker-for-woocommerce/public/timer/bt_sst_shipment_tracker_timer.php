<?php
if (!isset($total_seconds)) {
    $total_seconds = 59 * 60;
}

// if (WC()->cart) {
//     $cart = WC()->cart;
//     $saved_data = json_decode(get_option('bt_sst_timer_settings', '{}'), true);

//     if (!empty($saved_data['discount_percentage']) && is_numeric($saved_data['discount_percentage'])) {
//         $discount_percentage = floatval($saved_data['discount_percentage']); // Ensure it's a number
//         $cart = WC()->cart;
//         $discount = $cart->get_subtotal() * ($discount_percentage / 100);
        
//         $cart->add_fee(__($discount_percentage . '% Discount', 'woocommerce'), -$discount);
//     }
// }
?>
<div class="bt_sst_timer_container">
<div class="bt_sst_timer_heading">
    <?php echo $saved_data['bt_sst_quill_editer_html'] ?>
</div>
<input type="hidden" id="bt_sst_total_seconds" value="<?php echo $total_seconds; ?>">
<div class="bt_sst_timer_page_timer">
    <div>
        <div class="bt_sst_timer_page_box" style="color:<?php echo $saved_data['selected_color_timer_count_down'] ?>; background:<?php echo $saved_data['selected_color_timer_container'] ?>" id="bt_sst_timer_page_hours">00</div>
        <div class="bt_sst_timer_hour_min_sec">Hours</div>
    </div>
    <div class="bt_sst_timer_colon" style="color:<?php echo $saved_data['selected_color_timer_container'] ?>">:</div>
    <div>
        <div class="bt_sst_timer_page_box" style="color:<?php echo $saved_data['selected_color_timer_count_down'] ?>; background:<?php echo $saved_data['selected_color_timer_container'] ?>" id="bt_sst_timer_page_minutes">00</div>
        <div class="bt_sst_timer_hour_min_sec">Minutes</div>
    </div>
    <div class="bt_sst_timer_colon" style="color:<?php echo $saved_data['selected_color_timer_container'] ?>">:</div>
    <div>
        <div class="bt_sst_timer_page_box" style="color:<?php echo $saved_data['selected_color_timer_count_down'] ?>; background:<?php echo $saved_data['selected_color_timer_container'] ?>" id="bt_sst_timer_page_seconds">00</div>
        <div class="bt_sst_timer_hour_min_sec">Seconds</div>
    </div>
</div>

<div class="bt_sst_timer_page_progress-container">
    <div class="bt_sst_timer_page_progress-bar" id="bt_sst_timer_page_progress-bar"></div>
</div>

<div class="bt_sst_timer_page_stock"><?php echo $saved_data['bt_sst_quill_editer_html_subheading'] ?></div>
</div>
