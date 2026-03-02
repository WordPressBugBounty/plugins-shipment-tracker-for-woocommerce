<?php
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;
use PhpUnitsOfMeasure\PhysicalQuantity\Length;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://amitmittal.tech
 * @since      1.0.0
 *
 * @package    Bt_Sync_Shipment_Tracking
 * @subpackage Bt_Sync_Shipment_Tracking/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bt_Sync_Shipment_Tracking
 * @subpackage Bt_Sync_Shipment_Tracking/public
 * @author     Amit Mittal <amitmittal@bitsstech.com>
 */
class Bt_Sync_Shipment_Tracking_Public
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
	private $nimbuspost_new;
	private $licenser;
	private $delhivery;
	private $fship;
	private $ekart;
	private $proship;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version, $shiprocket, $shipmozo, $nimbuspost_new, $licenser, $delhivery, $fship, $ekart, $proship, $ithink)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->shiprocket = $shiprocket;
		$this->shipmozo = $shipmozo;
		$this->nimbuspost_new = $nimbuspost_new;
		$this->licenser = $licenser;
		$this->delhivery = $delhivery;
		$this->fship = $fship;
		$this->ekart = $ekart;
		$this->proship = $proship;
		$this->ithink = $ithink;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		wp_register_style('bt-sync-shipment-tracking-customer-shortcode-css', plugin_dir_url(__FILE__) . 'css/bt-sync-shipment-tracking-customer-shortcode.css', array(), $this->version, 'all');
		wp_register_style('bt-sync-shipment-tracking-primery-template-form-2', plugin_dir_url(__FILE__) . 'css/bt-sync-shipment-tracking-primery-template-form-2.css', array(), $this->version, 'all');
		wp_register_style('bt-sync-shipment-tracking-public-css', plugin_dir_url(__FILE__) . 'css/bt-sync-shipment-tracking-public.css', array(), $this->version, 'all');
		wp_register_style('input_box_pincode_show_prime_x', plugin_dir_url(__FILE__) . 'css/bt_sst_input_box_pincode_show_prime_x.css', array(), $this->version, 'all');
		wp_register_style('bt-sync-shipment-tracking-leaflet-css', plugin_dir_url(__FILE__) . 'css/bt-sync-shipment-tracking-leaflet.css', array(), $this->version, 'all');
		wp_register_style('bt-sync-shipment-tracking-result-page-template-second-css', plugin_dir_url(__FILE__) . 'css/bt-sync-shipment-tracking-result-page-template-second.css', array(), $this->version, 'all');
		wp_register_style('bt-shipment-tracking-timing-css', plugin_dir_url(__FILE__) . 'timer/css/bt_sst_shipment_tracker_timer.css', array(), $this->version, 'all');

		if (is_checkout()) {
			wp_enqueue_style('bt-sync-shipment-tracking-public-css');
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_register_script('bt-sync-shipment-tracking-public', plugin_dir_url(__FILE__) . 'js/bt-sync-shipment-tracking-public.js', array('jquery'), $this->version, true);
		wp_register_script('bt_sst_input_box_pincode_show_prime_x', plugin_dir_url(__FILE__) . 'js/bt_sst_input_box_pincode_show_prime_x.js', array('jquery'), $this->version, true);
		wp_register_script('bt-shipment-tracking-timing-js', plugin_dir_url(__FILE__) . 'timer/js/bt_sst_shipment_tracker.js', array('jquery'), $this->version, true);
		wp_register_script(
			'bt-sync-shipment-tracking-leaflet',
			plugin_dir_url(__FILE__) . 'js/bt-sync-shipment-tracking-leaflet.js',
			array(),
			$this->version,
			true
		);
		wp_register_script(
			'bt-sync-shipment-tracking-mapRender',
			plugin_dir_url(__FILE__) . 'js/bt-sync-shipment-racking-mapRender.js',
			array(),
			$this->version,
			true
		);
		wp_register_script('bt-sync-shipment-tracking-public-checkout-blocks', plugin_dir_url(__FILE__) . 'js/bt-sync-shipment-tracking-public-checkout-block.js', array('wp-data', 'wc-blocks-checkout'), $this->version, true);
		$post_code_auto_fill = carbon_get_theme_option('bt_sst_enable_auto_postcode_fill');
		$is_premium = $this->licenser->should_activate_premium_features();
		$post_code_auto_fill = ($post_code_auto_fill && $is_premium) ? true : false;
		$script_data = array(
			"ajax_url" => admin_url('admin-ajax.php'),
			"bt_sst_autofill_post_code" => $post_code_auto_fill,
			"plugin_public_url" => plugin_dir_url(__FILE__),
			"pincode_checkout_page_nonce" => wp_create_nonce('get_data_by_pincode_checkout_page')
		);
		wp_localize_script('bt-sync-shipment-tracking-public', 'bt_sync_shipment_tracking_data', $script_data);



		if (is_checkout() && $is_premium) {
			$fill = carbon_get_theme_option(
				"bt_sst_auto_fill_city_state"
			);
			if ($fill != 1) {
				return;
			}
			wp_enqueue_script('bt-sync-shipment-tracking-public');

			if ($this->is_checkout_block()) {
				wp_enqueue_script('bt-sync-shipment-tracking-public-checkout-blocks');
			}
		}

	}
	function is_checkout_block()
	{
		return WC_Blocks_Utils::has_block_in_page(wc_get_page_id('checkout'), 'woocommerce/checkout');
	}
	/**
	 * Adds a new column to the "My Orders" table in the account.
	 *
	 * @param string[] $columns the columns in the orders table
	 * @return string[] updated columns
	 */
	public function wc_add_my_account_orders_column($columns)
	{
		$bt_sst_show_shipment_info_myaccount_orders = carbon_get_theme_option('bt_sst_show_shipment_info_myaccount_orders');
		if ($bt_sst_show_shipment_info_myaccount_orders == 1) {
			$new_columns = array();

			foreach ($columns as $key => $name) {

				$new_columns[$key] = $name;

				// add ship-to after order status column
				if ('order-status' === $key) {
					$new_columns['order-shipment'] = __('Shipment', 'textdomain');
				}
			}
			return $new_columns;
		}
		return $columns;

	}

	/**
	 * Adds data to the custom "ship to" column in "My Account > Orders".
	 *
	 * @param \WC_Order $order the order object for the row
	 */
	function wc_my_orders_shipment_column($order)
	{
		$bt_sst_show_shipment_info_myaccount_orders = carbon_get_theme_option('bt_sst_show_shipment_info_myaccount_orders');
		if ($bt_sst_show_shipment_info_myaccount_orders == 1) {
			$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($order->get_id());
			$bt_shipping_provider = $bt_shipment_tracking->shipping_provider;
			include plugin_dir_path(dirname(__FILE__)) . 'public/partials/order_shipment_details.php';
		}
	}

	function bt_shipping_tracking_form()
	{
		wp_enqueue_style('bt-sync-shipment-tracking-public-css');
		wp_enqueue_script('bt-sync-shipment-tracking-public');
		ob_start();
		include plugin_dir_path(dirname(__FILE__)) . 'public/partials/bt-sync-shipment-tracking-public-display.php';
		return ob_get_clean();

	}

	function init_hook_handler()
	{
		$location_hook = carbon_get_theme_option('bt_sst_pincode_checker_location');
		if ($location_hook && !empty($location_hook)) {
			add_action($location_hook, array($this, "show_pincode_input_box"));
		}

		$saved_data = get_option('bt_sst_timer_settings');
		if (is_array($saved_data) && isset($saved_data['bt_sst_timer_location'])) {
			if ($saved_data['bt_sst_timer_location'] && !empty($saved_data['bt_sst_timer_location'])) {
				add_action($saved_data['bt_sst_timer_location'], array($this, "show_timer_container"));
			}
		}

		$bt_sst_shiprocket_processing_days_location = carbon_get_theme_option('bt_sst_shiprocket_processing_days_location');
		if ($bt_sst_shiprocket_processing_days_location && $bt_sst_shiprocket_processing_days_location != "do_not_show") {
			add_action($bt_sst_shiprocket_processing_days_location, array($this, "bt_sst_shiprocket_processing_days_location"));
		}

		//add track order endpoint
		add_rewrite_endpoint('bt-track-order', EP_ROOT | EP_PAGES);
	}

	public function bt_sst_shiprocket_processing_days_location()
	{
		$is_premium = $this->licenser->should_activate_premium_features();
		if (!$is_premium)
			return;
		$pincode_checker = carbon_get_theme_option('bt_sst_shiprocket_pincode_checker');
		if ($pincode_checker != 1) {
			return;
		}
		$processing_days = 0;
		global $product;
		if (isset($product) && $product != null) {
			$product_id = $product->get_id();
			$processing_days = $this->bt_get_processing_days($product_id);//first try to get at product or category level.
			if (!$processing_days) {
				//if not found, get processing days set at global level.
				$processing_days = carbon_get_theme_option("bt_sst_shiprocket_processing_days");
			}
		}
		if ($processing_days > 0) {
			echo "<div class='bt_sst_processing_time'>Processing time: " . esc_html($processing_days) . " days</div>";
		}
	}


	/**Show Input box & Button(pincode checker) on product page */
	public function show_pincode_input_box()
	{
		$product_id = get_the_ID();
		$product = wc_get_product($product_id);
		if ($product && !$product->is_virtual()) {
			$is_premium = $this->licenser->should_activate_premium_features();

			$pincode_checker = carbon_get_theme_option('bt_sst_shiprocket_pincode_checker');
			if ($pincode_checker != 1) {
				return;
			}
			wp_enqueue_script('bt-sync-shipment-tracking-public');

			$check_templet = carbon_get_theme_option('bt_sst_pincode_box_template');
			if (!$is_premium)
				$check_templet = "classic"; //if not premium, then use classic template.
			// $check_templet = "prime_x";
			$post_code_auto_fill = carbon_get_theme_option('bt_sst_enable_auto_postcode_fill');
			wp_enqueue_style('bt-sync-shipment-tracking-public-css');
			$script_data = array(
				"ajax_url" => admin_url('admin-ajax.php'),
				"bt_sst_autofill_post_code" => $post_code_auto_fill,
				"plugin_public_url" => dirname(plugin_dir_url(__FILE__)),
				"pincode_checkout_page_nonce" => wp_create_nonce('get_data_by_pincode_checkout_page')
			);
			wp_localize_script('bt-sync-shipment-tracking-public', 'bt_sync_shipment_tracking_data', $script_data);
			if ($check_templet == "realistic") {

				include plugin_dir_path(dirname(__FILE__)) . 'public/partials/bt_sst_pincode_sow_realistic.php';
			} else if ($check_templet == "classic") {
				include plugin_dir_path(dirname(__FILE__)) . 'public/partials/input_box_pincode_show_data.php';
			} else if ($check_templet == "prime_x") {
				wp_enqueue_style('input_box_pincode_show_prime_x');
				wp_enqueue_script('bt_sst_input_box_pincode_show_prime_x');
				include plugin_dir_path(dirname(__FILE__)) . 'public/partials/input_box_pincode_show_prime_x.php';
			} else {
				include plugin_dir_path(dirname(__FILE__)) . 'public/partials/input_box_pincode_show_data.php';
			}

		}
	}

	public function show_timer_container()
	{
		$is_premium = $this->licenser->should_activate_premium_features();
		if ($is_premium) {
			echo do_shortcode('[bt_free_shipping_timer hours="1" minutes="30" seconds="0"]');
		}
	}

	//[bt_estimated_delivery_widget] shortcode handler
	function bt_estimated_delivery_widget()
	{


		$is_premium = $this->licenser->should_activate_premium_features();

		$pincode_checker = carbon_get_theme_option('bt_sst_shiprocket_pincode_checker');
		if ($pincode_checker != 1) {
			return "";
		}

		wp_enqueue_style('bt-sync-shipment-tracking-public-css');
		wp_enqueue_script('bt-sync-shipment-tracking-public');
		$check_templet = carbon_get_theme_option('bt_sst_pincode_box_template');
		if (!$is_premium)
			$check_templet = "classic"; //if not premium, then use classic template.

		ob_start();
		if ($check_templet == "realistic") {
			include plugin_dir_path(dirname(__FILE__)) . 'public/partials/bt_sst_pincode_sow_realistic.php';
		} else if ($check_templet == "classic") {
			include plugin_dir_path(dirname(__FILE__)) . 'public/partials/input_box_pincode_show_data.php';
		} else if ($check_templet == "prime_x") {
			wp_enqueue_style('input_box_pincode_show_prime_x');
			wp_enqueue_script('bt_sst_input_box_pincode_show_prime_x');
			include plugin_dir_path(dirname(__FILE__)) . 'public/partials/input_box_pincode_show_prime_x.php';
		} else {
			include plugin_dir_path(dirname(__FILE__)) . 'public/partials/input_box_pincode_show_data.php';
		}
		return ob_get_clean();


	}

	function getDaysDifferenceFromToday($date)
	{
		try {
			// Attempt to create DateTime object from the input
			$givenDate = new DateTime($date);
			$currentDate = new DateTime();

			$interval = $currentDate->diff($givenDate);
			return (int)$interval->format('%r%a'); // returns signed number of days
		} catch (Exception $e) {
			// Handle invalid date format
			return null; // or you could return a specific error message or code
		}
	}

	function addDayswithdate($date, $days)
	{
		$date = date('M d, Y', strtotime($date . ' ' . $days . ' days'));

		return $date;
	}
	/**To Show Delivery data on Product Page using Pincode */
	function get_pincode_data_product_page()
	{

		if (isset($_POST["value"]) && is_array($_POST["value"])) {
			$_POST["value"] = array_map('sanitize_text_field', $_POST["value"]);
		}

		$nonce = $_POST["value"]['n'];

		if (!wp_verify_nonce($nonce, 'get_pincode_data_product_page')) {
			exit; // Get out of here, the nonce is rotten!
		}
		$response = array(
			"status" => false,
			"data" => [],
			"message" => "Unable to fetch data of this pincode, please try another pincode.",
			"check_error" => false,

		);
		$is_premium = $this->licenser->should_activate_premium_features();
		$city = "";
		$min_hours = 100000;
		$min_days = 0;
		$max_days = 0;
		$min_days_charges = 0;
		$max_days_charges = 0;
		$min_date = '';
		$min_date_charges = -1;
		$min_courier_name = '';
		$max_hours = 0;
		$max_date = '';
		$max_date_charges = -1;
		$max_courier_name = '';
		$should_activate = true;// 
		if ($should_activate) {

			$pickup_data_provider = carbon_get_theme_option(
				"bt_sst_pincode_data_provider"
			);
			$check_error_for_hide_show_ponbox = array("check_error" => false);
			$variation_id = "";
			$delivery_pincode = trim($_POST['value']['p']);
			$delivery_country = trim($_POST['value']['c']);
			$product_id = trim($_POST['value']['product_id']);
			if (isset($_POST['value']['variation_id'])) {
				$variation_id = trim($_POST['value']['variation_id']);
			}

			$bt_sst_message_text_template = carbon_get_theme_option('bt_sst_message_text_template');
			$push_resp = [];
			if ($pickup_data_provider == 'generic') {

				$shop_country = WC()->countries->get_base_country();
				$is_domestic = $shop_country == $delivery_country;

				$generic_config = carbon_get_theme_option("bt_sst_pincode_estimate_generic_provider");


				if ($is_domestic) {
					$config_obj = null;
					foreach ($generic_config as $key => $c) {
						if ($c['_type'] == "domestic") {
							$config_obj = $c;
							break;
						}
					}
					if ($config_obj != null) {
						$min_days = $config_obj["bt_sst_product_page_generic_domestic_min_days"];
						$min_days_charges = $config_obj["bt_sst_product_page_generic_domestic_min_charges"];
						$max_days = $config_obj["bt_sst_product_page_generic_domestic_max_days"];
						$max_days_charges = $config_obj["bt_sst_product_page_generic_domestic_max_charges"];
					}

					// Get any existing copy of our transient data
					if (false === ($bt_sst_cached_pincodes = get_transient('bt_sst_cached_pincodes'))) {
						// It wasn't there, so regenerate the data and save the transient
						$bt_sst_cached_pincodes = array();
					}

					$cached_pincode_key = $delivery_country . "_" . $delivery_pincode;

					if (isset($bt_sst_cached_pincodes[$cached_pincode_key])) {
						$data = $bt_sst_cached_pincodes[$cached_pincode_key];
						$city = $data['city'];
					} else {
						$google_key = carbon_get_theme_option("bt_sst_generic_google_key");
						if (!empty($google_key)) {
							$data = $this->get_data_from_google_api($delivery_pincode, $delivery_country, $google_key);
							if ($data != null && !empty($data)) {
								$city = $data['city'];
								$bt_sst_cached_pincodes[$cached_pincode_key] = $data;
								set_transient('bt_sst_cached_pincodes', $bt_sst_cached_pincodes, 12 * HOUR_IN_SECONDS);
								$response["message"] = "Data fetched from provider.";
							}
						}

					}


				} else {
					//international order
					$city = $delivery_country;
					$config_obj = null;
					foreach ($generic_config as $key => $c) {
						if ($c['_type'] == "international") {
							$config_obj = $c;
							break;
						}
					}
					if ($config_obj != null) {
						$min_days = $config_obj["bt_sst_product_page_generic_intl_min_days"];
						$min_days_charges = $config_obj["bt_sst_product_page_generic_intl_min_charges"];
						$max_days = $config_obj["bt_sst_product_page_generic_intl_max_days"];
						$max_days_charges = $config_obj["bt_sst_product_page_generic_intl_max_charges"];
					}
				}


				if (!$min_days) {
					$min_days = 1;
				}

				if (!$max_days) {
					$max_days = 1;
				}

				if (!$min_days_charges) {
					$min_days_charges = 0;
				}

				if (!$max_days_charges) {
					$max_days_charges = 0;
				}

				$current_date = new DateTime();

				$min_date = $current_date->add(new DateInterval("P{$min_days}D"))->format("l, d M, Y");
				$min_date_charges = $min_days_charges;

				$max_date = $current_date->add(new DateInterval("P{$max_days}D"))->format("l, d M, Y");
				$max_date_charges = $max_days_charges;
				$response["message"] = "Data populated as per rules set.";

			} else if ($pickup_data_provider == 'shiprocket') {

				// Get any existing copy of our transient data
				if (false === ($bt_sst_cached_delivery_estimates = get_transient('bt_sst_cached_delivery_estimates'))) {
					// It wasn't there, so regenerate the data and save the transient
					$bt_sst_cached_delivery_estimates = array();
				}


				if ($delivery_country == "IN") {
					$pickup_pin = carbon_get_theme_option(
						"bt_sst_shiprocket_pickup_pincode"
					);
					if (!empty($pickup_pin)) {
						$cached_pincode_key = $pickup_pin . "_" . $delivery_pincode;

						if (isset($bt_sst_cached_delivery_estimates[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates[$cached_pincode_key];
							$response["message"] = "Data fetched from cache.";
						} else {
							$push_resp = $this->shiprocket->get_courier_serviceability($pickup_pin, $delivery_pincode, 0, "0.5");

							if ($push_resp != null && !empty($push_resp)) {

								$bt_sst_cached_delivery_estimates[$cached_pincode_key] = $push_resp;
								set_transient('bt_sst_cached_delivery_estimates', $bt_sst_cached_delivery_estimates, 1 * HOUR_IN_SECONDS);
								$response["message"] = "Data fetched from provider.";
							}
						}


					}

				} else {

					$bt_sst_product_page_enable_international_shiprocket = carbon_get_theme_option(
						"bt_sst_product_page_enable_international_shiprocket"
					);
					if ($bt_sst_product_page_enable_international_shiprocket) {
						$cached_pincode_key = $delivery_country;

						if (isset($bt_sst_cached_delivery_estimates[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates[$cached_pincode_key];
							$response["message"] = "Data fetched from cache.";
						} else {
							$push_resp = $this->shiprocket->get_courier_serviceability_international($delivery_country, 0.1);

							if ($push_resp != null && !empty($push_resp)) {

								$bt_sst_cached_delivery_estimates[$cached_pincode_key] = $push_resp;
								set_transient('bt_sst_cached_delivery_estimates', $bt_sst_cached_delivery_estimates, 12 * HOUR_IN_SECONDS);
								$response["message"] = "Data fetched from provider.";
							}
						}
					}


				}

				$admin_courier_arr = carbon_get_theme_option("bt_sst_courier_companies_product_page");

				$filtered_arr = [];

				if (sizeof($admin_courier_arr) > 0 && $delivery_country == "IN") {
					foreach ($push_resp as $value) {
						$id = $value['courier_company_id'];
						if (in_array($id, $admin_courier_arr)) {
							array_push($filtered_arr, $value);
						}
					}
				} else {
					$filtered_arr = $push_resp;
				}



				if (is_array($filtered_arr) && sizeof($filtered_arr) > 0) {


					foreach ($filtered_arr as $key => $value) {

						if ($max_hours < $value['etd_hours']) {
							$max_hours = $value['etd_hours'];
							$max_date = $value['etd'];
							$max_date_charges = $value['rate'];
							$max_courier_name = $value['courier_name'];
						}
						if ($min_hours > $value['etd_hours']) {
							$min_hours = $value['etd_hours'];
							$min_date = $value['etd'];
							$min_date_charges = $value['rate'];
							$min_courier_name = $value['courier_name'];
						}

					}

					if ($delivery_country == "IN") {
						$city = $filtered_arr[0]['city'];
					} else {
						$city = $delivery_country;
						$min_date = explode("-", $min_date)[0];
						$max_date = explode("-", $max_date)[1];

						$min_date_charges = $min_date_charges["rate"];
						$max_date_charges = $max_date_charges["rate"];
					}

					$processing_days = $this->bt_get_processing_days($product_id, $variation_id);//first try to get at product or category level.
					if (!$processing_days) {
						//if not found, get processing days set at global level.
						$processing_days = carbon_get_theme_option("bt_sst_shiprocket_processing_days");
					}
					if (!$is_premium) {
						$processing_days = 0; //if not premium, then no processing days.
					}


					if ($processing_days && $processing_days > 0) {
						$min_date = $this->addDayswithdate($min_date, $processing_days);
						$max_date = $this->addDayswithdate($max_date, $processing_days);
					}
					$check_error_for_hide_show_ponbox["data"] = true;
				} else {
					$bt_sst_message_text_template = "Delivery not available. Try a different pincode or contact support.";
				}
			} else if ($pickup_data_provider == 'shipmozo') {


				// Get any existing copy of our transient data
				if (false === ($bt_sst_cached_delivery_estimates_shipmozo = get_transient('bt_sst_cached_delivery_estimates_shipmozo'))) {
					// It wasn't there, so regenerate the data and save the transient
					$bt_sst_cached_delivery_estimates_shipmozo = array();
				}


				if ($delivery_country == "IN") {
					$pickup_pin = carbon_get_theme_option(
						"bt_sst_shipmozo_pickup_pincode"
					);
					if (!empty($pickup_pin)) {
						$cached_pincode_key = $pickup_pin . "_" . $delivery_pincode;

						if (isset($bt_sst_cached_delivery_estimates_shipmozo[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates_shipmozo[$cached_pincode_key];
							$response["message"] = "Data fetched from cache.";
						} else {
							$push_resp = $this->shipmozo->get_rate_calcultor(0, $pickup_pin, $delivery_pincode, 'PREPAID', 1, 0, '.5', '1', '10', '10', '10');

							if ($push_resp != null && !empty($push_resp) && $push_resp['result'] == "1" && isset($push_resp['data'])) {
								$push_resp = $push_resp['data'];
								$bt_sst_cached_delivery_estimates_shipmozo[$cached_pincode_key] = $push_resp;
								set_transient('bt_sst_cached_delivery_estimates_shipmozo', $bt_sst_cached_delivery_estimates_shipmozo, 1 * HOUR_IN_SECONDS);
								$response["message"] = "Data fetched from shipmozo.";
							} else {
								$push_resp = [];
							}
						}


					}

				}

				$admin_courier_arr = carbon_get_theme_option("bt_sst_courier_companies_product_page");

				$filtered_arr = $push_resp;



				if (is_array($filtered_arr) && sizeof($filtered_arr) > 0) {


					foreach ($filtered_arr as $key => $value) {
						if (is_array($value['estimated_delivery']) && !empty($value['estimated_delivery'])) {
							$daytohour = $value['estimated_delivery'];
							preg_match('/\d+/', $daytohour, $matches);
							$days = intval($matches[0]);
							$hour = $days * 24;

							$value['etd_hours'] = $hour;

							$input = $value['estimated_delivery'];
							$currentDate = new DateTime();
							preg_match('/\d+/', $input, $matches);
							$days = intval($matches[0]);
							$interval = new DateInterval("P{$days}D");
							$currentDate->add($interval);
							$expectedDate = $currentDate->format('c');

							$value['etd'] = $expectedDate;




							if ($max_hours < $value['etd_hours']) {
								$max_hours = $value['etd_hours'];
								$max_date = $value['etd'];
								$max_date_charges = $value['total_charges'];
								$max_courier_name = $value['name'];
							}
							if ($min_hours > $value['etd_hours']) {
								$min_hours = $value['etd_hours'];
								$min_date = $value['etd'];
								$min_date_charges = $value['total_charges'];
								$min_courier_name = $value['name'];
							}
						}

					}

					if ($delivery_country == "IN") {
						// $city = $filtered_arr[0]['city'];
						$city = $delivery_pincode;
						$google_key = carbon_get_theme_option("bt_sst_google_key_shipmozo");
						if (!empty($google_key)) {
							if (isset($bt_sst_cached_pincodes[$cached_pincode_key . 'google'])) {
								$data = $bt_sst_cached_pincodes[$cached_pincode_key . 'google'];
								$city = $data['city'];
							} else {
								$data = $this->get_data_from_google_api($delivery_pincode, $delivery_country, $google_key);
								$city = $data['city'];
								$bt_sst_cached_pincodes[$cached_pincode_key . 'google'] = $data;
								set_transient('bt_sst_cached_pincodes', $bt_sst_cached_pincodes, 12 * HOUR_IN_SECONDS);
								$response["message"] = "Data fetched from provider.";
							}
						} else {
							$city = $delivery_pincode;
						}
					} else {
						$city = $delivery_country;
						$min_date = explode("-", $min_date)[0];
						$max_date = explode("-", $max_date)[1];

						$min_date_charges = $min_date_charges["rate"];
						$max_date_charges = $max_date_charges["rate"];
					}

					$processing_days = $this->bt_get_processing_days($product_id, $variation_id);//first try to get at product or category level.
					if (!$processing_days) {
						//if not found, get processing days set at global level.
						$processing_days = carbon_get_theme_option("bt_sst_shiprocket_processing_days");
					}
					if (!$is_premium) {
						$processing_days = 0; //if not premium, then no processing days.
					}

					if ($processing_days && $processing_days > 0) {
						$min_date = $this->addDayswithdate($min_date, $processing_days);
						$max_date = $this->addDayswithdate($max_date, $processing_days);
					} else {
						$min_date = $this->addDayswithdate($min_date, 0);
						$max_date = $this->addDayswithdate($max_date, 0);
					}
					$check_error_for_hide_show_ponbox["data"] = true;
				} else {
					$bt_sst_message_text_template = "Delivery not available. Try a different pincode or contact support.";
				}
			} else if ($pickup_data_provider == 'nimbuspost_new') {


				// Get any existing copy of our transient data
				if (false === ($bt_sst_cached_delivery_estimates_nimbuspost = get_transient('bt_sst_cached_delivery_estimates_nimbuspost'))) {
					// It wasn't there, so regenerate the data and save the transient
					$bt_sst_cached_delivery_estimates_nimbuspost = array();
				}


				if ($delivery_country == "IN") {
					$pickup_pin = carbon_get_theme_option(
						"bt_sst_nimbuspost_pickup_pincode"
					);
					if (!empty($pickup_pin)) {
						$cached_pincode_key = $pickup_pin . "_" . $delivery_pincode;

						if (isset($bt_sst_cached_delivery_estimates_nimbuspost[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates_nimbuspost[$cached_pincode_key];
							$response["message"] = "Data fetched from cache.";
						} else {
							$push_resp = $this->nimbuspost_new->get_rate_calcultor($pickup_pin, $delivery_pincode, 'PREPAID', 1, 0.5, '10', '10', '10');

							if ($push_resp != null && !empty($push_resp) && $push_resp['status'] == "true" && isset($push_resp['data'])) {
								$push_resp = $push_resp['data'];
								$bt_sst_cached_delivery_estimates_nimbuspost[$cached_pincode_key] = $push_resp;
								set_transient('bt_sst_cached_delivery_estimates_nimbuspost', $bt_sst_cached_delivery_estimates_nimbuspost, 1 * HOUR_IN_SECONDS);
								$response["message"] = "Data fetched from Nimbuspost.";
							} else {
								$push_resp = [];
							}
						}


					}

				}

				$admin_courier_arr = carbon_get_theme_option("bt_sst_courier_companies_product_page");

				$filtered_arr = $push_resp;


				if (is_array($filtered_arr) && sizeof($filtered_arr) > 0) {

					foreach ($filtered_arr as $key => $value) {


						// Current date and time
						$currentDateTime = new DateTime();
						// Target date
						$targetDate = new DateTime($value['edd']);
						// Calculate the difference
						$timeDiff = $targetDate->diff($currentDateTime);
						// Convert difference to hours
						$hour = $timeDiff->days * 24 + $timeDiff->h;
						// Display the result



						$value['etd_hours'] = $hour;


						$value['etd'] = $value['edd'];




						if ($max_hours < $value['etd_hours']) {
							$max_hours = $value['etd_hours'];
							$max_date = $value['etd'];
							$max_date_charges = $value['total_charges'];
							$max_courier_name = $value['name'];
						}
						if ($min_hours > $value['etd_hours']) {
							$min_hours = $value['etd_hours'];
							$min_date = $value['etd'];
							$min_date_charges = $value['total_charges'];
							$min_courier_name = $value['name'];
						}

					}

					if ($delivery_country == "IN") {
						$city = $filtered_arr[0]['city'];
					} else {
						$city = $delivery_country;
						$min_date = explode("-", $min_date)[0];
						$max_date = explode("-", $max_date)[1];

						$min_date_charges = $min_date_charges["rate"];
						$max_date_charges = $max_date_charges["rate"];
					}

					$processing_days = $this->bt_get_processing_days($product_id, $variation_id);//first try to get at product or category level.
					if (!$processing_days) {
						//if not found, get processing days set at global level.
						$processing_days = carbon_get_theme_option("bt_sst_shiprocket_processing_days");
					}
					//$processing_days = 9;
					if (!$is_premium) {
						$processing_days = 0; //if not premium, then no processing days.
					}

					if ($processing_days && $processing_days > 0) {
						$min_date = $this->addDayswithdate($min_date, $processing_days);
						$max_date = $this->addDayswithdate($max_date, $processing_days);
					} else {
						$min_date = $this->addDayswithdate($min_date, 0);
						$max_date = $this->addDayswithdate($max_date, 0);
					}
					$check_error_for_hide_show_ponbox["data"] = true;

				} else {
					$bt_sst_message_text_template = "Delivery not available. Try a different pincode or contact support.";
				}
			} else if ($pickup_data_provider == 'delhivery') {
				$express_tat = null;
				if (false === ($bt_sst_cached_delivery_estimates_delhivery = get_transient('bt_sst_cached_delivery_estimates_delhivery'))) {
					$bt_sst_cached_delivery_estimates_delhivery = array();
				}
				if ($delivery_country == "IN") {
					$pickup_pin = carbon_get_theme_option(
						"bt_sst_delhivery_pincodepickup"
					);
					if (!empty($pickup_pin)) {
						$cached_pincode_key = $pickup_pin . "_" . $delivery_pincode . "_3";
						if (isset($bt_sst_cached_delivery_estimates_delhivery[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates_delhivery[$cached_pincode_key][0];
							$express_tat = $bt_sst_cached_delivery_estimates_delhivery[$cached_pincode_key][1];
							$response["message"] = "Data fetched from cache.";
						} else {
							$push_resp = $this->delhivery->get_rate_calcultor_and_date('E', 'Delivered', $delivery_pincode, $pickup_pin, '500', 'PREPAID');

							if (!isset($push_resp["error"]) && $push_resp != null && !empty($push_resp) && sizeof($push_resp) > 0) {

								$express_tat = $this->delhivery->get_tat("E", $delivery_pincode, $pickup_pin);
								if ($express_tat && isset($express_tat["data"]) && isset($express_tat["data"]["tat"])) {
									$express_tat = $express_tat["data"]["tat"];
								} else {
									$express_tat = null;
								}
								$bt_sst_cached_delivery_estimates_delhivery[$cached_pincode_key] = [$push_resp, $express_tat];
								set_transient('bt_sst_cached_delivery_estimates_delhivery', $bt_sst_cached_delivery_estimates_delhivery, 1 * HOUR_IN_SECONDS);
								$response["message"] = "Data fetched from Delhivery.";
							} else {
								$push_resp = [];
							}
						}
					}

				}

				$filtered_arr = $push_resp;
				// var_dump($filtered_arr['error']); die;

				if (is_array($filtered_arr) && sizeof($filtered_arr) > 0 && empty($filtered_arr['error'])) {
					$check_error_for_hide_show_ponbox["data"] = true;
					$max_date_charges = $filtered_arr[0]['total_amount'];
					$min_date_charges = $filtered_arr[0]['total_amount'];
					foreach ($filtered_arr as $key => $value) {
						if ($max_date_charges < $value['total_amount']) {
							$max_date_charges = $value['total_amount'];
						}
						if ($min_date_charges < $value['total_amount']) {
							$min_date_charges = $value['total_amount'];
						}
					}

					$max_courier_name = 'delhivery';
					$min_courier_name = 'delhivery';

					$min_days = 2;
					$max_days = 5;

					if ($express_tat != null) {
						$min_days = $express_tat;
						$max_days = $express_tat;
					}

					if (!$min_days) {
						$min_days = 1;
					}
					if (!$max_days) {
						$max_days = 1;
					}
					$current_date = new DateTime();
					$min_date = $current_date->add(new DateInterval("P{$min_days}D"))->format("l, d M, Y");
					$max_date = $current_date->add(new DateInterval("P{$max_days}D"))->format("l, d M, Y");

					$processing_days = $this->bt_get_processing_days($product_id, $variation_id);
					if (!$processing_days) {
						$processing_days = carbon_get_theme_option("bt_sst_shiprocket_processing_days");
					}

					if (!$is_premium) {
						$processing_days = 0; //if not premium, then no processing days.
					}
					if ($processing_days && $processing_days > 0) {
						$min_date = $this->addDayswithdate($min_date, $processing_days);
						$max_date = $this->addDayswithdate($max_date, $processing_days);
					} else {
						$min_date = $this->addDayswithdate($min_date, 0);
						$max_date = $this->addDayswithdate($max_date, 0);
					}
					$check_error_for_hide_show_ponbox["data"] = true;
				} else {
					$bt_sst_message_text_template = "Delivery not available. Try a different pincode or contact support.";
				}
				$city = $delivery_pincode;
				$city_data = $this->delhivery->get_locality($delivery_pincode);
				if (isset($city_data['city'])) {
					$city = $city_data['city'];
				}
			} else if ($pickup_data_provider == 'fship') {

				if (false === ($bt_sst_cached_delivery_estimates_fship = get_transient('bt_sst_cached_delivery_estimates_fship'))) {
					$bt_sst_cached_delivery_estimates_fship = array();
				}
				if ($delivery_country == "IN") {
					$pickup_pin = carbon_get_theme_option(
						"bt_sst_fship_pincodepickup"
					);
					if (!empty($pickup_pin)) {
						$cached_pincode_key = $pickup_pin . "_" . $delivery_pincode . "_1";
						if (isset($bt_sst_cached_delivery_estimates_fship[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates_fship[$cached_pincode_key];
							$response["message"] = "Data fetched from cache.";
						} else {
							$push_resp = $this->fship->get_rate_calculator('cod', $pickup_pin, $delivery_pincode, 100, '', "0.5", "10", "10", "10", "10");

							if ($push_resp != null && !empty($push_resp) && sizeof($push_resp) > 0) {
								$bt_sst_cached_delivery_estimates_fship[$cached_pincode_key] = $push_resp;
								set_transient('bt_sst_cached_delivery_estimates_fship', $bt_sst_cached_delivery_estimates_fship, 1 * HOUR_IN_SECONDS);
								$response["message"] = "Data fetched from FShip.";
							} else {
								$push_resp = [];
							}
						}
					}

				}

				$filtered_arr = $push_resp;
				// var_dump($filtered_arr['error']); die;

				if (is_array($filtered_arr) && sizeof($filtered_arr) > 0 && empty($filtered_arr['error'])) {
					$check_error_for_hide_show_ponbox["data"] = true;
					$max_courier_name = '';
					$min_courier_name = '';
					$max_date_charges = $filtered_arr[0]['shipping_charge'];
					$min_date_charges = $filtered_arr[0]['shipping_charge'];
					foreach ($filtered_arr as $key => $value) {
						if ($max_date_charges < $value['shipping_charge']) {
							$max_date_charges = $value['shipping_charge'];
							$max_courier_name = $value['courier_name'];
						}
						if ($min_date_charges > $value['shipping_charge']) {
							$min_date_charges = $value['shipping_charge'];
							$min_courier_name = $value['courier_name'];
						}
					}

					$min_days = 2;
					$max_days = 5;
					//api currently does not return days, so we are setting default values.

					if (!$min_days) {
						$min_days = 1;
					}
					if (!$max_days) {
						$max_days = 1;
					}
					$current_date = new DateTime();
					$min_date = $current_date->add(new DateInterval("P{$min_days}D"))->format("l, d M, Y");
					$max_date = $current_date->add(new DateInterval("P{$max_days}D"))->format("l, d M, Y");

					$processing_days = $this->bt_get_processing_days($product_id, $variation_id);
					if (!$processing_days) {
						$processing_days = carbon_get_theme_option("bt_sst_shiprocket_processing_days");
					}
					if ($processing_days && $processing_days > 0) {
						$min_date = $this->addDayswithdate($min_date, $processing_days);
						$max_date = $this->addDayswithdate($max_date, $processing_days);
					} else {
						$min_date = $this->addDayswithdate($min_date, 0);
						$max_date = $this->addDayswithdate($max_date, 0);
					}
					$check_error_for_hide_show_ponbox["data"] = true;
				} else {
					$bt_sst_message_text_template = "Delivery not available. Try a different pincode or contact support.";
				}
				$city = $delivery_pincode;
				$city_data = $this->fship->get_pincode_serviceability($pickup_pin, $delivery_pincode);
				if (isset($city_data['destination'])) {
					$city = $city_data['destination'];
				}
			}else if ($pickup_data_provider == 'ekart') {
				$express_tat = null;
				if (false === ($bt_sst_cached_ekart_estimates_delhivery = get_transient('bt_sst_cached_ekart_estimates_delhivery'))) {
					$bt_sst_cached_ekart_estimates_delhivery = array();
				}
				if ($delivery_country == "IN") {
					$pickup_pin = carbon_get_theme_option("bt_sst_ekart_pickup_pin");
					if (!empty($pickup_pin)) {
						$cached_pincode_key = $pickup_pin . "_" . $delivery_pincode . "_3";
						if (isset($bt_sst_cached_ekart_estimates_delhivery[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_ekart_estimates_delhivery[$cached_pincode_key][0];
							$express_tat = $bt_sst_cached_ekart_estimates_delhivery[$cached_pincode_key][1];
							$response["message"] = "Data fetched from cache.";
						} else {
								$cart_totals = $this->get_cart_weight_and_dimentions();
								$weight_in_kg = $cart_totals['total_weight_kg'];
								$length_in_cms = $cart_totals['total_length_cm'];
								$breadth_in_cms = $cart_totals['total_width_cm'];
								$height_in_cms = $cart_totals['total_height_cm'];
								$declared_value = $cart_totals['declared_value'];

								if ($weight_in_kg < 0.1) {
									$weight_in_kg = 0.1;
								}
								if ($length_in_cms < 1) {
									$length_in_cms = 10;
								}
								if ($breadth_in_cms < 1) {
									$breadth_in_cms = 10;
								}
								if ($height_in_cms < 1) {
									$height_in_cms = 10;
								}

							$total_cost = 0;
							foreach (WC()->cart->get_cart() as $cart_item) {
								$product  = $cart_item['data'];
								$quantity = $cart_item['quantity'];
								$price    = (float) $product->get_price();

								$total_cost += $price * $quantity;
							}
								$push_resp = $this->ekart->get_rate_calcultor_by_mode(
															$delivery_pincode, 
															$weight_in_kg, 
															$length_in_cms, 
															$height_in_cms, 
															$breadth_in_cms,
															$total_cost,
															"EXPRESS"
														);
							if (!isset($push_resp["error"]) && $push_resp != null && !empty($push_resp) && sizeof($push_resp) > 0) {

								if ($push_resp['tat'] && isset($push_resp['tat'])) {
									$express_tat = $push_resp['tat'];
								} else {
									$express_tat = null;
								}
								$bt_sst_cached_ekart_estimates_delhivery[$cached_pincode_key] = [$push_resp, $express_tat];
								set_transient('bt_sst_cached_ekart_estimates_delhivery', $bt_sst_cached_ekart_estimates_delhivery, 1 * HOUR_IN_SECONDS);
								$response["message"] = "Data fetched from Delhivery.";
							} else {
								$push_resp = [];
							}
						}
					}

				}

				$filtered_arr = $push_resp;
				// var_dump($filtered_arr['error']); die;

				if (is_array($filtered_arr) && sizeof($filtered_arr) > 0) {
					$check_error_for_hide_show_ponbox["data"] = true;
					$max_date_charges = $filtered_arr['total'];
					$min_date_charges = $filtered_arr['total'];
				

					$max_courier_name = 'ekart';
					$min_courier_name = 'ekart';

					$min_days = 2;
					$max_days = 5;

					if ($express_tat != null) {
						$min_days = $express_tat;
						$max_days = $express_tat;
					}

					if (!$min_days) {
						$min_days = 1;
					}
					if (!$max_days) {
						$max_days = 1;
					}
					$current_date = new DateTime();
					$min_date = $current_date->add(new DateInterval("P{$min_days}D"))->format("l, d M, Y");
					$max_date = $current_date->add(new DateInterval("P{$max_days}D"))->format("l, d M, Y");

					$processing_days = $this->bt_get_processing_days($product_id, $variation_id);
					if (!$processing_days) {
						$processing_days = carbon_get_theme_option("bt_sst_shiprocket_processing_days");
					}

					if (!$is_premium) {
						$processing_days = 0; //if not premium, then no processing days.
					}
					if ($processing_days && $processing_days > 0) {
						$min_date = $this->addDayswithdate($min_date, $processing_days);
						$max_date = $this->addDayswithdate($max_date, $processing_days);
					} else {
						$min_date = $this->addDayswithdate($min_date, 0);
						$max_date = $this->addDayswithdate($max_date, 0);
					}
					$check_error_for_hide_show_ponbox["data"] = true;
				} else {
					$bt_sst_message_text_template = "Delivery not available. Try a different pincode or contact support.";
				}
				$city = $delivery_pincode;
				$city_data = $this->ekart->get_locality($delivery_pincode);
				if (isset($city_data['city'])) {
					$city = $city_data['city'];
				}
			}else if ($pickup_data_provider == 'proship') {
				$express_tat = null;
				if (false === ($bt_sst_cached_delivery_estimates_proship = get_transient('bt_sst_cached_delivery_estimates_proship'))) {
					$bt_sst_cached_delivery_estimates_proship = array();
				}

				if ($delivery_country == "IN") {
					$pickup_pin = carbon_get_theme_option(
						"bt_sst_proship_from_pincode"
					);
					$default_mode = carbon_get_theme_option('bt_sst_proship_default_mode');
					$payment_type = 'PREPAID';
					$length = 10;
					$breadth = 10;
					$height = 10;
					$weight = '1';
					$dispatch_mode = "SURFACE";
					$cod_amount = 100;
					$order_type = 'FORWARD';
					if (!empty($pickup_pin)) {
						$cached_pincode_key = $pickup_pin . "_" . $delivery_pincode . "_3";
						if (isset($bt_sst_cached_delivery_estimates_proship[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates_proship[$cached_pincode_key][0];
							$express_tat = $bt_sst_cached_delivery_estimates_proship[$cached_pincode_key][1];
							$response["message"] = "Data fetched from cache.";
						} else {
							$push_resp = $this->proship->get_proship_pricing(
											$pickup_pin,
											$delivery_pincode,
											$payment_type,
											$length,
											$breadth,
											$height,
											$weight,
											"SURFACE",
											$cod_amount,
											$order_type
										);

							if (!isset($push_resp["error"]) && $push_resp != null && !empty($push_resp) && sizeof($push_resp) > 0) {
								$bt_sst_cached_delivery_estimates_proship[$cached_pincode_key] = [$push_resp, $express_tat];
								set_transient('bt_sst_cached_delivery_estimates_proship', $bt_sst_cached_delivery_estimates_proship, 1 * HOUR_IN_SECONDS);
								$response["message"] = "Data fetched from Delhivery.";
							} else {
								$push_resp = [];
							}
						}
					}

				}

				$filtered_arr = $push_resp;
				$minMinTat = min(array_column($filtered_arr, 'providerMinTatDays'));
				$fastest = array_filter($filtered_arr, function ($item) use ($minMinTat) {
					return $item['providerMinTatDays'] == $minMinTat;
				});
				$minMaxTat = min(array_column($fastest, 'providerMaxTatDays'));
				$fastest = array_filter($fastest, function ($item) use ($minMaxTat) {
					return $item['providerMaxTatDays'] == $minMaxTat;
				});

				usort($fastest, function ($a, $b) {
					return $a['priceAfterTax'] <=> $b['priceAfterTax'];
				});

				$filtered_arr = $fastest[0];

				if (is_array($filtered_arr) && sizeof($filtered_arr) > 0) {
					$check_error_for_hide_show_ponbox["data"] = true;
					$max_date_charges = $filtered_arr['priceAfterTax'];
					$min_date_charges = $filtered_arr['priceAfterTax'];

					$max_courier_name = 'proship';
					$min_courier_name = 'proship';

					$min_days = 2;
					$max_days = 5;
					$express_tat = $filtered_arr['providerMaxTatDays'];
					if ($express_tat != null) {
						$min_days = $express_tat;
						$max_days = $express_tat;
					}

					if (!$min_days) {
						$min_days = 1;
					}
					if (!$max_days) {
						$max_days = 1;
					}
					$current_date = new DateTime();
					$min_date = $current_date->add(new DateInterval("P{$min_days}D"))->format("l, d M, Y");
					$max_date = $current_date->add(new DateInterval("P{$max_days}D"))->format("l, d M, Y");

					$processing_days = $this->bt_get_processing_days($product_id, $variation_id);
					if (!$processing_days) {
						$processing_days = carbon_get_theme_option("bt_sst_shiprocket_processing_days");
					}

					if (!$is_premium) {
						$processing_days = 0; //if not premium, then no processing days.
					}
					if ($processing_days && $processing_days > 0) {
						$min_date = $this->addDayswithdate($min_date, $processing_days);
						$max_date = $this->addDayswithdate($max_date, $processing_days);
					} else {
						$min_date = $this->addDayswithdate($min_date, 0);
						$max_date = $this->addDayswithdate($max_date, 0);
					}
					$check_error_for_hide_show_ponbox["data"] = true;
				} else {
					$bt_sst_message_text_template = "Delivery not available. Try a different pincode or contact support.";
				}
				$city = $delivery_pincode;
				// $city_data = $this->delhivery->get_locality($delivery_pincode);
				// if (isset($city_data['city'])) {
				// 	$city = $city_data['city'];
				// }
			}else if ($pickup_data_provider == 'ithink') {
				$express_tat = null;
				if (false === ($bt_sst_cached_delivery_estimates_ithink = get_transient('bt_sst_cached_delivery_estimates_ithink'))) {
					$bt_sst_cached_delivery_estimates_ithink = array();
				}

				if ($delivery_country == "IN") {
					$settings = get_option('ithink_logistics_settings');
					$pickup_pincode = isset($settings['pickup_pincode']) ? $settings['pickup_pincode'] : '';

					$length = 10;
					$breadth = 10;
					$height = 10;
					$weight = '1';
					$cod_amount = 100;
					if ($pickup_pincode) {
						$cached_pincode_key = $pickup_pincode . "_" . $delivery_pincode . "_3";
						if (isset($bt_sst_cached_delivery_estimates_ithink[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates_ithink[$cached_pincode_key][0];
							$express_tat = $bt_sst_cached_delivery_estimates_ithink[$cached_pincode_key][1];
							$response["message"] = "Data fetched from cache.";
						} else {
							$push_resp = $this->ithink->check_shipping_rate(
															$pickup_pincode,
															$delivery_pincode,
															$length,
															$breadth,
															$height,
															$weight,
															$cod_amount,
															"forward",
															"COD"
															);
							if (!isset($push_resp["error"]) && $push_resp != null && !empty($push_resp) && sizeof($push_resp) > 0) {
								$bt_sst_cached_delivery_estimates_ithink[$cached_pincode_key] = [$push_resp, $express_tat];
								set_transient('bt_sst_cached_delivery_estimates_ithink', $bt_sst_cached_delivery_estimates_ithink, 1 * HOUR_IN_SECONDS);
								$response["message"] = "Data fetched from Delhivery.";
							} else {
								$push_resp = [];
							}
						}
					}

				}

				$filtered_arr = $push_resp['data'];
				$minMinTat = min(array_column($filtered_arr, 'delivery_tat'));
				$fastest = array_filter($filtered_arr, function ($item) use ($minMinTat) {
					return $item['delivery_tat'] == $minMinTat;
				});
				$minMaxTat = min(array_column($fastest, 'delivery_tat'));
				$fastest = array_filter($fastest, function ($item) use ($minMaxTat) {
					return $item['delivery_tat'] == $minMaxTat;
				});

				usort($fastest, function ($a, $b) {
					return $a['rate'] <=> $b['rate'];
				});

				$filtered_arr = $fastest[0];

				if (is_array($filtered_arr) && sizeof($filtered_arr) > 0) {
					$check_error_for_hide_show_ponbox["data"] = true;
					$max_date_charges = $filtered_arr['rate'];
					$min_date_charges = $filtered_arr['rate'];

					$max_courier_name = 'ithink';
					$min_courier_name = 'ithink';

					$min_days = 2;
					$max_days = 5;
					$express_tat = $filtered_arr['delivery_tat'];
					if ($express_tat != null) {
						$min_days = $express_tat;
						$max_days = $express_tat;
					}

					if (!$min_days) {
						$min_days = 1;
					}
					if (!$max_days) {
						$max_days = 1;
					}
					$current_date = new DateTime();
					$min_date = $current_date->add(new DateInterval("P{$min_days}D"))->format("l, d M, Y");
					$max_date = $current_date->add(new DateInterval("P{$max_days}D"))->format("l, d M, Y");

					$processing_days = $this->bt_get_processing_days($product_id, $variation_id);
					if (!$processing_days) {
						$processing_days = carbon_get_theme_option("bt_sst_shiprocket_processing_days");
					}

					if (!$is_premium) {
						$processing_days = 0; //if not premium, then no processing days.
					}
					if ($processing_days && $processing_days > 0) {
						$min_date = $this->addDayswithdate($min_date, $processing_days);
						$max_date = $this->addDayswithdate($max_date, $processing_days);
					} else {
						$min_date = $this->addDayswithdate($min_date, 0);
						$max_date = $this->addDayswithdate($max_date, 0);
					}
					$check_error_for_hide_show_ponbox["data"] = true;
				} else {
					$bt_sst_message_text_template = "Delivery not available. Try a different pincode or contact support.";
				}
				$city = $delivery_pincode;
				// $city_data = $this->delhivery->get_locality($delivery_pincode);
				// if (isset($city_data['city'])) {
				// 	$city = $city_data['city'];
				// }
			}


			if (empty($bt_sst_message_text_template)) {
				$bt_sst_message_text_template = 'Estimated delivery by <b>#min_date#</b> <br> Delivering to #city#';
			}

			$message = "";
			//cut off time processing for premium users
			if ($is_premium) {
				$cutoff_time_str = carbon_get_theme_option('bt_sst_cutoff_time');


				if (!$cutoff_time_str) {
					$cutoff_time_str = '18:00:00';
				}
				$timezone_string = get_option('timezone_string');
				if (!$timezone_string) {
					$timezone_string = 'Asia/Kolkata';
				}

				try {
					$wp_timezone = new DateTimeZone($timezone_string);
				} catch (Exception $e) {
					// Handle the exception if the timezone is still invalid
					$wp_timezone = new DateTimeZone('UTC'); // Fallback to UTC
				}

				// Get the current time in the WordPress timezone
				$current_time = new DateTime('now', $wp_timezone);

				// Create the cutoff time in the WordPress timezone
				$cutoff_time = new DateTime($cutoff_time_str, $wp_timezone);

				// Calculate the time difference
				$time_diff = $cutoff_time->getTimestamp() - $current_time->getTimestamp();

				if ($time_diff > 0) {
					$hours = floor($time_diff / 3600);
					$minutes = floor(($time_diff % 3600) / 60);
					$message = "If ordered within <strong>" . $hours . " hrs " . $minutes . " mins</strong>";
				} else {
					// If the current time is past the cutoff, move the cutoff time to the next day
					$cutoff_time->modify('+1 day');
					$time_diff = $cutoff_time->getTimestamp() - $current_time->getTimestamp();
					$hours = floor($time_diff / 3600);
					$minutes = floor(($time_diff % 3600) / 60);
					$message = "If ordered within <strong>" . $hours . " hrs " . $minutes . " mins</strong>";
				}
			}
			$cut_of_time = $message;
			$edit_postcode = '<div id="bt_sst_pincode_box_change_button" style=""><a href="#">Change</a></div>';

			//processing time calculation for premium users
			$processing_time = "";
			$ordered_date = new DateTime(); // create DateTime object
			// Clone the DateTime object to calculate the shipped date
			$shipped_date = clone $ordered_date;
			if ($is_premium) {
				$processing_time = carbon_get_theme_option('bt_sst_shiprocket_processing_days');
				if ($processing_time) {
					$processing_time = "<div class='bt_sst_processing_time'>Processing time: " . esc_html($processing_time) . " days</div>";
				}
				$processing_time_min = carbon_get_theme_option('bt_sst_shiprocket_processing_days', 0);
				if (!empty($processing_time_min) && $processing_time_min > 0) {
					$shipped_date->modify("+{$processing_time_min} days");
				}
			}

			// Format both dates
			$ordered_date_formatted = $ordered_date->format('M d');
			$shipped_date_formatted = $shipped_date->format('M d');

			$date = new DateTime($min_date);
			$delivery_date = $date->format('M d');
			ob_start();
			include plugin_dir_path(dirname(__FILE__)) . 'public/partials/bt_sst_shipping_tracking_timeline.php';
			$shippingTimeline = ob_get_clean();

			$edd_variables = array(
				'bt_sst_message_text_template' => $bt_sst_message_text_template,
				'min_date' => $min_date,
				'max_date' => $max_date,
				'delivery_pincode' => $delivery_pincode,
				'city' => $city,
				'min_date_charges' => $min_date_charges,
				'max_date_charges' => $max_date_charges,
				'cut_off_time' => $cut_of_time,
				'processing_time' => $processing_time,
				'shipping_timeline_html' => $shippingTimeline
			);

			$edd_variables = apply_filters('bt_edd_variables', $edd_variables, $product_id);

			$bt_sst_message_text_template = $edd_variables['bt_sst_message_text_template'];
			$min_date = $edd_variables['min_date'];
			$max_date = $edd_variables['max_date'];
			$delivery_pincode = $edd_variables['delivery_pincode'];
			$city = $edd_variables['city'];
			$min_date_charges = $edd_variables['min_date_charges'];
			$max_date_charges = $edd_variables['max_date_charges'];
			$cut_of_time = $edd_variables['cut_off_time'];
			$processing_time = $edd_variables['processing_time'];
			$shippingTimeline = $edd_variables['shipping_timeline_html'];



			$message_text_template = $this->update_message_text_template($bt_sst_message_text_template, $min_date, $max_date, $delivery_pincode, $city, $min_date_charges, $max_date_charges, $cut_of_time, $edit_postcode, $processing_time, $shippingTimeline);

			$message_text_template = apply_filters('bt_edd_message_text', $message_text_template, $edd_variables, $product_id);

			$response = array(
				"status" => true,
				"data" => $message_text_template,
				"message" => $response["message"],
				"check_error" => $check_error_for_hide_show_ponbox,
			);
		}

		wp_send_json($response);
		die();
	}

	function get_processing_days_for_product($product_id)
	{
		$processing_days = 0;
		$product_processing_days = Bt_Sync_Shipment_Tracking::bt_sst_get_product_meta($product_id, '_bt_sst_product_processing_days_field', true);

		if ($product_processing_days) {
			$processing_days = $product_processing_days;
		} else {
			$product = wc_get_product($product_id);

			if ($product->is_type('variation')) {
				$product_id = $product->get_parent_id();
			}
			$product_categories = wp_get_post_terms($product_id, 'product_cat');
			foreach ($product_categories as $category) {
				$category_processing_days = get_term_meta($category->term_id, '_bt_sst_product_category_processing_days_field', true);
				if ($category_processing_days) {
					$processing_days = max($processing_days, $category_processing_days);
				}
			}
		}
		return $processing_days;

	}

	// function bt_get_processing_days($product_id = false, $variation_id = false)
	// {
	// 	// This function will populate the days it will take to process an order.
	// 	// Processing days for a particular product is stored in '_bt_sst_product_processing_days_field' meta data.
	// 	// At product category level, it is stored in '_bt_sst_product_category_processing_days_field' meta data of product category.
	// 	// Processing days is equal to the '_bt_sst_product_processing_days_field' value at product level whicle at cart level, processing days is equal to the max value of '_bt_sst_product_processing_days_field' meta value across all products in the cart.
	// 	//1. check if current page is cart page or product page.
	// 	//2. if product page, get value of '_bt_sst_product_processing_days_field' meta data and return as 
	// 	//3. if cart page, loop through all products and 

	// 	global $product, $woocommerce;
	// 	$processing_days = 0;
	// 	// Check for product page or cart page
	// 	if ($variation_id) {
	// 		$processing_days = $this->get_processing_days_for_product($variation_id);

	// 	}
	// 	if ($product_id && $processing_days == 0) {
	// 		$processing_days = $this->get_processing_days_for_product($product_id);
	// 	}
	// 	if (is_cart() || is_checkout()) {
	// 		foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
	// 			$product = $cart_item['data'];
	// 			$product_id = $product->get_id();
	// 			$product_processing_days = $this->get_processing_days_for_product($product_id);
	// 			$processing_days = max($processing_days, $product_processing_days);
	// 		}
	// 	}

	// 	return $processing_days;
	// }


	public function bt_get_processing_days($product_id = false, $variation_id = false)
	{
		global $woocommerce;
		$processing_days = 0;
		if ($variation_id) {
			$raw_value = $this->get_processing_days_for_product($variation_id);
			$processing_days = $this->normalize_processing_days($raw_value);
		}

		if ($product_id && $processing_days == 0) {
			$raw_value = $this->get_processing_days_for_product($product_id);
			$processing_days = $this->normalize_processing_days($raw_value);
		}

		if (is_cart() || is_checkout()) {
			foreach ($woocommerce->cart->get_cart() as $cart_item) {
				$product = $cart_item['data'];
				$cart_product_id = $product->get_id();
				$raw_value = $this->get_processing_days_for_product($cart_product_id);
				$product_processing_days = $this->normalize_processing_days($raw_value);

				$processing_days = max($processing_days, $product_processing_days);
			}
		}
		return (int) $processing_days;
	}

	private function normalize_processing_days($value)
	{
		if (empty($value)) {
			return 0;
		}
		$value = strtolower(trim($value));
		preg_match('/\d+/', $value, $matches);
		$number = isset($matches[0]) ? (int) $matches[0] : 0;

		if ($number <= 0) {
			return 0;
		}
		if (strpos($value, 'hour') !== false || strpos($value, 'hr') !== false) {
			return (int) ceil($number / 24);
		}
		return (int) $number;
	}


	function update_message_text_template($bt_sst_message_text_template, $min_date, $max_date, $delivery_pincode, $city, $min_date_charges, $max_date_charges, $cut_of_time, $edit_postcode, $processing_time, $shippingTimeline)
	{
		$mess_text = str_ireplace("#min_date#", $min_date, $bt_sst_message_text_template);
		$mess_text = str_ireplace("#max_date#", $max_date, $mess_text);
		$mess_text = str_ireplace("#pincode#", $delivery_pincode, $mess_text);
		$mess_text = str_ireplace("#city#", $city, $mess_text);
		$mess_text = str_ireplace("#min_date_charges#", $min_date_charges, $mess_text);
		$mess_text = str_ireplace("#max_date_charges#", $max_date_charges, $mess_text);
		$mess_text = str_ireplace("#cutoff_time#", $cut_of_time, $mess_text);
		if (strpos($mess_text, '#edit#') === false) {
			$mess_text .= ' #edit#';
		}
		$mess_text = str_ireplace("#edit#", $edit_postcode, $mess_text);
		$mess_text = str_ireplace("#processing_time#", $processing_time, $mess_text);
		$mess_text = str_ireplace("#shipping_timeline#", $shippingTimeline, $mess_text);


		return $mess_text;
	}


	/**To Auto fill City & State fields using Pincode on Checkout page */
	function get_pincode_data_checkout_page()
	{
		$response = array(
			"status" => false,
			"data" => null,
			"message" => "Data not found!.."
		);

		$is_premium = $this->licenser->should_activate_premium_features();
		if ($is_premium) {

			$nonce = $_POST["value"]['n'];
			if (!wp_verify_nonce($nonce, 'get_data_by_pincode_checkout_page')) {
				exit; // Get out of here, the nonce is rotten!
			}
			$auto_fill = carbon_get_theme_option("bt_sst_auto_fill_city_state");
			if ($auto_fill == 1) {

				$pincode = $_POST["value"]['p'];
				$country = $_POST["value"]['c'];

				// Get any existing copy of our transient data
				if (false === ($bt_sst_cached_pincodes = get_transient('bt_sst_cached_pincodes'))) {
					// It wasn't there, so regenerate the data and save the transient
					$bt_sst_cached_pincodes = array();
				}

				$cached_pincode_key = $country . "_" . $pincode;

				if (isset($bt_sst_cached_pincodes[$cached_pincode_key])) {
					$received_data = $bt_sst_cached_pincodes[$cached_pincode_key];
					$response["message"] = "Data fetched from cache.";
				} else {
					$received_data = $this->get_data_by_pincode_checkout_page($pincode, $country);
					if ($received_data != null && !empty($received_data)) {

						$bt_sst_cached_pincodes[$cached_pincode_key] = $received_data;
						set_transient('bt_sst_cached_pincodes', $bt_sst_cached_pincodes, 12 * HOUR_IN_SECONDS);
						$response["message"] = "Data fetched from provider.";
					}
				}

				if ($received_data == null || empty($received_data)) {
					$response = array(
						"status" => false,
						"data" => $received_data,
						"message" => "Not able to fetch data of this Pincode!"
					);

					$response['message'] = "Not able to fetch data of this Pincode!";
				} else {

					$response["status"] = true;
					$response["data"] = $received_data;

				}
			}

		}

		wp_send_json($response);
		die();
	}
	function get_data_by_pincode_checkout_page($pincode, $country)
	{

		$data = [];

		$option = carbon_get_theme_option("bt_sst_data_provider");

		if ($option == 'google') {
			$google_key = carbon_get_theme_option("bt_sst_google_key");
			if (!empty($google_key)) {
				$data = $this->get_data_from_google_api($pincode, $country, $google_key);
			}
		}
		/**get data form shiprocket api */ else if ($option == 'shiprocket') {
			if ($country == "IN") {
				$data = $this->shiprocket->get_locality($pincode);
				$data['state'] = $data['state_code'];
			} else {
				//to do for international shipments
			}

		} else if ($option == 'delhivery') {
			if ($country == "IN") {
				$data = $this->delhivery->get_locality($pincode);
				if (!isset($data)) {
					return null;
				}
				$data['state'] = $data['state_code'];
			} else {

			}

		}


		return $data;
	}
	function get_data_from_google_api($pincode, $country, $google_key)
	{
		$is_premium = $this->licenser->should_activate_premium_features();
		if (!$is_premium)
			return null;
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?components=postal_code:' . $pincode . '|country:' . $country . '&key=' . $google_key;
		$response = wp_remote_get($url);

		if (is_array($response) && !is_wp_error($response)) {
			//$headers = $response['headers']; // array of http header lines
			$body = $response['body']; // use the content
			$json = json_decode($body, true);
			$city = "";
			$state = "";
			$data_arr = [];
			if ($json["status"] == "OK") {
				$result = $json["results"][0]['address_components'];

				foreach ($result as $key => $value) {
					$type = $value['types'];

					if (in_array("locality", $type)) {
						$city = $value["long_name"];
					}
					if (in_array("administrative_area_level_1", $type)) {
						$state = $value["short_name"];
					}
				}

				$data_arr = ["city" => $city, "state" => $state];
			}
		}
		return $data_arr;
	}


	function findNearestWeights($filtered_arr, $weight_in_grams)
	{
		if ($filtered_arr == null || !is_array($filtered_arr)) {
			return $filtered_arr;
		}
		foreach ($filtered_arr as $r => $rb) {
			if (preg_match('/([0-9.]+)\s*KG/i', $rb['minimum_chargeable_weight'], $matches)) {
				$kg = floatval($matches[1]);
				$minimum_chargeable_weight_in_grm = $kg * 1000;
				$filtered_arr[$r]['minimum_chargeable_weight_in_grams'] = $minimum_chargeable_weight_in_grm;
			}
		}

		usort($filtered_arr, function ($a, $b) use ($weight_in_grams) {
			return abs($a['minimum_chargeable_weight_in_grams'] - $weight_in_grams) <=> abs($b['minimum_chargeable_weight_in_grams'] - $weight_in_grams);
		});

		$closest_five = array_slice($filtered_arr, 0, 5);
		return $closest_five;
	}

	function update_woocommerce_package_rates($rates, $package)
	{
		$total_cost = 0;
		foreach ($package['contents'] as $item_id => $values) {
        	$product = $values['data'];
			$quantity = $values['quantity'];
			$price = $product->get_price();

			$total_cost += $price * $quantity;
		}

		$push_resp = [];
		if (!is_cart() && !is_checkout()) {
			//	return $rates;
		}

		$is_premium = $this->licenser->should_activate_premium_features();
		if (!$is_premium) {
			return $rates;
		}
		$allow_to_choose = carbon_get_theme_option(
			"bt_sst_select_courier_company"
		);
		if ($allow_to_choose == 0) {
			// exit;
			return $rates;
		}
		if ($package == null || !isset($package['destination'])) {
			return $rates;
		}
		$delivery_pincode = $package['destination']['postcode'];
		if( empty($delivery_pincode)) {
			return $rates;
		}
		$delivery_country = $package['destination']['country'];
		$bt_sst_courier_rate_provider = carbon_get_theme_option("bt_sst_courier_rate_provider");

		if ($delivery_country != "IN") {
			$bt_sst_enable_international_shiprocket = carbon_get_theme_option(
				"bt_sst_enable_international_shiprocket"
			);
			//return rates from woocommerce if international is disabled for shiprocket.
			if ($bt_sst_courier_rate_provider == "shiprocket" && !$bt_sst_enable_international_shiprocket) {
				return $rates;
			}

		}

		$cod = 0;
		if (isset($_POST['payment_method'])) {

			$payment_method = sanitize_text_field($_POST['payment_method']);

			if ($payment_method == 'cod') {
				$cod = 1;
			}
		}
		$filtered_arr = [];

		if ($bt_sst_courier_rate_provider == 'generic') {
			$bt_sst_shipping_methods = carbon_get_theme_option("bt_sst_add_shipping_methods");
			$shop_country = WC()->countries->get_base_country();

			$id = '';
			$lable = '';
			$delivery_date = '';
			$method_id = '';
			$cost = '';
			$taxes = '';

			if (sizeof($bt_sst_shipping_methods) > 0) {
				//honor free shipping coupon, if any.
				$free_shipping_rates = [];
				foreach ($rates as $key => $r) {
					if (strpos($key, 'free_shipping') !== false) {
						$free_shipping_rates[$key] = $r;
					}
				}
				$rates = $free_shipping_rates;
			}

			foreach ($bt_sst_shipping_methods as $element) {
				$id = $element['bt_sst_shipping_method'];
				$bt_sst_rate_type = $element['bt_sst_rate_type'];
				$bt_sst_courier_type = $element['bt_sst_courier_type'];
				$is_domestic = $shop_country == $delivery_country;
				if ($is_domestic && $bt_sst_courier_type != "domestic") {
					continue;
				}
				if (!$is_domestic && $bt_sst_courier_type != "international") {
					continue;
				}
				$lable = $id;
				$method_id = $id;


				if ($cod) {
					$cost = $element['bt_sst_cod_rate'];
				} else {
					$cost = $element['bt_sst_prepaid_rate'];
				}
				if ($bt_sst_rate_type == "rate_per_500gm") {
					$cart_totals = $this->get_cart_weight_and_dimentions();
					$weight_in_kg = $cart_totals['total_weight_kg'];
					$cost = $cost * ceil($weight_in_kg / 0.5);
				}
				$courier_name = $lable;

				$WC_Shipping_Rate = new WC_Shipping_Rate();

				$WC_Shipping_Rate->set_id($id);
				$WC_Shipping_Rate->set_label($lable);
				$WC_Shipping_Rate->set_method_id($method_id);
				$WC_Shipping_Rate->set_cost($cost);
				$WC_Shipping_Rate->set_instance_id($id);
				// $WC_Shipping_Rate->set_taxes($taxes);
				//$WC_Shipping_Rate->add_meta_data('bt_sst_courier_company_id', '');
				$WC_Shipping_Rate->add_meta_data('bt_sst_courier_company_name', $courier_name);
				$WC_Shipping_Rate->add_meta_data('bt_sst_shipment_provider', 'generic');
				$bt_sst_shipping_duration_days = 1;
				$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
				$rates[$id] = $WC_Shipping_Rate;
			}

		} else if ($bt_sst_courier_rate_provider == 'shiprocket') {


			$cart_totals = $this->get_cart_weight_and_dimentions();

			$weight_in_kg = $cart_totals['total_weight_kg'];
			$length_in_cms = $cart_totals['total_length_cm'];
			$breadth_in_cms = $cart_totals['total_width_cm'];
			$height_in_cms = $cart_totals['total_height_cm'];
			$declared_value = $cart_totals['declared_value'];

			// Get any existing copy of our transient data
			if (false === ($bt_sst_cached_delivery_estimates = get_transient('bt_sst_cached_delivery_estimates'))) {
				// It wasn't there, so regenerate the data and save the transient
				$bt_sst_cached_delivery_estimates = array();
			}

			if ($delivery_country == "IN") {
				$pickup_pincode = carbon_get_theme_option("bt_sst_shiprocket_pickup_pincode");
				if (!empty($pickup_pincode)) {
					$pm = $cod == 1 ? "COD" : "PREPAID";
					$cached_pincode_key = $pickup_pincode . "_" . $delivery_pincode . "_" . $weight_in_kg . '_' . $pm;

					if (isset($bt_sst_cached_delivery_estimates[$cached_pincode_key])) {
						$push_resp = $bt_sst_cached_delivery_estimates[$cached_pincode_key];
					} else {
						$push_resp = $this->shiprocket->get_courier_serviceability($pickup_pincode, $delivery_pincode, $cod, $weight_in_kg, $length_in_cms, $breadth_in_cms, $height_in_cms, $declared_value);
						if ($push_resp != null && !empty($push_resp)) {
							$bt_sst_cached_delivery_estimates[$cached_pincode_key] = $push_resp;
							set_transient('bt_sst_cached_delivery_estimates', $bt_sst_cached_delivery_estimates, 1 * HOUR_IN_SECONDS);
						}
					}


				}

			} else {
				//international

				$pickup_pincode = carbon_get_theme_option("bt_sst_shiprocket_pickup_pincode");

				$cached_pincode_key = $delivery_country . '_11' . $weight_in_kg . '_' . $pickup_pincode;

				if (isset($bt_sst_cached_delivery_estimates[$cached_pincode_key])) {
					$push_resp = $bt_sst_cached_delivery_estimates[$cached_pincode_key];
				} else {
					$push_resp = $this->shiprocket->get_courier_serviceability_international($delivery_country, $weight_in_kg, $pickup_pincode);

					if ($push_resp != null && !empty($push_resp)) {
						$bt_sst_cached_delivery_estimates[$cached_pincode_key] = $push_resp;
						set_transient('bt_sst_cached_delivery_estimates', $bt_sst_cached_delivery_estimates, 1 * HOUR_IN_SECONDS);
					}
				}





			}
			$filtered_arr = [];
			$admin_courier_arr = carbon_get_theme_option("bt_sst_courier_companies");

			if (sizeof($admin_courier_arr) > 0 && $delivery_country == "IN") {
				foreach ($push_resp as $value) {
					$id = $value['courier_company_id'];
					if (in_array($id, $admin_courier_arr)) {
						array_push($filtered_arr, $value);
					}
				}
			} else {
				$filtered_arr = $push_resp;
			}
			//if (sizeof($filtered_arr) > 0) {
			//honor free shipping coupon, if any.
			$free_shipping_rates = [];
			foreach ($rates as $key => $r) {
				if (strpos($key, 'free_shipping') !== false) {
					$free_shipping_rates[$key] = $r;
				}
			}
			$rates = $free_shipping_rates;
			//}


			if (empty($filtered_arr) || sizeof($filtered_arr) == 0) {

				if ($delivery_country == "IN") {
					$cost = carbon_get_theme_option("bt_sst_shiprocket_fall_back_rate");
					if ($cost && $cost > 0) {
						$WC_Shipping_Rate = new WC_Shipping_Rate();
						$id = 'flat_rate:shiprocket:fallback';
						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label('Flat rate');
						$WC_Shipping_Rate->set_method_id($id);
						if ($weight_in_kg > 0.5) {
							$t_weight = $weight_in_kg / 0.5;
							$t_weight = ceil($t_weight);
							$cost = round($t_weight * $cost);
						}
						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$bt_sst_shipping_duration_days = 4; // set default of 4 days
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
						
						// $WC_Shipping_Rate->set_taxes($texes);

						$rates[$id] = $WC_Shipping_Rate;
					}
				} else {
					//to handle international fallback rate
				}
			} else {
				if ($delivery_country == "IN") {
					usort($filtered_arr, function ($a, $b) {
						return ($a['cod_charges'] + $a['freight_charge']) - ($b['cod_charges'] + $b['freight_charge']);
					});
					foreach ($filtered_arr as $r => $rb) {

						$id = 'flat_rate:shiprocket:' . $rb['courier_company_id'];
						$lable = $rb['courier_name'];
						$method_id = 'flat_rate';
						$markup_cost = carbon_get_theme_option(
							"bt_sst_markup_charges"
						);
						if (!$markup_cost) {
							$markup_cost = 0;
						}
						if (($rb['cod_charges'] + $rb['freight_charge'] + $markup_cost) < 0) {
							//if adding markup results in negative overall cost, set markup to zero
							$markup_cost = 0;
						}
						$cost = round($rb['cod_charges'] + $rb['freight_charge'] + $markup_cost, 2);
						if ($declared_value > 2500 && isset($rb['coverage_charges'])) {
							$bt_sst_show_secure_shipment_rates = carbon_get_theme_option(
								"bt_sst_show_secure_shipment_rates"
							);
							if ($bt_sst_show_secure_shipment_rates == 1) {
								$cost = $cost + $rb['coverage_charges'];
							}
						}
						$texes = [];
						$delivery_date = '';
						$d ="";
						$show_delivery_date = carbon_get_theme_option("bt_sst_show_delivery_date");
						$bt_sst_shipping_duration_days = null;
						if ($show_delivery_date == 1) {
							$processing_days = $this->bt_get_processing_days();//first try to get at product or category level.
							if (!$processing_days) {
								//if not found, get processing days set at global level.
								$processing_days = carbon_get_theme_option("bt_sst_shipment_processing_days");
							}

							if (!$processing_days || $processing_days < 0) {
								$processing_days = 0;
							}
							$d = $this->addDayswithdate($rb['etd'], $processing_days);
							$bt_sst_shipping_duration_days = $this->getDaysDifferenceFromToday($rb['etd']);
							$delivery_date = " (Edd: " . $d . ")";
						}
						$d = new DateTime($d);

						$WC_Shipping_Rate = new WC_Shipping_Rate();

						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label($lable . $delivery_date);
						$WC_Shipping_Rate->add_meta_data("edd", $d);
						$WC_Shipping_Rate->set_method_id($method_id);
						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$WC_Shipping_Rate->set_taxes($texes);
						$WC_Shipping_Rate->add_meta_data('bt_sst_sr_courier_company_id', $rb['courier_company_id']);
						$WC_Shipping_Rate->add_meta_data('bt_sst_sr_courier_company_name', $rb['courier_name']);
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipment_provider', 'shiprocket');
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
						$rates[$id] = $WC_Shipping_Rate;
					}

				} else {
					//international order
					foreach ($filtered_arr as $r => $rb) {
						$id = 'flat_rate:shiprocket:' . $rb['courier_company_id'];
						$lable = $rb['courier_name'];
						$method_id = 'flat_rate';
						$markup_cost = carbon_get_theme_option(
							"bt_sst_markup_charges"
						);
						if (!$markup_cost) {
							$markup_cost = 0;
						}
						if (($rb['rate']['rate'] + $markup_cost) < 0) {
							//if adding markup results in negative overall cost, set markup to zero
							$markup_cost = 0;
						}
						$cost = round($rb['rate']['rate'] + $markup_cost, 2);

						$texes = [];
						$delivery_date = '';
						$show_delivery_date = carbon_get_theme_option("bt_sst_show_delivery_date");
						$bt_sst_shipping_duration_days = null;
						if ($show_delivery_date == 1) {
							$processing_days = carbon_get_theme_option("bt_sst_shipment_processing_days");
							if (!$processing_days || $processing_days < 0) {
								$processing_days = 0;
							}
							$etd = $rb['etd'];
							$min_date = explode("-", $etd)[0];
							$max_date = explode("-", $etd)[1];
							$bt_sst_shipping_duration_days = $this->getDaysDifferenceFromToday($min_date);
							$min_date = $this->addDayswithdate($min_date, $processing_days);
							$max_date = $this->addDayswithdate($max_date, $processing_days);
							$delivery_date = " (" . $min_date . " - " . $max_date . ")";
						}

						$WC_Shipping_Rate = new WC_Shipping_Rate();

						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label($lable . $delivery_date);
						$WC_Shipping_Rate->add_meta_data("edd", $delivery_date);
						$WC_Shipping_Rate->set_method_id($method_id);
						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$WC_Shipping_Rate->set_taxes($texes);
						$WC_Shipping_Rate->add_meta_data('bt_sst_sr_courier_company_id', $rb['courier_company_id']);
						$WC_Shipping_Rate->add_meta_data('bt_sst_sr_courier_company_name', $rb['courier_name']);
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipment_provider', 'shiprocket');
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
						$rates[$id] = $WC_Shipping_Rate;
					}
				}
			}

		} else if ($bt_sst_courier_rate_provider == 'shipmozo') {


			$cart_totals = $this->get_cart_weight_and_dimentions();

			$weight_in_kg = $cart_totals['total_weight_kg'];
			$length_in_cms = $cart_totals['total_length_cm'];
			$breadth_in_cms = $cart_totals['total_width_cm'];
			$height_in_cms = $cart_totals['total_height_cm'];
			$declared_value = $cart_totals['declared_value'];

			if ($weight_in_kg < 0.1) {
				$weight_in_kg = 0.1;
			}
			if ($length_in_cms < 1) {
				$length_in_cms = 10;
			}
			if ($breadth_in_cms < 1) {
				$breadth_in_cms = 10;
			}
			if ($height_in_cms < 1) {
				$height_in_cms = 10;
			}

			if ($weight_in_kg > 0) {

				// Get any existing copy of our transient data
				if (false === ($bt_sst_cached_delivery_estimates_shipmozo = get_transient('bt_sst_cached_delivery_estimates_shipmozo'))) {
					// It wasn't there, so regenerate the data and save the transient
					$bt_sst_cached_delivery_estimates_shipmozo = array();
				}

				if ($delivery_country == "IN") {
					$pickup_pincode = carbon_get_theme_option("bt_sst_shipmozo_pickup_pincode");

					if (!empty($pickup_pincode)) {
						$pm = $cod == 1 ? "COD" : "PREPAID";
						$cached_pincode_key = $pickup_pincode . "_" . $delivery_pincode . "_" . $weight_in_kg . '_' . $pm;
						if (isset($bt_sst_cached_delivery_estimates_shipmozo[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates_shipmozo[$cached_pincode_key];
						} else {
							$push_resp = $this->shipmozo->get_rate_calcultor('0', $pickup_pincode, $delivery_pincode, $pm, $declared_value, $cod, $weight_in_kg, '1', $length_in_cms, $breadth_in_cms, $height_in_cms, );
							if ($push_resp != null && !empty($push_resp) && $push_resp['result'] == "1" && isset($push_resp['data'])) {
								$push_resp = $push_resp['data'];
								$bt_sst_cached_delivery_estimates_shipmozo[$cached_pincode_key] = $push_resp;
								set_transient('bt_sst_cached_delivery_estimates_shipmozo', $bt_sst_cached_delivery_estimates_shipmozo, 1 * HOUR_IN_SECONDS);
							} else {
								$push_resp = [];
							}
						}
						$filtered_arr = $push_resp;
					}


				}
			}

			//if (sizeof($filtered_arr) > 0) {
			//honor free shipping coupon, if any.
			$free_shipping_rates = [];
			foreach ($rates as $key => $r) {
				if (strpos($key, 'free_shipping') !== false) {
					$free_shipping_rates[$key] = $r;
				}
			}
			$rates = $free_shipping_rates;
			//}


			if ($filtered_arr == null || sizeof($filtered_arr) == 0) {

				if ($delivery_country == "IN") {
					$cost = carbon_get_theme_option("bt_sst_shipmozo_fall_back_rate");
					if ($cost && $cost > 0) {
						$WC_Shipping_Rate = new WC_Shipping_Rate();
						$id = 'flat_rate:shipmozo:fallback';
						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label('Flat rate');
						$WC_Shipping_Rate->set_method_id($id);
						if ($weight_in_kg > 0.5) {
							$t_weight = $weight_in_kg / 0.5;
							$t_weight = ceil($t_weight);
							$cost = round($t_weight * $cost);
						}
						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$bt_sst_shipping_duration_days = 4; // set default of 4 days
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
						// $WC_Shipping_Rate->set_taxes($texes);

						$rates[$id] = $WC_Shipping_Rate;
					}
				} else {
					//to handle international fallback rate
				}
			} else {
				if ($delivery_country == "IN") {
					usort($filtered_arr, function ($a, $b) {
						return ($a['total_charges'] - ($b['total_charges']));
					});
					$cart_totals = $this->get_cart_weight_and_dimentions();
					$weight_in_grams = $cart_totals['total_weight_kg'] * 1000;

					$filtered_arr = $this->findNearestWeights($filtered_arr, $weight_in_grams);
					foreach ($filtered_arr as $r => $rb) {
						$id = 'flat_rate:shipmozo:' . $rb['id'];
						$lable = $rb['name'];
						$method_id = 'flat_rate';
						$markup_cost = carbon_get_theme_option(
							"bt_sst_markup_charges"
						);
						if (!$markup_cost) {
							$markup_cost = 0;
						}
						if (($rb['total_charges'] + $markup_cost) < 0) {
							//if adding markup results in negative overall cost, set markup to zero
							$markup_cost = 0;
						}
						$cost = round($rb['total_charges'] + $markup_cost, 2);

						$texes = [];
						$delivery_date = '';
						$d ="";
						$show_delivery_date = carbon_get_theme_option("bt_sst_show_delivery_date");
						$bt_sst_shipping_duration_days = null; // set default of 4 days
						if ($show_delivery_date == 1) {
							$processing_days = $this->bt_get_processing_days();//first try to get at product or category level.
							if (!$processing_days) {
								//if not found, get processing days set at global level.
								$processing_days = carbon_get_theme_option("bt_sst_shipment_processing_days");
							}

							if (!$processing_days || $processing_days < 0) {
								$processing_days = 0;
							}
							$input = $rb['estimated_delivery'];
							$currentDate = new DateTime();
							preg_match('/\d+/', $input, $matches);
							$days = intval($matches[0]);
							$bt_sst_shipping_duration_days = $days;
							$interval = new DateInterval("P{$days}D");
							$currentDate->add($interval);
							$expectedDate = $currentDate->format('c');

							$rb['etd'] = $expectedDate;

							$d = $this->addDayswithdate($rb['etd'], $processing_days);
							$delivery_date = " (Edd: " . $d . ")";
						}
						$d = new DateTime($d);
						$WC_Shipping_Rate = new WC_Shipping_Rate();

						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label($lable . $delivery_date);
						$WC_Shipping_Rate->add_meta_data("edd", $d);
						$WC_Shipping_Rate->set_method_id($method_id);
						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$WC_Shipping_Rate->set_taxes($texes);
						
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
						$WC_Shipping_Rate->add_meta_data('bt_sst_courier_company_id', $rb['id']);
						$WC_Shipping_Rate->add_meta_data('bt_sst_courier_company_name', $rb['name']);
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipment_provider', 'shipmozo');

						$rates[$id] = $WC_Shipping_Rate;

					}
				}
			}

		} else if ($bt_sst_courier_rate_provider == 'nimbuspost_new') {


			$cart_totals = $this->get_cart_weight_and_dimentions();

			$weight_in_kg = $cart_totals['total_weight_kg'];
			$length_in_cms = $cart_totals['total_length_cm'];
			$breadth_in_cms = $cart_totals['total_width_cm'];
			$height_in_cms = $cart_totals['total_height_cm'];
			$declared_value = $cart_totals['declared_value'];

			if ($weight_in_kg < 0.1) {
				$weight_in_kg = 0.1;
			}
			if ($length_in_cms < 1) {
				$length_in_cms = 10;
			}
			if ($breadth_in_cms < 1) {
				$breadth_in_cms = 10;
			}
			if ($height_in_cms < 1) {
				$height_in_cms = 10;
			}

			if ($weight_in_kg > 0) {

				// Get any existing copy of our transient data
				if (false === ($bt_sst_cached_delivery_estimates_nimbuspost_new = get_transient('bt_sst_cached_delivery_estimates_nimbuspost_new'))) {
					// It wasn't there, so regenerate the data and save the transient
					$bt_sst_cached_delivery_estimates_nimbuspost_new = array();
				}

				if ($delivery_country == "IN") {
					$pickup_pincode = carbon_get_theme_option("bt_sst_nimbuspost_pickup_pincode");

					if (!empty($pickup_pincode)) {
						$pm = $cod == 1 ? "COD" : "PREPAID";
						$cached_pincode_key = $pickup_pincode . "_" . $delivery_pincode . "_" . $weight_in_kg . '_' . $pm;
						if (isset($bt_sst_cached_delivery_estimates_nimbuspost_new[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates_nimbuspost_new[$cached_pincode_key];
						} else {
							$push_resp = $this->nimbuspost_new->get_rate_calcultor($pickup_pincode, $delivery_pincode, $pm, $declared_value, $weight_in_kg, $length_in_cms, $breadth_in_cms, $height_in_cms, );
							if ($push_resp != null && !empty($push_resp) && $push_resp['status'] == "true" && isset($push_resp['data'])) {
								$push_resp = $push_resp['data'];
								$bt_sst_cached_delivery_estimates_nimbuspost_new[$cached_pincode_key] = $push_resp;
								set_transient('bt_sst_cached_delivery_estimates_nimbuspost_new', $bt_sst_cached_delivery_estimates_nimbuspost_new, 1 * HOUR_IN_SECONDS);
							} else {
								$push_resp = [];
							}
						}
						$filtered_arr = $push_resp;
					}


				}
			}

			//if (sizeof($filtered_arr) > 0) {
			//honor free shipping coupon, if any.
			$free_shipping_rates = [];
			foreach ($rates as $key => $r) {
				if (strpos($key, 'free_shipping') !== false) {
					$free_shipping_rates[$key] = $r;
				}
			}
			$rates = $free_shipping_rates;
			//}


			if ($filtered_arr == null || sizeof($filtered_arr) == 0) {

				if ($delivery_country == "IN") {
					$cost = carbon_get_theme_option("bt_sst_nimbuspost_new_fall_back_rate");
					if ($cost && $cost > 0) {
						$WC_Shipping_Rate = new WC_Shipping_Rate();
						$id = 'flat_rate:nimbuspost_new:fallback';
						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label('Flat rate');
						$WC_Shipping_Rate->set_method_id($id);
						if ($weight_in_kg > 0.5) {
							$t_weight = $weight_in_kg / 0.5;
							$t_weight = ceil($t_weight);
							$cost = round($t_weight * $cost);
						}
						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						// $WC_Shipping_Rate->set_taxes($texes);
						$bt_sst_shipping_duration_days = 4; // set default of 4 days
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
						$rates[$id] = $WC_Shipping_Rate;
					}
				} else {
					//to handle international fallback rate
				}
			} else {
				if ($delivery_country == "IN") {
					usort($filtered_arr, function ($a, $b) {
						return ($a['total_charges'] - ($b['total_charges']));
					});
					$filtered_arr = array_slice($filtered_arr, 0, 10);//take only first 10 couriers
					foreach ($filtered_arr as $r => $rb) {

						$id = 'flat_rate:nimbuspost_new:' . $rb['id'];
						$lable = $rb['name'];
						$method_id = 'flat_rate';
						$markup_cost = carbon_get_theme_option(
							"bt_sst_markup_charges"
						);
						if (!$markup_cost) {
							$markup_cost = 0;
						}
						if (($rb['total_charges'] + $markup_cost) < 0) {
							//if adding markup results in negative overall cost, set markup to zero
							$markup_cost = 0;
						}
						$cost = round($rb['total_charges'] + $markup_cost, 2);

						$texes = [];
						$delivery_date = '';
						$show_delivery_date = carbon_get_theme_option("bt_sst_show_delivery_date");
						$d="";
						if ($show_delivery_date == 1) {
							$processing_days = $this->bt_get_processing_days();//first try to get at product or category level.
							if (!$processing_days) {
								//if not found, get processing days set at global level.
								$processing_days = carbon_get_theme_option("bt_sst_shipment_processing_days");
							}

							if (!$processing_days || $processing_days < 0) {
								$processing_days = 0;
							}

							$d = $this->addDayswithdate($rb['edd'], $processing_days);
							$delivery_date = " (Edd: " . $d . ")";
						}
						$d = new DateTime($d);
						$WC_Shipping_Rate = new WC_Shipping_Rate();

						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label($lable . $delivery_date);
						$WC_Shipping_Rate->add_meta_data("edd", $d);
						$WC_Shipping_Rate->set_method_id($method_id);
						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$WC_Shipping_Rate->set_taxes($texes);
						$bt_sst_shipping_duration_days = 4; // set default of 4 days
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
						$WC_Shipping_Rate->add_meta_data('bt_sst_courier_company_id', $rb['id']);
						$WC_Shipping_Rate->add_meta_data('bt_sst_courier_company_name', $rb['name']);
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipment_provider', 'nimbuspost_new');

						$rates[$id] = $WC_Shipping_Rate;
					}
				}
			}

		} else if ($bt_sst_courier_rate_provider == 'delhivery') {


			$cart_totals = $this->get_cart_weight_and_dimentions();

			$weight_in_kg = $cart_totals['total_weight_kg'];
			$length_in_cms = $cart_totals['total_length_cm'];
			$breadth_in_cms = $cart_totals['total_width_cm'];
			$height_in_cms = $cart_totals['total_height_cm'];
			$declared_value = $cart_totals['declared_value'];

			if ($weight_in_kg < 0.1) {
				$weight_in_kg = 0.1;
			}
			if ($length_in_cms < 1) {
				$length_in_cms = 10;
			}
			if ($breadth_in_cms < 1) {
				$breadth_in_cms = 10;
			}
			if ($height_in_cms < 1) {
				$height_in_cms = 10;
			}

			if ($weight_in_kg > 0) {

				// Get any existing copy of our transient data
				if (false === ($bt_sst_cached_delivery_estimates_delhivery = get_transient('bt_sst_cached_delivery_estimates_delhivery'))) {
					// It wasn't there, so regenerate the data and save the transient
					$bt_sst_cached_delivery_estimates_delhivery = array();
				}
				//$bt_sst_cached_delivery_estimates_delhivery = array();
				if ($delivery_country == "IN") {
					$pickup_pincode = carbon_get_theme_option("bt_sst_delhivery_pincodepickup");

					if (!empty($pickup_pincode)) {
						$pm = $cod == 1 ? "COD" : "PREPAID";
						$cached_pincode_key = $pickup_pincode . "_" . $delivery_pincode . "_" . $weight_in_kg . '_' . $pm;
						if (isset($bt_sst_cached_delivery_estimates_delhivery[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates_delhivery[$cached_pincode_key];
						} else {
							$push_resp = $this->delhivery->get_rate_calcultor($pickup_pincode, $delivery_pincode, $pm, $declared_value, $cod, $weight_in_kg, '1', $length_in_cms, $breadth_in_cms, $height_in_cms, );

							if ($push_resp != null && !empty($push_resp)) {
								// $push_resp = $push_resp['data'];
								$bt_sst_cached_delivery_estimates_delhivery[$cached_pincode_key] = $push_resp;
								set_transient('bt_sst_cached_delivery_estimates_delhivery', $bt_sst_cached_delivery_estimates_delhivery, 1 * HOUR_IN_SECONDS);
							} else {
								$push_resp = [];
							}
						}
						$filtered_arr = $push_resp;
					}


				}
			}

			//if (sizeof($filtered_arr) > 0) {
			//honor free shipping coupon, if any.
			$free_shipping_rates = [];
			foreach ($rates as $key => $r) {
				if (strpos($key, 'free_shipping') !== false) {
					$free_shipping_rates[$key] = $r;
				}
			}
			$rates = $free_shipping_rates;
			//}


			if ($filtered_arr == null || sizeof($filtered_arr) == 0) {

				if ($delivery_country == "IN") {
					$cost = carbon_get_theme_option("bt_sst_delhivery_fall_back_rate");
					if ($cost && $cost > 0) {
						$WC_Shipping_Rate = new WC_Shipping_Rate();
						$id = 'flat_rate:delhivery:fallback';
						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label('Flat rate');
						$WC_Shipping_Rate->set_method_id($id);

						if ($weight_in_kg > 0.5) {
							$t_weight = $weight_in_kg / 0.5;
							$t_weight = ceil($t_weight);
							$cost = round($t_weight * $cost);
						}

						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$bt_sst_shipping_duration_days = 4; // set default of 4 days
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
						// $WC_Shipping_Rate->set_taxes($texes);

						$rates[$id] = $WC_Shipping_Rate;
					}
				} else {
					//to handle international fallback rate
				}
			} else {

				if ($delivery_country == "IN") {
					usort($filtered_arr, function ($a, $b) {
						return ($a['total_amount'] - ($b['total_amount']));
					});

					// foreach ($filtered_arr as  $rb) {
					// print_r($rb);
					// }die;
					$i = 0;
					foreach ($filtered_arr as $rb) {

						// $i++;
						// if($r=='express'){
						// 	$lable = "Delhivery Express";
						// 	$id = 'flat_rate:delhivery:Express';
						// }else{
						// 	$lable = "Delhivery Surface";
						// 	$id = 'flat_rate:delhivery:Surface';
						// }

						$lable = "Delhivery " . ucfirst($rb['mode']);
						$bt_sst_shipping_duration_days = null;
						$bt_sst_shipping_edd = null;
						if (isset($rb['tat']) && $rb['tat'] != null) {
							//$lable .= " / " . $rb['tat'] . ($rb['tat'] > 1 ? " days " : " day");
							$bt_sst_shipping_duration_days = $rb['tat'];
							$bt_sst_shipping_edd = new DateTime("+" . $bt_sst_shipping_duration_days . " days");
						}
						$id = 'flat_rate:delhivery:' . $rb['mode'];
						$method_id = 'flat_rate';
						$markup_cost = carbon_get_theme_option(
							"bt_sst_markup_charges"
						);
						if (!$markup_cost) {
							$markup_cost = 0;
						}
						if (($rb['total_amount'] + $markup_cost) < 0) {
							$markup_cost = 0;
						}

						$delivery_charge = 0;
						if ($cod == 1) {
							$two_percent = $rb['total_amount'] * (2 / 100);
							if ($two_percent > 40) {
								$delivery_charge = $two_percent;
							} else {
								$delivery_charge = 40;
							}
						}
						$cost = round($rb['total_amount'] + $markup_cost + $delivery_charge, 2);


						$texes = [];
						$delivery_date = '';
						$processing_days = $this->bt_get_processing_days();//first try to get at product or category level.
						if (!$processing_days) {
							//if not found, get processing days set at global level.
							$processing_days = carbon_get_theme_option("bt_sst_shipment_processing_days");
						}
						if (!$processing_days || $processing_days < 0) {
							$processing_days = 0;
						}
						$delivery_date = $this->addDayswithdate($bt_sst_shipping_edd->format('Y-m-d H:i:s'), $processing_days);
						
						$show_delivery_date = carbon_get_theme_option("bt_sst_show_delivery_date");
						if ($show_delivery_date == 1) {
							$lable .= " (Edd: " . $delivery_date . ")";
						}

						$WC_Shipping_Rate = new WC_Shipping_Rate();

						$WC_Shipping_Rate->add_meta_data("edd", $delivery_date);
						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label($lable);
						$WC_Shipping_Rate->set_method_id($method_id);
						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$WC_Shipping_Rate->set_taxes($texes);
						// $WC_Shipping_Rate->add_meta_data('bt_sst_courier_company_id', $rb['id']);
						$WC_Shipping_Rate->add_meta_data('bt_sst_courier_company_name', $lable);
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipment_provider', 'delhivery');
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
						$WC_Shipping_Rate->add_meta_data('bt_sst_processing_days', $processing_days);
						$rates[$id] = $WC_Shipping_Rate;
					}


				}
			}

		} else if ($bt_sst_courier_rate_provider == 'fship') {

			$cart_totals = $this->get_cart_weight_and_dimentions();
			// echo "<pre>"; print_r($cart_totals); die;
			// Set default weight if not set or falsy
			$weight_in_kg = isset($cart_totals['total_weight_kg']) ? $cart_totals['total_weight_kg'] : 0.1;
			$length_in_cms = isset($cart_totals['total_length_cm']) ? $cart_totals['total_length_cm'] : 0.1;
			$breadth_in_cms = isset($cart_totals['total_width_cm']) ? $cart_totals['total_width_cm'] : 0.1;
			$height_in_cms = isset($cart_totals['total_height_cm']) ? $cart_totals['total_height_cm'] : 0.1;
			$declared_value = isset($cart_totals['declared_value']) ? $cart_totals['declared_value'] : 0.1;


			// Get any existing copy of our transient data
			if (false === ($bt_sst_cached_delivery_estimates = get_transient('bt_sst_cached_delivery_estimates'))) {
				// It wasn't there, so regenerate the data and save the transient
				$bt_sst_cached_delivery_estimates = array();
			}

			if ($delivery_country == "IN") {
				$pickup_pincode = carbon_get_theme_option("bt_sst_fship_pincodepickup");
				if (!empty($pickup_pincode)) {
					$pm = $cod == 1 ? "COD" : "P";
					$cached_pincode_key = $pickup_pincode . "_" . $delivery_pincode . "_" . $weight_in_kg . '_' . $pm;

					if (isset($bt_sst_cached_delivery_estimates[$cached_pincode_key])) {
						$push_resp = $bt_sst_cached_delivery_estimates[$cached_pincode_key];
					} else {
						$push_resp = $this->fship->get_rate_calculator(
							$pm,
							$pickup_pincode,
							$delivery_pincode,
							$declared_value,
							"",
							$weight_in_kg,
							$length_in_cms,
							$breadth_in_cms,
							$height_in_cms,
							"1"//to do
						);
						if ($push_resp != null && !empty($push_resp) && sizeof($push_resp) > 0) {
							$bt_sst_cached_delivery_estimates[$cached_pincode_key] = $push_resp;
							set_transient('bt_sst_cached_delivery_estimates', $bt_sst_cached_delivery_estimates, 1 * HOUR_IN_SECONDS);
						}
					}


				}

			}
			$filtered_arr = [];


			$filtered_arr = $push_resp;

			//if (sizeof($filtered_arr) > 0) {
			//honor free shipping coupon, if any.
			$free_shipping_rates = [];
			foreach ($rates as $key => $r) {
				if (strpos($key, 'free_shipping') !== false) {
					$free_shipping_rates[$key] = $r;
				}
			}
			$rates = $free_shipping_rates;
			//}


			if (empty($filtered_arr) || sizeof($filtered_arr) == 0) {

				if ($delivery_country == "IN") {
					$cost = 50; // to do: fallback rate option for fship
					if ($cost && $cost > 0) {
						$WC_Shipping_Rate = new WC_Shipping_Rate();
						$id = 'flat_rate:fship:fallback';
						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label('Flat rate');
						$WC_Shipping_Rate->set_method_id($id);
						if ($weight_in_kg > 0.5) {
							$t_weight = $weight_in_kg / 0.5;
							$t_weight = ceil($t_weight);
							$cost = round($t_weight * $cost);
						}
						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						// $WC_Shipping_Rate->set_taxes($texes);

						$rates[$id] = $WC_Shipping_Rate;
					}
				} else {
					//to handle international fallback rate
				}
			} else {
				if ($delivery_country == "IN") {
					usort($filtered_arr, function ($a, $b) {
						return ($a['cod_charge'] + $a['shipping_charge']) - ($b['cod_charge'] + $b['shipping_charge']);
					});
					foreach ($filtered_arr as $r => $rb) {

						$id = 'flat_rate:fship:' . str_replace(" ", "", $rb['courier_name']);
						$lable = $rb['courier_name'];
						$method_id = 'flat_rate';
						$markup_cost = 0; // to do: get markup charges from fship settings
						if (!$markup_cost) {
							$markup_cost = 0;
						}
						if (($rb['cod_charge'] + $rb['shipping_charge'] + $markup_cost) < 0) {
							//if adding markup results in negative overall cost, set markup to zero
							$markup_cost = 0;
						}
						$cost = round($rb['cod_charge'] + $rb['shipping_charge'] + $markup_cost, 2);

						$texes = [];
						$delivery_date = '';
						$d ="";
						$show_delivery_date = carbon_get_theme_option("bt_sst_show_delivery_date");
						if ($show_delivery_date == 1) {
							$processing_days = $this->bt_get_processing_days();//first try to get at product or category level.
							if (!$processing_days) {
								//if not found, get processing days set at global level.
								$processing_days = carbon_get_theme_option("bt_sst_shipment_processing_days");
							}

							if (!$processing_days || $processing_days < 0) {
								$processing_days = 0;
							}
							$d = $this->addDayswithdate(time(), $processing_days);
							$delivery_date = " (Edd: " . $d . ")";
						}
						$d = new DateTime($d);
						$WC_Shipping_Rate = new WC_Shipping_Rate();

						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label($lable . $delivery_date);
						$WC_Shipping_Rate->add_meta_data("edd", $d);
						$WC_Shipping_Rate->set_method_id($method_id);
						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$WC_Shipping_Rate->set_taxes($texes);
						$WC_Shipping_Rate->add_meta_data('bt_sst_sr_courier_company_name', $rb['courier_name']);
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipment_provider', 'fship');

						$rates[$id] = $WC_Shipping_Rate;
					}

				} else {
					//international order
					//to do: handle international orders
				}
			}

		} else if ($bt_sst_courier_rate_provider == 'ekart') {
			$cart_totals = $this->get_cart_weight_and_dimentions();
			$weight_in_kg = $cart_totals['total_weight_kg'];
			$length_in_cms = $cart_totals['total_length_cm'];
			$breadth_in_cms = $cart_totals['total_width_cm'];
			$height_in_cms = $cart_totals['total_height_cm'];
			$declared_value = $cart_totals['declared_value'];

			if ($weight_in_kg < 0.1) {
				$weight_in_kg = 0.1;
			}
			if ($length_in_cms < 1) {
				$length_in_cms = 10;
			}
			if ($breadth_in_cms < 1) {
				$breadth_in_cms = 10;
			}
			if ($height_in_cms < 1) {
				$height_in_cms = 10;
			}

			if ($weight_in_kg > 0) {
				if (false === ($bt_sst_cached_ekart_estimates_delhivery = get_transient('bt_sst_cached_ekart_estimates_delhivery'))) {
					$bt_sst_cached_ekart_estimates_delhivery = array();
				}
				if ($delivery_country == "IN") {
					$pickup_pincode = carbon_get_theme_option("bt_sst_ekart_pickup_pin");
					if (!empty($pickup_pincode)) {
						$pm = $cod == 1 ? "COD" : "PREPAID";
						$cached_pincode_key = $pickup_pincode . "_" . $delivery_pincode . "_" . $weight_in_kg . '_' . $pm;
						if (isset($bt_sst_cached_ekart_estimates_delhivery[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_ekart_estimates_delhivery[$cached_pincode_key];
						} else {
							$push_resp = $this->ekart->get_rate_calcultor(
															$delivery_pincode, 
															$weight_in_kg, 
															$length_in_cms, 
															$height_in_cms, 
															$breadth_in_cms,
															$total_cost
														);
							if ($push_resp != null && !empty($push_resp)) {
								$bt_sst_cached_ekart_estimates_delhivery[$cached_pincode_key] = $push_resp;
								set_transient('bt_sst_cached_ekart_estimates_delhivery', $bt_sst_cached_ekart_estimates_delhivery, 1 * HOUR_IN_SECONDS);
							} else {
								$push_resp = [];
							}
						}
						$filtered_arr = $push_resp;
					}
				}
			}

			$free_shipping_rates = [];
			foreach ($rates as $key => $r) {
				if (strpos($key, 'free_shipping') !== false) {
					$free_shipping_rates[$key] = $r;
				}
			}
			$rates = $free_shipping_rates;

			if ($filtered_arr == null || sizeof($filtered_arr) == 0) {

				if ($delivery_country == "IN") {
					$cost = carbon_get_theme_option("bt_sst_ekart_fall_back_rate");
					if ($cost && $cost > 0) {
						$WC_Shipping_Rate = new WC_Shipping_Rate();
						$id = 'flat_rate:ekart:fallback';
						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label('Flat rate');
						$WC_Shipping_Rate->set_method_id($id);

						if ($weight_in_kg > 0.5) {
							$t_weight = $weight_in_kg / 0.5;
							$t_weight = ceil($t_weight);
							$cost = round($t_weight * $cost);
						}

						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$bt_sst_shipping_duration_days = 4;
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);

						$rates[$id] = $WC_Shipping_Rate;
					}
				} else {
					//to handle international fallback rate
				}
			} else {

				if ($delivery_country == "IN") {
					usort($filtered_arr, function ($a, $b) {
						return ($a['total'] - ($b['total']));
					});

					$i = 0;
					foreach ($filtered_arr as $rb) {

						$lable = "Ekart " . ucfirst($rb['mod']);
						$bt_sst_shipping_duration_days = null;
						$bt_sst_shipping_edd = null;
						if (isset($rb['tat']) && $rb['tat'] != null) {
							$bt_sst_shipping_duration_days = $rb['tat'];
							$bt_sst_shipping_edd = new DateTime("+" . $bt_sst_shipping_duration_days . " days");
						}
						$id = 'flat_rate:ekart:' . $rb['mod'];
						$method_id = 'flat_rate';
						$markup_cost = carbon_get_theme_option(
							"bt_sst_markup_charges"
						);
						if (!$markup_cost) {
							$markup_cost = 0;
						}
						if (($rb['total'] + $markup_cost) < 0) {
							$markup_cost = 0;
						}

						$delivery_charge = 0;
						if ($cod == 1) {
							$two_percent = $rb['total'] * (2 / 100);
							if ($two_percent > 40) {
								$delivery_charge = $two_percent;
							} else {
								$delivery_charge = 40;
							}
						}
						$cost = round($rb['total'] + $markup_cost + $delivery_charge, 2);


						$texes = [];
						$delivery_date = '';
						$processing_days = $this->bt_get_processing_days();
						if (!$processing_days) {
							$processing_days = carbon_get_theme_option("bt_sst_shipment_processing_days");
						}
						if (!$processing_days || $processing_days < 0) {
							$processing_days = 0;
						}
						$delivery_date = $this->addDayswithdate($bt_sst_shipping_edd->format('Y-m-d H:i:s'), $processing_days);
						
						$show_delivery_date = carbon_get_theme_option("bt_sst_show_delivery_date");
						if ($show_delivery_date == 1) {
							$lable .= " (Edd: " . $delivery_date . ")";
						}

						$WC_Shipping_Rate = new WC_Shipping_Rate();
						$WC_Shipping_Rate->add_meta_data("edd", $delivery_date);
						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label($lable);
						$WC_Shipping_Rate->set_method_id($method_id);
						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$WC_Shipping_Rate->set_taxes($texes);
						$WC_Shipping_Rate->add_meta_data('bt_sst_courier_company_name', $lable);
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipment_provider', 'ekart');
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
						$WC_Shipping_Rate->add_meta_data('bt_sst_processing_days', $processing_days);
						$rates[$id] = $WC_Shipping_Rate;
					}


				}
			}

		} else if ($bt_sst_courier_rate_provider == 'proship') {

			$cart_totals = $this->get_cart_weight_and_dimentions();

			$weight_in_kg = $cart_totals['total_weight_kg'];
			$length_in_cms = $cart_totals['total_length_cm'];
			$breadth_in_cms = $cart_totals['total_width_cm'];
			$height_in_cms = $cart_totals['total_height_cm'];
			$declared_value = $cart_totals['declared_value'];

			if ($weight_in_kg < 0.1) {
				$weight_in_kg = 0.1;
			}
			if ($length_in_cms < 1) {
				$length_in_cms = 10;
			}
			if ($breadth_in_cms < 1) {
				$breadth_in_cms = 10;
			}
			if ($height_in_cms < 1) {
				$height_in_cms = 10;
			}

			if ($weight_in_kg > 0) {

				if (false === ($bt_sst_cached_delivery_estimates_proship = get_transient('bt_sst_cached_delivery_estimates_proship'))) {
					$bt_sst_cached_delivery_estimates_proship = array();
				}
				if ($delivery_country == "IN") {
					$pickup_pincode = carbon_get_theme_option('bt_sst_proship_from_pincode');

					if (!empty($pickup_pincode)) {
						$pm = $cod == 1 ? "COD" : "PREPAID";
						$cached_pincode_key = $pickup_pincode . "_" . $delivery_pincode . "_" . $weight_in_kg . '_' . $pm;
						if (isset($bt_sst_cached_delivery_estimates_proship[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates_proship[$cached_pincode_key];
						} else {
							$cod_amount = $cod == 1 ? $declared_value : 0;
							$push_resp = $this->proship->get_proship_pricing(
											$pickup_pincode,
											$delivery_pincode,
											$pm, 
											$length_in_cms,
											$breadth_in_cms,
											$height_in_cms,
											$weight_in_kg,
											"SURFACE",
											$cod_amount,
											"FORWARD",
										);
							if ($push_resp != null && !empty($push_resp)) {
								$bt_sst_cached_delivery_estimates_proship[$cached_pincode_key] = $push_resp;
								set_transient('bt_sst_cached_delivery_estimates_proship', $bt_sst_cached_delivery_estimates_proship, 1 * HOUR_IN_SECONDS);
							} else {
								$push_resp = [];
							}
						}
						$filtered_arr = $push_resp;
					}


				}
			}

			$free_shipping_rates = [];
			foreach ($rates as $key => $r) {
				if (strpos($key, 'free_shipping') !== false) {
					$free_shipping_rates[$key] = $r;
				}
			}
			$rates = $free_shipping_rates;

			if ($filtered_arr == null || sizeof($filtered_arr) == 0) {

				if ($delivery_country == "IN") {
					$cost = carbon_get_theme_option("bt_sst_proship_fall_back_rate");
					if ($cost && $cost > 0) {
						$WC_Shipping_Rate = new WC_Shipping_Rate();
						$id = 'flat_rate:proship:fallback';
						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label('Flat rate');
						$WC_Shipping_Rate->set_method_id($id);

						if ($weight_in_kg > 0.5) {
							$t_weight = $weight_in_kg / 0.5;
							$t_weight = ceil($t_weight);
							$cost = round($t_weight * $cost);
						}

						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$bt_sst_shipping_duration_days = 4;
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);

						$rates[$id] = $WC_Shipping_Rate;
					}
				} else {
					//to handle international fallback rate
				}
			} else {

				if ($delivery_country == "IN") {
					usort($filtered_arr, function ($a, $b) {
						return ($a['priceAfterTax'] - ($b['priceAfterTax']));
					});
					$i = 0;
					foreach ($filtered_arr as $rb) {
						$lable = "Proship " . ucfirst($rb['provider']['parent']);
						$bt_sst_shipping_duration_days = null;
						$bt_sst_shipping_edd = null;
						if (isset($rb['providerMaxTatDays']) && $rb['providerMaxTatDays'] != null) {
							$bt_sst_shipping_duration_days = $rb['providerMaxTatDays'];
							$bt_sst_shipping_edd = new DateTime("+" . $bt_sst_shipping_duration_days . " days");
						}
						$id = 'flat_rate:delhivery:' . $rb['provider']['parent'];
						$method_id = 'flat_rate';
						$markup_cost = carbon_get_theme_option(
							"bt_sst_markup_charges"
						);
						if (!$markup_cost) {
							$markup_cost = 0;
						}
						if (($rb['priceAfterTax'] + $markup_cost) < 0) {
							$markup_cost = 0;
						}

						$delivery_charge = 0;
						if ($cod == 1) {
							$two_percent = $rb['total_amopriceAfterTaxunt'] * (2 / 100);
							if ($two_percent > 40) {
								$delivery_charge = $two_percent;
							} else {
								$delivery_charge = 40;
							}
						}
						$cost = round($rb['priceAfterTax'] + $markup_cost + $delivery_charge, 2);


						$texes = [];
						$delivery_date = '';
						$processing_days = $this->bt_get_processing_days();
						if (!$processing_days) {
							$processing_days = carbon_get_theme_option("bt_sst_shipment_processing_days");
						}
						if (!$processing_days || $processing_days < 0) {
							$processing_days = 0;
						}
						$delivery_date = $this->addDayswithdate($bt_sst_shipping_edd->format('Y-m-d H:i:s'), $processing_days);
						
						$show_delivery_date = carbon_get_theme_option("bt_sst_show_delivery_date");
						if ($show_delivery_date == 1) {
							$lable .= " (Edd: " . $delivery_date . ")";
						}

						$WC_Shipping_Rate = new WC_Shipping_Rate();

						$WC_Shipping_Rate->add_meta_data("edd", $delivery_date);
						$WC_Shipping_Rate->set_id($id);
						$WC_Shipping_Rate->set_label($lable);
						$WC_Shipping_Rate->set_method_id($method_id);
						$WC_Shipping_Rate->set_cost($cost);
						$WC_Shipping_Rate->set_instance_id($id);
						$WC_Shipping_Rate->set_taxes($texes);
						$WC_Shipping_Rate->add_meta_data('bt_sst_courier_company_name', $lable);
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipment_provider', 'delhivery');
						$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
						$WC_Shipping_Rate->add_meta_data('bt_sst_processing_days', $processing_days);
						$rates[$id] = $WC_Shipping_Rate;
					}
				}
			}

		}else if ($bt_sst_courier_rate_provider == 'ithink') {

			$cart_totals = $this->get_cart_weight_and_dimentions();

			$weight_in_kg = $cart_totals['total_weight_kg'];
			$length_in_cms = $cart_totals['total_length_cm'];
			$breadth_in_cms = $cart_totals['total_width_cm'];
			$height_in_cms = $cart_totals['total_height_cm'];
			$declared_value = $cart_totals['declared_value'];

			if ($weight_in_kg < 0.1) {
				$weight_in_kg = 0.1;
			}
			if ($length_in_cms < 1) {
				$length_in_cms = 10;
			}
			if ($breadth_in_cms < 1) {
				$breadth_in_cms = 10;
			}
			if ($height_in_cms < 1) {
				$height_in_cms = 10;
			}

			if ($weight_in_kg > 0) {

				$settings = get_option('ithink_logistics_settings');
				if (false === ($bt_sst_cached_delivery_estimates_ithink = get_transient('bt_sst_cached_delivery_estimates_ithink'))) {
					$bt_sst_cached_delivery_estimates_ithink = array();
				}
				if ($delivery_country == "IN") {
					$pickup_pincode = isset($settings['pickup_pincode']) ? $settings['pickup_pincode'] : '';

					if (!empty($pickup_pincode)) {
						$pm = $cod == 1 ? "COD" : "PREPAID";
						$cached_pincode_key = $pickup_pincode . "_" . $delivery_pincode . "_" . $weight_in_kg . '_' . $pm;
						if (isset($bt_sst_cached_delivery_estimates_ithink[$cached_pincode_key])) {
							$push_resp = $bt_sst_cached_delivery_estimates_ithink[$cached_pincode_key];
						} else {
							$cod_amount = $cod == 1 ? $declared_value : 0;
							$push_resp = $this->ithink->check_shipping_rate(
															$pickup_pincode,
															$delivery_pincode,
															$length_in_cms,
															$breadth_in_cms,
															$height_in_cms,
															$weight_in_kg,
															$declared_value,
															"forward",
															"COD",);
							if ($push_resp != null && !empty($push_resp)) {
								$bt_sst_cached_delivery_estimates_ithink[$cached_pincode_key] = $push_resp;
								set_transient('bt_sst_cached_delivery_estimates_ithink', $bt_sst_cached_delivery_estimates_ithink, 1 * HOUR_IN_SECONDS);
							} else {
								$push_resp = [];
							}
						}
						$filtered_arr = $push_resp["data"];
					}


				}
			}

			$free_shipping_rates = [];
			foreach ($rates as $key => $r) {
				if (strpos($key, 'free_shipping') !== false) {
					$free_shipping_rates[$key] = $r;
				}
			}
			$rates = $free_shipping_rates;
			
			if ($delivery_country == "IN") {
				usort($filtered_arr, function ($a, $b) {
					return ($a['rate'] - ($b['rate']));
				});
				$i = 0;
				foreach ($filtered_arr as $rb) {
					$lable =  ucfirst($rb['logistic_name']);
					$bt_sst_shipping_duration_days = null;
					$bt_sst_shipping_edd = null;
					if (isset($rb['delivery_tat']) && $rb['delivery_tat'] != null) {
						$bt_sst_shipping_duration_days = $rb['delivery_tat'];
						$bt_sst_shipping_edd = new DateTime("+" . $bt_sst_shipping_duration_days . " days");
					}
					$id = 'flat_rate:ithink:' . $rb['logistic_name'];
					$method_id = 'flat_rate';
					$markup_cost = carbon_get_theme_option(
						"bt_sst_markup_charges"
					);
					if (!$markup_cost) {
						$markup_cost = 0;
					}
					if (($rb['rate'] + $markup_cost) < 0) {
						$markup_cost = 0;
					}

					$delivery_charge = 0;
					if ($cod == 1) {
						$two_percent = $rb['rate'] * (2 / 100);
						if ($two_percent > 40) {
							$delivery_charge = $two_percent;
						} else {
							$delivery_charge = 40;
						}
					}
					$cost = round($rb['rate'] + $markup_cost + $delivery_charge, 2);


					$texes = [];
					$delivery_date = '';
					$processing_days = $this->bt_get_processing_days();
					if (!$processing_days) {
						$processing_days = carbon_get_theme_option("bt_sst_shipment_processing_days");
					}
					if (!$processing_days || $processing_days < 0) {
						$processing_days = 0;
					}
					$delivery_date = $this->addDayswithdate($bt_sst_shipping_edd->format('Y-m-d H:i:s'), $processing_days);
					
					$show_delivery_date = carbon_get_theme_option("bt_sst_show_ithink_date");
					if ($show_delivery_date == 1) {
						$lable .= " (Edd: " . $delivery_date . ")";
					}

					$WC_Shipping_Rate = new WC_Shipping_Rate();

					$WC_Shipping_Rate->add_meta_data("edd", $delivery_date);
					$WC_Shipping_Rate->set_id($id);
					$WC_Shipping_Rate->set_label($lable);
					$WC_Shipping_Rate->set_method_id($method_id);
					$WC_Shipping_Rate->set_cost($cost);
					$WC_Shipping_Rate->set_instance_id($id);
					$WC_Shipping_Rate->set_taxes($texes);
					$WC_Shipping_Rate->add_meta_data('bt_sst_courier_company_name', $lable);
					$WC_Shipping_Rate->add_meta_data('bt_sst_shipment_provider', 'delhivery');
					$WC_Shipping_Rate->add_meta_data('bt_sst_shipping_duration_days', $bt_sst_shipping_duration_days);
					$WC_Shipping_Rate->add_meta_data('bt_sst_processing_days', $processing_days);
					$rates[$id] = $WC_Shipping_Rate;
				}
			}
		

		}

		$saved_data = get_option('bt_sst_timer_settings', []);
		$percentage_discount = isset($saved_data['discount_percentage']) && is_numeric($saved_data['discount_percentage'])
			? floatval($saved_data['discount_percentage'])
			: 0;
		$startTime = isset($_COOKIE['bt_sst_start_time']) ? ((int) $_COOKIE['bt_sst_start_time'] ? (int) $_COOKIE['bt_sst_start_time'] : 0):0;
		$currentTime = time();

		$elapsedTime = $currentTime - $startTime;

		$timerHours   = isset($saved_data['bt_sst_timer_hours']) && is_numeric($saved_data['bt_sst_timer_hours']) ? (int) $saved_data['bt_sst_timer_hours'] : 0;
		$timerMinutes = isset($saved_data['bt_sst_timer_minutes']) && is_numeric($saved_data['bt_sst_timer_minutes']) ? (int) $saved_data['bt_sst_timer_minutes'] : 0;
		$timerSeconds = isset($saved_data['bt_sst_timer_seconds']) && is_numeric($saved_data['bt_sst_timer_seconds']) ? (int) $saved_data['bt_sst_timer_seconds'] : 0;

		$total_seconds = ($timerHours * 3600) + ($timerMinutes * 60) + $timerSeconds;

		if ($elapsedTime < $total_seconds && isset($saved_data['bt_sst_timer_enable']) && $saved_data['bt_sst_timer_enable']) {
			$free_shipping = !empty($saved_data['free_shipping']);

			foreach ($rates as $rate_key => $rate) {
				$original_cost = number_format(floatval($rate->cost), 2);
				$strikethrough_price = preg_replace('/./u', '$0̶', $original_cost);

				if ($free_shipping) {
					$rates[$rate_key]->cost = 0;
					$rates[$rate_key]->label .= " {$strikethrough_price} (Free Shipping: 0)";
				} else {
					$discount_multiplier = (100 - $percentage_discount) / 100;
					$discounted_cost = round(floatval($rate->cost) * $discount_multiplier, 2);
					$rates[$rate_key]->cost = $discounted_cost;
					$rates[$rate_key]->label .= " {$strikethrough_price}";
				}
			}
		}

		$rates = apply_filters('bt_dynamic_courier_rates', $rates, $package, $bt_sst_courier_rate_provider);
		return $rates;

	}

	public function woocommerce_cart_totals_before_shipping()
	{
		$is_premium = $this->licenser->should_activate_premium_features();
		if (!$is_premium)
			return;
		$bt_sst_show_shipment_weight = carbon_get_theme_option("bt_sst_show_shipment_weight");
		if ($bt_sst_show_shipment_weight == 1) {
			$cart_totals = $this->get_cart_weight_and_dimentions();

			$weight_in_kg = $cart_totals['total_weight_kg'];
			// $length_in_cms=$cart_totals['total_length_cm'];
			// $breadth_in_cms=$cart_totals['total_width_cm'];
			// $height_in_cms=$cart_totals['total_height_cm'];
			// $declared_value=$cart_totals['declared_value'];
			$bt_sst_list_weight_unit = carbon_get_theme_option("bt_sst_list_weight_unit");
			if ($weight_in_kg && $weight_in_kg > 0 && $bt_sst_list_weight_unit != 'kg') {
				$weight_in_kg = new Mass($weight_in_kg, 'kg');
				$weight_in_kg = round($weight_in_kg->toUnit($bt_sst_list_weight_unit), 2);
			}

			if ($weight_in_kg && $weight_in_kg > 0) {
				echo '
						<tr>
							<th>Shipment Weight</th>
            				<td>' . esc_html($weight_in_kg) . ' ' . esc_html($bt_sst_list_weight_unit) . ' (approx)</td>
						</tr>
					';
			}
		}
	}

	function refresh_shipping_methods($post_data)
	{
		$is_premium = $this->licenser->should_activate_premium_features();
		if (!$is_premium)
			return;

		$payment_method = 'cod';
		$bool = true;

		if (WC()->session->get('chosen_payment_method') === $payment_method)
			$bool = false;

		// Mandatory to make it work with shipping methods
		foreach (WC()->cart->get_shipping_packages() as $package_key => $package) {
			WC()->session->set('shipping_for_package_' . $package_key, $bool);
		}
		WC()->cart->calculate_shipping();
	}

	function payment_methods_trigger_update_checkout()
	{
		$is_premium = $this->licenser->should_activate_premium_features();
		if (!$is_premium)
			return;
		?>
		<script type="text/javascript">
			(function ($) {
				$('form.checkout').on('change blur', 'input[name^="payment_method"]', function () {
					setTimeout(function () {
						$(document.body).trigger('update_checkout');
					}, 250);
				});
			})(jQuery);
		</script>
		<?php
	}

	private function get_cart_weight_and_dimentions()
	{
		$total_weight = null;
		$total_width = null;
		$total_length = null;
		$total_height = null;
		$declared_value = null;

		if (WC()->cart) {
			$cart = WC()->cart;

			if ($cart->get_total('value') <= 0) {
				$declared_value = $cart->cart_contents_total + $cart->tax_total - $cart->get_shipping_total();
			} else {
				$declared_value = $cart->get_total('value') - $cart->get_shipping_total();
			}

			if ($declared_value) {
				$declared_value = round($declared_value, 2);
			}
			if (!$cart->is_empty() && $cart->needs_shipping()) {
				foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
					$product_id = $cart_item['product_id'];
					$variation_id = $cart_item['variation_id']; // to do
					$quantity = $cart_item['quantity'];
					$product = wc_get_product($product_id);
					if (isset($variation_id) && $variation_id > 0) {
						$product = wc_get_product($variation_id);
					}
					if (!empty($product->get_weight()) && $product->get_weight() > 0) {
						$total_weight = $total_weight + ($product->get_weight() * $quantity);
					}
					if (!empty($product->get_width()) && $product->get_width() > 0) {
						$total_width = $total_width + ($product->get_width() * $quantity);
						if ($product->get_length() > $total_length) {
							$total_length = $product->get_length();
						}
						if ($product->get_height() > $total_height) {
							$total_height = $product->get_height();
						}
					}
				}
				$weight_unit = get_option('woocommerce_weight_unit');
				$dimension_unit = get_option('woocommerce_dimension_unit');

				if ($total_weight) {
					$total_weight = new Mass($total_weight, $weight_unit);
					$total_weight = round($total_weight->toUnit('kg'), 2);
				}
				if ($total_length) {
					$total_length = new Length($total_length, $dimension_unit);
					$total_length = round($total_length->toUnit('cm'), 2);
				} else {
					$total_length = 10;
				}
				if ($total_width) {
					$total_width = new Length($total_width, $dimension_unit);
					$total_width = $total_width->toUnit('cm');
				} else {
					$total_width = 10;
				}
				if ($total_height) {
					$total_height = new Length($total_height, $dimension_unit);
					$total_height = round($total_height->toUnit('cm'), 2);
				} else {
					$total_height = 10;
				}
			}
		}

		return array(
			'total_weight_kg' => $total_weight,
			'total_width_cm' => $total_width,
			'total_length_cm' => $total_length,
			'total_height_cm' => $total_height,
			'declared_value' => $declared_value,
		);
	}

	function woocommerce_default_address_fields($fields)
	{
		$is_premium = $this->licenser->should_activate_premium_features();
		if (!$is_premium)
			return $fields;
		$auto_fill = carbon_get_theme_option("bt_sst_auto_fill_city_state");
		if ($auto_fill == 1) {
			$fields['postcode']['priority'] = 65;
		}
		return $fields;
	}

	public function bt_shipping_tracking_form_2($atts, $content, $tag)
	{
		$atts = shortcode_atts(array(
			'order_id' => '',
			'email' => false
		), $atts);
		$args_order_id = $atts['order_id'];
		$email = $atts['email'];

		$bt_track_order_id = "";
		$the_order = false;
		$tracking = null;
		$bt_last_four_digit = '';
		$phone = '';
		$message = "";
		$auto_post = false;
		if (!empty($args_order_id)) {
			//order id is set in shortcode's argument, just show the tracking
			$bt_track_order_id = $args_order_id;
			$the_order = wc_get_order($bt_track_order_id);
			$last_four_digit = false;
			if ($the_order) {
				$tracking = bt_get_shipping_tracking($bt_track_order_id);
			}


		} else {
			//order id is not set in shortcode's argument
			if (!isset($_POST["bt_track_order_id"]) && isset($_GET["order"])) {
				$bt_track_order_id = sanitize_text_field($_GET["order"]);
				$auto_post = true;
			}

			if ($_POST && isset($_POST["bt_track_order_id"])) {
				if (!empty($awb_order_ids = Bt_Sync_Shipment_Tracking_Shipment_Model::get_orders_by_awb_number($_POST["bt_track_order_id"]))) {
					$i = 0;
					foreach ($awb_order_ids as $awb_order_id) {
						if ($i == 0) {
							$_POST["bt_track_order_id"] = $awb_order_id;
						}
						$i++;
					}
				}

				$auto_post = false;

				$bt_track_order_id = sanitize_text_field($_POST["bt_track_order_id"]);
				$nonce = sanitize_text_field($_POST["bt_tracking_form_nonce"]);
				if (wp_verify_nonce($nonce, 'bt_shipping_tracking_form_2')) {

					$the_order = wc_get_order($bt_track_order_id);

					$last_four_digit = carbon_get_theme_option('bt_sst_valid_phone_no');
					if (is_user_logged_in() && isset($_GET["order"])) {
						$last_four_digit = false;
					}
					if ($last_four_digit && $the_order != false) {
						if (!isset($_POST["bt_track_order_phone"]) || empty($_POST["bt_track_order_phone"])) {
							//reload tracking form to ask for phone number
							$the_order = false;
							$auto_post = true;
						} else {
							$bt_last_four_digit = sanitize_text_field($_POST["bt_track_order_phone"]);
							$phone = $the_order->get_billing_phone();
							$phone = substr($phone, -4);
							if ($phone != $bt_last_four_digit) {
								$the_order = false;
								$message = "Please enter the correct last 4 digit of phone number.";
							}
						}
					}

					if ($the_order) {
						$tracking = bt_get_shipping_tracking($bt_track_order_id);
					} else {
						$message = "You have entered a wrong Order Id or AWB No, please try again";
					}
				} else {
					$message = "Please refresh the page and try again.";
				}

			}
		}

		$is_premium = $this->licenser->should_activate_premium_features();
		if (!empty($args_order_id) && $email == true) {
			//to be embedded in an email
			ob_start();
			include plugin_dir_path(dirname(__FILE__)) . 'public/partials/bt_shipping_tracking_form_2_email.php';
			$result = ob_get_clean();
			return $result;
		} else {
			//to be embedded in a web page

			wp_enqueue_style('bt-sync-shipment-tracking-customer-shortcode-css');
			wp_enqueue_style('bt-sync-shipment-tracking-primery-template-form-2');

			$shipping_tracking_template = carbon_get_theme_option('bt_sst_tracking_page_template');
			if ($shipping_tracking_template == "trackingmaster" && $is_premium) {
				ob_start();
				include plugin_dir_path(dirname(__FILE__)) . 'public/partials/bt_shipping_tracking_page_template_second.php';
				$result = ob_get_clean();
				return $result;
			} else {
				ob_start();
				include plugin_dir_path(dirname(__FILE__)) . 'public/partials/bt_shipping_tracking_form_2.php';
				$result = ob_get_clean();
				return $result;
			}
		}


	}

	public function bt_shipment_tracking_url_shortcode_callback($atts, $content, $tag)
	{
		$atts = shortcode_atts(array(
			'order_id' => '',
			'type' => 'myaccount'
		), $atts);

		$args_order_id = $atts['order_id'];


		if (empty($args_order_id) && isset($order) && is_a($order, 'WC_Order')) {
			//get order id from current post
			$args_order_id = $order->get_id();

		}

		$result = "";
		if (!empty($args_order_id)) {
			$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($args_order_id);
			if (!empty($bt_shipment_tracking)) {
				//$response['tracking_data'] = (array)$bt_shipment_tracking;
				$result = $bt_shipment_tracking->get_tracking_link();
			}
		}
		//get tracking url of the order 

		return $result;

	}

	public function bt_shipment_status_shortcode_callback($atts, $content, $tag)
	{
		$atts = shortcode_atts(array(
			'order_id' => '',
		), $atts);

		$args_order_id = $atts['order_id'];


		if (empty($args_order_id) && isset($order) && is_a($order, 'WC_Order')) {
			//get order id from current post
			$args_order_id = $order->get_id();

		}

		$result = "";
		if (!empty($args_order_id)) {
			$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($args_order_id);
			if (!empty($bt_shipment_tracking)) {
				//$response['tracking_data'] = (array)$bt_shipment_tracking;
				$result = bt_format_shipment_status($bt_shipment_tracking->current_status);
			}
		}
		//get tracking url of the order 

		return $result;

	}

	public function bt_shipment_courier_name_shortcode_callback($atts, $content, $tag)
	{
		$atts = shortcode_atts(array(
			'order_id' => '',
		), $atts);

		$args_order_id = $atts['order_id'];


		if (empty($args_order_id) && isset($order) && is_a($order, 'WC_Order')) {
			//get order id from current post
			$args_order_id = $order->get_id();

		}

		$result = "";
		if (!empty($args_order_id)) {
			$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($args_order_id);
			if (!empty($bt_shipment_tracking)) {
				$result = $bt_shipment_tracking->courier_name;
			}
		}
		//get tracking url of the order 

		return $result;

	}

	public function bt_shipment_edd_shortcode_callback($atts, $content, $tag)
	{
		$atts = shortcode_atts(array(
			'order_id' => '',
		), $atts);

		$args_order_id = $atts['order_id'];


		if (empty($args_order_id) && isset($order) && is_a($order, 'WC_Order')) {
			//get order id from current post
			$args_order_id = $order->get_id();

		}

		$result = "";
		if (!empty($args_order_id)) {
			$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($args_order_id);
			if (!empty($bt_shipment_tracking)) {
				$result = $bt_shipment_tracking->etd;
			}
		}
		//get tracking url of the order 

		return $result;

	}

	public function bt_shipment_awb_shortcode_callback($atts, $content, $tag)
	{
		$atts = shortcode_atts(array(
			'order_id' => '',
		), $atts);

		$args_order_id = $atts['order_id'];


		if (empty($args_order_id) && isset($order) && is_a($order, 'WC_Order')) {
			//get order id from current post
			$args_order_id = $order->get_id();

		}

		$result = "";
		if (!empty($args_order_id)) {
			$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($args_order_id);
			if (!empty($bt_shipment_tracking)) {
				$result = $bt_shipment_tracking->awb;
			}
		}
		//get tracking url of the order 

		return $result;

	}

	// Shortcode function for shipment timer
	public function bt_shipment_timer_shortcode_callback($atts, $content = null, $tag = '')
	{
		wp_enqueue_style('bt-shipment-tracking-timing-css');
		wp_enqueue_script('bt-shipment-tracking-timing-js');
		// Set default values and extract attributes
		$atts = shortcode_atts(
			array(
				'hours' => 0,  // Default: 0 hours
				'minutes' => 59, // Default: 59 minutes
				'seconds' => 59  // Default: 59 seconds
			),
			$atts,
			'bt_free_shipping_timer'
		);

		$saved_data = get_option('bt_sst_timer_settings');

		$saved_data = array_merge([
			'bt_sst_quill_editer_html' => 'heading',
			'bt_sst_quill_editer_html_subheading' => 'sub heading',
			'bt_sst_timer_location' => '',
			'bt_sst_timer_hours' => '01',
			'bt_sst_timer_minutes' => '59',
			'bt_sst_timer_seconds' => '59',
			'set_timing_cookie' => 'no',
			'free_shipping' => 'no',
			'discount_percentage' => '',
		], $saved_data);

		// 3. Validate and retrieve timer values
		$timerHours   = isset($saved_data['bt_sst_timer_hours']) && is_numeric($saved_data['bt_sst_timer_hours']) ? (int) $saved_data['bt_sst_timer_hours'] : 0;
		$timerMinutes = isset($saved_data['bt_sst_timer_minutes']) && is_numeric($saved_data['bt_sst_timer_minutes']) ? (int) $saved_data['bt_sst_timer_minutes'] : 0;
		$timerSeconds = isset($saved_data['bt_sst_timer_seconds']) && is_numeric($saved_data['bt_sst_timer_seconds']) ? (int) $saved_data['bt_sst_timer_seconds'] : 0;

		// 4. Calculate total countdown duration in seconds
		$total_seconds = ($timerHours * 3600) + ($timerMinutes * 60) + $timerSeconds;
		ob_start();

		// Include the external file and pass attributes
		include plugin_dir_path(dirname(__FILE__)) . 'public/timer/bt_sst_shipment_tracker_timer.php';

		// Capture output
		return ob_get_clean();
	}

	public function woocommerce_email_format_string_shipment_placeholders_callback($string, $email)
	{
		// Get WC_Order object from email
		$order = $email->object;

		// Add new placeholders
		$new_placeholders = array(
			'{bt_shipment_tracking_url}' => "",
			'{bt_shipment_status}' => "",
			'{bt_shipment_courier_name}' => "",
			'{bt_shipment_edd}' => "",
			'{bt_shipment_awb}' => "",
		);

		if (is_a($order, 'WC_Order')) {
			$args_order_id = $order->get_id();
			$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($args_order_id);
			if (!empty($bt_shipment_tracking)) {
				$new_placeholders = array(
					'{bt_shipment_tracking_url}' => $bt_shipment_tracking->get_tracking_link(),
					'{bt_shipment_status}' => bt_format_shipment_status($bt_shipment_tracking->current_status),
					'{bt_shipment_courier_name}' => $bt_shipment_tracking->courier_name,
					'{bt_shipment_edd}' => $bt_shipment_tracking->etd,
					'{bt_shipment_awb}' => $bt_shipment_tracking->awb,
				);
			}

		}


		// return the string with new placeholder replacements
		return str_ireplace(array_keys($new_placeholders), array_values($new_placeholders), $string);
	}





	public function woocommerce_order_details_after_customer_details($order)
	{

		$bt_sst_shipment_info_myaccount_order_detail = carbon_get_theme_option('bt_sst_shipment_info_myaccount_order_detail');
		if ($bt_sst_shipment_info_myaccount_order_detail == 1) {

			$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($order->get_id());
			$bt_shipping_provider = $bt_shipment_tracking->shipping_provider;
			echo '<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
				<h2 class="woocommerce-column__title">Shipment Details</h2>
				<address>';
			include plugin_dir_path(dirname(__FILE__)) . 'public/partials/order_shipment_details.php';
			echo '</address></div><!-- /.col-2 -->';

		}
	}

	public function add_track_button_to_my_orders($actions, $order)
	{
		$bt_sst_show_tracking_now_button_myaccount_order_list = carbon_get_theme_option('bt_sst_show_tracking_now_button_myaccount_order_list');
		$is_premium = $this->licenser->should_activate_premium_features();
		if ($bt_sst_show_tracking_now_button_myaccount_order_list == 1) {
			$myaccount_url = wc_get_page_permalink('myaccount');
			$tracking_url = $myaccount_url . '/bt-track-order?order=' . $order->get_id();

			if ($tracking_url) {
				$actions['bt_sst_track'] = array(
					'url' => $tracking_url,
					'name' => __('Track', 'woocommerce')
				);
			}
		}
		return $actions;
	}

	public function add_query_vars($vars)
	{
		$vars[] = 'bt-track-order';
		return $vars;
	}

	public function woocommerce_account_track_order_endpoint()
	{
		$bt_sst_show_tracking_now_button_myaccount_order_list = carbon_get_theme_option('bt_sst_show_tracking_now_button_myaccount_order_list');
		$is_premium = $this->licenser->should_activate_premium_features();
		if ($bt_sst_show_tracking_now_button_myaccount_order_list == 1) {
			echo do_shortcode('[bt_shipping_tracking_form_2]');
		}
	}

	// function custom_dokan_order_details( $order ) {
	// 	// Check if the order object exists
	// 	if ( ! is_a( $order, 'WC_Order' ) ) {
	// 		return;
	// 	}
	// 	$post_id = $order->get_id();
	// 	$order_id = $order->get_id();
	// 	if(empty($post_id)){
	// 		$post_id = $_GET["id"];
	// 	}	

	//     $bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($post_id);
	// 	$bt_shipping_provider = $bt_shipment_tracking->shipping_provider;
	// 	$bt_shipping_awb_number = $bt_shipment_tracking->awb;

	// 	// $shipping_mode_is_manual_or_ship24 = carbon_get_theme_option( 'bt_sst_enabled_custom_shipping_mode' );
	// 	$shipping_mode_is_manual_or_ship24 = Bt_Sync_Shipment_Tracking::bt_sst_get_order_meta($post_id, '_bt_sst_custom_shipping_mode', true);

	// 	include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/bt-woocommerce-order-actions-end.php';

	//     if($bt_shipping_provider == 'manual' && $shipping_mode_is_manual_or_ship24 =="manual"){
	//         include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/bt-shipment-tracking-manual-metabox.php';
	//     } else if($bt_shipping_provider == 'shiprocket' || $bt_shipping_provider == 'shyplite'|| $bt_shipping_provider == 'nimbuspost'|| $bt_shipping_provider == 'xpressbees' || $bt_shipping_provider == 'shipmozo'|| $bt_shipping_provider == 'nimbuspost_new'|| $bt_shipping_provider == 'delhivery' || $shipping_mode_is_manual_or_ship24=="ship24") {
	//         include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/bt-shipment-tracking-metabox.php';
	//     }

	// }
	function add_tracking_data_after_order_in_email($order)
	{
		$bt_sst_shipment_info_in_woocommerce = carbon_get_theme_option('bt_sst_shipment_info_in_woocommerce');

		if ($bt_sst_shipment_info_in_woocommerce == 1) {
			$bt_shipment_tracking = Bt_Sync_Shipment_Tracking_Shipment_Model::get_tracking_by_order_id($order->get_id());
			$bt_shipping_provider = $bt_shipment_tracking->shipping_provider;
			if (!empty($bt_shipment_tracking) && $bt_shipment_tracking instanceof Bt_Sync_Shipment_Tracking_Shipment_Model && !empty($bt_shipment_tracking->awb)) {

				echo '<table id="m_-3526290118834812421addresses" cellspacing="0" cellpadding="0" border="0" style="width:100%;vertical-align:top;margin-bottom:40px;padding:0" width="100%">
						<tbody>
							<tr>
								<td valign="top" width="50%" style="text-align:left;font-family:\'Helvetica Neue\',Helvetica,Roboto,Arial,sans-serif;border:0;padding:0" align="left">
									<h2 style="color:#7f54b3;display:block;font-family: \'Helvetica Neue\',Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">Order Tracking</h2>
									<div style="padding: 12px;color: #636363;border: 1px solid #e5e5e5;">';
				include plugin_dir_path(dirname(__FILE__)) . 'public/partials/order_shipment_details.php';
				echo '</div>
								</td>
							</tr>
						</tbody>
					</table>';
			} else {
				$tracking_page_id = get_option('_bt_sst_tracking_page');
				$link = get_permalink($tracking_page_id);
				$separator = (strpos($link, '?') !== false) ? '&' : '?';
				$full_url = $link . $separator . 'order=' . $order->get_id();
				echo '<table id="m_-3526290118834812421addresses" cellspacing="0" cellpadding="0" border="0" style="width:100%;vertical-align:top;margin-bottom:40px;padding:0" width="100%">
						<tbody>
							<tr>
								<td valign="top" width="50%" style="text-align:left;font-family:\'Helvetica Neue\',Helvetica,Roboto,Arial,sans-serif;border:0;padding:0" align="left">
									<h2 style="color:#7f54b3;display:block;font-family: \'Helvetica Neue\',Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">Order Tracking</h2>
									<div style="padding: 12px;color: #636363;border: 1px solid #e5e5e5;">
										<a href = "' . esc_url($full_url) . '"> Track Order </a>
									</div>
								</td>
							</tr>
						</tbody>
					</table>';
			}
		}
	}

	public function custom_save_shipping_rate_meta_to_order($item, $package_key, $package, $order)
	{
		$selected_label = $item->get_name();
		foreach ($package['rates'] as $rate_id => $rate_obj) {
			if ($selected_label === $rate_obj->get_label()) {
				$meta_data = $rate_obj->get_meta_data();
				$order->update_meta_data('esimated_delivery_date', $meta_data['edd']->date);
			}
		}
	}



}