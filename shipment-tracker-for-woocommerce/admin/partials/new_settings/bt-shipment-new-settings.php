<?php
$check_active_registration = get_option('register_for_sms_apy_key');
$check_skip_or_not = get_option('bt_sst_check_skip_or_not');
$shipment_setting_url = admin_url('admin.php?page=crb_carbon_fields_container_shipment_tracking.php');
$notification_url = admin_url('admin.php?page=bt-shipment-tracking-sms-setting');
$timer_url = admin_url('admin.php?page=bt-shipment-tracking-timer');
?>

<div ng-app="bt-sst-settings" class="bt-sst-settings wrap container is-max-desktop has-background-white mt-5 p-5">
	<div ng-controller="MainController" class="container">
		<nav class="panel is-dark">
			<div class="panel-heading">
				<div class="level">
					<div class="level-left">
						<div class="level-item">
							<div>
								<p class="title has-text-white">Shipment Tracker For Woocommerce</p>
								<p class="heading has-text-grey-light">Premium Shipment Tracking System (Free Version)
								</p>
							</div>
						</div>
					</div>
					<div class="level-right">
						<div class="level-item">
							<a href="#" class="js-modal-trigger" data-target="modal-bt-company-about">
								<figure class="image is-48x48">
									<img alt="Bitss Techniques"
										src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__))) . '../images/Bitss-Techniques-Logo-80x80.png'; ?>"></img>
								</figure>
							</a>
						</div>
					</div>
				</div>
			</div>
			<?php
			if ($active_tab == 'dashboard') {
				include plugin_dir_path(dirname(__FILE__)) . '../partials/new_settings/popup.php';
				?>
				<div class="level has-background-info-light p-3">
					<div class="level-item has-text-centered">
						<p id="bt_sst_introduction_message" class="level">
							<br>
							<button class="button is-small is-ghost ml-2 is-loading"></button>
						</p>
					</div>
				</div>

				<!-- OTP & Credit Info -->
				<div class="container">
					<div class="tile is-ancestor mt-2 p-3">
						<div class="tile is-parent">
							<article class="tile is-child notification is-light  is-primary  has-text-centered">
								<i data-lucide="package-plus" class="icon-lg"></i>
								<p id="orders_received_last_x_days" class="title">-</p>
								<p class="subtitle is-6">
									orders received in last 30 days
								</p>
							</article>
						</div>
						<div class="tile is-parent">
							<a id="orders_waiting_to_be_shipped_link"
								class="tile is-child notification is-warning  is-light  has-text-centered" href="#">
								<i data-lucide="package-2" class="icon-lg"></i>
								<p id="orders_waiting_to_be_shipped" class="title">-</p>
								<p class="subtitle is-6">
									waiting to be shipped
								</p>
							</a>
						</div>
						<div class="tile is-parent">
							<article class="tile is-child notification is-warning is-light has-text-centered">
								<i data-lucide="truck" class="icon-lg"></i>
								<p id="orders_in_transit" class="title">-</p>
								<p class="subtitle is-6">shipped & in-transit</p>
							</article>
						</div>
						<div class="tile is-parent">
							<article class="tile is-child notification is-warning is-light has-text-centered">
								<i data-lucide="package-search" class="icon-lg"></i>
								<p id="get_delayed_orders_list" class="title">-</p>
								<p class="subtitle is-6">delayed beyond edd</p>
							</article>
						</div>
						<div class="tile is-parent">
							<article class="tile is-child notification is-light  is-info has-text-centered">
								<i data-lucide="package-check" class="icon-lg"></i>
								<p id="get_delivered_orders_count" class="title">-</p>
								<p class="subtitle is-6">delivered to customer</p>
							</article>
						</div>
					</div>
				</div>
				<?php
				include plugin_dir_path(dirname(__FILE__)) . '../partials/new_settings/bt-shipment-dashboard.php';
			}if ($active_tab == 'timer_tab') {
				include plugin_dir_path(dirname(__FILE__)) . '../partials/new_settings/bt-shipment-timer_setting_tab.php';
			} elseif ($active_tab == 'premium_tab') {
				include plugin_dir_path(dirname(__FILE__)) . '../partials/new_settings/bt-shipment-premium_setting_tab.php';
			}elseif ($active_tab == 'help_tab') {
				include plugin_dir_path(dirname(__FILE__)) . '../partials/new_settings/bt-shipment-help.php';
			}
			?>
		</nav>
	</div>
</div>
<style>
	.bt-sst-settings .subtitle {
		padding-left: 0;
	}

	.bt-sst-settings .icon-lg {
		width: 60px;
		height: 60px;
		opacity: 0.6;
		stroke: black;
		mix-blend-mode: multiply;
	}
</style>