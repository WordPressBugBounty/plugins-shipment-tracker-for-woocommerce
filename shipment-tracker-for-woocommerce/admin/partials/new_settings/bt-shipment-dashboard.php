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
    

    <div class="mb-4 is-size-3 is-size-3-mobile has-text-weight-bold">Core Features</div>
    <div class="columns is-multiline">
      <div class="column is-12 is-6-desktop">
            <div class="mb-6 is-flex">
              <span>
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                  <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                </svg></span>
              <div class="ml-3">
                <h4 class="is-size-4 has-text-weight-bold mb-2">Order Shipment Tracking</h4>
                <p class="subtitle has-text-grey">
                    <ol>
                        <li>Add, update, and display shipment tracking information for WooCommerce orders.</li>
                        <li>Supports multiple shipping providers (Delhivery, Nimbuspost, Shiprocket, Shipmozo, Xpressbees, Fship, Ekart, Custom/Manual, etc.).</li>
                        <li>Store and retrieve tracking numbers, courier names, estimated delivery dates, and tracking URLs.</li>
                    </ol>
                </p>
              </div>
            </div>
            <div class="mb-6 is-flex">
              <span>
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                  <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                </svg></span>
              <div class="ml-3">
                <h4 class="is-size-4 has-text-weight-bold mb-2">Automatic Shipment Status Sync</h4>
                <p class="subtitle has-text-grey">
                    <ol>
                        <li>Sync shipment status automatically from supported courier APIs.</li>
                        <li>Force manual sync for any order.</li>
                   </ol>
                </p>
              </div>
            </div>
            
      </div>
     
        <div class="column is-12 is-6-desktop">
            <div class="mb-6 is-flex">
              <span>
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                  <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                </svg></span>
              <div class="ml-3">
                <h4 class="is-size-4 has-text-weight-bold mb-2">Custom Shipping Providers</h4>
                <p class="subtitle has-text-grey">
                    <ol>
                        <li>Add and manage custom courier companies.</li>
                        <li>Use a manual mode and rest apis for unsupported providers.</li>
                   </ol>
                </p>
              </div>
            </div>
            <div class="mb-6 is-flex">
                <span>
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                    <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                    </svg></span>
                <div class="ml-3">
                    <h4 class="is-size-4 has-text-weight-bold mb-2">Order Meta & Tracking Data</h4>
                    <p class="subtitle has-text-grey">
                    <ol>
                        <li>Save and retrieve tracking data as order meta.</li>
                        <li>Public functions to get and update tracking info programmatically.</li>
                   </ol>
                </p>
                </div>
            </div>
           
      </div>
    </div>


    <div class="mb-4 is-size-3 is-size-3-mobile has-text-weight-bold">Customer Experience</div>
    <div class="columns is-multiline">
      <div class="column is-12 is-6-desktop">
            <div class="mb-6 is-flex">
              <span>
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                  <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                </svg></span>
              <div class="ml-3">
                <h4 class="is-size-4 has-text-weight-bold mb-2">Email Notifications</h4>
                <p class="subtitle has-text-grey">
                    <ol>
                        <li>Add tracking details to WooCommerce order emails.</li>
                        <li>Customizable email templates for shipment updates.</li>
                    </ol>
                </p>
              </div>
            </div>
            <div class="mb-6 is-flex">
              <span>
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                  <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                </svg></span>
              <div class="ml-3">
                <h4 class="is-size-4 has-text-weight-bold mb-2">SMS Notifications</h4>
                <p class="subtitle has-text-grey">
                    <ol>
                        <li>Send order and tracking updates via SMS.</li>
                        <li>Co-branded pre-approved DLT sms templates.</li>
                        <li>Integrate your DLT account to send branded sms.</li>
                   </ol>
                </p>
              </div>
            </div>

            <div class="mb-6 is-flex">
              <span>
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                  <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                </svg></span>
              <div class="ml-3">
                <h4 class="is-size-4 has-text-weight-bold mb-2">WhatsApp Notifications</h4>
                <p class="subtitle has-text-grey">
                    <ol>
                        <li>Send order and tracking updates via WhatsApp.</li>
                        <li>Co-branded pre-approved WhatsApp templates.</li>
                        <li>Integrate your meta account to send using your brand name.</li>
                   </ol>
                </p>
              </div>
            </div>
            
      </div>
     
        <div class="column is-12 is-6-desktop">
            <div class="mb-6 is-flex">
              <span>
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                  <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                </svg></span>
              <div class="ml-3">
                <h4 class="is-size-4 has-text-weight-bold mb-2">Frontend Tracking Page</h4>
                <p class="subtitle has-text-grey">
                    <ol>
                        <li>Generate a public tracking page for customers to check order status</li>
                        <li>Shortcodes and endpoints for embedding tracking forms.</li>
                   </ol>
                </p>
              </div>
            </div>
            <div class="mb-6 is-flex">
                <span>
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                    <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                    </svg></span>
                <div class="ml-3">
                    <h4 class="is-size-4 has-text-weight-bold mb-2">Estimated Delivery Checker</h4>
                    <p class="subtitle has-text-grey">
                        <ol>
                            <li>Product page pincode checker for delivery availability and estimated delivery date.</li>
                            <li>Customizable labels and messages via filters.</li>
                        </ol>
                    </p>
                </div>
            </div>

            <div class="mb-6 is-flex">
                <span>
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                    <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                    </svg></span>
                <div class="ml-3">
                    <h4 class="is-size-4 has-text-weight-bold mb-2">Dynamic Shipping Methods</h4>
                    <p class="subtitle has-text-grey">
                        <ol>
                            <li>Shipping rates based on customer location, package weight, and delivery speed.</li>
                            <li>Customers can choose their preferred courier during checkout.</li>
                        </ol>
                    </p>
                </div>
            </div>
           
      </div>
    </div>

    <div class="mb-4 is-size-3 is-size-3-mobile has-text-weight-bold">Admin & Developer Features</div>
    <div class="columns is-multiline">
      <div class="column is-12 is-6-desktop">
            <div class="mb-6 is-flex">
              <span>
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                  <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                </svg></span>
              <div class="ml-3">
                <h4 class="is-size-4 has-text-weight-bold mb-2">REST API/Webhook</h4>
                <p class="subtitle has-text-grey">
                    <ol>
                        <li>Webhook endpoint to update shipment tracking from external systems.</li>
                        <li>API key authentication for secure updates.</li>
                    </ol>
                </p>
              </div>
            </div>
            <div class="mb-6 is-flex">
              <span>
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                  <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                </svg></span>
              <div class="ml-3">
                <h4 class="is-size-4 has-text-weight-bold mb-2">Public PHP Functions</h4>
                <p class="subtitle has-text-grey">
                    <ol>
                        <li>Functions to get tracking info, force sync, update tracking, format status, and get city/state by pincode.</li>
                     
                   </ol>
                </p>
              </div>
            </div>

           
            
      </div>
     
        <div class="column is-12 is-6-desktop">
            <div class="mb-6 is-flex">
              <span>
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                  <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                </svg></span>
              <div class="ml-3">
                <h4 class="is-size-4 has-text-weight-bold mb-2">Hooks & Extensibility</h4>
                <p class="subtitle has-text-grey">
                    <ol>
                        <li>Multiple action and filter hooks for developers to extend or customize functionality.</li>
                        <li>Webhook receiver endpoint for 3rd party integrations to update shipment data.</li>
                   </ol>
                </p>
              </div>
            </div>
            <div class="mb-6 is-flex">
                <span>
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.6 22.9C25.7 23 25.8 23 26 23H33C33.6 23 34 22.6 34 22C34 21.8 34 21.7 33.9 21.6L30.4 14.6C30.1 14.1 29.5 13.9 29 14.2C28.9 14.3 28.7 14.4 28.6 14.6L25.1 21.6C24.9 22 25.1 22.6 25.6 22.9ZM29.5 17.2L31.4 21H27.6L29.5 17.2ZM18.5 14C16 14 14 16 14 18.5C14 21 16 23 18.5 23C21 23 23 21 23 18.5C23 16 21 14 18.5 14ZM18.5 21C17.1 21 16 19.9 16 18.5C16 17.1 17.1 16 18.5 16C19.9 16 21 17.1 21 18.5C21 19.9 19.9 21 18.5 21ZM22.7 25.3C22.3 24.9 21.7 24.9 21.3 25.3L18.5 28.1L15.7 25.3C15.3 24.9 14.7 24.9 14.3 25.3C13.9 25.7 13.9 26.3 14.3 26.7L17.1 29.5L14.3 32.3C13.9 32.7 13.9 33.3 14.3 33.7C14.7 34.1 15.3 34.1 15.7 33.7L18.5 30.9L21.3 33.7C21.7 34.1 22.3 34.1 22.7 33.7C23.1 33.3 23.1 32.7 22.7 32.3L19.9 29.5L22.7 26.7C23.1 26.3 23.1 25.7 22.7 25.3ZM33 25H26C25.4 25 25 25.4 25 26V33C25 33.6 25.4 34 26 34H33C33.6 34 34 33.6 34 33V26C34 25.4 33.6 25 33 25ZM32 32H27V27H32V32Z" fill="#00d1b2"></path>
                    <circle cx="24" cy="24" r="23.5" stroke="#00d1b2"></circle>
                    </svg></span>
                <div class="ml-3">
                    <h4 class="is-size-4 has-text-weight-bold mb-2">Custom Order Status</h4>
                    <p class="subtitle has-text-grey">
                        <ol>
                            <li>Sync order statuses with shipping status, from pending pickup to final delivery.</li>
                            <li>Customize status mappings to match your workflow</li>
                        </ol>
                    </p>
                </div>
            </div>

      </div>
    </div>

    <ol style="display:none" class="content ml-4">
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