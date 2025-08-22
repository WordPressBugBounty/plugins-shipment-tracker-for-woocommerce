<?php

$selected_messages = carbon_get_theme_option('bt_sst_shipment_when_to_send_messages', array());
// var_dump($selected_messages); die;
$woocommerce_email_settings_url = admin_url('admin.php?page=wc-settings&tab=email&section=bt_sst_wc_shipment_email');

$all_options = [
	'new_order' => 'New Order',
	'failed_order' => 'Failed Order',
	'canceled_order' => 'Canceled Order',
	'in_transit' => 'In Transit',
	'out_for_delivery' => 'Out For Delivery',
	'delivered' => 'Delivered',
	'review_after_delivery' => 'Review After Delivery (Sent 2 hrs after delivery)',
];

$selected_methods1 = carbon_get_theme_option('bt_sst_shipment_from_what_send_messages', array());
$bt_sst_sms_review_url = carbon_get_theme_option('bt_sst_sms_review_url') ?? "";

$method_options = [
	'sms' => 'SMS',
	'email' => 'Email',
	'whatsapp' => 'WhatsApp',
	'push_notification' => 'Push Notification (Coming Soon)',
];
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$input = $_POST['carbon_fields_compact_input'] ?? [];
	if (!empty($input['_bt_sst_shipment_when_to_send_messages'])) {
		$when_messages_send = array_map('sanitize_text_field', $input['_bt_sst_shipment_when_to_send_messages']);
		carbon_set_theme_option('bt_sst_shipment_when_to_send_messages', $when_messages_send);
	}

	if (!empty($input['_bt_sst_shipment_from_what_send_messages'])) {
		$send_messages_via = array_map('sanitize_text_field', $input['_bt_sst_shipment_from_what_send_messages']);
		carbon_set_theme_option('bt_sst_shipment_from_what_send_messages', $send_messages_via);
	}
	if (!empty($input['_bt_sst_sms_review_url'])) {
		carbon_set_theme_option('bt_sst_sms_review_url', $input['_bt_sst_sms_review_url']);
	}
	header("Location: " . $_SERVER["REQUEST_URI"]);

}
// $check_skip_or_not = get_option('bt_sst_check_skip_or_not');
$check_skip_or_not = false;
// $check_active_registration = false;

include plugin_dir_path(dirname(__FILE__)) . '../partials/new_settings/popup.php';

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
							<article class="tile is-child notification is-warning has-text-centered">
								<p id="orders_received_last_x_days" class="title">-</p>
								<p class="subtitle">
									orders received in last 7 days
								</p>
							</article>
						</div>
						<div class="tile is-parent">
							<a id="orders_waiting_to_be_shipped_link"
								class="tile is-child notification is-danger has-text-centered" href="#">
								<p id="orders_waiting_to_be_shipped" class="title">-</p>
								<p class="subtitle">
									waiting to be shipped
								</p>
							</a>
						</div>
						<div class="tile is-parent">
							<article class="tile is-child notification is-success has-text-centered">
								<p id="orders_not_delivered_after_10_days" class="title">-</p>
								<p class="subtitle">delayed beyond edd</p>
							</article>
						</div>
						<div class="tile is-parent">
							<article class="tile is-child notification is-primary has-text-centered">
								<p id="orders_marked_as_rto" class="title">-</p>
								<p class="subtitle">returning to origin</p>
							</article>
						</div>
					</div>
					<div>generated key (key)<button></button></div>
				</div>
			<?php } ?>
		</nav>
		
			<div class="cf-container__fields">
				<div class="cf-field cf-html">
					<div class="cf-field__body">
						<div class="cf-html__content">
							<div class="container">
								
								<div class="tile is-ancestor mt-2 p-3">
									<div class="tile is-parent">
										<article class="tile is-child notification px-1 is-warning">
											<p id="bt_sms_credit_bal" class="title has-text-centered">NA</p>
											<p id="bt_sms_buy_credits" class="subtitle has-text-centered px-0">Credits
												Balance <br>
												<a class="js-modal-trigger" data-target="bt_sst_buy_credits_modal">Buy
													Credits</a>
											</p>
										</article>
									</div>
									<div class="tile is-parent">
										<article class="tile is-child notification px-1 is-danger">
											<p id="bt_sms_credit_consume" class="title has-text-centered">NA</p>
											<p class="subtitle has-text-centered px-0">Credits Consumed <br> (Last 7
												Days)
											</p>
										</article>
									</div>
									<div class="tile is-parent">
										<article class="tile is-child notification px-1 is-success">
											<p id="bt_sms_sent" class="title has-text-centered">NA</p>
											<p class="subtitle has-text-centered px-0">SMS Sent <br> (Last 7 Days)</p>
										</article>
									</div>
									<div class="tile is-parent">
										<article class="tile is-child notification px-1 is-primary">
											<p id="bt_sms_last_sent_time" class="title has-text-centered">NA</p>
											<p class="subtitle has-text-centered px-0">(Last SMS Sent)</p>
										</article>
									</div>
								</div>
								<?php if (empty($check_active_registration)) : ?>
									<div class="is-overlay " style="background:rgba(0, 0, 0, 0.80);">
										<div class="columns is-vcentered" style="height: 100%;">
											<div class="column">
												<div class="has-text-centered ">
													<p class="title has-text-white is-5 ">Activate SMS/WhatsApp Messaging
														<button class="button " onclick="jQuery('#thankYouModal').addClass('is-active');">
															<span>Register Now</span>
														</button>
													</p>
													
												</div>
											</div>
										</div>
									</div>
								<?php endif ?>
							</div>

						</div>
					</div>
				</div>
				<div class="cf-field cf-set box">
					<?php 
					$register_for_sms_form = "";
					echo $register_for_sms_form ?>
					*Credits are used to send Order/Shipment Notification via SMS/WhatsApp to customers.<br>
					To see message sent history and credit usage logs, please login into: <a
						href="https://quickengage.bitss.in/Auth/Loginwithotp?email=<?php echo esc_html( rawurlencode(get_bloginfo('admin_email')) ); ?>"
						target="_blank">Quick Engage Messaging Portal</a>
					<?php
					if (isset($_POST['remove_generated_key'])) {
						update_option('register_for_sms_apy_key', '');
					}
					if (!empty($check_active_registration)) : ?>
						<div class="is-primary is-flex is-justify-content-space-between is-align-items-center">
							<div>
								
								<form method="post">
									<input type="hidden" name="remove_generated_key" value="1">
									Your Api Key: <span class="tag is-rounded"><?php echo esc_html($check_active_registration); ?> </span>
									<button type="submit" class=" button is-text is-small" title="Remove" id="bt_sst_remove_sms_key_btn">Remove Key</button>							
								</form>
							</div>
							
						</div>
					<?php endif; ?>
					</div>
					<div class="container">
						<form method="post" class="cf-form">
							<div class="cf-field cf-set box">
								<div class="cf-field__head"><label class="cf-field__label" for="cf-guE3uPhflWAqk9HhRfjnT">When do
										you want to send SMS/WhatsApp message?</label></div>
								<div class="cf-field__body">
									<ul class="cf-set__list">
										<?php foreach ($all_options as $value => $label): ?>
											<?php
											$id = 'cf-' . uniqid() . '-' . $value;
											$checked = in_array($value, $selected_messages) ? 'checked' : '';
											?>
											<li class="cf-set__list-item">
												<input type="checkbox" id="<?php echo esc_attr($id); ?>"
													name="carbon_fields_compact_input[_bt_sst_shipment_when_to_send_messages][]"
													class="cf-set__input" value="<?php echo esc_attr($value); ?>" <?php echo $checked; ?>>
												<label class="cf-set__label" for="<?php echo esc_attr($id); ?>">
													<?php echo esc_html($label); ?>
												</label>
											</li>
										<?php endforeach; ?>
									</ul>
									<div id="review-url-field" class="cf-field mt-4" style="display: none;">
										<div class="cf-field__head">
											<label class="cf-field__label" for="cf-Mh9phr8q558uUGntG4Eu9">Enter Review URL</label>
										</div>
										<div class="cf-field__body">
											<input type="url" id="cf-Mh9phr8q558uUGntG4Eu9"
												name="carbon_fields_compact_input[_bt_sst_sms_review_url]" class="cf-text__input"
												value="<?php echo $bt_sst_sms_review_url ?>">
										</div>
										<em class="cf-field__help">
											This link will be sent to the customer after 2 hours of delivery. It can be your Google
											Review URL or your website's URL where the customer can leave feedback.
										</em>
									</div>
								</div>
							</div>
							<div class="cf-field cf-set box">
								<div class="cf-field__head"><label class="cf-field__label" for="cf-JnCJcsCtG8UNTQazjeTm9">Send
										Message
										Via</label></div>
								<div class="cf-field__body">
									<ul class="cf-set__list">
										<?php foreach ($method_options as $value => $label): ?>
											<?php
											$id = 'cf-' . uniqid() . '-' . $value;
											$checked = in_array($value, $selected_methods1) ? 'checked' : '';
											?>
											<li class="cf-set__list-item">
												<input type="checkbox" id="<?php echo esc_attr($id); ?>"
													name="carbon_fields_compact_input[_bt_sst_shipment_from_what_send_messages][]"
													class="cf-set__input" value="<?php echo esc_attr($value); ?>" <?php echo $checked; ?>>
												<label class="cf-set__label" for="<?php echo esc_attr($id); ?>">
													<?php echo esc_html($label); ?>
												</label>
											</li>
										<?php endforeach; ?>
										<li style="font-size: 12px;">
											Configure Email in <a href="<?php echo $woocommerce_email_settings_url ?>"
												target="_blank">WooCommerce Email Settings</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="box">
							<button class="button is-primary is-rounded is-medium mt-4"
								id="bt_sst_update_sms_and_mail_setting">Update Settings</button>
							</div>
						</form>
					</div>
				<div class="cf-field cf-html box">
				
					<div class="cf-field__body">
						<div class="cf-html__content">
							<h5 class="title is-6">Try SMS:</h5>
							<div class="columns">
								<div class="column is-narrow">
									<img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__))) . '../images/official dlt telemarketer.png'; ?>"
										alt="SMS Sending" style="width: 100px; height: auto;">
								</div>
								<div class="column ">
									<div class="field has-addons ">
										<p class="control">
											<span class="select  is-medium">
												<select id="myselect">
													<option value="new-order">New Order</option>
													<option value="failed-order">Failed Order</option>
													<option value="canceled-order">Canceled Order</option>
													<option value="in-transit"> In Transit</option>
													<option value="out-for-delivery">Out for Delivery</option>
													<option value="delivered">Delivered</option>
													<option value="abandoned-cart">Abandoned Cart</option>
													<option value="review-after-delivery">Review after Delivery</option>
												</select>
											</span>
										</p>
										<div class="control is-expanded">
											<input id="bt_sst_test_phone_otp" class="input is-medium" name="bt_sst_number"
												type="text" value="" placeholder="Enter mobile number with country code">
										</div>
										<div class="field is-horizontal"
											style="width: 100%; max-width: 150px; text-align: center;">
											<div class="field-body" style="width: 100%;">
												<div class="field" style="width: 100%;">
													<div class="control">
														<button type="button" class=" button is-medium" id="get_sms_trial"
															style="width: 100%;">
															Send <span><i data-lucide="send" style="width:18px;padding-top: 8px;"></i> </span>
														</button>
														<div id="get_sms_trial_test_connection_modal" class="modal">
															<div class="modal-background"></div>
															<div class="modal-card">
																<header class="modal-card-head">
																	<p id="get_sms_trial_tc-m-content"
																		class="modal-content">
																	</p>
																	<button type="button" id="api_tc_m_close_btn"
																		class="delete" aria-label="close"></button>
																</header>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<p class="help is-info">
										<strong><a target="_blank" href="https://shipment-tracker-for-woocommerce.bitss.tech/pricing-plans/">Buy Premium</a></strong> to send SMS Messages using your own brand name. <br>
										Supported Countries: <b>All</b>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="cf-field cf-html box">
					<h5 class="title is-6">Try WhatsApp:</h5>
					<div class="columns">
						<div class="column is-narrow">
							<img src="<?php echo esc_url(plugin_dir_url(dirname(__FILE__))) . '../images/official whatsapp tech provider.png'; ?>"
								alt="SMS Sending" style="width: 100px; height: auto;">
						</div>
						<div class="column ">
							<div class="field has-addons ">
								<p class="control">
									<span class="select  is-medium">
										<select id="myselect_whatsapp">
											<option value="new-order">New Order</option>
											<option value="in-transit"> In Transit</option>
											<option value="out-for-delivery">Out for Delivery</option>
											<option value="delivered">Delivered</option>
											<option value="review-after-delivery">Review after Delivery</option>
										</select>
									</span>
								</p>
								<div class="control is-expanded">
									<input id="bt_otpfy_test_whatsapp_mobile" class="input is-medium"
										name="bt_otpfy_test_whatsapp_mobile" type="text" value=""
										placeholder="Enter mobile number with country code">
								</div>
								<div class="field is-horizontal" style="width: 100%; max-width: 150px; text-align: center;">
									<div class="field-body" style="width: 100%;">
										<div class="field" style="width: 100%;">
											<div class="control">
												<button type="button" class=" button is-medium" id="get_whatsapp_trial"
													style="width: 100%;">
													Send <span><i data-lucide="send" style="width:18px;padding-top: 8px;"></i> </span>
												</button>
												<div id="get_whatsapp_trial_test_connection_modal" class="modal">
													<div class="modal-background"></div>
													<div class="modal-card">
														<header class="modal-card-head">
															<p id="get_whatsapp_trial_tc-m-content" class="modal-content">
															</p>
															<button type="button" id="api_tc_m_close_btn" class="delete"
																aria-label="close"></button>
														</header>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<p class="help is-info">
								<strong><a target="_blank" href="https://shipment-tracker-for-woocommerce.bitss.tech/pricing-plans/">Buy Premium</a></strong>
								to send WhatsApp messages using your own brand name. 
								<br>
								Supported Countries: <b>All</b></p>
						</div>
					</div>
				</div>
				<div class="cf-field cf-html">
					
					<div class="cf-field__body">
						<div class="cf-html__content">
							<h5 class="title is-6">Email:</h5>
							<div class="column ">
								<div class="field has-addons " style="display:none">
									<p class="control">
										<span class="select  is-medium">
											<select id="bt_sst_test_email_event">
												<option value="in-transit">In Transit</option>
												<option value="out-for-delivery">Out for Delivery</option>
												<option value="delivered">Delivered</option>
											</select>
										</span>
									</p>
									<div class="control is-expanded">
										<input id="bt_sst_test_email" class="input is-medium" name="bt_sst_test_email"
											type="email" value="" placeholder="Enter email address">
									</div>
									<div class="field is-horizontal"
										style="width: 100%; max-width: 150px; text-align: center;">
										<div class="field-body" style="width: 100%;">
											<div class="field" style="width: 100%;">
												<div class="control">
													<button type="button" class=" button is-medium"
														id="bt_sst_test_email_send_btn" style="width: 100%;">
														Send Email
													</button>
													<div id="bt_sst_test_email_modal" class="modal">
														<div class="modal-background"></div>
														<div class="modal-card">
															<header class="modal-card-head">
																<p id="bt_sst_test_email_m_content"
																	class="modal-content">
																</p>
															</header>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php
									$admin_url = admin_url('admin.php?page=wc-settings&tab=email&section=bt_sst_wc_shipment_email');
									echo '<p class="help is-info">You can customize the email template from <a target="_blank" href="' . esc_url($admin_url) . '">WooCommerce settings</a>.</p>';
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="cf-field cf-html">
				
					<div class="cf-field__body">
						<div class="cf-html__content">
							<div class="box">
							<h3 class="title is-5">Note:</h3>
							<h4 class="title is-6">SMS/WhatsApp are chargeable and requires registration. You can buy messaging credits from within this plugin.</h4>
							<h4 class="title is-6">SMS/WhatsApp messages are sent using pre-approved, co-branded DLT/Meta templates. For sending fully branded sms using your brand name, <strong><a target="_blank" href='https://shipment-tracker-for-woocommerce.bitss.tech/pricing-plans/'>Buy Premium</a></strong>.</h4>
							<h4 class="title is-6">Need a Full Featured SMS Panel for advanced SMS Messaging? <a target="_blank" href="https://smsapi.bitss.tech">Signup here for fast, reliable & cost effective sms service.</a></h4>
							<h4 class="title is-6">Emails are free and are sent using your own server. Email template can be customized from <a target="_blank"  href="/wp-admin/admin.php?page=wc-settings&tab=email&section=bt_sst_wc_shipment_email">woocommerce email settings</a>.</h4>
						</div>
						</div>
					</div>
				</div>
				
			</div>
	
	</div>
</div>
<style>
	.subtitle {
		padding-left: 0;
	}
</style>
<script>
	document.addEventListener("DOMContentLoaded", function () {
		// Use startsWith in case the checkbox ID has dynamic uniqid()
		const checkboxes = document.querySelectorAll('input[type="checkbox"][name="carbon_fields_compact_input[_bt_sst_shipment_when_to_send_messages][]"]');
		const reviewCheckbox = Array.from(checkboxes).find(cb => cb.value === 'review_after_delivery');
		const reviewField = document.getElementById('review-url-field');

		if (reviewCheckbox) {
			const toggleReviewField = () => {
				reviewField.style.display = reviewCheckbox.checked ? 'block' : 'none';
			};

			// Toggle on page load
			toggleReviewField();

			// Toggle on change
			reviewCheckbox.addEventListener('change', toggleReviewField);
		}
	});
</script>