<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://amitmittal.tech
 * @since      1.0.0
 *
 * @package    Bt_Sync_Shipment_Tracking
 * @subpackage Bt_Sync_Shipment_Tracking/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bt_Sync_Shipment_Tracking
 * @subpackage Bt_Sync_Shipment_Tracking/admin
 * @author     Amit Mittal <amitmittal@bitsstech.com>
 */
//ref: https://rudrastyh.com/woocommerce/customize-order-details.html
//ref: https://woocommerce.github.io/code-reference/hooks/hooks.html
class Bt_Sync_Shipment_Tracking_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	private $shiprocket;
	private $shipmozo;
	private $shyplite;
	private $nimbuspost;
	private $nimbuspost_new;
	private $manual;
	private $licenser;
	private $delhivery;
	private $ship24;
	private $fship;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version, $shiprocket, $shyplite, $nimbuspost, $manual, $licenser, $shipmozo, $nimbuspost_new, $delhivery, $ship24, $fship)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->shiprocket = $shiprocket;
		$this->shipmozo = $shipmozo;
		$this->shyplite = $shyplite;
		$this->nimbuspost = $nimbuspost;
		$this->nimbuspost_new = $nimbuspost_new;
		$this->manual = $manual;
		$this->licenser = $licenser;
		$this->delhivery = $delhivery;
		$this->ship24 = $ship24;
		$this->fship = $fship;
	}



	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bt_Sync_Shipment_Tracking_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bt_Sync_Shipment_Tracking_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		// CSS Files
		wp_register_style('angular-toastr', plugin_dir_url(__FILE__) . 'css/angular-toastr.css');
		wp_register_style('quill-css', plugin_dir_url(__FILE__) . 'css/quill.css');
		wp_register_style('bulma-css', plugin_dir_url(__FILE__) . 'css/bulma.min.css');

		$current_screen = get_current_screen();
		if ($current_screen != null && $current_screen->id == "shipment-tracking_page_crb_carbon_fields_container_shipment_tracking") {
			wp_enqueue_style('wp-jquery-ui-dialog');
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/bt-sync-shipment-tracking-admin.css', array(), $this->version, 'all');
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bt_Sync_Shipment_Tracking_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bt_Sync_Shipment_Tracking_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/bt-sync-shipment-tracking-admin.js', array('jquery', 'jquery-ui-dialog'), $this->version, false);
		    // JS Files
		wp_register_script('angular-js', plugin_dir_url(__FILE__) . 'js/angular.min.js', $this->version, false);
		wp_register_script('angular-toastr-js', plugin_dir_url(__FILE__) . 'js/angular-toastr.tpls.js',array('angular-js'), $this->version, false);
		wp_register_script('lucide-js', plugin_dir_url(__FILE__) . 'js/lucide.js', $this->version, false);
		wp_register_script('quill-js', plugin_dir_url(__FILE__) . 'js/quill.js', $this->version, false);
		wp_register_script('bt-shipment-new-settings-js', plugin_dir_url(__FILE__) . 'js/bt-shipment-new-settings-js.js', array('jquery', 'jquery-ui-dialog','lucide-js','quill-js','angular-toastr-js'), $this->version, false);

		
		$current_screen = get_current_screen();
		// echo json_encode($current_screen);exit;
		// echo json_encode($current_screen);exit;
		if ($current_screen != null && (
			$current_screen->id == "shipment-tracking_page_crb_carbon_fields_container_shipment_tracking" ||
			$current_screen->id == "woocommerce_page_wc-orders" || 
			$current_screen->id == "edit-shop_order" ||
			$current_screen->id == "shop_order" || 
			$current_screen->id == "shipment-tracking_page_bt-shipment-tracking-premium-activation" || 
			$current_screen->id == "shipment-tracking_page_bt-shipment-tracking-sms-setting" || 
			$current_screen->id == "shipment-tracking_page_bt-shipment-tracking-timer" || 
			$current_screen->id == "toplevel_page_bt-shipment-tracking"||
			$current_screen->id == "shipment-tracking_page_bt-shipment-tracking-help-support"
			)) {
			$script_data = array(

				"ajax_url" => admin_url('admin-ajax.php'),
				"plugin_public_url" => plugin_dir_url(__FILE__),
				"is_premium_active" => $this->licenser->get_license(),
				"test_conn_nonce" => wp_create_nonce('api_call_for_test_connection'),
				"sync_order_nonce" => wp_create_nonce('api_call_for_sync_order_by_order_id'),
				"show_st_popup" => wp_create_nonce('get_st_form_with_data'),
				"create_tracking_page" => wp_create_nonce('get_st_form_with_data'),
				"test_conn_shipmozo_nonce" => wp_create_nonce('api_call_for_shipmozo_test_connection'),
				"test_conn_delhivery_nonce" => wp_create_nonce('api_call_for_delhivery_test_connection'),
				"test_conn_fship_nonce" => wp_create_nonce('api_call_for_fship_test_connection'),
				"test_conn_ship24_nonce" => wp_create_nonce('api_call_for_ship24_test_connection'),
				"test_conn_nimbuspost_nonce" => wp_create_nonce('api_check_for_nimbuspost_test_connection'),
				"buy_credit_balance_nonce" => wp_create_nonce('buy_credit_balance'),
				"credit_balance_details_nonce" => wp_create_nonce('credit_balance_details'),
				"register_for_sms_nonce" => wp_create_nonce('register_for_sms'),
				"get_sms_trial_nonce" => wp_create_nonce('get_sms_trial'),


			);
			wp_localize_script($this->plugin_name, 'bt_sync_shipment_track_data', $script_data);
			wp_enqueue_script($this->plugin_name);

		}

		wp_register_script($this->plugin_name . "_deactivation", plugin_dir_url(__FILE__) . 'js/bt-sync-shipment-deactivation-admin.js', array('jquery', 'jquery-ui-dialog'), $this->version, false);
		if ($current_screen != null && ($current_screen->id == "plugins")) {
			$script_data = array(
				"ajax_url" => admin_url('admin-ajax.php'),
			);
			wp_localize_script($this->plugin_name . '_deactivation', 'bt_sync_shipment_track_deactivation', $script_data);
			wp_enqueue_script($this->plugin_name . "_deactivation");

		}

	}

	function plugin_admin_menu()
	{
		//add_menu_page( 'Sync Shipment Tracking Settings', 'Sync Shipment Tracking', 'manage_options', 'bt-sync-shipment-tracking-settings', array($this , 'render_admin_settings' ), );
	}

	function render_admin_settings()
	{
		$url = get_site_url(null, '/wp-json/bt-sync-shipment-tracking-shiprocket/v1.0.0/webhook_receiver');
		echo esc_html("Enter this url in Shiprocket webhook settings: " . esc_url($url));
	}

	public function custom_shop_order_column($columns)
	{
		$reordered_columns = array();
		// Inserting columns to a specific location
		foreach ($columns as $key => $column) {
			$reordered_columns[$key] = $column;
			if ($key == 'order_status') {
				// Inserting after "Status" column
				$reordered_columns['bt-shipping-status'] = 'Shipping Status';
			}
		}
		return $reordered_columns;
	}

	public function custom_orders_list_column_content_hpos($column, $order)
	{
		$this->custom_orders_list_column_content($column, $order->get_id());
	}

	public function custom_orders_list_column_content($column, $order_id)
	{
		switch ($column) {
			case 'bt-shipping-status':
				echo "<div class='bt-sync-tracking order-" . esc_attr($order_id) . "'>";
				include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/order_shipment_details.php';
				echo "</div>";

				break;
		}
	}

	public function show_order_shipping_admin($order)
	{
		$order_id = $order->get_id();
		include_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/order_admin_after_shipping.php';
		include_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/order_shipment_details.php';
	}

	public function woocommerce_process_shop_order_meta($order_id)
	{
		$new_provider = "";
		if (isset($_POST['wc_order_action_bt_sst_shipping_provider'])) {
			$new_provider = wc_clean(sanitize_text_field($_POST['wc_order_action_bt_sst_shipping_provider']));
		}
		if (!empty($new_provider)) {
			Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipping_provider', $new_provider);
		}

	}

	public function woocommerce_order_status_processing($order_id)
	{

		$enabled_shipping_providers = carbon_get_theme_option('bt_sst_enabled_shipping_providers');
		$bt_sst_default_shipping_provider = carbon_get_theme_option('bt_sst_default_shipping_provider');



		//check if default shipping provider is set and enabled.
		if (!empty($bt_sst_default_shipping_provider) && is_array($enabled_shipping_providers) && in_array($bt_sst_default_shipping_provider, $enabled_shipping_providers)) {
			$bt_shipping_provider = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_shipping_provider', true);
			//make sure a shipping provider is not already assigned.
			if (empty($bt_shipping_provider)) {
				$bt_shipping_provider = $bt_sst_default_shipping_provider;
				Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipping_provider', $bt_shipping_provider);
				//if custom shipping, then set shipping mode in order meta
				if ($bt_shipping_provider == "manual") {
					$shipping_mode_is_manual_or_ship24 = carbon_get_theme_option('bt_sst_enabled_custom_shipping_mode');
					Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_sst_custom_shipping_mode', $shipping_mode_is_manual_or_ship24);
				}
			}
			if ($bt_shipping_provider == "shiprocket") {
				$order = wc_get_order($order_id);
				$order->add_order_note('Added schedule to push this order to shiprocket.' . "\n\n- Shipment tracker for woocommerce", false);
				wp_schedule_single_event(time() + 10, 'bt_push_order_to_shiprocket', array($order_id));//schedule non blocking future event

			} else if ($bt_shipping_provider == "shipmozo") {
				$order = wc_get_order($order_id);
				$order->add_order_note('Added schedule to push this order to shipmozo.' . "\n\n- Shipment tracker for woocommerce", false);
				wp_schedule_single_event(time() + 10, 'bt_push_order_to_shipmozo', array($order_id));//schedule non blocking future event


			} else if ($bt_shipping_provider == "nimbuspost_new") {
				$order = wc_get_order($order_id);
				$order->add_order_note('Added schedule to push this order to nimbuspost.' . "\n\n- Shipment tracker for woocommerce", false);
				wp_schedule_single_event(time() + 10, 'bt_push_order_to_nimbuspost', array($order_id));//schedule non blocking future event

			} else if ($bt_shipping_provider == "delhivery") {
				$bt_sst_delhivery_push_order_method_is_automatic = carbon_get_theme_option('bt_sst_delhivery_push_orders');
				$is_premium = $this->licenser->is_license_active();
				if ($bt_sst_delhivery_push_order_method_is_automatic == 1 && $is_premium) {
					$order = wc_get_order($order_id);
					$order->add_order_note('Added schedule to push this order to delhivery.' . "\n\n- Shipment tracker for woocommerce", false);
					wp_schedule_single_event(time() + 1, 'bt_push_order_to_delhivery', array($order_id));

				}
			}

		} else {

		}

	}
	public function woocommerce_order_status_on_hold($order_id)
	{

		$enabled_shipping_providers = carbon_get_theme_option('bt_sst_enabled_shipping_providers');
		$bt_sst_default_shipping_provider = carbon_get_theme_option('bt_sst_default_shipping_provider');



		//check if default shipping provider is set and enabled.
		if (!empty($bt_sst_default_shipping_provider) && is_array($enabled_shipping_providers) && in_array($bt_sst_default_shipping_provider, $enabled_shipping_providers)) {
			$bt_shipping_provider = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_shipping_provider', true);
			//make sure a shipping provider is not already assigned.
			if (empty($bt_shipping_provider)) {
				$bt_shipping_provider = $bt_sst_default_shipping_provider;
				Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipping_provider', $bt_shipping_provider);
				//if custom shipping, then set shipping mode in order meta
				if ($bt_shipping_provider == "manual") {
					$shipping_mode_is_manual_or_ship24 = carbon_get_theme_option('bt_sst_enabled_custom_shipping_mode');
					Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_sst_custom_shipping_mode', $shipping_mode_is_manual_or_ship24);
				}
			}
			// if ($bt_shipping_provider == "shiprocket") {
			// 	$order = wc_get_order($order_id);
			// 	$order->add_order_note('Added schedule to push this order to shiprocket.' . "\n\n- Shipment tracker for woocommerce", false);
			// 	wp_schedule_single_event(time() + 10, 'bt_push_order_to_shiprocket', array($order_id));//schedule non blocking future event

			// } else if ($bt_shipping_provider == "shipmozo") {
			// 	$order = wc_get_order($order_id);
			// 	$order->add_order_note('Added schedule to push this order to shipmozo.' . "\n\n- Shipment tracker for woocommerce", false);
			// 	wp_schedule_single_event(time() + 10, 'bt_push_order_to_shipmozo', array($order_id));//schedule non blocking future event


			// } else if ($bt_shipping_provider == "nimbuspost_new") {
			// 	$order = wc_get_order($order_id);
			// 	$order->add_order_note('Added schedule to push this order to nimbuspost.' . "\n\n- Shipment tracker for woocommerce", false);
			// 	wp_schedule_single_event(time() + 10, 'bt_push_order_to_nimbuspost', array($order_id));//schedule non blocking future event
			// if($bt_shipping_provider=="shiprocket"){
			// 	$order = wc_get_order( $order_id );
			// 	$order->add_order_note('Added schedule to push this order to shiprocket.' . "\n\n- Shipment tracker for woocommerce", false );
			// 	wp_schedule_single_event( time()+10 , 'bt_push_order_to_shiprocket',array( $order_id)  );//schedule non blocking future event
			
			// }
			// else if($bt_shipping_provider=="shipmozo"){
			// 		$order = wc_get_order( $order_id );
			// 		$order->add_order_note('Added schedule to push this order to shipmozo.' . "\n\n- Shipment tracker for woocommerce", false );
			// 		wp_schedule_single_event( time()+10 , 'bt_push_order_to_shipmozo',array( $order_id)  );//schedule non blocking future event
				

			// }

			// else if($bt_shipping_provider=="nimbuspost_new"){
			// 	$order = wc_get_order( $order_id );
			// 	$order->add_order_note('Added schedule to push this order to nimbuspost.' . "\n\n- Shipment tracker for woocommerce", false );
			// 	wp_schedule_single_event( time()+10, 'bt_push_order_to_nimbuspost',array( $order_id)  );//schedule non blocking future event
		
			// }

			// else if($bt_shipping_provider=="delhivery"){
			// 	$bt_sst_delhivery_push_order_method_is_automatic = carbon_get_theme_option( 'bt_sst_delhivery_push_orders' );
			// 	$is_premium = $this->licenser->is_license_active();
			// 	if($bt_sst_delhivery_push_order_method_is_automatic ==1 && $is_premium){
			// 		$order = wc_get_order( $order_id );
			// 		$order->add_order_note('Added schedule to push this order to delhivery.' . "\n\n- Shipment tracker for woocommerce", false );
			// 		wp_schedule_single_event( time()+1, 'bt_push_order_to_delhivery',array( $order_id)  );
					
			// 	}
			// }

			// } else if ($bt_shipping_provider == "delhivery") {
			// 	$bt_sst_delhivery_push_order_method_is_automatic = carbon_get_theme_option('bt_sst_delhivery_push_orders');
			// 	$is_premium = $this->licenser->is_license_active();
			// 	if ($bt_sst_delhivery_push_order_method_is_automatic == 1 && $is_premium) {
			// 		$order = wc_get_order($order_id);
			// 		$order->add_order_note('Added schedule to push this order to delhivery.' . "\n\n- Shipment tracker for woocommerce", false);
			// 		wp_schedule_single_event(time() + 1, 'bt_push_order_to_delhivery', array($order_id));

			// 	}
			// }

		} else {

		}

	}


	public function push_order_to_shiprocket($order_id)
	{
		try {
			$order = wc_get_order($order_id);
			$bt_sst_shiprocket_push_orders = carbon_get_theme_option('bt_sst_shiprocket_push_orders');
			if ($bt_sst_shiprocket_push_orders != 1) {
				$order->add_order_note("Shiprocket Push turned off " . $bt_sst_shiprocket_push_orders . "\n\n- Shipment tracker for woocommerce", false);
				return;
			}


			if (!empty(Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_shiprocket_shipment_id', true))) {
				$order->add_order_note('Shipment is already assigned with id: ' . Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_shiprocket_shipment_id', true) . "\n\n- Shipment tracker for woocommerce", false);
				return;
			}

			$order->add_order_note('Pushing order to shiprocket:' . $order_id . "\n\n- Shipment tracker for woocommerce", false);
			$push_resp = $this->shiprocket->push_order_to_shiprocket($order_id);
			if ($push_resp != null) {

				if (isset($push_resp["order_id"]) && $push_resp["order_id"] && $push_resp["shipment_id"]) {

					Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shiprocket_shipment_id', $push_resp["shipment_id"]);
					Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shiprocket_order_id', $push_resp["order_id"]);
					$order->add_order_note("Order pushed to shiprocket. Shipment ID: " . $push_resp["shipment_id"] . ", Shiprocket Order ID: " . $push_resp["order_id"] . "\n\n- Shipment tracker for woocommerce", false);
					$is_premium = $this->licenser->is_license_active();
					$bt_sst_shiprocket_assign_courier_to_shipment = carbon_get_theme_option('bt_sst_shiprocket_assign_courier_to_shipment');
					if ($bt_sst_shiprocket_assign_courier_to_shipment == 1 && $is_premium) {
						//get selected courier, if any
						$courier_selected_via_shiprocket_plugin = false;
						foreach ($order->get_items('shipping') as $s_item_id => $s_item) {
							$ph_shiprocket_shipping_rates = $s_item->get_meta("ph_shiprocket_shipping_rates", true);
							if (!empty($ph_shiprocket_shipping_rates) && !empty($ph_shiprocket_shipping_rates["courier_company_id"])) {
								//call shiprocket api to book shipment using selected courier partner
								$book_resp = $this->shiprocket->book_shipment_courier($push_resp["shipment_id"], $ph_shiprocket_shipping_rates["courier_company_id"]);
								if ($book_resp && isset($book_resp["awb_assign_status"])) {
									$awb_code = $book_resp["response"]["data"]["awb_code"];
									if (!empty($awb_code)) {
										$order->add_order_note("Shipment booked via: " . $ph_shiprocket_shipping_rates["serviceId"] . ", Courier id: " . $ph_shiprocket_shipping_rates["courier_company_id"] . " AWB: " . $awb_code . "\n\n- Shipment tracker for woocommerce", false);
										Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipping_awb', $awb_code);
										bt_force_sync_order_tracking($order_id);
									} else {
										$order->add_order_note("Failed to book shipment using the courier: " . $ph_shiprocket_shipping_rates["serviceId"] . ". Response:" . json_encode($book_resp), false);
									}
								} else {
									$order->add_order_note("Failed to book shipment using the selected courier: " . $ph_shiprocket_shipping_rates["serviceId"] . ". Response:" . json_encode($book_resp), false);
								}
								$courier_selected_via_shiprocket_plugin = true;
								break;
							}
						}

						if (!$courier_selected_via_shiprocket_plugin) {
							//check if courier selected via this plugin, if yes, proceed to assign courier in shiprocket.

							foreach ($order->get_items('shipping') as $s_item_id => $s_item) {
								$bt_sst_sr_courier_company_id = $s_item->get_meta("bt_sst_sr_courier_company_id", true);
								$bt_sst_sr_courier_company_name = $s_item->get_meta("bt_sst_sr_courier_company_name", true);

								if (!empty($bt_sst_sr_courier_company_id) && !empty($bt_sst_sr_courier_company_name)) {

									$book_resp = $this->shiprocket->book_shipment_courier($push_resp["shipment_id"], $bt_sst_sr_courier_company_id);
									if ($book_resp && isset($book_resp["awb_assign_status"])) {
										$awb_code = $book_resp["response"]["data"]["awb_code"];
										if (!empty($awb_code)) {
											$order->add_order_note("Shipment booked via: " . $bt_sst_sr_courier_company_name . ", Courier id: " . $bt_sst_sr_courier_company_id . " AWB: " . $awb_code . "\n\n- Shipment tracker for woocommerce", false);
											Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipping_awb', $awb_code);
											bt_force_sync_order_tracking($order_id);
										} else {
											$order->add_order_note("Failed to book shipment using the user selected courier: " . $bt_sst_sr_courier_company_name . ". Response:" . json_encode($book_resp), false);
										}
									} else {
										$order->add_order_note("Failed to book shipment using the selected courier: " . $bt_sst_sr_courier_company_name . ". Response:" . json_encode($book_resp), false);
									}
									break;
								}
							}
						}

						//check if an awb is already assigned to shipment, if not try booking it using default courier
						$bt_shipping_awb_number = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_shipping_awb', true);
						if (empty($bt_shipping_awb_number)) {
							$bt_sst_shiprocket_default_courier_companies = carbon_get_theme_option('bt_sst_shiprocket_default_courier_companies');

							foreach ($bt_sst_shiprocket_default_courier_companies as $bt_sst_sr_courier_company_id) {

								$arr_active_cc = get_option('_bt_sst_shiprocket_active_courier_companies', []);
								$bt_sst_sr_courier_company_name = $arr_active_cc[$bt_sst_sr_courier_company_id];

								if (!empty($bt_sst_sr_courier_company_id)) {

									$book_resp = $this->shiprocket->book_shipment_courier($push_resp["shipment_id"], $bt_sst_sr_courier_company_id);
									if ($book_resp && isset($book_resp["awb_assign_status"])) {
										$awb_code = $book_resp["response"]["data"]["awb_code"];
										if (!empty($awb_code)) {
											$order->add_order_note("Shipment booked via: " . $bt_sst_sr_courier_company_name . ", Courier id: " . $bt_sst_sr_courier_company_id . " AWB: " . $awb_code . "\n\n- Shipment tracker for woocommerce", false);
											Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipping_awb', $awb_code);
											bt_force_sync_order_tracking($order_id);
											break;
										} else {
											$order->add_order_note("Failed to book shipment using the default courier: " . $bt_sst_sr_courier_company_name . ". Response:" . json_encode($book_resp), false);
										}

									} else {
										$order->add_order_note("Failed to book shipment using the default courier: " . $bt_sst_sr_courier_company_name . ". Response:" . json_encode($book_resp), false);
									}
								}

							}
						}
					}


				} else {
					$order->add_order_note("Failed to push order to shiprocket, got error response from shiprocket: '" . json_encode($push_resp) . "'" . "\n\n- Shipment tracker for woocommerce", false);
					//got error response from shiprocket server

				}

			} else {
				//failed to push to shiprocket
				$order->add_order_note("Failed to push order to shiprocket, please verify api credentials." . "\n\n- Shipment tracker for woocommerce", false);
				$order->add_order_note("Response from Shiprocket Push api: " . json_encode($push_resp) . "\n\n- Shipment tracker for woocommerce", false);
			}
		} catch (Exception $e) {
			error_log('Error in pushing order id: ' . $order_id . ' got error: ' . $e->getMessage());
		}
	}

	public function push_order_to_shipmozo($order_id)
	{

		try {
			$order = wc_get_order($order_id);
			$is_premium = $this->licenser->is_license_active();
			if (!$is_premium) {
				$order->add_order_note("Shipmozo Push is available only in Premium version of the plugin. " . $bt_sst_shipmozo_push_orders . "\n\n- Shipment tracker for woocommerce", false);
				return;
			}
			$bt_sst_shipmozo_push_orders = carbon_get_theme_option('bt_sst_shipmozo_push_orders');
			if ($bt_sst_shipmozo_push_orders != 1) {
				$order->add_order_note("Shipmozo Push turned off " . $bt_sst_shipmozo_push_orders . "\n\n- Shipment tracker for woocommerce", false);
				return;

			}


			if (!empty(Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_shipmozo_order_id', true))) {
				$order->add_order_note('Shipment is already assigned with id: ' . Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_shipmozo_order_id', true) . "\n\n- Shipment tracker for woocommerce", false);
				return;
			}

			$order->add_order_note('Pushing order to shipmozo:' . $order_id . "\n\n- Shipment tracker for woocommerce", false);
			$push_resp = $this->shipmozo->push_order_to_shipmozo($order_id);

			if ($push_resp != null) {

				if (isset($push_resp["result"]) && $push_resp["result"] == "1") {

					$shipmozo_orderid = $push_resp["data"]["order_id"];
					Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipmozo_order_id', $shipmozo_orderid);
					$order->add_order_note("Order pushed to shipmozo. Shipmozo Order ID: " . $shipmozo_orderid . "\n\n- Shipment tracker for woocommerce", false);

				} else {
					$order->add_order_note("Failed to push order to shipmozo, got error response from shipmozo: '" . json_encode($push_resp) . "'" . "\n\n- Shipment tracker for woocommerce", false);
					//got error response from shiprocket server

				}

			} else {
				//failed to push to shipmozo
				$order->add_order_note("Failed to push order to shipmozo, please verify api credentials." . "\n\n- Shipment tracker for woocommerce", false);
				$order->add_order_note("Response from Shipmozo Push api: " . json_encode($push_resp) . "\n\n- Shipment tracker for woocommerce", false);
			}
		} catch (Exception $e) {
			error_log('Error in pushing order id: ' . $order_id . ' got error: ' . $e->getMessage());
		}
	}

	public function push_order_to_delhivery($order_id)
	{
		try {
			$order = wc_get_order($order_id);

			// $bt_sst_delhivery_push_orders = carbon_get_theme_option( 'bt_sst_delhivery_push_orders' );

			// if($bt_sst_delhivery_push_orders!=1){
			// 	$order->add_order_note( "Delhivery Push turned off ." . "\n\n- Shipment tracker for woocommerce", false );
			// 	// 	die("fdgdf");
			//  	return;
			// } 

			if (!empty(Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_delhivery_waybill_no', true))) {
				$order->add_order_note('Delhivery is already assigned with Waybil No: ' . Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_delhivery_waybill_no', true) . "\n\n- Shipment tracker for woocommerce", false);
				return;
			}

			$order->add_order_note('Pushing order to delhivery: ' . $order_id . "\n\n- Shipment tracker for woocommerce", false);
			$push_resp = $this->delhivery->push_order_to_delhivery($order_id);

			if ($push_resp != null) {
				if (isset($push_resp["success"]) && $push_resp["success"] == true) {
					$delhivery_waybil = $push_resp['upload_wbn'];
					Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_delhivery_waybill_no', $delhivery_waybil);
					$order->add_order_note("Order pushed to Delhivery. Waybill No: " . $delhivery_waybil . "\n\n- Shipment tracker for woocommerce", false);
					bt_force_sync_order_tracking($order_id);
				} else {
					$order->add_order_note("Failed to push order to Delhivery, got error response from delhivery: '" . json_encode($push_resp['packages'][0]['remarks']) . "'" . "\n\n- Shipment tracker for woocommerce", false);
				}

			} else {
				$order->add_order_note("Failed to push order to Delhivery, please verify api credentials." . "\n\n- Shipment tracker for woocommerce", false);
				$order->add_order_note("Response from Delhivery Push api: " . json_encode($push_resp) . "\n\n- Shipment tracker for woocommerce", false);
			}
		} catch (Exception $e) {
			error_log('Error in pushing order id: ' . $order_id . ' got error: ' . $e->getMessage());
		}
	}
	public function push_order_to_nimbuspost($order_id)
	{

		try {
			$order = wc_get_order($order_id);
			$is_premium = $this->licenser->is_license_active();
			if (!$is_premium) {
				$order->add_order_note("Nimbuspost Push is available only in Premium version of the plugin. " . "\n\n- Shipment tracker for woocommerce", false);
				return;
			}
			$bt_sst_nimbuspost_push_orders = carbon_get_theme_option('bt_sst_nimbuspost_push_orders');
			if ($bt_sst_nimbuspost_push_orders != 1) {
				$order->add_order_note("Nimbuspost Push turned off " . $bt_sst_nimbuspost_push_orders . "\n\n- Shipment tracker for woocommerce", false);
				return;

			}


			if (!empty(Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_nimbuspost_order_id', true))) {
				$order->add_order_note('Shipment is already assigned with id: ' . Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_nimbuspost_order_id', true) . "\n\n- Shipment tracker for woocommerce", false);
				return;
			}

			$order->add_order_note('Pushing order to nimbuspost:' . $order_id . "\n\n- Shipment tracker for woocommerce", false);
			$push_resp = $this->nimbuspost_new->push_order_to_nimbuspost($order_id);

			if ($push_resp != null) {

				if (isset($push_resp["status"]) && ($push_resp["status"] == true || $push_resp["message"] == "No autoship rule found.")) {

					$nimbuspost_orderid = "NA"; //$push_resp["data"]["order_id"];
					Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_nimbuspost_order_id', $nimbuspost_orderid);
					$order->add_order_note("Order pushed to nimbuspost. nimbuspost Order ID: " . $nimbuspost_orderid . "\n\n- Shipment tracker for woocommerce", false);

				} else {
					$order->add_order_note("Failed to push order to nimbuspost, got error response from nimbuspost: '" . json_encode($push_resp) . "'" . "\n\n- Shipment tracker for woocommerce", false);
					//got error response from shiprocket server

				}

			} else {
				//failed to push to shipmozo
				$order->add_order_note("Failed to push order to nimbuspost, please verify api credentials." . "\n\n- Shipment tracker for woocommerce", false);
				$order->add_order_note("Response from nimbuspost Push api: " . json_encode($push_resp) . "\n\n- Shipment tracker for woocommerce", false);
			}
		} catch (Exception $e) {
			error_log('Error in pushing order id: ' . $order_id . ' got error: ' . $e->getMessage());
		}
	}

	public function push_order_on_fship($order_id)
	{
		try {
			$order = wc_get_order($order_id);

			if (!empty(Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_fship_shipment_id', true))) {
				$order->add_order_note('Shipment is already assigned with id: ' . Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_fship_shipment_id', true) . "\n\n- Shipment tracker for woocommerce", false);
				return;
			}

			$order->add_order_note('Pushing order to shiprocket:' . $order_id . "\n\n- Shipment tracker for woocommerce", false);
			$push_resp = $this->fship->push_order_on_fship($order_id);
			//echo "<pre>"; print_r($push_resp); die;
			if ($push_resp != null) {
				if (isset($push_resp['response']) && isset($push_resp['response']["status"]) && $push_resp['response']["status"]) {
					$apiorderid = $push_resp['response']['apiorderid'];
					Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_apiorderid_no', $apiorderid);
					Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order_id, '_bt_shipping_awb', $push_resp['response']['waybill']);
					$order->add_order_note("Order pushed to Fship. api order id No: " . $apiorderid . "\n\n- Shipment tracker for woocommerce", false);
					bt_force_sync_order_tracking($order_id);
				} else {
					$order->add_order_note("Failed to push order to Fship, got error response from Fship: ".$push_resp['response']['response'], false);
				}
			} else {
				//failed to push to fship
				$order->add_order_note("Failed to push order to fship, please verify api credentials." . "\n\n- Shipment tracker for woocommerce", false);
				$order->add_order_note("Response from fship Push api: " . json_encode($push_resp) . "\n\n- Shipment tracker for woocommerce", false);
			}
		} catch (Exception $e) {
			error_log('Error in pushing order id: ' . $order_id . ' got error: ' . $e->getMessage());
		}
	}

	public function bt_shipment_status_changed($order_id, $shipment_obj, $old_tracking_obj)
	{
		// die("dfgfd");
		// echo "<pre>"; print_r($shipment_obj); die;
		//shipment status updated..
		$bt_sst_complete_delivered_orders = carbon_get_theme_option('bt_sst_complete_delivered_orders');
		if ($bt_sst_complete_delivered_orders == 1) {
			//change status of delivered order to completed.
			if (strcasecmp($shipment_obj->current_status, "delivered") == 0) {
				$order = wc_get_order($order_id);
				$order->update_status('completed', "Shipment has been delivered.");
			}
		}

		//check if order note needs to be added
		$bt_sst_add_order_note = carbon_get_theme_option('bt_sst_add_order_note');
		if ($bt_sst_add_order_note == 1) {
			//making sure that status has changed
			if ($old_tracking_obj == null || $shipment_obj->current_status != $old_tracking_obj->current_status) {
				$bt_sst_order_note_type = carbon_get_theme_option('bt_sst_order_note_type');
				$bt_sst_order_note_template = carbon_get_theme_option('bt_sst_order_note_template');
				if (empty($bt_sst_order_note_template)) {
					$bt_sst_order_note_template = "Shipment status has been updated to #new_status#. #track_url#";
				}
				if (!empty($bt_sst_order_note_template)) {
					$order = wc_get_order($order_id);
					$note = str_ireplace("#old_status#", bt_format_shipment_status($old_tracking_obj == null ? "" : $old_tracking_obj->current_status), $bt_sst_order_note_template);
					$note = str_ireplace("#new_status#", bt_format_shipment_status($shipment_obj->current_status), $note);
					$note = str_ireplace("#track_url#", $shipment_obj->get_tracking_link(), $note);

					$note = str_ireplace("#courier_name#", $shipment_obj->courier_name, $note);
					$note = str_ireplace("#awb_tracking_number#", $shipment_obj->awb, $note);

					if (!empty($shipment_obj->etd)) {
						try {
							$date = new DateTime($shipment_obj->etd);
							$note = str_ireplace("#estimated_delivery#", $date->format('l, jS F Y'), $note);

						} catch (Exception $e) {
							//parse failed, just use the value from object.
							$note = str_ireplace("#estimated_delivery#", $shipment_obj->etd, $note);
						}

					} else {
						$note = str_ireplace("#estimated_delivery#", 'NA', $note);
					}


					$note = str_ireplace("#track_link#", '<a target="_blank" href="' . $shipment_obj->get_tracking_link() . '">Track</a>', $note);
					$note = $note . "\n\n- Shipment tracker for woocommerce";
					$order->add_order_note($note, $bt_sst_order_note_type == 'customer');
				}
			}
		}
		// $send_data ="";
		// $order_data = wc_get_order( $order_id );

		$is_premium = $this->licenser->is_license_active();
		//call woocommerce webhook to post shipment updates to 3rd party.
		if ($old_tracking_obj == null || $shipment_obj->current_status != $old_tracking_obj->current_status) {
			if ($is_premium) {
				do_action('bt_sst_order_shipment_updated', $order_id);
			}

		}


		//Custom order status mapping
		if ($is_premium) {
			$wc_order_statuses = array_map('strtolower', wc_get_order_statuses());
			$saved_statuses_keys_values = get_option('bt_sst_order_and_shipp_status_keys_array', []);

			$mapped_order_status = '';
			if (isset($saved_statuses_keys_values[$shipment_obj->current_status])) {
				$mapped_order_status = $saved_statuses_keys_values[$shipment_obj->current_status];
			}

			if (!empty($mapped_order_status) && array_key_exists($mapped_order_status, $wc_order_statuses)) {

				$order = wc_get_order($order_id);
				// Get the current order status (strip 'wc-' prefix)
				$current_status = $order->get_status();
				$current_status = 'wc-' . $current_status;

				// Update only if the new status is different from the current status
				if ($current_status !== $mapped_order_status) {
					$order->update_status($mapped_order_status, "Order status updated to " . $mapped_order_status);
				}
			}
		}

	}
	function findKeysByValue(string $value, array $array = null): array
	{

		if ($array === null) {
			global $status_map; // Access the global $status_map if no array is provided.
			$array = $status_map;
		}

		$keys = array_keys($array, $value);
		return $keys;
	}

	function woocommerce_order_status_changed_of_shipment_tracker($order_id, $old_order_status, $new_order_status)
	{
		$is_premium = $this->licenser->is_license_active();
		//custom order status mapping
		if (!$is_premium) {
			return;
		}
		$courier_name = "";
		$awb_number = $order_id;
		$edd = "";
		$tracking_link = "";
		$saved_statuses_keys_values = get_option('bt_sst_order_and_shipp_status_keys_array', []);
		$mapped_shipment_status = $this->findKeysByValue('wc-' . $new_order_status, $saved_statuses_keys_values);
		$tracking_data = bt_get_shipping_tracking($order_id);
		foreach ($mapped_shipment_status as $key => $value) {


			if (!empty($tracking_data['tracking_data'])) {
				if ($value == $tracking_data["tracking_data"]["current_status"]) {
					continue;
				}
				$courier_name = $tracking_data['tracking_data']['courier_name'];
				$awb_number = $tracking_data['tracking_data']['awb'];
				$edd = $tracking_data['tracking_data']['etd'];
				$tracking_link = $tracking_data['tracking_data']['tracking_url'];
			}
			bt_update_shipment_tracking($order_id, $courier_name, $awb_number, $value, $edd, $tracking_link);
			break;
		}
	}

	function cron_schedules($schedules)
	{
		// Adds once weekly to the existing schedules.
		$schedules['minutely'] = array(
			'interval' => 60,
			'display' => __('Once every minute')
		);
		$schedules['every_15_minutes'] = array(
			'interval' => 15 * 60, // 15 minutes in seconds
			'display' => __('Every 15 Minutes')
		);
		$schedules['every_30_minutes'] = array(
			'interval' => 30 * 60, // 30 minutes in seconds
			'display' => __('Every 30 Minutes')
		);
		$schedules['every_1_hour'] = array(
			'interval' => 60 * 60, // 1 hour in seconds
			'display' => __('Every 1 Hour')
		);
		$schedules['every_4_hours'] = array(
			'interval' => 4 * 60 * 60, // 4 hours in seconds
			'display' => __('Every 4 Hours')
		);
		$schedules['daily'] = array(
			'interval' => 24 * 60 * 60, // 24 hours in seconds
			'display' => __('Every 24 Hours')
		);
		return $schedules;
	}

	public function add_meta_boxes()
	{
		global $post_id;
		if (empty($post_id) && isset($_GET["id"])) {
			$post_id = sanitize_text_field($_GET['id']);
		}
		if (empty($post_id)) {
			return;
		}

		$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($post_id);
		//if(!empty($bt_shipment_tracking->shipping_provider) && $bt_shipment_tracking->shipping_provider !== 'none'){
		add_meta_box(
			'bt_sync-box',
			__('Shipment Tracking', 'bt-sync-order'),
			array($this, 'sync_actions_meta_box'),
			'shop_order',
			'side',
			'default'
		);

		add_meta_box(
			'bt_sync-box',
			__('Shipment Tracking', 'bt-sync-order'),
			array($this, 'sync_actions_meta_box'),
			'woocommerce_page_wc-orders',
			'side',
			'default'
		);
		//}
	}

	public function sync_actions_meta_box()
	{
		global $post_id;
		if (empty($post_id)) {
			$post_id = sanitize_text_field($_GET["id"]);
		}
		$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($post_id);
		$bt_shipping_provider = $bt_shipment_tracking->shipping_provider;
		$bt_shipping_awb_number = $bt_shipment_tracking->awb;

		$shipping_mode_is_manual_or_ship24 = carbon_get_theme_option('bt_sst_enabled_custom_shipping_mode');
		// $shipping_mode_is_manual_or_ship24 = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($post_id, '_bt_sst_custom_shipping_mode', true);

		include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/bt-woocommerce-order-actions-end.php';

		if (!$bt_shipping_provider || ($bt_shipping_provider == 'manual' && $shipping_mode_is_manual_or_ship24 == "manual")) {
			include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/bt-shipment-tracking-manual-metabox.php';
		} else if ($bt_shipping_provider == 'shiprocket' || $bt_shipping_provider == 'shyplite' || $bt_shipping_provider == 'nimbuspost' || $bt_shipping_provider == 'xpressbees' || $bt_shipping_provider == 'shipmozo' || $bt_shipping_provider == 'nimbuspost_new' || $bt_shipping_provider == 'delhivery' || $shipping_mode_is_manual_or_ship24 == "ship24" || $bt_shipping_provider == "fship") {
			$order_id = isset($_GET['post']) ? $_GET['post'] : sanitize_text_field($_GET['id']);
			include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/bt-shipment-tracking-metabox.php';
		}

	}

	public function woocommerce_order_actions_end()
	{
		// include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/bt-woocommerce-order-actions-end.php';
	}


	function check_user_data_for_premium_features()
	{
		$response = array(
			"status" => false,
			"data" => null,
			"message" => "Data not found!.."
		);

		$nonce = sanitize_text_field($_POST["value"]['nonce']);
		// $nonce = $_REQUEST['_wpnonce'];
		if (!wp_verify_nonce($nonce, 'check_user_data_for_premium_features')) {
			exit; // Get out of here, the nonce is rotten!
		}

		$user = sanitize_text_field($_POST["value"]['user']);
		$password = sanitize_text_field($_POST["value"]['password']);

		$received_data = $this->licenser->get_premium_user_data_by_user_password($user, $password);

		$status = false;
		if ($received_data && $received_data['status']) {
			$status = true;
		}


		if (!$status) {
			$response = array(
				"status" => false,
				"data" => null,
				"message" => empty($received_data["message"]) ? "No response from server" : $received_data["message"]
			);
		} else {
			$this->licenser->save_license($user, $password, $status);
			$response = array(
				"status" => true,
				"data" => null,
				"message" => empty($received_data["message"]) ? "No response from server" : $received_data["message"]
			);
		}

		wp_send_json($response);
		die();
	}

	public function api_call_for_test_connection()
	{
		$nonce = sanitize_text_field($_GET["value"]);

		if (!wp_verify_nonce($nonce, 'api_call_for_test_connection')) {
			exit; // Get out of here, the nonce is rotten!
		}

		$response = array(
			"status" => false,
			"data" => null,
			"message" => "Test Connection Failed. Please verify api credentials and try again."
		);
		delete_transient("bt_sst_shiprocket_courier_companies");
		delete_transient("bt_sst_shiprocket_auth_token");
		delete_transient("bt_sst_shiprocket_courier_companies_fetched");
		$api_call = $this->shiprocket->test_connection();

		if (sizeof($api_call) > 0 && isset($api_call['token'])) {

			//call get couriers api and save in db
			$all_active_cc = $this->shiprocket->get_courier_companies_name();
			if (!empty($all_active_cc)) {
				$arr_active_cc = [];
				foreach ($all_active_cc as $value) {
					$id = $value['id'];
					$arr_active_cc[$id] = $value['name'];
				}
				update_option('_bt_sst_shiprocket_active_courier_companies', $arr_active_cc);
				$response = array(
					"status" => true,
					"data" => $api_call,
					"message" => "Test Connection Successful. And " . count($arr_active_cc) . " Courier Fethced Successfully. Great Work!!"
				);
			} else {
				$response = array(
					"status" => false,
					"data" => $api_call,
					"message" => "Test Connection Failed. Please Try After A Minute!"
				);
			}
		} else {
			$response = array(
				"status" => false,
				"data" => $api_call,
				"message" => "Test Connection Failed. " . $api_call['message']
			);
		}

		wp_send_json($response);
		die();
	}
	public function api_call_for_shipmozo_test_connection()
	{
		$nonce = sanitize_text_field($_GET["value"]);


		if (!wp_verify_nonce($nonce, 'api_call_for_shipmozo_test_connection')) {
			//exit; // Get out of here, the nonce is rotten!
		}

		$response = array(
			"status" => false,
			"data" => null,
			"message" => "Test Connection Failed. Please verify api credentials and try again."
		);

		//ini_set('display_errors', '1');
		//ini_set('display_startup_errors', '1');

		$api_call = $this->shipmozo->test_shipmozo();



		if ($api_call == true) {
			$response = array(
				"status" => true,
				"data" => null,
				"message" => "Test Connection Successful. Great work!!"
			);
		}

		wp_send_json($response);
		die();
	}

	public function api_call_for_delhivery_test_connection()
	{
		// die("rgdrg");
		$nonce = sanitize_text_field($_GET["value"]);

		if (!wp_verify_nonce($nonce, 'api_call_for_delhivery_test_connection')) {
			// $response = "idfugdf";
		}
		$response = array(
			"status" => false,
			"data" => null,
			"message" => "Test Connection Failed. Please verify api credentials and try again."
		);
		// die("fdfrgdrg");
		$api_call = $this->delhivery->test_delhivery();
		// var_dump($this->delhivery);
		if ($api_call == true) {
			$response = array(
				"status" => true,
				"data" => null,
				"message" => "Test Connection Successful. Great work!!"
			);
		}
		// $response = "idfugdf";
		wp_send_json($response);
		die();
	}
	public function api_call_for_fship_test_connection()
	{
		$nonce = sanitize_text_field($_GET["value"]);
		// var_dump($nonce); die;

		if (!wp_verify_nonce($nonce, 'api_call_for_fship_test_connection')) {
			return;
		}
		$response = array(
			"status" => false,
			"data" => null,
			"message" => "Test Connection Failed. Please verify api credentials and try again."
		);
		// die("fdfrgdrg");
		$api_call = $this->fship->test_fship();
		// var_dump($this->delhivery);
		if ($api_call == true) {
			$response = array(
				"status" => true,
				"data" => null,
				"message" => "Test Connection Successful. Great work!!"
			);
		}
		// $response = "idfugdf";
		wp_send_json($response);
		die();
	}
	public function api_call_for_ship24_test_connection()
	{

		$nonce = sanitize_text_field($_GET["value"]);
		if (!wp_verify_nonce($nonce, 'api_call_for_ship24_test_connection')) {
			// $response = "idfugdf";
		}
		$response = array(
			"status" => false,
			"data" => null,
			"message" => "Test Connection Failed. Please verify api credentials and try again."
		);
		// die("fdfrgdrg");
		$api_call = $this->ship24->get_coriers_name_and_test_connectin();
		// var_dump($this->delhivery);
		if (sizeof($api_call) > 1) {
			$response = array(
				"status" => true,
				"data" => null,
				"message" => "Test Connection Successful. Great work!!"
			);
		}
		// $response = "idfugdf";
		wp_send_json($response);
		die();
	}


	public function api_check_for_nimbuspost_test_connection()
	{
		$nonce = sanitize_text_field($_GET["value"]);


		if (!wp_verify_nonce($nonce, 'api_check_for_nimbuspost_test_connection')) {
			//exit; // Get out of here, the nonce is rotten!
		}


		$response = array(
			"status" => false,
			"data" => null,
			"message" => "Test Connection Failed... Please verify api credentials and try again."
		);

		//ini_set('display_errors', '1');
		//ini_set('display_startup_errors', '1');


		$api_call = $this->nimbuspost_new->test_nimbuspost();



		if ($api_call == true) {
			$response = array(
				"status" => true,
				"data" => null,
				"message" => "Test Connection Successful. Great work!!"
			);
		}

		wp_send_json($response);
		die();
	}

	public function buy_credit_balance()
	{
		$nonce = sanitize_text_field($_GET["nonce"]);

		if (!wp_verify_nonce($nonce, 'buy_credit_balance')) {
			exit; // Get out of here, the nonce is rotten!
		}
		if (!current_user_can('manage_options')) {
			exit;
		}
		$message_type = 'sms';

		$pricing = $this->get_credits_pricing($message_type);

		$response = array(
			"status" => true,
			"data" => $pricing,
			"message" => "Pricing Received!"
		);

		wp_send_json($response);
		die();
	}
	public function get_credits_pricing($message_type)
	{
		$api = get_option('register_for_sms_apy_key');
		if (empty($api)) {
			return null;
		}
		$body = array();

		$args = array(
			'body' => json_encode($body),
			'headers' => array(
				'Authorization' => 'Bearer ' . $api,
				'ClientDomain' => get_site_url()
				// 'Content-Type'=> 'application/json'
			),
		);
		$url = "https://quickengage.bitss.in/api/WpShipmentTracking/GetPricing?message_type=" . $message_type;

		$response = wp_remote_post($url, $args);
		$body = wp_remote_retrieve_body($response);
		$body = json_decode($body, true);
		return $body;
	}

	public function credit_balance_details()
	{
		$nonce = sanitize_text_field($_GET["value"]);

		if (!wp_verify_nonce($nonce, '')) {
			//exit; // Get out of here, the nonce is rotten!
		}


		$response = array(
			"status" => false,
			"data" => array(
				"credit_balance" => "NA",
				"credit_consumed" => "NA",
				"sms_sent" => "NA",
				"last_sms_sent" => "NA"
			),
			"message" => "Failed to get sms balance. Try Again"
		);

		$api = "";
		if (!$api) {
			$api = get_option('register_for_sms_apy_key');
		}

		if (!empty($api)) {
			$api_call = $this->get_balance();

			if ($api_call['response_code'] == 200) {
				$datetime_string = "NA";
				if ($api_call['data']['lastSentTimeStamp'] > 0) {
					$datetime_string = $this->convert_timestamp_to_datetime_string($api_call['data']['lastSentTimeStamp']);
				}
				$response = array(
					"status" => true,
					"data" => array(
						"credit_balance" => $api_call['data']['creditsBalance'],
						"credit_consumed" => $api_call['data']['creditsConsumedLast7Days'],
						"sms_sent" => $api_call['data']['smsSentLast7Days'],
						"last_sms_sent" => $datetime_string
					),
					"message" => "Balance Fetch Successful."
				);
			}
		}


		wp_send_json($response);
		die();
	}


	function convert_timestamp_to_datetime_string($timestamp)
	{
		// Get the blog's timezone string
		$timestamp = (int) $timestamp;
		$timezone_string = get_option('timezone_string');

		// If timezone string is empty, fallback to the offset
		if (empty($timezone_string)) {
			$timezone_offset = get_option('gmt_offset');
			$timezone_string = timezone_name_from_abbr('', $timezone_offset * 3600, 0);
		}

		// Create a DateTime object from the timestamp
		$date = new DateTime();
		$date->setTimestamp($timestamp);

		// Set the timezone for the DateTime object
		$timezone = new DateTimeZone($timezone_string);
		$date->setTimezone($timezone);

		// Format the datetime string
		$datetime_string = $date->format('Y-m-d H:i:s');

		return $datetime_string;
	}

	public function get_balance($api = false)
	{
		$resp = array(
			"response_code" => 0,
			"data" => ""
		);
		if (!$api) {
			$api = get_option('register_for_sms_apy_key');
		}

		if (empty($api)) {
			return null;
		}

		$body = array();
		$args = array(
			'body' => json_encode($body),
			'headers' => array(
				'Authorization' => 'Bearer ' . $api,
				'ClientDomain' => get_site_url()
				// 'Content-Type'=> 'application/json'
			),
		);
		$url = "https://quickengage.bitss.in/api/WpShipmentTracking/GetBalance";

		$response = wp_remote_post($url, $args);

		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			$resp = array(
				"response_code" => 0,
				"data" => $error_message
			);
		} else {
			$status_code = wp_remote_retrieve_response_code($response);
			$body = wp_remote_retrieve_body($response);
			$body_obj = json_decode($body, true);
			$resp = array(
				"response_code" => $status_code,
				"data" => $body_obj != null ? $body_obj : $body
			);
		}

		return $resp;
	}

	public function register_for_sms()
	{
		$nonce = isset($_GET['value']) ? sanitize_text_field($_GET['value']) : '';
		$admin_email = isset($_GET['user_mail']) ? sanitize_email($_GET['user_mail']) : '';
		$full_name = isset($_GET['first_name']) ? sanitize_text_field($_GET['first_name']) : '';
		$mobile_no = isset($_GET['user_mobile']) ? sanitize_text_field($_GET['user_mobile']) : '';
		
		if ( ! wp_verify_nonce( $nonce, 'register_for_sms' ) ) {
			//exit; // Get out of here, the nonce is rotten!
		}

		

		$response = array(
			"status" => false,
			"data" => null,
			"message" => "Failed to Register. Try Again"
		);
		

		if (!$admin_email) {
			$admin_email = get_bloginfo('admin_email');
		}
		if (!$full_name && is_user_logged_in() ) {

				$current_user = wp_get_current_user();
				$full_name = trim($current_user->first_name . ' ' . $current_user->last_name);

				// Fallback if full name is not set
				if (empty($full_name)) {
					$full_name = $current_user->display_name;
				}			
		}

		$current_url = get_site_url();
		$shop_country = WC()->countries->get_base_country();
		$websiteName = get_bloginfo('name');
		$api_call = $this->signup_api($admin_email, $current_url,$websiteName, $shop_country, $full_name, $mobile_no, true, true);


		// $api_call = $this->signup_api($admin_email, $current_url, $shop_country, $current_user, "NA", true, true);


		if ($api_call['response_code'] == 200 && isset($api_call['data']['apiKey']) && !empty($api_call['data']['apiKey'])) {

			$api = $api_call['data']['apiKey'];

			update_option('register_for_sms_apy_key', $api);


			$message = $api_call['data']['message'];


			$response = array(
				"status" => true,
				"data" => null,
				"message" => $message
			);
		}

		wp_send_json($response);
		die();
	}
	public function update_check_skip_or_not()
	{
		update_option('bt_sst_check_skip_or_not', true);
		wp_send_json_success('Updated successfully');
		exit;
	}


	public function get_bt_sst_email_trial()
	{

		$nonce = sanitize_text_field($_GET["value"]);
		$bt_sst_test_email = sanitize_text_field($_GET["bt_sst_test_email"]);
		$bt_sst_test_email_event = sanitize_text_field($_GET["bt_sst_test_email_event"]);


		if (!wp_verify_nonce($nonce, 'get_sms_trial')) {
			exit; // Get out of here, the nonce is rotten!
		}

		$response = array(
			"status" => false,
			"data" => "",
			"message" => "Failed to email sms. Try Again."
		);

		if (empty($bt_sst_test_email)) {
			$response = array(
				"status" => false,
				"data" => "",
				"message" => "Please enter email to send."
			);
			wp_send_json($response);
			die();
		}
		if (empty($bt_sst_test_email_event)) {
			$response = array(
				"status" => false,
				"data" => "",
				"message" => "Please select an event type."
			);
			wp_send_json($response);
			die();
		}


		// Load the WooCommerce mailer
		//  $mailer = WC()->mailer();

		//  // Get all emails
		//  $emails = $mailer->get_emails();

		//  // Send the new order email
		//  if ( ! empty( $emails ) ) {
		// 	 foreach ( $emails as $email ) {
		// 		 if ( $email instanceof WC_Email_New_Order ) {
		// 			 $email->trigger( $order_id );
		// 			 break;
		// 		 }
		// 	 }
		//  }



		$response1 = array(
			"status" => true,
			"data" => $body,
			"message" => "This feature is not implemented yet.."
		);

		wp_send_json($response1);
		die();
	}

	public function get_sms_trial()
	{
		$nonce = sanitize_text_field($_GET["value"]);
		$phoneNumber = sanitize_text_field($_GET["phonenumber"]);
		$selectValue = sanitize_text_field($_GET["selectvalue"]);
		$via = sanitize_text_field($_GET["via"]);


		if (!wp_verify_nonce($nonce, 'get_sms_trial')) {
			exit; // Get out of here, the nonce is rotten!
		}
		$bt_sst_sms_review_url = carbon_get_theme_option('bt_sst_sms_review_url');

		$response = array(
			"status" => false,
			"data" => "",
			"message" => "Failed to send sms. Try Again."
		);

		if (empty($phoneNumber)) {
			$response = array(
				"status" => false,
				"data" => "",
				"message" => "Please enter phone number to send sms."
			);
			wp_send_json($response);
			die();
		}
		if (empty($selectValue)) {
			$response = array(
				"status" => false,
				"data" => "",
				"message" => "Please select a sms type."
			);
			wp_send_json($response);
			die();
		}

		$body = array(
			"phonenumber" => $phoneNumber,
			"eventName" => $selectValue,
			"reviewUrl" => $bt_sst_sms_review_url,
			"via" => $via,
		);

		$auth_token = get_option('register_for_sms_apy_key');

		if (empty($auth_token)) {
			$response = array(
				"status" => false,
				"data" => "",
				"message" => "To test the sms, please register using the button above."
			);
			wp_send_json($response);
			die();
		}

		$args = array(
			//'body'        => $body,
			//'timeout'   => 0.01,
			//'blocking'  => false,
			'sslverify' => false,
			'headers' => array(
				'Authorization' => 'Bearer ' . $auth_token,
				'Content-Type: multipart/form-data'
			),
		);
		$url = "https://quickengage.bitss.in/trigger/TrialMessage?" . http_build_query($body);
		$response = wp_remote_post($url, $args); //its a non-blocking call. so website's speed is not effected.
		$body = wp_remote_retrieve_body($response);

		//  echo "<pre>"; print_r($body); die;
		$response1 = array(
			"status" => true,
			"data" => $body,
			"message" => "Message Sent. Check your phone."
		);

		wp_send_json($response1);
		die();
	}

	public function signup_api($email, $website,$websiteName, $country, $name, $phone, $HasTermsAgreed, $HasAuthorizedForSMS)
	{
		$resp = array(
			"response_code" => 0,
			"data" => ""
		);
		$body = array(
			"email" => $email,
			"website" => $website,
			"websitename" => $websiteName,
			"country" => $country,
			"name" => $name,
			"phonenumber" => $phone,
			"HasTermsAgreed" => true,
			"HasAuthorizedForSMS" => true
		);

		$args = array(
			'body' => json_encode($body),
			'headers' => array(
				//'Authorization' => 'Bearer ' . $auth_token,
				'Content-Type' => 'application/json',
				'ClientDomain' => get_site_url()
			),
		);
		$url = "https://quickengage.bitss.in/api/WpShipmentTracking/Signup";

		$response = wp_remote_post($url, $args);

		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			$resp = array(
				"response_code" => 0,
				"data" => $error_message
			);
		} else {
			$status_code = wp_remote_retrieve_response_code($response);
			$body = wp_remote_retrieve_body($response);
			$body_obj = json_decode($body, true);
			$resp = array(
				"response_code" => $status_code,
				"data" => $body_obj != null ? $body_obj : $body
			);
		}

		return $resp;
	}
	// function add_shipment_updated_webhook_topic($topics, $webhook) {
	// 	error_log(print_r($topics, true));  // Log existing topics
	// 	$topics['shipment_updated'] = __('Shipment Updated', 'shipment-tracking-for-woocommerce');
	// 	error_log(print_r($topics, true));  // Log modified topics
	// 	return $topics;
	// }

	function add_new_topic_hooks($topic_hooks)
	{
		$new_hooks = array(
			'order.bt_sst_shipment_updated' => array(
				'bt_sst_order_shipment_updated',
			),
		);

		return array_merge($topic_hooks, $new_hooks);
	}
	function add_new_topic_events($topic_events)
	{
		$new_events = array(
			'bt_sst_shipment_updated',
		);

		return array_merge($topic_events, $new_events);
	}
	function add_new_webhook_topics($topics)
	{
		$new_topics = array(
			'order.bt_sst_shipment_updated' => __('Shipment Updated (Shipment Tracker Premium Only)', 'shipment-tracking-for-woocommerce'),
		);

		return array_merge($topics, $new_topics);
	}


	function bt_quickengage_messaging_order($order_id)
	{
		$this->bt_quickengage_messaging_api($order_id, null, null);
	}
	function bt_quickengage_messaging_api($order_id, $shipment_obj, $shipment_obj_old)
	{
		$order = wc_get_order($order_id);
		try {
			$blog_title = get_bloginfo('name');
			$site_url = get_site_url();
			$bt_sst_sms_review_url = carbon_get_theme_option('bt_sst_sms_review_url');
			$order_data = json_encode($order->get_data());
			$order_data = json_decode($order_data);

			$body = array(
				"order" => (array) $order_data,
				"shipment_current" => (array) $shipment_obj,
				"shipment_old" => (array) $shipment_obj_old,
				"store_name" => $blog_title,
				"store_url" => $site_url,
				"review_url" => $bt_sst_sms_review_url,
			);
			$event_name = $this->getEventId($body);

			if (empty($event_name))
				return;
			$send_via = $this->should_send_msg($event_name);
			if (sizeof($send_via) > 0) {

				$body['event_name'] = $event_name;
				$body['via'] = implode(",", $send_via);
				$auth_token = get_option('register_for_sms_apy_key');

				$args = array(
					'body' => $body,
					'sslverify' => false,
					'headers' => array(
						'Authorization' => 'Bearer ' . $auth_token,
						'Content-Type: multipart/form-data'
					),
				);

				$url = "https://quickengage.bitss.in/trigger/message";
				$response = wp_remote_post($url, $args); //its a non-blocking call. so website's speed is not effected.
				$body = wp_remote_retrieve_body($response);
				//$resp = json_decode($body,true);

				$order = wc_get_order($order_id);
				$order->add_order_note("'$event_name'" . ' Message Sent via ' . implode(",", $send_via) . '. Request ID: ' . $body . "\n\n- Shipment tracker for woocommerce", false);
			} else {
				$order = wc_get_order($order_id);
				$order->add_order_note("'$event_name'" . " Message Inactive. \n\n- Shipment tracker for woocommerce", false);
			}
		} catch (Exception $Exception) {
			$order->add_order_note('An error ' . $Exception->getMessage());
		}

	}

	private function should_send_msg($event_name)
	{

		$send_via = [];

		$bt_sst_shipment_when_to_send_messages = carbon_get_theme_option('bt_sst_shipment_when_to_send_messages');

		if (!$bt_sst_shipment_when_to_send_messages  || !in_array($event_name, $bt_sst_shipment_when_to_send_messages, true)) {
			return $send_via;
		}

		$bt_sst_shipment_from_what_send_messages = carbon_get_theme_option('bt_sst_shipment_from_what_send_messages');

		if (in_array('sms', $bt_sst_shipment_from_what_send_messages, true)) {
			$send_via[] = 'sms';
		}

		if (in_array('whatsapp', $bt_sst_shipment_from_what_send_messages, true)) {
			$send_via[] = 'whatsapp';
		}

		return $send_via;
	}




	private function getEventId($QMessage)
	{
		$event_name = "";

		if (!empty($QMessage['order'])) {


			if ($QMessage['order']['status'] == "failed") {
				$event_name = "failed_order";
			} else if ($QMessage['order']['status'] == "cancelled") {
				$event_name = "canceled_order";
			} else if (empty($QMessage['shipment_current'])) {
				// probably a new order
				$event_name = "new_order";

			} else if (
				empty($QMessage['shipment_old']) ||
				(strtolower((string) $QMessage['shipment_current']['current_status']) != strtolower((string) $QMessage['shipment_old']['current_status']))
			) {
				// shipment status update
				$current_status = strtolower((string) $QMessage['shipment_current']['current_status']);
				if ($current_status == "out for pickup" || $current_status == "out-for-pickup") {
					$event_name = "out_for_pickup";
				} else if ($current_status == "in transit" || $current_status == "in-transit") {
					$event_name = "in_transit";
				} else if ($current_status == "out for delivery" || $current_status == "out-for-delivery") {
					$event_name = "out_for_delivery";
				} else if ($current_status == "delivered") {
					$event_name = "delivered";
				} else if ($current_status == "canceled") {
					$event_name = "canceled";
				}
			}
		}
		return $event_name;
	}




	public function api_call_for_sync_order_by_order_id()
	{
		$nonce = sanitize_text_field($_GET['nonce']);

		if (!wp_verify_nonce($nonce, 'api_call_for_sync_order_by_order_id')) {
			exit;
		}
		$order_id = sanitize_text_field($_GET['order_id']);

		$responsed = bt_force_sync_order_tracking($order_id);

		ob_start();
		include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/order_shipment_details.php';
		$result = ob_get_clean();

		$response = array(
			"status" => true,
			"data" => $result,
			"message" => "Sync Successful"
		);

		wp_send_json($response);
		die();
	}

	public function api_call_for_sync_order_shipmozo_by_order_id()
	{
		$nonce = sanitize_text_field($_GET['nonce']);

		if (!wp_verify_nonce($nonce, 'api_call_for_sync_order_shipmozo_by_order_id')) {
			exit;
		}
		$order_id = sanitize_text_field($_GET['order_id']);

		bt_force_sync_order_tracking($order_id);

		ob_start();
		include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/order_shipment_details.php';
		$result = ob_get_clean();

		$response = array(
			"status" => true,
			"data" => $result,
			"message" => "Sync Successful1"
		);
		wp_send_json($response);
		die();
	}

	public function api_call_hide_bt_sst_premium_notice()
	{
		$response = array(
			"status" => true,
			"data" => "",
			"message" => "Hidden for next 30 days."
		);

		wp_send_json($response);
		die();
	}

	public function add_shop_order_filter_by_shipment_status()
	{
		global $pagenow, $typenow;

		if ('shop_order' === $typenow && 'edit.php' === $pagenow) {
			$arr = apply_filters('bt_sst_shipping_statuses', BT_SHIPPING_STATUS);
			$filter_id = 'bt_shipment_status';
			$selected = '';
			if (isset($_GET[$filter_id]) && !empty($_GET[$filter_id])) {
				$selected = sanitize_text_field($_GET[$filter_id]);
			}
			echo "<select name='bt_shipment_status' placeholder='Filter by Shipment Status'>";
			if (empty($selected)) {
				echo "<option value='' selected>Filter by shipment status</option>";
			} else {
				echo "<option value=''>Filter by shipment status</option>";
			}

			foreach ($arr as $key => $value) {
				if ($selected == $key) {
					echo "<option selected value='" . esc_attr($key) . "'>" . esc_html($value) . "</option>";
				} else {
					echo "<option value='" . esc_attr($key) . "'>" . esc_html($value) . "</option>";
				}

			}
			echo "</select>";
		}
	}

	public function process_admin_shop_order_filtering_by_shipment_status($vars)
	{
		global $pagenow, $typenow;

		$filter_id = 'bt_shipment_status';

		if (
			$pagenow == 'edit.php' && 'shop_order' === $typenow
			&& isset($_GET[$filter_id]) && !empty($_GET[$filter_id])
		) {
			//$vars['meta_key']   = '_bt_shipment_tracking';
			//$vars['meta_value'] = ';s:14:"current_status";s:'.strlen($_GET[$filter_id]).':"'.$_GET[$filter_id].'";';
			//$vars['meta_compare']    = 'LIKE';

			$vars['meta_query'] = array(
				'relation' => 'OR',
				array(
					'key' => '_bt_shipment_tracking',
					'value' => ';s:14:"current_status";s:' . strlen(sanitize_text_field($_GET[$filter_id])) . ':"' . sanitize_text_field($_GET[$filter_id]) . '";',
					'compare' => 'LIKE',
				),
				array(
					'key' => '_bt_shipment_tracking',
					'value' => ';s:14:"current_status";s:' . strlen(sanitize_text_field($_GET[$filter_id])) . ':"' . str_ireplace("-", " ", sanitize_text_field($_GET[$filter_id])) . '";',
					'compare' => 'LIKE',
				),
			);
		}
		return $vars;
	}


	function bulk_actions_edit_shop_order_bulk_sync($actions)
	{
		$actions['bt_sync_tracking'] = __('Sync Tracking', 'woocommerce');
		return $actions;
	}

	function downloads_handle_bulk_action_edit_shop_order($redirect_to, $action, $post_ids)
	{
		if ($action !== 'write_downloads')
			return $redirect_to; // Exit

		global $attach_download_dir, $attach_download_file; // ???

		$processed_ids = array();

		foreach ($post_ids as $post_id) {
			$order = wc_get_order($post_id);
			$order_data = $order->get_data();

			// Your code to be executed on each selected order
			// fwrite(
			// 	$myfile,
			// 	$order_data['date_created']->date('d/M/Y') . '; ' .
			// 	'#' . (($order->get_type() === 'shop_order') ? $order->get_id() : $order->get_parent_id()) . '; ' .
			// 	'#' . $order->get_id()
			// );
			$processed_ids[] = $post_id;
		}

		return $redirect_to = add_query_arg(
			array(
				'write_downloads' => '1',
				'processed_count' => count($processed_ids),
				'processed_ids' => implode(',', $processed_ids),
			),
			$redirect_to
		);
	}

	function downloads_bulk_action_admin_notice()
	{
		if (empty($_REQUEST['write_downloads']))
			return; // Exit

		$count = intval($_REQUEST['processed_count']);

		printf('<div id="message" class="updated fade"><p>' .
			_n(
				'Processed %s Order for downloads.',
				'Processed %s Orders for downloads.',
				$count,
				'write_downloads'
			) . '</p></div>', $count);
	}
	function admin_notices()
	{
		$current_screen = get_current_screen();

		if ($current_screen != null && $current_screen->id == "shipment-tracking_page_crb_carbon_fields_container_shipment_tracking") {

			$is_premium = $this->licenser->is_license_active();
			$premium_message = '
				<div class="notification is-info">
					🔓 Premium features are locked — activate your license to deliver a better customer experience! <a href="https://shipment-tracker-for-woocommerce.bitss.tech/" target="_blank">See What\'s Missing..</a>
				</div>
			';
			if ($is_premium) {
				$premium_message = '
					<div class="notification is-success">
						✅ Premium Activated! All premium features are now live on your website — thank you for upgrading and supporting Shipment Tracker! 
					</div>
				';
			}

			?>
			<div class="notice notice-info is-dismissible bt-sst-review-notice">
				<div class="bt-sst-review-step bt-sst-review-step-1">
					<h4>This plugin can do a lot more than you think!!</h4>
					<p>Visit plugin's website to see it's powerful features and how it can benefit you & your customers.</p>
					<a href="https://shipment-tracker-for-woocommerce.bitss.tech/" target="_blank">Visit Website</a>
					| <a href="https://www.youtube.com/playlist?list=PLzDSHgek2t1KJvJyT2JmCQspa0m4jdSgS" target="_blank">See Video
						Guides</a>
					| <a href="https://wordpress.org/support/plugin/shipment-tracker-for-woocommerce/" target="_blank">Get Help</a>
					| <a href="#" class=' stw_wizard_button' id='open-modal'>Launch Setup Wizard</a>
				</div>
			</div>
			<div class="notice " style="padding:0">
				<?php echo wp_kses_post($premium_message); ?>
			</div>
			<div class="notice notice-warning is-dismissible bt-sst-review-notice">
				<p>We're actively developing <em>Shipment Tracker for Woocommerce</em>. <b>Help us make it better</b> by <a
						id="bt-feedback" href="" target="_blank">sharing your feedback</a>. Liked this plugin? Spread the word, <a
						href="https://wordpress.org/support/plugin/shipment-tracker-for-woocommerce/reviews/#new-post"
						target="_blank">give ⭐⭐⭐⭐⭐ on wordress.</a> As a thank you, enjoy a ₹500 free gift voucher for premium
					plugins, SMS, OTPs, domain, hosting, and more on our website for sharing your feedback or review. </p>
			</div>
			<!-- The Modal -->
			<div id="bt-feedback-modal" style="display:none;" title="Shipment Tracker for Woocommerce">
				<div class="bt-sst-review-step bt-sst-review-step-2">
					<p> We would love a chance to improve. Could you take a minute and let us know what we can do better?</p>
					<textarea class="bt-textarea" name="bt-feedback-text" id="bt-feedback-text"
						placeholder="Please write your concern here" rows="5" style="width: 100%"></textarea>
					<small>Note: This site's address, admin email and your name will be sent to us along with your feedback.</small>
					<span class="spinner"></span>
				</div>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function ($) {

					$(document).on('click', '#bt-feedback', function (e) {
						e.preventDefault();
						$('#bt-feedback-modal').dialog({
							resizable: false,
							height: "auto",
							width: 400,
							modal: true,
							buttons: [
								{
									text: "Submit",
									"class": 'bt_btn_sst_feedback_submit',
									click: function () {

										var feedback = '';
										if ((feedback = $("#bt-feedback-text").val()) != '') {
											$('.bt_btn_sst_feedback_submit').addClass("disabled");
											$('#bt-feedback-modal .spinner').addClass("is-active");
											$(this).addClass('disabled');
											$.post(ajaxurl, { action: 'post_customer_feedback_to_sever', feedback: feedback }, function (data) {

												alert("Thank you for your valuable feedback.");
												$('.bt_btn_sst_feedback_submit').removeClass("disabled");
												$('#bt-feedback-modal .spinner').removeClass("is-active");

												$('#bt-feedback-modal').dialog("close");


											});
										} else {
											alert("Sharing is caring... please share your feedback...");
										}
									}
								},
								{
									text: "Cancel",
									"class": 'bt_btn_sst_feedback_cancel',
									click: function () {
										$(this).dialog("close");
									}
								},

							]
						});
					});

				});
			</script>
			<?php
		}
	}

	function handle_admin_init()
	{

		if (isset($_GET['bt_push_to_shiprocket']) && $_GET['bt_push_to_shiprocket'] == 1 && (isset($_GET['post']) || isset($_GET['id']))) {
			$order_id = isset($_GET['post']) ? $_GET['post'] : $_GET['id'];
			$this->push_order_to_shiprocket($order_id);
			unset($_GET['bt_push_to_shiprocket']);
			global $pagenow;
			$current_page = admin_url(sprintf($pagenow . '?%s', http_build_query($_GET)));
			wp_safe_redirect($current_page);
		} else if (isset($_GET['bt_push_to_shipmozo']) && $_GET['bt_push_to_shipmozo'] == 1 && (isset($_GET['post']) || isset($_GET['id']))) {
			$order_id = isset($_GET['post']) ? $_GET['post'] : $_GET['id'];
			$this->push_order_to_shipmozo($order_id);
			unset($_GET['bt_push_to_shipmozo']);
			global $pagenow;
			$current_page = admin_url(sprintf($pagenow . '?%s', http_build_query($_GET)));
			wp_safe_redirect($current_page);
		} else if (isset($_GET['bt_push_to_nimbuspost_new']) && $_GET['bt_push_to_nimbuspost_new'] == 1 && (isset($_GET['post']) || isset($_GET['id']))) {
			$order_id = isset($_GET['post']) ? $_GET['post'] : $_GET['id'];
			$this->push_order_to_nimbuspost($order_id);
			unset($_GET['bt_push_to_nimbuspost_new']);
			global $pagenow;
			$current_page = admin_url(sprintf($pagenow . '?%s', http_build_query($_GET)));
			wp_safe_redirect($current_page);
		} else if (isset($_GET['bt_push_to_delhivery']) && $_GET['bt_push_to_delhivery'] == 1 && (isset($_GET['post']) || isset($_GET['id']))) {
			$order_id = isset($_GET['post']) ? $_GET['post'] : $_GET['id'];
			$getresponce = $this->push_order_to_delhivery($order_id);
			unset($_GET['bt_push_to_delhivery']);
			global $pagenow;
			$current_page = admin_url(sprintf($pagenow . '?%s', http_build_query($_GET)));
			wp_safe_redirect($current_page);
		} else if (isset($_GET['bt_push_to_fship']) && $_GET['bt_push_to_fship'] == 1 && (isset($_GET['post']) || isset($_GET['id']))) {
			$order_id = isset($_GET['post']) ? $_GET['post'] : $_GET['id'];
			$getresponce = $this->push_order_on_fship($order_id);
			unset($_GET['bt_push_to_fship']);
			global $pagenow;
			$current_page = admin_url(sprintf($pagenow . '?%s', http_build_query($_GET)));
			wp_safe_redirect($current_page);
		} else if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'crb_carbon_fields_container_shipment_tracker.php') {
			wp_safe_redirect(admin_url('admin.php?page=bt-shipment-tracking'));
			exit;
		}
	}


	function get_st_form_with_data()
	{

		$nonce = $_GET['nonce'];
		if (!wp_verify_nonce($nonce, 'get_st_form_with_data')) {
			exit; // Get out of here, the nonce is rotten!
		}

		$response = array(
			"status" => false,
			"data" => "",
			"message" => "failed!!"
		);

		$order_id = $_GET["order_id"];
		if (empty(($order_id))) {
			$response = array(
				"status" => false,
				"data" => "",
				"message" => "an error happened."
			);
			wp_send_json($response);
			die();
		}

		$box_html = "";
		$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($order_id);
		$bt_shipping_provider = $bt_shipment_tracking->shipping_provider;
		$bt_shipping_awb_number = $bt_shipment_tracking->awb;
		$shipping_mode_is_manual_or_ship24 = carbon_get_theme_option('bt_sst_enabled_custom_shipping_mode');
		// $shipping_mode_is_manual_or_ship24 = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_sst_custom_shipping_mode', true);

		if (!$bt_shipping_provider || $bt_shipping_provider == 'manual') {
			if ($shipping_mode_is_manual_or_ship24 == "ship24") {
				ob_start();
				include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/bt-shipment-tracking-add-awb.php';
				$box_html = ob_get_clean();
			} else {
				ob_start();
				include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/bt-shipment-tracking-manual-metabox.php';
				$box_html = ob_get_clean();
			}

			$response = array(
				"status" => true,
				"data" => $box_html,
				"message" => "success!"
			);
		} else {
			$box_html = "NA";
			$response = array(
				"status" => true,
				"data" => $box_html,
				"message" => "success"
			);
		}

		wp_send_json($response);
		die();
	}

	function load_coriures_name_for_manual()
	{
		$couriers = get_option('bt_sst_manual_coriures_names_array', array());
		$plugin_dir = plugin_dir_path(__FILE__);
		$json_file = $plugin_dir . "custom_coriures_list.json";

		if (file_exists($json_file)) {
			$json_data = file_get_contents($json_file);
			// echo $json_data;
		}

		// echo "<pre>"; print_r($combinecouriersd_data); die;
		$json_data = json_decode($json_data, true);
		if (is_array($couriers) && sizeof($couriers) > 0) {
			$combined_data = array_merge($json_data, $couriers);
		} else {
			$combined_data = $json_data;
		}
		// echo "<pre>"; print_r($combined_data);
		$combined_data = json_encode($combined_data, true);
		// echo($combined_data); die;
		echo $combined_data;
		wp_die();
	}

	function bt_sst_save_new_order_status()
	{

		// Validate and sanitize input data
		if (empty($_POST['data']['title']) || empty($_POST['data']['slug'])) {
			wp_send_json_error(array('message' => 'Both title and slug are required'), 400);
			return;
		}
		$title = sanitize_text_field($_POST['data']['title']);
		$slug = sanitize_title($_POST['data']['slug']);
		$shipping_status = sanitize_title($_POST['data']['shipping_status']);
		// Retrieve the current list of order statuses
		$order_status_array = get_option('bt_sst_order_status_list', array());

		if (!$order_status_array) {
			$order_status_array = [];
		}
		// Check if slug already exists
		foreach ($order_status_array as $status) {
			if ($status['slug'] === $slug) {
				wp_send_json_error(array('message' => 'Slug already exists'), 400);
				return;
			}
		}
		// Add the new order status to the array
		$order_status_array[] = array(
			'title' => $title,
			'slug' => $slug
		);

		$result = update_option('bt_sst_order_status_list', $order_status_array);
		$updated_order_and_shipp_status_keys_array = get_option('bt_sst_order_and_shipp_status_keys_array', array());
		$updated_order_and_shipp_status_keys_array[$shipping_status] = "wc-" . $slug;
		$result2 = update_option('bt_sst_order_and_shipp_status_keys_array', $updated_order_and_shipp_status_keys_array);

		// Send appropriate response
		if ($result) {
			wp_send_json_success(array('message' => 'Order status saved successfully'));
		} else {
			wp_send_json_error(array('message' => 'Failed to save the order status'), 500);
		}
	}


	function bt_sst_add_custom_order_statuses()
	{
		// Retrieve the custom order statuses from the options table
		$order_status_array = get_option('bt_sst_order_status_list', array());

		// Check if the array is not empty
		if (!empty($order_status_array) && is_array($order_status_array)) {
			foreach ($order_status_array as $order_status) {
				// Validate required fields
				if (isset($order_status['title'], $order_status['slug'])) {
					$slug = sanitize_title($order_status['slug']); // Sanitize the slug
					$title = sanitize_text_field($order_status['title']); // Sanitize the title

					// Register the custom order status
					register_post_status('wc-' . $slug, array(
						'label' => _x($title, 'Order status', 'your-textdomain'),
						'public' => true,
						'exclude_from_search' => false,
						'show_in_admin_all_list' => true,
						'show_in_admin_status_list' => true,
						'label_count' => _n_noop($title . ' (%s)', $title . ' (%s)', 'your-textdomain'),
					));
				}
			}
		}
	}


	// Add custom statuses to the list of WooCommerce order statuses
	function add_custom_status_to_wc_order_statuses($order_statuses)
	{
		// Retrieve the custom order statuses from the options table
		$order_status_array = get_option('bt_sst_order_status_list', array());

		// Check if the array is not empty
		if (!empty($order_status_array) && is_array($order_status_array)) {
			foreach ($order_status_array as $order_status) {
				// Validate required fields
				if (isset($order_status['title'], $order_status['slug'])) {
					$slug = sanitize_title($order_status['slug']); // Sanitize the slug
					$title = sanitize_text_field($order_status['title']); // Sanitize the title

					// Add the custom status to the order statuses array
					$order_statuses['wc-' . $slug] = _x($title, 'Order status', 'your-textdomain');
				}
			}
		}

		return $order_statuses;
	}


	// function bt_sst_save_new_order_status() {
	// 	// Check for required fields
	// 	if (empty($_POST['data']['title']) || empty($_POST['data']['slug']) || empty($_POST['data']['icon'])) {
	// 		wp_send_json_error(array('message' => 'Invalid input data'));
	// 	}

	// 	// Sanitize inputs
	// 	$title = sanitize_text_field($_POST['data']['title']);
	// 	$slug = sanitize_text_field($_POST['data']['title']);
	// 	$icon = sanitize_text_field($_POST['data']['icon']);
	// 	$slug = strtolower(str_ireplace(' ', '-', $slug));

	// 	// Get existing order statuses or initialize an empty array
	// 	$order_status_array = get_option('bt_sst_order_status_list', array());

	// 	// Add the new status to the custom status list
	// 	$order_status_array[] = array(
	// 		'title' => $title,
	// 		'slug'  => $slug,
	// 		'icon'  => $icon,
	// 	);

	// 	// Update the custom order status list
	// 	$result = update_option('bt_sst_order_status_list', $order_status_array);

	// 	// Register the new status dynamically
	// 	register_post_status('wc-' . sanitize_title($slug), array(
	// 		'label'                     => $title,
	// 		'public'                    => true,
	// 		'exclude_from_search'       => false,
	// 		'show_in_admin_all_list'    => true,
	// 		'show_in_admin_status_list' => true,
	// 		'label_count'               => _n_noop($title . ' <span class="count">(%s)</span>', $title . ' <span class="count">(%s)</span>'),
	// 	));

	// 	// Hook into WooCommerce to add the new status
	// 	add_filter('wc_order_statuses', function ($statuses) use ($title, $slug) {
	// 		$statuses['wc-' . sanitize_title($slug)] = $title;
	// 		return $statuses;
	// 	});

	// 	// Return success or failure
	// 	if ($result) {
	// 		wp_send_json_success(array('message' => 'Status added successfully'));
	// 	} else {
	// 		wp_send_json_error(array('message' => 'Failed to save status'));
	// 	}
	// }

	function bt_sst_update_status_mapping()
	{
		//$is_premium = $this->licenser->is_license_active();
		//if ($is_premium) {
			$order_status_key = sanitize_text_field($_POST['shipping_data']['shippingValue']);
			$shipping_status_Key = sanitize_text_field($_POST['shipping_data']['shippingKey']);
			$updated_order_and_shipp_status_keys_array = get_option('bt_sst_order_and_shipp_status_keys_array', array());
			if (empty($order_status_key)) {
				unset($updated_order_and_shipp_status_keys_array[$shipping_status_Key]);
			} else {
				$updated_order_and_shipp_status_keys_array[$shipping_status_Key] = $order_status_key;
			}

			$result = update_option('bt_sst_order_and_shipp_status_keys_array', $updated_order_and_shipp_status_keys_array);
			wp_send_json_success(array('message' => 'Updated Status Successfully'));
		//} else {
		//	wp_send_json_error(array('message' => 'Upgrade to premium version to activate custom order status mapping.'));
		//}


	}
	function bt_sst_remove_status_mapping()
	{
		//$is_premium = $this->licenser->is_license_active();
		//if ($is_premium) {
			$shipping_status_Key = sanitize_text_field($_POST['shipping_key']);
			$updated_order_and_shipp_status_keys_array = get_option('bt_sst_order_and_shipp_status_keys_array', array());
			$order_status_key = "";
			if ($shipping_status_Key && isset($updated_order_and_shipp_status_keys_array[$shipping_status_Key])) {
				$order_status_key = $updated_order_and_shipp_status_keys_array[$shipping_status_Key];
				unset($updated_order_and_shipp_status_keys_array[$shipping_status_Key]);
			}

			$order_status_array = get_option('bt_sst_order_status_list', array());
			foreach ($order_status_array as $order_status) {
				// Validate required fields
				if ('wc-' . $order_status['slug'] == $order_status_key) {
					$key = array_search($order_status, $order_status_array);
					if ($key !== false) {
						unset($order_status_array[$key]);
						$result2 = update_option('bt_sst_order_status_list', $order_status_array);
					}
				}
			}

			$result = update_option('bt_sst_order_and_shipp_status_keys_array', $updated_order_and_shipp_status_keys_array);
			wp_send_json_success(array('message' => 'Remove Status Successfully'));
		//} else {
		//	wp_send_json_error(array('message' => 'Upgrade to premium version to activate custom order status mapping.'));
		//}


	}

	function status_orders_bulk_actions($bulk_actions)
	{
		// $updated_order_and_shipp_status_keys_array = get_option('bt_sst_order_and_shipp_status_keys_array', array());
		$order_status_array = get_option('bt_sst_order_status_list', array());

		// $enabled_order_status = apply_filters( 'bt_sst_shipping_statuses', BT_SHIPPING_STATUS );
		foreach ($order_status_array as $key => $value) {
			if (!isset($bulk_actions[$value['slug']])) {
				$bulk_actions[$value['slug']] = __('Change to ' . $value['title']);
			}
		}
		return $bulk_actions;
	}

	function status_bulk_action_edit_shop_order($redirect_to, $action, $post_ids)
	{
		$order_status_array = get_option('bt_sst_order_status_list', array());

		$slugs = array_column($order_status_array, 'slug');
		if (in_array($action, $slugs)) {
			foreach ($post_ids as $post_id) {
				$order = wc_get_order($post_id);
				$order->update_status($action);
			}
		}



		return $redirect_to;
	}




	function bt_sst_save_manual_coriure_name()
	{
		$company_name = sanitize_text_field($_POST['company_name']);
		$region_coverage = sanitize_text_field($_POST['region_coverage']);
		$company_url = esc_url_raw($_POST['company_url']);
		$tracking_url = esc_url_raw($_POST['tracking_url']);

		$couriers = get_option('bt_sst_manual_coriures_names_array', array());
		$plugin_dir = plugin_dir_path(__FILE__);
		$json_file = $plugin_dir . "custom_coriures_list.json";

		if (file_exists($json_file)) {
			$json_data = file_get_contents($json_file);
		}

		$json_data = json_decode($json_data, true);

		if (is_array($couriers) && sizeof($couriers) > 0) {
			// Merge the arrays if $couriers has elements
			$combined_data = array_merge($json_data, $couriers);
		} else {
			// If $couriers is empty, use $json_data as the result
			$combined_data = $json_data;
			$couriers = array();
		}

		$id = sizeof($combined_data) + 1;

		$couriers[] = array(
			'serial_no' => $id,
			'company_name' => $company_name,
			'region_coverage' => $region_coverage,
			'company_url' => $company_url,
			'tracking_url' => $tracking_url
		);
		$result = update_option('bt_sst_manual_coriures_names_array', $couriers);

		if ($result) {
			wp_send_json_success();
		} else {
			wp_send_json_error(array('message' => 'Failed to save courier'));
		}
	}



	// function register_shipment_arrival_order_status() {
	// 	$enabled_order_status = apply_filters( 'bt_sst_shipping_statuses', BT_SHIPPING_STATUS );;// carbon_get_theme_option( 'bt_sst_enable_order_status' );
	// 	foreach ($enabled_order_status as $key=>$value) {
	// 		$l = BT_SHIPPING_STATUS[$key];

	// 		register_post_status( $value, array(
	// 			'label'                     => $value,
	// 			'public'                    => true,
	// 			'show_in_admin_status_list' => true,
	// 			'show_in_admin_all_list'    => true,
	// 			'exclude_from_search'       => false,
	// 			'label_count'               => _n_noop( $l.' <span class="count">(%s)</span>', $l.' <span class="count">(%s)</span>' )
	// 		) );
	// 	}
	//  }

	// function add_awaiting_shipment_to_order_statuses( $order_statuses ) {
	// 	$new_order_statuses = array();
	// 	foreach ( $order_statuses as $key => $status ) {
	// 		$new_order_statuses[ $key ] = $status;
	// 		if ( 'wc-processing' === $key ) {
	// 			$enabled_order_status = apply_filters( 'bt_sst_shipping_statuses', BT_SHIPPING_STATUS );

	// 			foreach ($enabled_order_status as $key => $value) {
	// 				$new_order_statuses[$key] = $value;
	// 			}
	// 		}
	// 	}
	// 	return $new_order_statuses;
	// }

	function bt_sst_get_users_list()
	{
		if (isset($_POST['task']) && sanitize_text_field($_POST['task']) === 'get_pick_up_location') {
			$pick_up_locations = $this->shiprocket->get_all_pickup_locations();
			$html_pick_lo = '<label for="bt_sst_vendor_pickup_location" class="label">Pickup Location</label>';
			$html_pick_lo .= '<div class="control"><div class="select">';
			$html_pick_lo .= '<select id="bt_sst_vendor_pickup_location">';
			$html_pick_lo .= '<option value="">Select Pick-Up Location</option>';

			foreach ($pick_up_locations as $value) {
				$html_pick_lo .= '<option value="' . htmlspecialchars($value['pickup_location']) . '">' . htmlspecialchars($value['pickup_location']) . '</option>';
			}

			$html_pick_lo .= '</select></div></div>';

			$resp = [
				"html_pick_lo" => $html_pick_lo,
			];
		} else {
			$args = array(
				'role__in' => array('seller'),
				'orderby' => 'user_nicename',
				'order' => 'ASC'
			);

			$users = get_users($args);
			$html = '<label for="bt_sst_vendor_pickup_location" class="label">Vendor Name</label>';
			$html .= '<div class="control">
			  <div class="select">';
			$html .= '<select id="bt_sst_select">';
			$html .= '<option value="" >Select Vendor</option>';

			foreach ($users as $user) {
				$html .= '<option value="' . esc_attr($user->ID) . '" title="' . esc_attr($user->display_name . '(' . $user->user_nicename . ')') . '">';
				$html .= esc_html($user->display_name) . ' [' . esc_html($user->user_nicename) . ']';
				$html .= '</option>';
			}
			$html .= '</select>';
			$html .= '</div>
			  </div>';

			$pick_up_locations = $this->shiprocket->get_all_pickup_locations();
			$html_pick_lo = '<label for="bt_sst_vendor_pickup_location" class="label">Pickup Location</label>';
			$html_pick_lo .= '<div class="control">
			  <div class="select">';
			$html_pick_lo .= '<select id="bt_sst_vendor_pickup_location">';
			$html_pick_lo .= '<option value="" >Select Pick-Up Location</option>';
			foreach ($pick_up_locations as $value) {
				$html_pick_lo .= '<option value="' . $value['pickup_location'] . '" >' . $value['pickup_location'] . '</option>';
			}
			$html_pick_lo .= '</select>';
			$html_pick_lo .= '</div>
			  </div>';
			$resp = [
				"html_pick_lo" => $html_pick_lo,
				"html" => $html
			];
		}
		wp_send_json($resp);
		die();
	}
	function bt_sst_set_users_list()
	{
		if (isset($_POST['vendor_pickup_location']) && isset($_POST['vendor_user_id'])) {
			$vendor_pickup_location = sanitize_text_field($_POST['vendor_pickup_location']);
			$vendor_user_id = sanitize_text_field($_POST['vendor_user_id']);
			update_user_meta($vendor_user_id, 'vendor_pickup_location', $vendor_pickup_location);
			$resp = "success";
		} else {
			$resp = "faild";
		}

		wp_send_json($resp);
		die();
	}
	function bt_sst_check_users_list()
	{
		$current_user_id = get_current_user_id();
		// var_dump($current_user_id); die;
		if (isset($_POST['vendor_user_id'])) {
			$vendor_user_id = sanitize_text_field($_POST['vendor_user_id']);

			$vendor_pickup_location = get_user_meta($vendor_user_id, 'vendor_pickup_location', true);
			if (!$vendor_user_id) {
				$resp = "";
			} else {
				$resp = $vendor_pickup_location;
			}
		} else {
			$resp = "";
		}

		wp_send_json($resp);
		die();
	}

	function create_and_add_tracking_page()
	{
		$nonce = sanitize_text_field($_GET['nonce']);

		if (!wp_verify_nonce($nonce, 'create_and_add_tracking_page')) {
			exit;
		}

		$response = array(
			"status" => true,
			"data" => '',
			"message" => "Page created!"
		);

		wp_send_json($response);
		die();
	}

	public function my_admin_footer_function()
	{

		echo "<div id='show_dialog' title='Update Shipment Details'>
				
		</div>";

		require_once (dirname(__FILE__)) . '/partials/bt-shipment-deactivation-popup.php';
		if (is_admin() && isset($_GET["page"]) && $_GET['page'] == "crb_carbon_fields_container_shipment_tracking.php") {
			require_once (dirname(__FILE__)) . '/partials/bt-st-buy-credits-popup.php';
			require_once (dirname(__FILE__)) . '/partials/bt-st-setup-guide.php';
		}
		$bt_sst_enable_orde_status_mapping = carbon_get_theme_option('bt_sst_enable_orde_status_mapping');
		if($bt_sst_enable_orde_status_mapping){
			$this->add_custom_order_status_colors();
		}
	}


	private function add_custom_order_status_colors(){
		$statuses = [
			// Original statuses with colors from second snippet
			'pre-order'             => ['#ffcc00', '#000'],         // Yellow with black text
			'custom-order'          => ['#ff5733', '#fff'],         // Orange with white text
			
			// Your original shipping statuses
			'ready-to-ship'         => ['#00c1d4', '#fff'],         // Teal with white text
			'out-for-pickup'        => ['#9c27b0', '#fff'],         // Purple with white text
			'pickup-scheduled'      => ['#673ab7', '#fff'],         // Deep purple with white text
			'pickup-pending'        => ['#3f51b5', '#fff'],         // Indigo with white text
			
			// Refund-related statuses (with corrected slugs)
			'refunded'              => ['#8e24aa', '#fff'],         // Vibrant purple with white text
			'wc-return-requested'   => ['#ff9800', '#000'],         // Amber with black text
			'wc-return-approved'    => ['#4caf50', '#fff'],         // Green with white text
			
			// Shipping statuses
			'wc-in-transit'         => ['#00bcd4', '#fff'],         // Cyan with white text (for in-transit)
			
			// Standard WooCommerce statuses (for reference)
			//'completed'             => ['#4caf50', '#fff'],         // Green with white text
			//'processing'            => ['#2196f3', '#fff'],         // Blue with white text
			//'on-hold'               => ['#ff9800', '#000'],         // Amber with black text
			//'cancelled'             => ['#f44336', '#fff'],         // Red with white text
		];
		
		$css = '';
		foreach ($statuses as $status => $colors) {
			$clean_status = str_replace('wc-', '', $status); // Remove wc- prefix for some selectors
			$css .= "
			.order-status.status-{$status},
			.order-status.status-{$clean_status},  
			.widefat .column-order_status mark.{$status},
			.widefat .column-order_status mark.{$clean_status},
			.order-preview .order-status.status-{$status},
			.order-preview .order-status.status-{$clean_status} {
				background: {$colors[0]} !important;
				color: {$colors[1]} !important;
				border-color: {$colors[0]} !important;
			}
			.order-status.status-{$status}:after,
			.order-status.status-{$clean_status}:after {
				color: {$colors[1]} !important;
			}
			";
		}
		
		echo '<style>' . $css . '</style>';
	}

	function register_shipment_email($email_classes)
	{
		require_once(plugin_dir_path(dirname(__FILE__)) . 'includes/emails/class-bt-sst-wc-shipment-email.php'); // Path to your email class file
		// add the email class to the list of email classes that WooCommerce loads
		$email_classes['Bt_Sst_WC_Shipment_Email'] = new Bt_Sst_WC_Shipment_Email();

		return $email_classes;
	}

	function add_product_processing_time_shipping_field()
	{
		//$enabled_shipping_providers = carbon_get_theme_option( 'bt_sst_enabled_shipping_providers' );
		//if(is_array($enabled_shipping_providers) && in_array('shiprocket',$enabled_shipping_providers)){
		global $post;

		// Display a text input field
		woocommerce_wp_text_input(
			array(
				'id' => '_bt_sst_product_processing_days_field',
				'label' => __('Processing Time', 'bt_sst'),
				'placeholder' => __('Enter Processing Time (Days)', 'bt_sst'),
				'desc_tip' => true,
				'description' => __('Enter this product\'s processing time for shipping - Shipment Tracker by Woocommerce', 'bt_sst'),
				'value' => Bt_Sync_Shipment_Tracking::bt_sst_get_product_meta($post->ID, '_bt_sst_product_processing_days_field', true),
			)
		);
		//}

	}

	function save_product_processing_time_shipping_field($post_id)
	{
		//$enabled_shipping_providers = carbon_get_theme_option( 'bt_sst_enabled_shipping_providers' );
		//if(is_array($enabled_shipping_providers) && in_array('shiprocket',$enabled_shipping_providers)){
		$custom_field_value = isset($_POST['_bt_sst_product_processing_days_field']) ? sanitize_text_field($_POST['_bt_sst_product_processing_days_field']) : '';
		Bt_Sync_Shipment_Tracking::bt_sst_update_product_meta($post_id, '_bt_sst_product_processing_days_field', esc_attr($custom_field_value));
		//}
	}

	function add_variable_product_processing_time_shipping_field($loop, $variation_data, $variation)
	{

		woocommerce_wp_text_input(
			array(
				'id' => '_bt_sst_product_processing_days_field_' . $loop,
				'label' => __('Processing Time', 'bt_sst'),
				'placeholder' => __('Enter Processing Time (Days)', 'bt_sst'),
				'desc_tip' => true,
				'description' => __('Enter this variation\'s processing time for shipping - Shipment Tracker by Woocommerce', 'bt_sst'),
				'value' => Bt_Sync_Shipment_Tracking::bt_sst_get_product_meta($variation->ID, '_bt_sst_product_processing_days_field', true),
			)
		);
	}

	function save_variable_product_processing_time_shipping_field($variation_id, $i)
	{
		$bt_sst_product_days_field = isset($_POST['_bt_sst_product_days_field_' . $i]) ? sanitize_text_field($_POST['_bt_sst_product_processing_days_field_' . $i]) : '';
		Bt_Sync_Shipment_Tracking::bt_sst_update_product_meta($variation_id, '_bt_sst_product_processing_days_field', $bt_sst_product_processing_days_field);
	}


	function add_product_cat_processing_time_shipping_field($term)
	{


		//$enabled_shipping_providers = carbon_get_theme_option( 'bt_sst_enabled_shipping_providers' );
		//if(is_array($enabled_shipping_providers) && in_array('shiprocket',$enabled_shipping_providers)){
		// Get existing value for editing
		$value = "";
		if ($term instanceof WP_Term) {
			$value = get_term_meta($term->term_id, '_bt_sst_product_category_processing_days_field', true);
		}

		?>
		<tr class="form-field">
			<th scope="row"><label
					for="_bt_sst_product_category_processing_days_field"><?php _e('Processing Time', 'bt_sst'); ?></label>
			</th>
			<td>
				<input type="text" name="term_meta[_bt_sst_product_category_processing_days_field]"
					id="_bt_sst_product_category_processing_days_field" value="<?php echo esc_attr($value); ?>">
				<p class="description">
					<?php _e('Enter this product\'s processing time (Days) for shipping - Shipment Tracker by Woocommerce.', 'bt_sst'); ?>
				</p>
			</td>
		</tr>
		<?php
		//}
	}

	function save_product_cat_processing_time_shipping_field($term_id, $tt_id)
	{
		//$enabled_shipping_providers = carbon_get_theme_option( 'bt_sst_enabled_shipping_providers' );
		//if(is_array($enabled_shipping_providers) && in_array('shiprocket',$enabled_shipping_providers)){
		if (isset($_POST['term_meta'])) {
			$term_meta = $_POST['term_meta'];
			$term_meta['_bt_sst_product_category_processing_days_field'] = sanitize_text_field($term_meta['_bt_sst_product_category_processing_days_field']);
			update_term_meta($term_id, '_bt_sst_product_category_processing_days_field', sanitize_text_field($term_meta['_bt_sst_product_category_processing_days_field']));
		}
		//}
	}
	function custom_dokan_order_details($order)
	{
		// Check if the order object exists
		if (!is_a($order, 'WC_Order')) {
			return;
		}
		echo "<div class = 'dokan-panel dokan-panel-default'>";
		echo '<div class="dokan-panel-heading">Shipment Tracking</div>';
		$post_id = $order->get_id();
		$order_id = $order->get_id();
		if (empty($post_id)) {
			$post_id = sanitize_text_field($_GET["id"]);
		}

		$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($post_id);
		$bt_shipping_provider = $bt_shipment_tracking->shipping_provider;
		$bt_shipping_awb_number = $bt_shipment_tracking->awb;

		$shipping_mode_is_manual_or_ship24 = carbon_get_theme_option('bt_sst_enabled_custom_shipping_mode');
		// $shipping_mode_is_manual_or_ship24 = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($post_id, '_bt_sst_custom_shipping_mode', true);
		echo "<div class='dokan-panel-body'>";
		if (current_user_can("manage_options")) {
			if (isset($_POST['wc_order_action_bt_sst_shipping_provider'])) {
				$new_provider = sanitize_text_field($_POST['wc_order_action_bt_sst_shipping_provider']);
				Bt_Sync_Shipment_Tracking::bt_sst_update_order_meta($order->get_id(), '_bt_shipping_provider', $new_provider);
			}

			$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			echo '<form action="' . esc_url($current_url) . '" method="post">';
			include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/bt-woocommerce-order-actions-end.php';
			echo "</form>";
		} else {
			echo '<div style="pointer-events: none; opacity: 0.5;">'; // Makes it non-clickable and visually indicates it's disabled
			include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/bt-woocommerce-order-actions-end.php';
			echo "</div>";
		}
		if (!$bt_shipping_provider || ($bt_shipping_provider == 'manual' && $shipping_mode_is_manual_or_ship24 == "manual")) {
			include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/bt-shipment-tracking-manual-metabox.php';
		} else if ($bt_shipping_provider == 'shiprocket' || $bt_shipping_provider == 'shyplite' || $bt_shipping_provider == 'nimbuspost' || $bt_shipping_provider == 'xpressbees' || $bt_shipping_provider == 'shipmozo' || $bt_shipping_provider == 'nimbuspost_new' || $bt_shipping_provider == 'delhivery' || $shipping_mode_is_manual_or_ship24 == "ship24" || $bt_shipping_provider == "fship") {
			include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/bt-shipment-tracking-metabox.php';
		}
		echo "</div>";
		echo "</div>";

	}
	// public function post_customer_feedback_to_sever() {

	// 	$current_user = wp_get_current_user();
	// 	$body = array(
	// 		'your-message'    => esc_html($_POST['feedback']),
	// 		'your-name'    => esc_html( $current_user->display_name ),
	// 		'your-subject'    => "Plugin Feedback from " . get_site_url(),
	// 		'your-email'    => esc_html(get_bloginfo('admin_email')),
	// 	);

	// 	$base_url="https://shipment-tracker-for-woocommerce.bitss.tech";
	// 	$cfid=4;
	// 	$resp = $this->post_cf7_data($body,$cfid,$base_url );
	// 	return;
	// }

	// private function post_cf7_data($body,$cfid,$base_url ){
	// 	// Same user agent as in regular wp_remote_post().
	// 	$userAgent = 'WordPress/' . get_bloginfo('version') . '; ' . get_bloginfo('url');
	// 	// Note that Content-Type wrote in a bit different way.
	// 	$header = ['Content-Type: multipart/form-data'];

	// 	$apiUrl = "$base_url/wp-json/contact-form-7/v1/contact-forms/$cfid/feedback";

	// 	$curlOpts = [
	// 		// Send as POST
	// 		CURLOPT_POST => 1,
	// 		// Get a response data instead of true
	// 		CURLOPT_RETURNTRANSFER => 1,
	// 		// CF7 will reject your request as spam without it.
	// 		CURLOPT_USERAGENT => $userAgent,
	// 		CURLOPT_HTTPHEADER => $header,
	// 		CURLOPT_POSTFIELDS => $body,
	// 	];

	// 	$ch = curl_init($apiUrl);          // Create a new cURL resource.
	// 	curl_setopt_array($ch, $curlOpts); // Set options.
	// 	$response = curl_exec($ch);        // Grab response.

	// 	if (!$response) {
	// 		// Do something if an error occurred.
	// 	} else {
	// 		$response = json_decode($response);
	// 		// Do something with the response data.
	// 	}

	// 	// Close cURL resource, and free up system resources.
	// 	curl_close($ch);

	// 	return  $response ;

	// }

	function download_label_pdf()
	{
		$order_id = sanitize_text_field($_POST['order_id']);
		$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($order_id);
		$shipping_provider = $bt_shipment_tracking->shipping_provider;

		//  var_dump($shipping_provider); die;
		// $shipping_provider = 'delhivery';
		if ($shipping_provider == 'delhivery') {
			if (isset($_POST['awbs'])) {
				$awb_number = array_map('sanitize_text_field', $_POST['awbs']);
				$resp = $this->delhivery->get_order_label_by_awb_numbers($awb_number);
			}
		} else if ($shipping_provider == 'shiprocket') {
			$shipments_ids = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_shiprocket_shipment_id', true);
			if (isset($_POST['awbs'])) {
				// $shipments_ids = $_POST['awbs'];
				$resp = $this->shiprocket->get_order_label_by_shipment_id([$shipments_ids]);
			}
		} else if ($shipping_provider == 'shipmozo') {
			// $shipments_ids = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta( $order_id, '_bt_shiprocket_shipment_id', true );
			if (isset($_POST['awbs'])) {
				$awbs = array_map('sanitize_text_field', $_POST['awbs']);
				$resp = $this->shipmozo->get_order_label_by_awb_numbers_shipmozo($awbs);
			}
		} else if ($shipping_provider == 'nimbuspost_new') {
			$nimbuspost_new_label_url = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_nimbuspost_new_label_url', true);
			if ($nimbuspost_new_label_url) {
				// $awbs = $_POST['awbs'];
				// var_dump($awbs);
				// $resp = $this->nimbuspost_new->get_order_label_by_order_ids();
				$resp = array($nimbuspost_new_label_url);
			} else {
				$resp = false;
			}
		}
		// else if($shipping_provider == 'nimbuspost'){
		// $shipments_ids = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta( $order_id, '_bt_shiprocket_shipment_id', true );
		// if (isset($_POST['awbs'])){
		// $awbs = $_POST['awbs'];
		// var_dump($awbs);
		// $resp = $this->nimbuspost->get_order_label_by_order_ids();
		// }
		// }
		wp_send_json($resp);
		wp_die();
	}
	function handle_stw_wizard_form_data_save()
	{
		if (isset($_POST['data'])) {
			$wizard_post_data = array_map('sanitize_text_field', $_POST['data']);

			$w_shipping_company = $wizard_post_data['shipping_company'];
			$w_create_tracking_page = $wizard_post_data['create_tracking_page'];
			$w_enable_location_map = $wizard_post_data['enable_location_map'];
			$w_enable_rating_bar = $wizard_post_data['enable_rating_bar'];
			$w_etd_checker = $wizard_post_data['etd_checker'];
			$w_dynamic_shipping_method = $wizard_post_data['dynamic_shipping_methods'];
			$shipping_manual_or_s24 = $wizard_post_data['shipping_manual_or_s24'];

			$base_pincode = WC()->countries->get_base_postcode();

			// set shipping provide 
			$w_shipping_company_arra = array($w_shipping_company);
			carbon_set_theme_option('bt_sst_enabled_shipping_providers', $w_shipping_company_arra);

			//defauld shipping provide
			carbon_set_theme_option('bt_sst_default_shipping_provider', $w_shipping_company);

			// if($w_create_tracking_page=="yes"){
			// 	$page_title = 'Shipment Tracking';
			// 	$page_check = get_page_by_title($page_title);
			// 	if (!isset($page_check->ID)) {
			// 		$new_page = array(
			// 			'post_title'     => $page_title,
			// 			'post_content'   => '[bt_shipping_tracking_form_2]',
			// 			'post_status'    => 'publish',
			// 			'post_type'      => 'page',
			// 			'post_author'    => get_current_user_id(),
			// 		);

			// 		$page_id = wp_insert_post($new_page);
			// 		// return $page_id;
			// 	}
			// 	$page_id = $page_check->ID;
			// 	carbon_set_theme_option('bt_sst_tracking_page', $page_id);
			// }
			$page_id = "";
			if ($w_create_tracking_page == "yes") {
				$page_title = 'Shipment Tracking';
				$args = array(
					'post_type' => 'page',
					'title' => $page_title,
					'post_status' => 'publish',
					'numberposts' => 1
				);

				$page_check = get_posts($args);

				if (empty($page_check)) {
					$new_page = array(
						'post_title' => sanitize_text_field($page_title), // Sanitize title
						'post_content' => '[bt_shipping_tracking_form_2]',  // Shortcode or content
						'post_status' => 'publish',
						'post_type' => 'page',
						'post_author' => get_current_user_id(),
					);
					$page_id = wp_insert_post($new_page);
				} else {
					$page_id = $page_check[0]->ID;
				}
				carbon_set_theme_option('bt_sst_tracking_page', $page_id);
			}

			if ($w_enable_location_map == "true") {
				carbon_set_theme_option('bt_sst_navigation_map', "yes");
			}
			if ($w_enable_rating_bar == "true") {
				carbon_set_theme_option('bt_sst_enable_rating', "yes");
			}
			if ($w_etd_checker == "yes") {
				carbon_set_theme_option('bt_sst_shiprocket_pincode_checker', 'no');
				carbon_set_theme_option('bt_sst_pincode_checker_location', 'woocommerce_after_add_to_cart_button');
				if ($w_shipping_company == "delhivery") {
					carbon_set_theme_option('bt_sst_pincode_data_provider', $w_shipping_company);
					carbon_set_theme_option('bt_sst_shiprocket_processing_days', "1");
					carbon_set_theme_option('bt_sst_shiprocket_processing_days_location', "woocommerce_after_add_to_cart_button");
				} elseif ($w_shipping_company == "nimbuspost") {
					carbon_set_theme_option('bt_sst_pincode_data_provider', $w_shipping_company);
					carbon_set_theme_option('bt_sst_nimbuspost_pickup_pincode', $base_pincode);
					carbon_set_theme_option('bt_sst_shiprocket_processing_days', "1");
					carbon_set_theme_option('bt_sst_shiprocket_processing_days_location', "woocommerce_after_add_to_cart_button");
				} elseif ($w_shipping_company == "shipmozo") {
					carbon_set_theme_option('bt_sst_pincode_data_provider', $w_shipping_company);
					carbon_set_theme_option('bt_sst_shipmozo_pickup_pincode', $base_pincode);
					carbon_set_theme_option('bt_sst_shiprocket_processing_days', "1");
					carbon_set_theme_option('bt_sst_shiprocket_processing_days_location', "woocommerce_after_add_to_cart_button");
				} elseif ($w_shipping_company == "shiprocket") {
					carbon_set_theme_option('bt_sst_pincode_data_provider', $w_shipping_company);
					carbon_set_theme_option('bt_sst_shiprocket_pickup_pincode', $base_pincode);
					carbon_set_theme_option('bt_sst_shiprocket_processing_days', "1");
					carbon_set_theme_option('bt_sst_shiprocket_processing_days_location', "woocommerce_after_add_to_cart_button");
				} elseif ($w_shipping_company == "manual") {
					carbon_set_theme_option('bt_sst_pincode_data_provider', $w_shipping_company);
				}

			}
			if ($w_dynamic_shipping_method == "yes") {
				carbon_set_theme_option('bt_sst_select_courier_company', 'no');
				carbon_set_theme_option('bt_sst_show_shipment_weight', 'no');
				if ($w_shipping_company == "delhivery") {
					carbon_set_theme_option('bt_sst_courier_rate_provider', $w_shipping_company);
					carbon_set_theme_option('bt_sst_markup_charges', "20");
					carbon_set_theme_option('bt_sst_delhivery_fall_back_rate', "40");
					carbon_set_theme_option('bt_sst_auto_fill_city_state', "no");
					carbon_set_theme_option('bt_sst_data_provider', "delhivery");

				} elseif ($w_shipping_company == "nimbuspost") {
					carbon_set_theme_option('bt_sst_courier_rate_provider', $w_shipping_company);
					carbon_set_theme_option('bt_sst_markup_charges', "20");
					carbon_set_theme_option('bt_sst_nimbuspost_new_fall_back_rate', "40");
					carbon_set_theme_option('bt_sst_nimbuspost_pickup_pincode', $base_pincode);

				} elseif ($w_shipping_company == "shipmozo") {
					carbon_set_theme_option('bt_sst_courier_rate_provider', $w_shipping_company);
					carbon_set_theme_option('bt_sst_markup_charges', "20");
					carbon_set_theme_option('bt_sst_shipmozo_fall_back_rate', "40");
					carbon_set_theme_option('bt_sst_shipmozo_pickup_pincode', $base_pincode);

				} elseif ($w_shipping_company == "shiprocket") {
					carbon_set_theme_option('bt_sst_courier_rate_provider', $w_shipping_company);
					carbon_set_theme_option('bt_sst_markup_charges', "20");
					carbon_set_theme_option('bt_sst_shiprocket_fall_back_rate', "40");
					carbon_set_theme_option('bt_sst_shiprocket_pickup_pincode', $base_pincode);
					carbon_set_theme_option('bt_sst_auto_fill_city_state', "no");
					carbon_set_theme_option('bt_sst_data_provider', "shiprocket");

				} elseif ($w_shipping_company == "manual") {
					carbon_set_theme_option('bt_sst_courier_rate_provider', $w_shipping_company);
					carbon_set_theme_option('bt_sst_add_shipping_methods', array(
						array(
							'value' => "_",
							'bt_sst_shipping_method' => array($w_shipping_company),
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

				}
			}
			if ($w_shipping_company == "manual") {
				carbon_set_theme_option('bt_sst_enabled_shipping_providers', $w_shipping_company);
				if ($shipping_manual_or_s24 == "manual") {
					carbon_set_theme_option('bt_sst_enabled_custom_shipping_mode', $w_shipping_company);
				} else {
					carbon_set_theme_option('bt_sst_enabled_custom_shipping_mode', "ship24");
				}
			}
		}
		// update_option("bt_sst_check_wizard_active", "active");

		$wizard_post_datasite_url = get_permalink($page_id);
		$wizard_post_data['tracking_page_url'] = $wizard_post_datasite_url;
		if ($w_shipping_company == "manual" && $shipping_manual_or_s24 == "ship24.com") {
			$wizard_post_data['shipping_company'] = 'ship24';
		}
		$data = $wizard_post_data;
		wp_send_json($data);
		wp_die();
	}

	function get_coriers_name_for_ship24()
	{

		if ($_POST['status']) {
			$response = $this->ship24->get_coriers_name_and_test_connectin();
			wp_send_json_success($response);
		}
		return "faild";
		wp_die();
	}

	public function bt_add_admin_menu()
	{
		add_menu_page(
			'Shipment Tracking Dashboard', // Page title
			'Shipment Tracking',       // Menu title
			'manage_options',    // Capability
			'bt-shipment-tracking',  // Menu slug
			[$this, 'bt_shipment_tracking_dashboard_callback'],
			'dashicons-airplane', // Icon
			55                     // Position after WooCommerce
		);

		add_submenu_page(
			'bt-shipment-tracking',
			'Dashboard - Shipment Tracker for Woocommerce',           // Page title
			'Dashboard',       // Menu title
			'manage_options',          // Capability
			'bt-shipment-tracking',           // Menu slug
			[$this, 'bt_shipment_tracking_dashboard_callback'], // Correct way to call class method
		);



		add_submenu_page(
			'bt-shipment-tracking',
			'Order Notifications - Shipment Tracker for Woocommerce',           // Page title
			'Messaging',       // Menu title
			'manage_options',          // Capability
			'bt-shipment-tracking-sms-setting',           // Menu slug
			[$this, 'bt_shipment_tracking_sms_setting'], // Correct way to call class method
		);

		add_submenu_page(
			'bt-shipment-tracking',
			'Free Shipping Timer - Shipment Tracker for Woocommerce',           // Page title
			'Free Shipping Timer',       // Menu title
			'manage_options',          // Capability
			'bt-shipment-tracking-timer',           // Menu slug
			[$this, 'bt_shipment_tracking_timer_callback'], // Correct way to call class method
		);

		// add_submenu_page(
		// 	'bt-shipment-tracking',
		// 	'Shipment Tracking Custom Order Status Mapping',           // Page title
		// 	'Custom Statuses',       // Menu title
		// 	'manage_options',          // Capability
		// 	'bt-shipment-tracking-custom-order-statuses',           // Menu slug
		// 	[$this, 'bt_shipment_tracking_dashboard_callback'], // Correct way to call class method
		// );
		add_submenu_page(
			'bt-shipment-tracking',
			'Premium Activation - Shipment Tracker for Woocommerce',           // Page title
			'Activate Premium',       // Menu title
			'manage_options',          // Capability
			'bt-shipment-tracking-premium-activation',           // Menu slug
			[$this, 'bt_shipment_tracking_premium_callback'], // Correct way to call class method
		);

		add_submenu_page(
			'bt-shipment-tracking',
			'Developer - Shipment Tracker for Woocommerce',           // Page title
			'Developer',       // Menu title
			'manage_options',          // Capability
			'bt-shipment-tracking-developer',           // Menu slug
			[$this, 'bt_shipment_tracking_developer_callback'], // Correct way to call class method
		);

		add_submenu_page(
			'bt-shipment-tracking',
			'Help & Support - Shipment Tracker for Woocommerce',           // Page title
			'Help & Support',       // Menu title
			'manage_options',          // Capability
			'bt-shipment-tracking-help-support',           // Menu slug
			[$this, 'bt_shipment_tracking_help_callback'], // Correct way to call class method
		);
	}

	public function bt_shipment_tracking_developer_callback()
	{
		// wp_enqueue_script('bt-shipment-new-settings-js');
		wp_enqueue_script($this->plugin_name);
		$active_tab = "developer_tab";
		wp_enqueue_style('bulma-css');
		include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/new_settings/bt-shipment-new-settings.php';
		// include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/new_settings/bt-shipment-help.php';
	}

	public function bt_shipment_tracking_help_callback()
	{
		// wp_enqueue_script('bt-shipment-new-settings-js');
		wp_enqueue_script($this->plugin_name);
		$active_tab = "help_tab";
		wp_enqueue_style('bulma-css');
		include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/new_settings/bt-shipment-new-settings.php';
		// include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/new_settings/bt-shipment-help.php';
	}

	public function bt_shipment_tracking_sms_setting()
	{
		wp_enqueue_script('bt-shipment-new-settings-js');
		wp_enqueue_script($this->plugin_name);
		$active_tab = "sms_tab";
		$check_active_registration = get_option('register_for_sms_apy_key');
		wp_enqueue_style('bulma-css');
		include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/new_settings/bt-shipment-sms-settings.php';
	}
	public function bt_shipment_tracking_dashboard_callback()
	{
		$active_tab = "dashboard";
		wp_enqueue_style('angular-toastr');
		wp_enqueue_style('quill-css');
		wp_enqueue_style('bulma-css');
		wp_enqueue_script('angular-js');
		wp_enqueue_script('angular-toastr-js');
		wp_enqueue_script('unpkg-js');
		wp_enqueue_script('quill-js');
		wp_enqueue_script('bt-shipment-new-settings-js');
		include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/new_settings/bt-shipment-new-settings.php';
	}

	public function bt_shipment_tracking_timer_callback()
	{
		$is_premium = $this->licenser->is_license_active();
		$active_tab = "timer_tab";
		wp_enqueue_style('angular-toastr');
		wp_enqueue_style('quill-css');
		wp_enqueue_style('bulma-css');
		wp_enqueue_script('angular-js');
		wp_enqueue_script('angular-toastr-js');
		wp_enqueue_script('unpkg-js');
		wp_enqueue_script('quill-js');
		wp_enqueue_script('bt-shipment-new-settings-js');
		include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/new_settings/bt-shipment-new-settings.php';

	}

	public function bt_shipment_tracking_premium_callback()
	{
		$active_tab = "premium_tab";
		wp_enqueue_style('angular-toastr');
		wp_enqueue_style('quill-css');
		wp_enqueue_style('bulma-css');
		wp_enqueue_script('angular-js');
		wp_enqueue_script('angular-toastr-js');
		wp_enqueue_script('unpkg-js');
		wp_enqueue_script('quill-js');
		wp_enqueue_script('bt-shipment-new-settings-js');
		include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/new_settings/bt-shipment-new-settings.php';

	}

	public function bt_sst_get_tracking_settings_data()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bt-sync-shipment-setting-update-core.php';
		$shipment_setting_obj = new Class_bt_sync_shipment_setting_update_core();
		$bt_sst_timer_settings = get_option('bt_sst_timer_settings', []);
		$product_url_etd = get_option('_bt_sst_shiprocket_pincode_checker') ? $shipment_setting_obj->get_first_published_product_url() : false;
		$product_url_timer = (isset($bt_sst_timer_settings['bt_sst_timer_enable']) && $bt_sst_timer_settings['bt_sst_timer_enable'] === true) ? $shipment_setting_obj->get_first_published_product_url() : false;
		$bt_sst_timer_settings = get_option('bt_sst_timer_settings', []);
		$page_id = get_option('_bt_sst_tracking_page');
		$page_status = get_post_status($page_id);
		$tracking_url = get_permalink($page_id) && $page_status === 'publish' ? get_permalink($page_id) : '';
		$shipment_setting_url = admin_url('admin.php?page=crb_carbon_fields_container_shipment_tracking.php');
		$default_rpvider = get_option('_bt_sst_default_shipping_provider');
		if ($default_rpvider) {
			$primary_shipping_provider_url = $shipment_setting_url . '&t=' . $default_rpvider;
		} else {
			$primary_shipping_provider_url = $shipment_setting_url . '&t=manual';
		}

		$resp = [
			'tracking_page_status' => $page_status === 'publish' ? true : false,
			'estimate_delivery_checker' => get_option('_bt_sst_shiprocket_pincode_checker') ? true : false,
			'timer_setting_update' => $bt_sst_timer_settings['bt_sst_timer_enable'] === true ? true : false,
			'fetch_auto_pincode' => get_option('_bt_sst_enable_auto_postcode_fill') ? true : false,
			'fetch_weight' => get_option('_bt_sst_show_shipment_weight') ? true : false,
			'fetch_dynamic_ship_method' => get_option('_bt_sst_select_courier_company') ? true : false,
			'google_api_key' => get_option('_bt_sst_generic_google_key') ? get_option('_bt_sst_generic_google_key') : "",
			'primary_shipping_method_setting_update' => get_option('_bt_sst_default_shipping_provider'),
			'tracking_page_url' => $tracking_url,
			'primary_shipping_provider_url' => $primary_shipping_provider_url,
			'product_url_etd' => $product_url_etd,
			'product_url_timer' => $product_url_timer,
		];
		wp_send_json_success($resp);

	}
	public function bt_sst_update_tracking_settings()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bt-sync-shipment-setting-update-core.php';
		$shipment_setting_obj = new Class_bt_sync_shipment_setting_update_core();
		$type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
		$value = isset($_POST['value']) ? sanitize_text_field($_POST['value']) : '';

		$res = [];

		switch ($value) {
			case 'create_tracking_page':
				$res['tracking_url'] = $shipment_setting_obj->handle_create_tracking_page($type);
				break;
			case 'etd':
				if ($type === 'true') {
					carbon_set_theme_option('bt_sst_shiprocket_pincode_checker', true);
					$res['product_url_etd'] = $shipment_setting_obj->update_estimated_delivery_settings();
				} else {
					carbon_set_theme_option('bt_sst_shiprocket_pincode_checker', '');
					update_option('product_url_etd', false);
				}
				break;
			case 'timer':
				$res['product_url_timer'] = $shipment_setting_obj->handle_timer($type);
				break;
			case 'fetch_auto_pincode':
				if ($type === 'true') {
					carbon_set_theme_option('bt_sst_enable_auto_postcode_fill', true);
					carbon_set_theme_option('bt_sst_auto_fill_city_state', true);
				}else{
					carbon_set_theme_option('bt_sst_enable_auto_postcode_fill', '');
					carbon_set_theme_option('bt_sst_auto_fill_city_state', '');
				}
				break;
			case 'fetch_weight':
				if ($type == 'true') {
					carbon_set_theme_option('bt_sst_show_shipment_weight', true);
					carbon_set_theme_option('bt_sst_list_weight_unit', 'kg');
				}else{
					carbon_set_theme_option('bt_sst_show_shipment_weight', '');
				}
				break;
			case 'fetch_dynamic_ship_method':
				$shipment_setting_obj->handle_dynamic_ship_method($type);
				break;
			case 'primary_shipping_method':
				$res["primary_shipping_provider_url"] = $shipment_setting_obj->update_primary_shipping_method_settings($type);
				break;
			case 'google_api_key':
				carbon_set_theme_option('bt_sst_google_key_shipmozo', $type);
				carbon_set_theme_option('bt_sst_generic_google_key', $type);
				carbon_set_theme_option('bt_sst_google_key', $type);
				break;
		}

		wp_send_json_success($res);
	}


	public function update_primary_shipping_provider($provider)
	{
		carbon_set_theme_option('bt_sst_default_shipping_provider', $provider);
	}

	function bt_remove_license_callback() {
		delete_option('bt_sst_is_premium');
		wp_send_json_success(['message' => 'License removed successfully.']);
	}





}

//3rd party developer functions
function bt_get_shipping_tracking($order_id)
{
	if (empty($order_id)) {
		return null;
	}

	$response = array();
	$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($order_id);
	if (!empty($bt_shipment_tracking)) {
		$response['tracking_data'] = (array) $bt_shipment_tracking;
		$response['tracking_link'] = $bt_shipment_tracking->get_tracking_link();
	}
	return (array) $response;
}

function bt_force_sync_order_tracking($order_id)
{
	if (empty($order_id)) {
		return null;
	}

	$response = array();

	$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($order_id);
	$bt_shipping_provider = $bt_shipment_tracking->shipping_provider;
	if ($bt_shipping_provider == 'nimbuspost') {
		$obj = new Bt_Sync_Shipment_Tracking_Nimbuspost();
		$response = $obj->update_order_shipment_status($order_id);
	} else if ($bt_shipping_provider == 'shiprocket') {
		$obj = new Bt_Sync_Shipment_Tracking_Shiprocket();
		$response = $obj->update_order_shipment_status($order_id);
	} else if ($bt_shipping_provider == 'shyplite') {
		$obj = new Bt_Sync_Shipment_Tracking_Shyplite();
		$response = $obj->update_order_shipment_status($order_id);
	} else if ($bt_shipping_provider == 'xpressbees') {
		$response = array();
	} else if ($bt_shipping_provider == 'shipmozo') {
		$obj = new Bt_Sync_Shipment_Tracking_Shipmozo();
		$response = $obj->update_order_shipment_status($order_id);
	} else if ($bt_shipping_provider == 'nimbuspost_new') {
		$obj = new Bt_Sync_Shipment_Tracking_Nimbuspost_New();
		$response = $obj->update_order_shipment_status($order_id);
	} else if ($bt_shipping_provider == 'delhivery') {
		$obj = new Bt_Sync_Shipment_Tracking_Delhivery();
		$response = $obj->update_order_shipment_status($order_id);
	} else if ($bt_shipping_provider == "manual") {

		$shipping_mode_is_manual_or_ship24 = carbon_get_theme_option('bt_sst_enabled_custom_shipping_mode');
		// $shipping_mode_is_manual_or_ship24 = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($order_id, '_bt_sst_custom_shipping_mode', true);
		if ($shipping_mode_is_manual_or_ship24 == "ship24") {
			$obj = new Bt_Sync_Shipment_Tracking_ship24();
			$response = $obj->update_order_shipment_status($order_id);
		}
	}else if ($bt_shipping_provider == 'fship') {
		$obj = new Bt_Sync_Shipment_Tracking_Fship();
		$response = $obj->update_order_shipment_status($order_id);
	}

	return (array) $response;
}

function bt_update_shipment_tracking($order_id, $courier_name, $awb_number, $shipping_status, $edd, $tracking_link)
{

	if (empty($order_id)) {
		return null;
	}

	$response = array();

	//get existing tracking data for the order
	$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($order_id);
	$bt_shipping_provider = $bt_shipment_tracking->shipping_provider;
	if (empty($bt_shipping_provider)) {
		$bt_shipping_provider = "manual";
	}

	$shipment_obj = new Bt_Sync_Shipment_Tracking_Shipment_Model();
	$shipment_obj->shipping_provider = $bt_shipping_provider;
	$shipment_obj->order_id = $order_id;
	$shipment_obj->awb = sanitize_text_field($awb_number);
	$shipment_obj->courier_name = sanitize_text_field($courier_name);
	$shipment_obj->etd = sanitize_text_field($edd);
	$shipment_obj->scans = array();
	$shipment_obj->current_status = sanitize_text_field($shipping_status);
	$shipment_obj->tracking_url = sanitize_text_field($tracking_link);

	Bt_Sync_Shipment_Tracking_Shipment_Model::save_tracking($order_id, $shipment_obj);
	$response = $shipment_obj;

	return (array) $response;
}

function bt_format_shipment_status($shipment_status)
{
	$formatted_shipment_status = $shipment_status;
	$arr = apply_filters('bt_sst_shipping_statuses', BT_SHIPPING_STATUS);
	if (!empty($shipment_status) && isset($arr[$shipment_status])) {
		$formatted_shipment_status = $arr[$shipment_status];
	}
	if (!empty($formatted_shipment_status)) {
		$formatted_shipment_status = apply_filters('bt_format_shipment_status_string', ucwords(strtolower($formatted_shipment_status)), $shipment_status);

		return $formatted_shipment_status;
	} else {
		$formatted_shipment_status = apply_filters('bt_format_shipment_status_string', $shipment_status, $shipment_status);

		return $formatted_shipment_status;
	}

}


function bt_sst_get_city_state_by_pincode($pincode, $country)
{
	$data = [];

	if (empty($pincode) || empty($country)) {
		return $data;
	}

	if (strtoupper($country) === 'IN') {
		$obj = new Bt_Sync_Shipment_Tracking_Shiprocket();

		$locality_data = $obj->get_locality($pincode);
		if (is_array($locality_data) && isset($locality_data['state_code'])) {
			$locality_data['state'] = $locality_data['state_code'];
			$data = $locality_data;
		}
	}

	return $data;
}
