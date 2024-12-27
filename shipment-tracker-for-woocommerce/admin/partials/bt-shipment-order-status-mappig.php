<style>
    /* Table Styling */
.bt_sst_status_mapping_table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 16px;
    text-align: left;
    border: 1px solid #ddd;
}

/* Header Row */
.bt_sst_status_mapping_header {
    background-color: #f4f4f4;
    color: #333;
    font-weight: bold;
    padding: 10px;
    border: 1px solid #ddd;
}

/* Table Rows */
.bt_sst_status_mapping_row:nth-child(even) {
    background-color: #f9f9f9;
}

.bt_sst_status_mapping_row:nth-child(odd) {
    background-color: #fff;
}

/* Table Cells */
.bt_sst_status_mapping_cell {
    padding: 10px;
    border: 1px solid #ddd;
}

/* Select Dropdown */
.bt_sst_status_mapping_select {
    width: 100%;
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
}

.bt_sst_add_order_status:hover{
    cursor: pointer;
}

/* pop-up css  */
/* Overlay background for the popup */
.bt_sst_mapping_pop_up_overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

/* Popup container */
.bt_sst_mapping_pop_up_container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    position: relative;
}

/* Close button style */
.bt_sst_mapping_pop_up_close {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 24px;
    font-weight: bold;
    color: #333;
    cursor: pointer;
}

/* Popup title */
.bt_sst_mapping_pop_up_title {
    text-align: center;
    font-size: 18px;
    margin-bottom: 20px;
}

/* Form fields */
.bt_sst_mapping_pop_up_form input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

/* Submit button */
.bt_sst_mapping_pop_up_submit {
    width: 100%;
    padding: 10px;
    background-color: #2271b1;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

/* Submit button hover effect */
.bt_sst_mapping_pop_up_submit:hover {
    background-color: #2271b1;
}

.bt_sst_mapping_pop_fields{
    margin: 10px 0;
    display: flex;
    justify-content: space-between;
}


</style>
<?php
    $shipping_statuses = apply_filters('bt_sst_shipping_statuses', BT_SHIPPING_STATUS);
    $wc_order_statuses = wc_get_order_statuses();
    $saved_statuses_keys_values = get_option('bt_sst_order_and_shipp_status_keys_array');
    // echo "<pre>"; print_r($shipping_statuses); 
    // echo "<pre>"; print_r($wc_order_statuses); 
    // echo "<pre>"; print_r($saved_statuses_keys_values); die;
    ?>
<table class="bt_sst_status_mapping_table">
    <thead>
        <tr>
            <th class="bt_sst_status_mapping_header">Shipping Status</th>
            <th class="bt_sst_status_mapping_header">Order Status</th>
            <th class="bt_sst_status_mapping_header">Add Order Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($shipping_statuses as $shipping_key => $shipping_status):?>
            <tr class="bt_sst_status_mapping_row">
                <td class="bt_sst_status_mapping_cell"><?php echo esc_html($shipping_status); ?></td>
                <td class="bt_sst_status_mapping_cell">
                    <select name="bt_sst_select_wc_order_status-<?php echo esc_attr($shipping_key); ?>" data-shipping-key="<?php echo esc_attr($shipping_key); ?>" class="bt_sst_status_mapping_select" id="bt_sst_status-<?php echo esc_attr($shipping_key); ?>">
                        <option value="" 
                        <?php 
                            if(isset($saved_statuses_keys_values[$shipping_key])){
                                echo esc_attr($saved_statuses_keys_values[$shipping_key]==""?"selected":""); 
                            }
                        ?> 
                        ><?php esc_html_e('Select Status'); ?></option>
                        <?php
                        foreach ($wc_order_statuses as $order_key => $wc_order_status):
                            ?>
                            <option value="<?php echo esc_attr($order_key); ?>" 
                            <?php 
                                if(isset($saved_statuses_keys_values[$shipping_key])){
                                    echo esc_attr($saved_statuses_keys_values[$shipping_key]==$order_key?"selected":""); 
                                }
                            ?> 
                            
                            >
                                <?php echo esc_html($wc_order_status); ?>
                            </option>
                        <?php
                        endforeach; ?>
                    </select>
                </td>
                <td class="bt_sst_status_mapping_cell"><button data-shipping_status="<?php echo esc_attr($shipping_status); ?>" data-shipping_key="<?php echo esc_attr($shipping_key); ?>" class="bt_sst_add_order_status" type="button">+</button></td>
            </tr>
        <?php endforeach;?>
    </tbody>

</table>

<!-- pop-up content  -->
<!-- Popup Form -->
<div class="bt_sst_mapping_pop_up_overlay" id="bt_sst_mapping_pop_up_overlay">
    <div class="bt_sst_mapping_pop_up_container">
        <!-- Close button -->
        <button class="bt_sst_mapping_pop_up_close" type="button" id="bt_sst_mapping_pop_up_close_btn">&times;</button>
        
        <!-- Form inside the popup -->
        <form id="bt_sst_mapping_pop_up_form">
            <h3 class="bt_sst_mapping_pop_up_title">Add New Custom Order Status</h3>
            <div class="bt_sst_mapping_pop_fields">
                <label for="bt_sst_mapping_pop_up_status_name">Order Status Title*</label>
                <input type="text" id="bt_sst_mapping_pop_up_status_name" name="status_name" placeholder="Title">
            </div>            
            <div class="bt_sst_mapping_pop_fields">
                <label for="bt_sst_mapping_pop_up_status_slug">Order Status Slug*</label>
                <input type="text" id="bt_sst_mapping_pop_up_status_slug" name="slug" placeholder="status-slug">
            </div>
            <div class="bt_sst_mapping_pop_fields">
                <button type="button" class="bt_sst_mapping_pop_up_submit">Submit</button>
            </div>
        </form>
    </div>
</div>