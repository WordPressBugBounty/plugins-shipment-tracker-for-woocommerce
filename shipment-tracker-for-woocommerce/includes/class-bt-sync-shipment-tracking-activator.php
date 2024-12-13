<?php

/**
 * Fired during plugin activation
 *
 * @link       https://amitmittal.tech
 * @since      1.0.0
 *
 * @package    Bt_Sync_Shipment_Tracking
 * @subpackage Bt_Sync_Shipment_Tracking/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Bt_Sync_Shipment_Tracking
 * @subpackage Bt_Sync_Shipment_Tracking/includes
 * @author     Amit Mittal <amitmittal@bitsstech.com>
 */
class Bt_Sync_Shipment_Tracking_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if ( 
			! get_option('_bt_sst_shipment_when_to_send_messages|||0|value') &&
			! get_option('_bt_sst_shipment_when_to_send_messages|||1|value') &&
			! get_option('_bt_sst_shipment_when_to_send_messages|||2|value') &&
			! get_option('_bt_sst_shipment_from_what_send_messages|||0|value') &&
			! get_option('_bt_sst_show_shipment_info_myaccount_orders') &&
			! get_option('_bt_sst_show_shipment_info_myaccount_order_detail') &&
			! get_option('_bt_sst_show_shipment_info_in_woocommerce') &&
			! get_option('_bt_sst_shipment_info_show_fields|||0|value') &&
			! get_option('_bt_sst_shipment_info_show_fields|||1|value') &&
			! get_option('_bt_sst_shipment_info_show_fields|||2|value') &&
			! get_option('_bt_sst_shipment_info_show_fields|||3|value') &&
			! get_option('_bt_sst_shipment_info_show_fields|||4|value') &&
			! get_option('_bt_sst_tracking_link_types')
		) {
			update_option('_bt_sst_shipment_when_to_send_messages|||0|value', 'in_transit');
			update_option('_bt_sst_shipment_when_to_send_messages|||1|value', 'out_for_delivery');
			update_option('_bt_sst_shipment_when_to_send_messages|||2|value', 'delivered');
			update_option('_bt_sst_shipment_from_what_send_messages|||0|value', 'email');
			update_option('_bt_sst_show_shipment_info_myaccount_orders', 'no');
			update_option('_bt_sst_show_shipment_info_myaccount_order_detail', 'no');
			update_option('_bt_sst_show_shipment_info_in_woocommerce', 'no');
			update_option('_bt_sst_shipment_info_show_fields|||0|value', 'shipment_status');
			update_option('_bt_sst_shipment_info_show_fields|||1|value', 'edd');
			update_option('_bt_sst_shipment_info_show_fields|||2|value', 'courier_name');
			update_option('_bt_sst_shipment_info_show_fields|||3|value', 'awb_number');
			update_option('_bt_sst_shipment_info_show_fields|||4|value', 'tracking_link');
			update_option('_bt_sst_tracking_link_types', 'website');
		}		

		$tracking_page_title = 'Tracking Page';
		$page = get_page_by_title($tracking_page_title, OBJECT, 'page');
	
		if (!$page) {
			$page_id = wp_insert_post(array(
				'post_title'   => $tracking_page_title,
				'post_content' => '[bt_shipping_tracking_form_2]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			));
	
			if ($page_id) {
			   update_option( '_bt_sst_tracking_page', $page_id);
			}
		}

	    //store plugin activation time
        add_option( '_bt_sst_activated_time', time(), '', false );
		$enabled_shipping_providers = get_option( '_bt_sst_enabled_shipping_providers' );
		if(!$enabled_shipping_providers){
			update_option('_bt_sst_enabled_shipping_providers|||0|value', 'manual');
			update_option('_bt_sst_enabled_custom_shipping_mode', "manual");
			update_option('_bt_sst_default_shipping_provider', "manual");
		}
	}

}
