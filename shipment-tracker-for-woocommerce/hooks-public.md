# Hooks

- [Actions](#actions)
- [Filters](#filters)

## Actions

*This project does not contain any WordPress actions.*

## Filters

### `bt_edd_variables`

*The public-facing functionality of the plugin.*

Defines the plugin name, version, and two examples hooks for how to
enqueue the public-facing stylesheet and JavaScript.

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$edd_variables` |  | 
`$product_id` |  | 

Source: [public/class-bt-sync-shipment-tracking-public.php](class-bt-sync-shipment-tracking-public.php), [line 15](class-bt-sync-shipment-tracking-public.php#L15-L1172)

### `bt_edd_message_text`

*The public-facing functionality of the plugin.*

Defines the plugin name, version, and two examples hooks for how to
enqueue the public-facing stylesheet and JavaScript.

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$message_text_template` |  | 
`$edd_variables` |  | 
`$product_id` |  | 

Source: [public/class-bt-sync-shipment-tracking-public.php](class-bt-sync-shipment-tracking-public.php), [line 15](class-bt-sync-shipment-tracking-public.php#L15-L1189)

### `bt_dynamic_courier_rates`

*The public-facing functionality of the plugin.*

Defines the plugin name, version, and two examples hooks for how to
enqueue the public-facing stylesheet and JavaScript.

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$rates` |  | 
`$package` |  | 
`$bt_sst_courier_rate_provider` |  | 

Source: [public/class-bt-sync-shipment-tracking-public.php](class-bt-sync-shipment-tracking-public.php), [line 15](class-bt-sync-shipment-tracking-public.php#L15-L2489)

### `bt_sst_shipping_status_message`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`"Your order has been " . $shipped_string` |  | 
`$shipment_status` |  | 

Source: [public/partials/bt_shipping_tracking_page_template_second.php](partials/bt_shipping_tracking_page_template_second.php), [line 166](partials/bt_shipping_tracking_page_template_second.php#L166-L166)

### `bt_sst_shipping_status_message`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`"Your order has been " . $shipped_string` |  | 
`$shipment_status` |  | 

Source: [public/partials/bt_shipping_tracking_form_2_email.php](partials/bt_shipping_tracking_form_2_email.php), [line 60](partials/bt_shipping_tracking_form_2_email.php#L60-L60)

### `bt_sst_product_page_delivery_checker_label_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`carbon_get_theme_option('bt_sst_product_page_delivery_checker_label')` |  | 

Source: [public/partials/input_box_pincode_show_prime_x.php](partials/input_box_pincode_show_prime_x.php), [line 23](partials/input_box_pincode_show_prime_x.php#L23-L23)

### `bt_sync_shimpent_track_pincode_checker_shipping_to_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$bt_sync_shimpent_track_pincode_checker_shipping_to_text` |  | 

Source: [public/partials/input_box_pincode_show_prime_x.php](partials/input_box_pincode_show_prime_x.php), [line 36](partials/input_box_pincode_show_prime_x.php#L36-L36)

### `bt_sst_product_page_delivery_checker_label_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`carbon_get_theme_option('bt_sst_product_page_delivery_checker_label')` |  | 

Source: [public/partials/bt_sst_pincode_sow_realistic.php](partials/bt_sst_pincode_sow_realistic.php), [line 14](partials/bt_sst_pincode_sow_realistic.php#L14-L14)

### `bt_sync_shimpent_track_pincode_checker_shipping_to_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$bt_sync_shimpent_track_pincode_checker_shipping_to_text` |  | 

Source: [public/partials/bt_sst_pincode_sow_realistic.php](partials/bt_sst_pincode_sow_realistic.php), [line 25](partials/bt_sst_pincode_sow_realistic.php#L25-L25)

### `bt_sst_shipping_status_message`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`"Your order has been " . $shipped_string` |  | 
`$shipment_status` |  | 

Source: [public/partials/bt_shipping_tracking_form_2.php](partials/bt_shipping_tracking_form_2.php), [line 164](partials/bt_shipping_tracking_form_2.php#L164-L164)

### `bt_tracking_form_heading_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`'Take control of your deliveries<br>from the comfort of your home.'` |  | 

Source: [public/partials/bt_shipping_tracking_page_primary_template_second.php](partials/bt_shipping_tracking_page_primary_template_second.php), [line 12](partials/bt_shipping_tracking_page_primary_template_second.php#L12-L12)

### `bt_sst_product_page_delivery_checker_label_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`carbon_get_theme_option('bt_sst_product_page_delivery_checker_label')` |  | 

Source: [public/partials/input_box_pincode_show_data.php](partials/input_box_pincode_show_data.php), [line 25](partials/input_box_pincode_show_data.php#L25-L25)

### `bt_sync_shimpent_track_pincode_checker_shipping_to_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$bt_sync_shimpent_track_pincode_checker_shipping_to_text` |  | 

Source: [public/partials/input_box_pincode_show_data.php](partials/input_box_pincode_show_data.php), [line 36](partials/input_box_pincode_show_data.php#L36-L36)


<p align="center"><a href="https://github.com/pronamic/wp-documentor"><img src="https://cdn.jsdelivr.net/gh/pronamic/wp-documentor@main/logos/pronamic-wp-documentor.svgo-min.svg" alt="Pronamic WordPress Documentor" width="32" height="32"></a><br><em>Generated by <a href="https://github.com/pronamic/wp-documentor">Pronamic WordPress Documentor</a> <code>1.2.0</code></em><p>

