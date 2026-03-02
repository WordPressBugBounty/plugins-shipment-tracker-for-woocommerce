<?php
$settings = get_option('ithink_logistics_settings');
$api_key = isset($settings['api_key']) ? $settings['api_key'] : '';
$api_secret = isset($settings['api_secret']) ? $settings['api_secret'] : '';
$warehouse_id = isset($settings['warehouse_id']) ? $settings['warehouse_id'] : '';
$pickup_pincode = isset($settings['pickup_pincode']) ? $settings['pickup_pincode'] : '';
$shipping_mode  = isset($settings['shipping_mode']) ? $settings['shipping_mode'] : 'Surface';
$cron_schedule  = isset($settings['cron_schedule']) ? $settings['cron_schedule'] : '';
$store_id  = isset($settings['store_id']) ? $settings['store_id'] : '';
$auto_push = isset($settings['auto_push']) ? $settings['auto_push'] : '0';

if (isset($_POST['ithink_settings_submit'])) {

    if (!isset($_POST['ithink_nonce']) || !wp_verify_nonce($_POST['ithink_nonce'], 'ithink_save_settings')) {
        die('Security check failed');
    }

    $api_key     = sanitize_text_field($_POST['ithink_api_key']);
    $api_secret  = sanitize_text_field($_POST['ithink_api_secret']);
    $warehouse_id = sanitize_text_field($_POST['ithink_warehouse_id']);
    $pickup_pincode = sanitize_text_field($_POST['ithink_pickup_pincode']);
    $shipping_mode  = sanitize_text_field($_POST['ithink_shipping_mode']);
    $store_id  = sanitize_text_field($_POST['ithink_store_id']);
    $cron_schedule  = sanitize_text_field($_POST['ithink_cron_schedule']);
    $auto_push = isset($_POST['ithink_auto_push']) ? '1' : '0';

    $ithink_settings = array(
        'api_key'      => $api_key,
        'api_secret'   => $api_secret,
        'warehouse_id' => $warehouse_id,
        'pickup_pincode' => $pickup_pincode,
        'shipping_mode'  => $shipping_mode,
        'store_id'  => $store_id,
        'cron_schedule'  => $cron_schedule,
        'auto_push'      => $auto_push,
    );

    update_option('ithink_logistics_settings', $ithink_settings);

    echo '<div class="notice notice-success is-dismissible"><p>Settings Saved Successfully!</p></div>';
}
?>

<div class="wrap container is-max-desktop has-background-white mt-5 p-5">
    <div class="container">
        <nav class="panel is-dark">
            <div class="panel-heading">
                <div class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <div>
                                <p class="title has-text-white">Shipment Tracker For Woocommerce</p>
                                <p class="heading has-text-grey-light">Premium Shipment Tracking System (Free Version)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="content">
            <p class="subtitle" style="font-weight: bold;">iThink Logistics Settings</p>
            <form method="post" action="">
                <?php wp_nonce_field('ithink_save_settings', 'ithink_nonce'); ?>
                <div class="box">
                    <div class="notification is-light is-link mb-5">
                        <div class="columns is-vcentered">
                            <div class="column">
                                <p class="is-size-7 mb-2"><strong>Need help finding your credentials?</strong></p>
                                <div class="buttons">
                                    <a href="https://my.ithinklogistics.com/view-store-v3" target="_blank" class="button is-small is-info is-outlined is-flex is-align-items-center">
                                        <span class="icon is-small"><span class="dashicons dashicons-external"></span></span>
                                        <span>Get API Credentials</span>
                                    </a>
                                    <a href="https://my.ithinklogistics.com/v4/account-setting/6" target="_blank" class="button is-small is-info is-outlined is-flex is-align-items-center">
                                        <span class="icon is-small"><span class="dashicons dashicons-store"></span></span>
                                        <span>Find Warehouse ID</span>
                                    </a>
                                    <a href="https://my.ithinklogistics.com/view-store-v3" target="_blank" class="button is-small is-info is-outlined is-flex is-align-items-center">
                                        <span class="icon is-small"><span class="dashicons dashicons-id"></span></span>
                                        <span>Store ID</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">API Access Token</label>
                        <div class="control has-icons-right">
                            <input class="input password-field" type="password" value="<?php echo esc_attr($api_key) ?>" name="ithink_api_key" required>
                            <span class="icon is-small is-right is-clickable toggle-password" style="pointer-events: all; cursor: pointer;">
                                <span class="dashicons dashicons-visibility"></span>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">API Secret Key</label>
                        <div class="control has-icons-right">
                            <input class="input password-field" type="password" value="<?php echo esc_attr($api_secret) ?>" name="ithink_api_secret" required>
                            <span class="icon is-small is-right is-clickable toggle-password" style="pointer-events: all; cursor: pointer;">
                                <span class="dashicons dashicons-visibility"></span>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Warehouse ID</label>
                        <div class="control">
                            <input class="input" type="text" value="<?php echo esc_attr($warehouse_id) ?>" name="ithink_warehouse_id" required>
                        </div>
                    </div>
                    <div class="field">
                        <div class="control">
                            <button type="button" id="api_test_connection_btn_ithink" class="button is-small">
                                Test Connection
                            </button>
                        </div>
                        <p class="help mt-2">
                            Please save all changes before testing the connection.
                        </p>
                    </div>
                    <div class="field">
                        <label class="label">Pickup Pincode</label>
                        <div class="control">
                            <input class="input" type="text" value="<?php echo esc_attr($pickup_pincode) ?>" name="ithink_pickup_pincode" required>
                            <p>Required. Enter pincode of your warehouse/pickup point. Should be same as given in shipping aggregator.</p>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Store Id</label>
                        <div class="control">
                            <input class="input" type="text" value="<?php echo esc_attr($store_id) ?>" name="ithink_store_id" required>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Default Shipping Mode</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="ithink_shipping_mode">
                                    <option value="Surface" <?php selected($shipping_mode, 'Surface'); ?>>Surface</option>
                                    <option value="Air" <?php selected($shipping_mode, 'Air'); ?>>Express</option>
                                </select>
                            </div>
                        </div>
                        <p class="help">Select the default mode for your shipments.</p>
                    </div>
                    <hr><div class="field">
                        <label class="label">Auto push orders to iThink Logistics</label>
                        <div class="control">
                            <label class="checkbox">
                                <input type="checkbox" name="ithink_auto_push" value="1" <?php checked($auto_push, '1'); ?>>
                                Enable automatic order push
                            </label>
                        </div>
                        <p class="help">
                            The plugin will automatically send orders to iThink Logistics when the order status is set to Processing.
                        </p>
                    </div><hr>
                    <div class="field">
                        <label class="label">Sync Tracking every (Premium Only)</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="ithink_cron_schedule">
                                    <option value="" <?php selected($cron_schedule, ''); ?>>Never</option>
                                    <option value="15mins" <?php selected($cron_schedule, '15mins'); ?>>15 mins</option>
                                    <option value="1hour" <?php selected($cron_schedule, '1hour'); ?>>1 hour</option>
                                    <option value="4hours" <?php selected($cron_schedule, '4hours'); ?>>4 hours</option>
                                    <option value="24hours" <?php selected($cron_schedule, '24hours'); ?>>24 hours</option>
                                </select>
                            </div>
                        </div>
                        <p class="help">Tracking information will be periodically synced from iThink Logistic at this interval. Use this option if auto sync is not working on your website.</p>
                    </div>
                </div>

                <div class="field is-grouped">
                    <div class="control">
                        <button type="submit" name="ithink_settings_submit" class="button is-link">
                            Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="api_test_connection_modal_ithink" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
        <p id="api_tc-m-content_ithink" class="modal-content"></p>
        <button type="button" id="api_tc_m_close_btn_ithink" class="delete" aria-label="close"></button>
        </header>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.closest('.control').querySelector('.password-field');
        const icon = this.querySelector('.dashicons');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('dashicons-visibility');
            icon.classList.add('dashicons-hidden');
        } else {
            input.type = 'password';
            icon.classList.remove('dashicons-hidden');
            icon.classList.add('dashicons-visibility');
        }
    });
});
</script>