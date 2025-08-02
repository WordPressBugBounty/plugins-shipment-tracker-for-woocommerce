<?php
    $all_providers = BT_SHIPPING_PROVIDERS;
?>
<div class="box quick-settings">
    <p class="title is-6">Quick steps to setup Shipment Tracker for WooCommerce:</p>

     <!-- 5. Primary Shipping Provider -->
    <div class="columns is-vcentered is-mobile">
        <div class="column is-half">
            <strong>1. Primary Shipping Provider:</strong>
        </div>
        <div class="column is-half has-text-right">
            <div class="field is-grouped is-justify-content-flex-end">
                
                <div class="control has-icons-left">
                    <div class="select">
                        <select ng-model="primary_shipping_method_setting_update"
                            ng-change="updatePrimaryShippingMethodSettings()" name="shipping_method"
                            class="bt_sst_shipping_method">
                            <?php foreach ($all_providers as $provider_key => $provider): ?>
                                <option value="<?php echo esc_attr($provider_key); ?>">
                                    <?php echo esc_html($provider); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <span class="icon is-medium is-left">
                        <i data-lucide="truck-electric" class="icon-sm"></i>
                    </span>
                </div>
                <div class="control">
                    <a class="button is-white is-small" target="_blank" title="Configure API keys / Webhook URLs"
                        href="{{primary_shipping_provider_url}}"> <i data-lucide="sliders-horizontal" class="icon-sm"></i></a>
                </div>
            </div>
        </div>
    </div>


    <!-- 1. Tracking Page -->
    <div class="columns is-vcentered is-mobile">
        <div class="column is-half">
            <strong>2. Tracking Page:</strong>
        </div>
        <div class="column is-half has-text-right">
            <div class="toggle-button is-inline-block">
                <div class="field">
                    <label class="switch is-rounded">
                        <input ng-model="tracking_page_status" ng-change="updateTrackingPageSettings()"
                            class="bt_sst_call_ajax2" type="checkbox" value="create_tracking_page">
                        <span class="check"></span>
                        <span class="control-label">On</span>
                    </label>
                </div>
            </div>
            <span class="bt_sst_traking_page_url"><a ng-show="tracking_page_url && tracking_page_status" title="Preview Tracking Page"
                    href="{{tracking_page_url}}" target="_blank"><i data-lucide="external-link" class="icon-sm"></i></a></span>
            <a class="button is-white is-small" target="_blank" title="Configure Tracking Page Settings"
                href="<?php echo $shipment_setting_url . '&t=tracking'; ?>"><i data-lucide="sliders-horizontal" class="icon-sm"></i></a>

            <p class="is-size-7 mt-2">or use shortcode <code>[bt_shipping_tracking_form_2]</code></p>
        </div>
    </div>

    <!-- 2. Estimated Delivery Checker -->
    <div class="columns is-vcentered is-mobile">
        <div class="column is-half">
            <strong>3. Estimated Delivery Checker:</strong>
        </div>
        <div class="column is-half has-text-right">
            <div class="toggle-button is-inline-block">
                <div class="field">
                    <label class="switch is-rounded">
                        <input ng-model="estimate_delivery_checker" ng-change="updateETDSettings()"
                            class="bt_sst_call_ajax" type="checkbox" value="etd" >
                        <span class="check"></span>
                        <span class="control-label">On</span>
                    </label>
                </div>
            </div>
             <span class="bt_sst_product_url_etd"><a ng-show="product_url_etd" title="Preview ETD Page"
                    href="{{product_url_etd}}" target="_blank"><i data-lucide="external-link" class="icon-sm"></i></a></span>
            <a class="button is-white is-small" target="_blank" title="Configure Estimated Delivery Checker"
                href="<?php echo $shipment_setting_url . '&t=etd'; ?>">
                <i data-lucide="sliders-horizontal" class="icon-sm"></i></a>
        </div>
    </div>

    <!-- 3. Free-Shipping Timer -->
    <div class="columns is-vcentered is-mobile">
        <div class="column is-half">
            <strong>4. Free-Shipping Timer:</strong>
        </div>
        <div class="column is-half has-text-right">
            <div class="toggle-button is-inline-block">
                <div class="field">
                    <label class="switch is-rounded">
                        <input ng-model="timer_setting_update" ng-change="updateTimerSettings()"
                            class="bt_sst_call_ajax" type="checkbox" value="timer" >
                        <span class="check"></span>
                        <span class="control-label">On</span>
                    </label>
                </div>
            </div>
            <span class="bt_sst_product_url_timer"><a ng-show="product_url_timer" title="Preview timer Page"
                    href="{{product_url_timer}}" target="_blank"><i data-lucide="external-link" class="icon-sm"></i></a></span>
            <a class="button is-white is-small" target="_blank" href="<?php echo $timer_url; ?>" title="Configure Free-Shipping Timer Settings"> 
                <i data-lucide="sliders-horizontal" class="icon-sm"></i></a>
        </div>
    </div>

    <!-- 4. Notifications -->
    <div class="columns is-vcentered is-mobile">
        <div class="column is-half">
            <strong>5. Notifications (SMS, WhatsApp):</strong>
        </div>
        <div class="column is-half has-text-right">
            <a class="button is-white is-small" target="_blank" href="<?php echo $notification_url ?>">Configure how
                &amp; when
                notifications are sent</a>
        </div>
    </div>

   
    <!-- 6. Improve Checkout Experience -->
    <div class="columns is-vcentered is-mobile">
        <div class="column is-half">
            <strong>6. Improve Checkout Experience:</strong>
        </div>
        <div class="column is-half"></div>
    </div>

    <!-- Auto fetch pincode -->
    <div class="columns is-vcentered is-mobile pl-4">
        <div class="column is-half">
            Auto fetch pincode details:
        </div>
        <div class="column is-half has-text-right">
            <div class="toggle-button is-inline-block">
                <div class="field">
                    <label class="switch is-rounded">
                        <input class="bt_sst_call_ajax" ng-model="fetch_auto_pincode"
                            ng-change="updateAutoFetchPinSettings()" type="checkbox" value="fetch_auto_pincode">
                        <span class="check"></span>
                        <span class="control-label">On</span>
                    </label>
                </div>
            </div>
             <a class="button is-white is-small" target="_blank" title="" style="visibility: hidden;"
                href="<?php echo $shipment_setting_url . '&t=checkout' ?>">
                <i data-lucide="sliders-horizontal" class="icon-sm"></i>
            </a>
        </div>
    </div>

    <!-- Show Shipment Weight -->
    <div class="columns is-vcentered is-mobile pl-4">
        <div class="column is-half">
            Show Shipment Weight:
        </div>
        <div class="column is-half has-text-right">
            <div class="toggle-button is-inline-block">
                <div class="field">
                    <label class="switch is-rounded">
                        <input class="bt_sst_call_ajax" ng-model="fetch_weight" ng-change="updateFetchWeightSettings()"
                            type="checkbox" value="fetch_weight">
                        <span class="check"></span>
                        <span class="control-label">On</span>
                    </label>
                </div>
            </div>
             <a class="button is-white is-small" target="_blank" title="" style="visibility: hidden;"
                href="<?php echo $shipment_setting_url . '&t=checkout' ?>">
                <i data-lucide="sliders-horizontal" class="icon-sm"></i>
            </a>
        </div>
    </div>

    <!-- Dynamic Shipping Methods -->
    <div class="columns is-vcentered is-mobile pl-4">
        <div class="column is-half">
            Dynamic Shipping Methods (Premium):
        </div>
        <div class="column is-half has-text-right">
            <div class="toggle-button is-inline-block">
                <div class="field">
                    <label class="switch is-rounded">
                        <input class="bt_sst_call_ajax" ng-model="fetch_dynamic_ship_method"
                            ng-change="updateDynamicShipMethodSettings()" type="checkbox"
                            value="fetch_dynamic_ship_method">
                        <span class="check"></span>
                        <span class="control-label">On</span>
                    </label>
                </div>
            </div>
            <a class="button is-white is-small" target="_blank" title="Configure Dynamic Shipping Method Widget"
                href="<?php echo $shipment_setting_url . '&t=checkout' ?>">
                <i data-lucide="sliders-horizontal" class="icon-sm"></i>
            </a>
          
        </div>
    </div>

    <!-- Google API Key -->
    <div class="columns is-vcentered is-mobile">
        <div class="column is-half">
            <strong>7. Set Google API Key:</strong>
        </div>
        <div class="column is-half">
            <div class="field has-addons is-justify-content-flex-end">
                <div class="control is-expanded">
                    <input ng-model="google_api_key" class="input is-small" type="text"
                        placeholder="Enter Google API Key">
                        <a target="_blank" href="https://developers.google.com/maps/documentation/geocoding/get-api-key">Click here to get Google Geocode Api Key</a>
                </div>
                <div class="control">
                    <button class="button is-primary is-small" ng-click="updateGoogleApiSettings()">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="box">
    <p class="title is-6">Support Us & Do Even More with <strong>Shipment Tracker Premium</strong>!</p>
    <ol class="content ml-4">
        <li>
            <strong>Custom Order Status:</strong>
            <a href="#">Configure Order &lt;-&gt; Tracking Statuses</a>
        </li>
        <li>Integrate any courier with Ship24.com</li>
        <li>
            <strong>Advanced Tracking Widget Features:</strong>
            <ul>
                <li>Show tracking details in My Account</li>
                <li>Multiple tracking form & page designs</li>
                <li>Pickup & Delivery map on tracking page</li>
                <li>Rating bar in tracking page</li>
                <li>Send tracking info in WooCommerce emails</li>
            </ul>
        </li>
        <li>
            <strong>Advanced Shipping Provider Integration:</strong>
            <ul>
                <li>Sync tracking data periodically</li>
                <li>Auto-push order details to courier</li>
                <li>Fetch real-time courier rates by weight & size</li>
            </ul>
        </li>
        <li>Add & show processing days in delivery estimates</li>
    </ol>
</div>

<style>
.quick-settings .columns {
    border-bottom: 1px solid #e6e6e6;
}
.control select{
    background: none;
}
</style>
<script>

    jQuery(document).ready(function ($) {
        fetchShipmentData();
    });
</script>