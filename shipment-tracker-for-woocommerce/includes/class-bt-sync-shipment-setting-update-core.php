<?php

class Class_bt_sync_shipment_setting_update_core
{

    public function update_estimated_delivery_settings()
    {
        if(!carbon_get_theme_option('bt_sst_pincode_checker_location')){
            carbon_set_theme_option('bt_sst_pincode_checker_location', 'woocommerce_after_add_to_cart_button');
        }
        carbon_set_theme_option('bt_sst_message_text_template', '🚚 Delivery by <b>#min_date#</b> to #city# - #pincode# <br> #cutoff_time#<br>#processing_time# #shipping_timeline#');
        carbon_set_theme_option('bt_sst_product_page_delivery_checker_label', 'Check delivery options in your location');
        $bt_sst_default_shipping_provider = carbon_get_theme_option('bt_sst_default_shipping_provider');
        $bt_sst_pincode_data_provider = carbon_get_theme_option('bt_sst_pincode_data_provider');

        if (!$bt_sst_pincode_data_provider || $bt_sst_pincode_data_provider != $bt_sst_default_shipping_provider) {
            // If no pincode data provider is set or it doesn't match the default shipping provider,
            // set the pincode data provider to the default shipping provider
            
            if ($bt_sst_default_shipping_provider) {
                carbon_set_theme_option('bt_sst_pincode_data_provider', $bt_sst_default_shipping_provider);
            } else {
                carbon_set_theme_option('bt_sst_pincode_data_provider', 'manual');
                carbon_set_theme_option('bt_sst_default_shipping_provider', 'manual');
            }
        }
        $bt_sst_pincode_data_provider = carbon_get_theme_option('bt_sst_pincode_data_provider');
        if ($bt_sst_pincode_data_provider) {
            if ($bt_sst_pincode_data_provider == 'manual') {
                carbon_set_theme_option('bt_sst_pincode_checker_location', 'woocommerce_after_add_to_cart_form');
                carbon_set_theme_option('bt_sst_enabled_custom_shipping_mode', 'manual');
                carbon_set_theme_option('bt_sst_enabled_shipping_providers', 'manual');

                carbon_set_theme_option('bt_sst_add_shipping_methods_second', [
                    [
                        'value' => "_",
                        'bt_sst_shipping_method' => ['manual'],
                        'bt_sst_rate_type' => ['rate_per_500gm'],
                        'bt_sst_prepaid_rate' => ['50'],
                        'bt_sst_cod_rate' => ['50'],
                        'bt_sst_courier_type' => ['domestic'],
                    ],
                ]);

                carbon_set_theme_option('bt_sst_pincode_estimate_generic_provider_second', [
                    [
                        'value' => "_",
                        'bt_sst_product_page_generic_domestic_min_days' => ['3'],
                        'bt_sst_product_page_generic_domestic_max_days' => ['5'],
                        'bt_sst_product_page_generic_domestic_min_charges' => ['50'],
                        'bt_sst_product_page_generic_domestic_max_charges' => ['100'],
                    ],
                ]);
            } else if ($bt_sst_pincode_data_provider == 'delhivery') {
                carbon_set_theme_option('bt_sst_delhivery_pincodepickup', $this->get_pickup_pincode());
            } else if ($bt_sst_pincode_data_provider == 'nimbuspost_new') {
                carbon_set_theme_option('bt_sst_nimbuspost_pickup_pincode', $this->get_pickup_pincode());
            } else if ($bt_sst_pincode_data_provider == 'shipmozo') {
                carbon_set_theme_option('bt_sst_shipmozo_pickup_pincode', $this->get_pickup_pincode());
            } else if ($bt_sst_pincode_data_provider == 'shiprocket') {
                carbon_set_theme_option('bt_sst_shiprocket_pickup_pincode', $this->get_pickup_pincode());
            } else if ($bt_sst_pincode_data_provider == 'fship') {
                carbon_set_theme_option('bt_sst_fship_pickup_pincode', $this->get_pickup_pincode());
            }

        }
    // update_option('product_url_etd', $this->get_first_published_product_url());
    return $this->get_first_published_product_url();

    }

    public function update_primary_shipping_method_settings($new_shiping_provider)
    {
        $enabled_shipping_providers = carbon_get_theme_option('bt_sst_enabled_shipping_providers', []);


        if (!in_array($new_shiping_provider, $enabled_shipping_providers)) {
            $enabled_shipping_providers[] = $new_shiping_provider;
            carbon_set_theme_option('bt_sst_enabled_shipping_providers', $enabled_shipping_providers);
        }

        carbon_set_theme_option('bt_sst_default_shipping_provider', $new_shiping_provider);

        // $bt_sst_pincode_data_provider = carbon_get_theme_option('bt_sst_pincode_data_provider');
        if ($new_shiping_provider == 'delhivery' || $new_shiping_provider == 'nimbuspost_new' || $new_shiping_provider == 'shipmozo' || $new_shiping_provider == 'shiprocket' || $new_shiping_provider == 'fship') {
            carbon_set_theme_option('bt_sst_pincode_data_provider', $new_shiping_provider);
            carbon_set_theme_option('bt_sst_courier_rate_provider', $new_shiping_provider);
        }else{
            carbon_set_theme_option('bt_sst_pincode_data_provider', 'manual');
            carbon_set_theme_option('bt_sst_courier_rate_provider', 'generic');
        }
        if ($new_shiping_provider == 'delhivery' || $new_shiping_provider == 'shiprocket') {
            carbon_set_theme_option('bt_sst_data_provider', $new_shiping_provider);
        }

        $res = $this->get_shipping_provider_configure_url($new_shiping_provider);
        return $res;

    }

    public function get_shipping_provider_configure_url($shipping_provider)
    {
        $url = "";
        $shipment_setting_url = admin_url('admin.php?page=crb_carbon_fields_container_shipment_tracking.php');
        if ($shipping_provider == 'manual') {
            $url = $shipment_setting_url . '&t=manual';
        } else if ($shipping_provider == 'delhivery') {
            $url = $shipment_setting_url . '&t=delhivery';
        } else if ($shipping_provider == 'nimbuspost_new') {
            $url = $shipment_setting_url . '&t=nimbuspost_new';
        } else if ($shipping_provider == 'shipmozo') {
            $url = $shipment_setting_url . '&t=shipmozo';
        } else if ($shipping_provider == 'shiprocket') {
            $url = $shipment_setting_url . '&t=shiprocket';
        } else if ($shipping_provider == 'fship') {
            $url = $shipment_setting_url . '&t=fship';
        } else if ($shipping_provider == 'xpressbees') {
            $url = $shipment_setting_url . '&t=xpressbees';
        } else if ($shipping_provider == 'nimbuspost') {
            $url = $shipment_setting_url . '&t=nimbuspost';
        }
        return $url;
    }

    public function handle_create_tracking_page($tracking_page_status)
    {
        $option_name = '_bt_sst_tracking_page';
        $page_id = get_option($option_name);
        if ($tracking_page_status === 'false') {
            if ($page_id && get_post($page_id)) {
                $page_data = [
                    'ID' => $page_id,
                    'post_status' => 'draft',
                ];
                wp_update_post($page_data);
            }
            return false;
        }

        $this->handle_tracking_page_data_save();
        if ($page_id && get_post_status($page_id) && get_post_status($page_id) !== 'trash') {
            if (get_post_status($page_id) !== 'publish') {
                $page_data = [
                    'ID' => $page_id,
                    'post_status' => 'publish',
                ];
                wp_update_post($page_data);
            }
        } else {
            $page_args = [
                'post_title' => 'Shipment Tracking',
                'post_content' => '[bt_shipping_tracking_form_2]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => get_current_user_id() ?: 1,
            ];

            $new_page_id = wp_insert_post($page_args);

            if (is_wp_error($new_page_id) || $new_page_id === 0) {
                return false;
            }
            $page_id = $new_page_id;
        }
        update_option($option_name, $page_id);
        return get_permalink($page_id);
    }

    public function handle_tracking_page_data_save()
    {	
        carbon_set_theme_option('bt_sst_show_tracking_now_button_myaccount_order_list', 1);
        carbon_set_theme_option('bt_sst_show_shipment_info_myaccount_orders', 1);
        carbon_set_theme_option('bt_sst_shipment_info_myaccount_order_detail', 1);
        carbon_set_theme_option('bt_sst_shipment_info_show_fields', [
            'shipment_status',
            'edd',
            'courier_name',
            'awb_number',
            'tracking_link',
        ]);
        carbon_set_theme_option('bt_sst_tracking_link_types', 'website');
        carbon_set_theme_option('bt_sst_tracking_page_template', 'classic');
        carbon_set_theme_option('bt_sst_selected_tracking_template', 'classic_template');
    }
    public function handle_timer($type)
    {
        if ($type === 'false') {
            $form_data = [
                'bt_sst_quill_editer_html' => 'Order within', // from hidden input
                'bt_sst_quill_editer_html_subheading' => 'Free shipping', // from hidden input
                'bt_sst_timer_location' => '', // selected option in <select>
                'bt_sst_timer_hours' => '0', // input value
                'bt_sst_timer_minutes' => '5', // input value
                'bt_sst_timer_seconds' => '0', // input value
                'set_timing_cookie' => 'yes', // selected radio
                'free_shipping' => 'yes', // selected radio
                'discount_percentage' => '50', // value from hidden field
                'selected_color_timer_count_down' => '#740707', // input color
                'selected_color_timer_container' => '#ffffff', // input color
                'bt_sst_timer_enable' => false, // input color
            ];
            update_option('bt_sst_timer_settings', $form_data);
            // update_option('product_url_timer', false);
            return false;

        } else {
            $form_data = [
                'bt_sst_quill_editer_html' => 'Order within', // from hidden input
                'bt_sst_quill_editer_html_subheading' => 'Free shipping', // from hidden input
                'bt_sst_timer_location' => 'woocommerce_after_add_to_cart_form', // selected option in <select>
                'bt_sst_timer_hours' => '0', // input value
                'bt_sst_timer_minutes' => '5', // input value
                'bt_sst_timer_seconds' => '0', // input value
                'set_timing_cookie' => 'yes', // selected radio
                'free_shipping' => 'yes', // selected radio
                'discount_percentage' => '50', // value from hidden field
                'selected_color_timer_count_down' => '#740707', // input color
                'selected_color_timer_container' => '#ffffff', // input color
                'bt_sst_timer_enable' => true, // input color
            ];
            update_option('bt_sst_timer_settings', $form_data);
            // update_option('product_url_timer', $this->get_first_published_product_url());
            return $this->get_first_published_product_url();
        }
        
    }

    public function handle_dynamic_ship_method($type)
    {
        if ($type === 'false') {
            carbon_set_theme_option('bt_sst_select_courier_company', '');
        } else {
            carbon_set_theme_option('bt_sst_select_courier_company', true);
            $bt_sst_courier_rate_provider = carbon_get_theme_option('bt_sst_courier_rate_provider');
            // if (!$bt_sst_courier_rate_provider) {
                $bt_sst_default_shipping_provider = carbon_get_theme_option('bt_sst_default_shipping_provider');
                if ($bt_sst_default_shipping_provider) {
                    carbon_set_theme_option('bt_sst_courier_rate_provider', $bt_sst_default_shipping_provider);
                } else {
                    carbon_set_theme_option('bt_sst_courier_rate_provider', 'manual');
                    carbon_set_theme_option('bt_sst_default_shipping_provider', 'manual');
                }
            // }else{

            // }

            $bt_sst_courier_rate_provider = carbon_get_theme_option('bt_sst_courier_rate_provider');
            if ($bt_sst_courier_rate_provider) {
                carbon_set_theme_option('bt_sst_markup_charges', "20");

                switch ($bt_sst_courier_rate_provider) {
                    case 'manual':
                        // Logic for manual shipping method
                        carbon_set_theme_option('bt_sst_add_shipping_methods', array(
                            array(
                                'value' => "_",
                                'bt_sst_shipping_method' => array('manual'),
                                'bt_sst_rate_type' => array('rate_per_500gm'),
                                'bt_sst_prepaid_rate' => array('50'),
                                'bt_sst_cod_rate' => array('50'),
                                'bt_sst_courier_type' => array('domestic'),
                            ),
                        ));
                        carbon_set_theme_option('bt_sst_pincode_estimate_generic_provider', array(
                            array(
                                'value' => "_",
                                'bt_sst_product_page_generic_domestic_min_days' => array('3'),
                                'bt_sst_product_page_generic_domestic_max_days' => array('5'),
                                'bt_sst_product_page_generic_domestic_min_charges' => array('50'),
                                'bt_sst_product_page_generic_domestic_max_charges' => array('100'),
                            ),
                        ));
                        break;
                    case 'delhivery':
                        carbon_set_theme_option('bt_sst_delhivery_fall_back_rate', "40");

                        break;
                    case 'nimbuspost_new':
                        carbon_set_theme_option('bt_sst_nimbuspost_new_fall_back_rate', "40");
                        carbon_set_theme_option('bt_sst_nimbuspost_pickup_pincode', $this->get_pickup_pincode());

                        break;
                    case 'shipmozo':
                        carbon_set_theme_option('bt_sst_shipmozo_fall_back_rate', "40");
                        carbon_set_theme_option('api_call_for_sync_order_by_order_id', $this->get_pickup_pincode());
                        carbon_set_theme_option('bt_sst_show_delivery_date', 1);

                        break;
                    case 'shiprocket':
                        carbon_set_theme_option('bt_sst_shiprocket_fall_back_rate', "40");
                        carbon_set_theme_option('bt_sst_shiprocket_pickup_pincode', $this->get_pickup_pincode());

                        break;
                    case 'fship':
                        $this->dynamic_ship_method_fship();
                        break;
                }

            }

        }
        return true;
    }

    public function get_pickup_pincode()
    {
        return get_option('woocommerce_store_postcode', '');
    }

    public function get_first_published_product_url() {
    // Query for the first published product
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'ASC',
        'fields'         => 'ids', // Only get IDs for performance
    );

    $product_query = new WP_Query($args);

    if ( $product_query->have_posts() ) {
        $product_id = $product_query->posts[0];
        return get_permalink($product_id);
    }

    return false; // No product found
    }


}