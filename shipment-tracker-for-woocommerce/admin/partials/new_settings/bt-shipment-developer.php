<?php

?>
<section class="section">
    <div class="container">
        <div class="columns is-variable is-6">
            <!-- FAQ Section -->
            <div class="column ">
                <!-- Action Hooks -->
                <div class="panel is-info">
                     <p class="panel-heading">Action Hooks</p>
                    <div class="panel-block content">
                        <ul>
                           
                            <li>
                                <strong>bt_shipment_status_changed</strong><br>
                                <em>Fires when shipment status changes.</em><br>
                                <b>Arguments:</b> <code>$order_id, $shipment_obj, $bt_shipment_tracking_old</code>
                            </li>
                           
                        </ul>
                    </div>
                </div>

                <!-- Filter Hooks -->
                <div class="panel is-info">
                      <p class="panel-heading">Filter Hooks</p>
                    <div class="panel-block content">
                        <ul>
                            <li>
                                <strong>bt_sst_shipping_statuses</strong><br>
                                <em>Modify available shipping statuses.</em><br>
                                <b>Arguments:</b> <code>BT_SHIPPING_STATUS</code>
                            </li>
                            <li>
                                <strong>bt_format_shipment_status_string</strong><br>
                                <em>Format shipment status string.</em><br>
                                <b>Arguments:</b> <code>$formatted_shipment_status, $shipment_status</code>
                            </li>
                            <li>
                                <strong>bt_edd_variables</strong><br>
                                <em>Modify EDD variables (public).</em><br>
                                <b>Arguments:</b> <code>$edd_variables, $product_id</code>
                            </li>
                            <li>
                                <strong>bt_edd_message_text</strong><br>
                                <em>Modify EDD message text (public).</em><br>
                                <b>Arguments:</b> <code>$message_text_template, $edd_variables, $product_id</code>
                            </li>
                            <li>
                                <strong>bt_dynamic_courier_rates</strong><br>
                                <em>Modify courier rates (public).</em><br>
                                <b>Arguments:</b> <code>$rates, $package, $bt_sst_courier_rate_provider</code>
                            </li>
                            <li>
                                <strong>bt_sst_shipping_status_message</strong><br>
                                <em>Modify shipping status message (public).</em><br>
                                <b>Arguments:</b> <code>$message, $shipment_status</code>
                            </li>
                            <li>
                                <strong>bt_sst_product_page_delivery_checker_label_text</strong><br>
                                <em>Modify delivery checker label (public).</em><br>
                                <b>Arguments:</b> <code>$label</code>
                            </li>
                            <li>
                                <strong>bt_sync_shimpent_track_pincode_checker_shipping_to_text</strong><br>
                                <em>Modify pincode checker shipping-to text (public).</em><br>
                                <b>Arguments:</b> <code>$bt_sync_shimpent_track_pincode_checker_shipping_to_text</code>
                            </li>
                            <li>
                                <strong>bt_tracking_form_heading_text</strong><br>
                                <em>Modify tracking form heading (public).</em><br>
                                <b>Arguments:</b> <code>$heading_text</code>
                            </li>
                            <li>
                                <strong>bt_shipmozo_order_object</strong><br>
                                <em>Modify Shipmozo order object.</em><br>
                                <b>Arguments:</b> <code>$so, $order_id</code>
                            </li>
                            <li>
                                <strong>bt_shiprocket_order_object</strong><br>
                                <em>Modify Shiprocket order object.</em><br>
                                <b>Arguments:</b> <code>$so, $order_id</code>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Public Functions -->
                <div class="panel is-info">
                      <p class="panel-heading">Public Functions</p>
                    <div class="panel-block content">
                         <ul>
                            <li>
                                <strong>bt_get_shipping_tracking($order_id)</strong><br>
                                <em>Get tracking data and link for an order.</em>
                            </li>
                            <li>
                                <strong>bt_force_sync_order_tracking($order_id)</strong><br>
                                <em>Force sync shipment status for an order.</em>
                            </li>
                            <li>
                                <strong>bt_update_shipment_tracking($order_id, $courier_name, $awb_number, $shipping_status, $edd, $tracking_link)</strong><br>
                                <em>Update shipment tracking details for an order.</em>
                            </li>
                            <li>
                                <strong>bt_format_shipment_status($shipment_status)</strong><br>
                                <em>Format a shipment status string using filters.</em>
                            </li>
                            <li>
                                <strong>bt_sst_get_city_state_by_pincode($pincode, $country)</strong><br>
                                <em>Get city/state info by pincode (India only).</em>
                            </li>

                        </ul>
                       
                    </div>
                </div>

                <!-- API Sample Section -->
                <div class="panel is-info">
                    <p class="panel-heading">API: Update Shipment Data via Webhook</p>
                    <div class="panel-block content">
                        <ul>
                            <li>
                                <p>
                                    You can update shipment info from any 3rd party application using the following webhook receiver endpoint:
                                </p>
                                <p>
                                    <code>
                                        curl --location 'https://thiswebsite.in/wp-json/bt-sync-shipment-tracking-manual/v1.0.0/webhook_receiver' \<br>
                                        --form 'order_id="244"' \<br>
                                        --form 'awb_number="00001"' \<br>
                                        --form 'courier_name="Amazon"' \<br>
                                        --form 'etd="25/05/2020"' \<br>
                                        --form 'shipping_status="Pending pickup"' \<br>
                                        --form 'tracking_link=""' \<br>
                                        --form 'api_key="11fb1e48ba2c1234"'<br>
                                    </code>
                                </p>
                                <strong>Parameters:</strong>
                                <ul>
                                    <li><code>order_id</code>: WooCommerce order ID to update.</li>
                                    <li><code>awb_number</code>: Air Waybill (tracking) number.</li>
                                    <li><code>courier_name</code>: Name of the courier service.</li>
                                    <li><code>etd</code>: Estimated delivery date (format: DD/MM/YYYY).</li>
                                    <li><code>shipping_status</code>: Current shipment status (e.g., Pending pickup, Shipped, Delivered).</li>
                                    <li><code>tracking_link</code>: URL to track the shipment (optional).</li>
                                    <li><code>api_key</code>: API key for authentication.</li>
                                </ul>
                                <p>
                                    <strong>Note:</strong> You can get the api key by going to "Plugin Settings -> Custom Shipping" tab.
                                </p>


                            </li>

                        </ul>
                        
                    </div>
                </div>
            


            </div>
        </div>
    </div>
</section>