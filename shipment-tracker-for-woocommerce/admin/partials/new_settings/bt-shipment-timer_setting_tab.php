<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $form_data = [
          'bt_sst_quill_editer_html' => isset($_POST['bt_sst_quill_editer_html']) ? wp_kses_post($_POST['bt_sst_quill_editer_html']) : '',
          'bt_sst_quill_editer_html_subheading' => $_POST['bt_sst_quill_editer_html_subheading'] ?? '',
          'bt_sst_timer_location' => $_POST['bt_sst_timer_enable'] === 'yes' ? $_POST['bt_sst_timer_location'] : '',
          'bt_sst_timer_hours' => $_POST['bt_sst_timer_hours'] ?? '',
          'bt_sst_timer_minutes' => $_POST['bt_sst_timer_minutes'] ?? '',
          'bt_sst_timer_seconds' => $_POST['bt_sst_timer_seconds'] ?? '',
          'set_timing_cookie' => $_POST['set_timing_cookie'] ?? '',
          'free_shipping' => $_POST['free_shipping'] ?? '',
          'discount_percentage' => $_POST['discount_percentage'] ?? '',
          'selected_color_timer_count_down' => $_POST['selected_color_timer_count_down'] ?? '',
          'selected_color_timer_container' => $_POST['selected_color_timer_container'] ?? '',
          'bt_sst_timer_enable' => $_POST['bt_sst_timer_enable'] === 'yes' ? true : false,
      ];
      update_option('bt_sst_timer_settings', $form_data);
      $currentTime = time();
      setcookie('bt_sst_start_time', $currentTime, time() + (24 * 60 * 60), "/");
      header("Location: " . $_SERVER["REQUEST_URI"]);
  }
  $saved_data = get_option('bt_sst_timer_settings', []);

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
      'selected_color_timer_count_down' => '',
      'selected_color_timer_container' => '',
      'bt_sst_timer_enable' => false,
  ], $saved_data);

  $locations = [
      '' => 'None',
      'woocommerce_before_single_product_summary' => 'Before single product summary',
      'woocommerce_single_product_summary' => 'Single product summary',
      'woocommerce_before_add_to_cart_form' => 'Before add to cart form',
      'woocommerce_before_add_to_cart_button' => 'Before add to cart button',
      'woocommerce_before_add_to_cart_quantity' => 'Before add to cart quantity',
      'woocommerce_after_add_to_cart_button' => 'After add to cart button',
      'woocommerce_after_add_to_cart_form' => 'After add to cart form',
      'woocommerce_product_meta_start' => 'Product meta start',
      'woocommerce_product_meta_end' => 'Product meta end',
      'woocommerce_after_single_product_summary' => 'After single product summary',
  ];
?>
<div class="section">
  <div class="container">
    <div class="columns is-variable is-6 is-desktop">
      
      <!-- Left Column: Settings -->
      <div class="column is-half">
        <div class="box">
          <div class="panel-heading">
            <div class=" title is-5">
              Free Shipping Timer
            </div>
          </div>
          <div class="panel-block">

            <form id="bt_sst_quill_editer_form" method="POST">

              <!-- Hidden Fields -->
              <input type="hidden" id="bt_sst_quill_editer_html" name="bt_sst_quill_editer_html" value="<?= esc_attr($saved_data['bt_sst_quill_editer_html'] ?? ''); ?>" />
              <input type="hidden" id="bt_sst_quill_editer_html_subheading" name="bt_sst_quill_editer_html_subheading" value="<?= esc_attr($saved_data['bt_sst_quill_editer_html_subheading'] ?? ''); ?>" />

              <!-- Enable Timer -->
              <div class="field">
                <label class="label">Enable "Free Shipping" Timer</label>
                <label class="switch is-rounded">
                  <input type="checkbox" name="bt_sst_timer_enable" value="yes" <?= checked($saved_data['bt_sst_timer_enable'], true, false); ?>>
                  <span class="check"></span>
                </label>
              </div>

                      <!-- Timer Time -->
              <div class="field">
                <label class="label">Timer (HH:MM:SS)</label>
                <div class="field has-addons">
                  <p class="control is-expanded">
                    <input class="input" type="number" name="bt_sst_timer_hours" min="0" max="23" placeholder="HH" value="<?= esc_attr($saved_data['bt_sst_timer_hours']); ?>">
                  </p>
                  <p class="control"><span class="button is-static">:</span></p>
                  <p class="control is-expanded">
                    <input class="input" type="number" name="bt_sst_timer_minutes" min="0" max="59" placeholder="MM" value="<?= esc_attr($saved_data['bt_sst_timer_minutes']); ?>">
                  </p>
                  <p class="control"><span class="button is-static">:</span></p>
                  <p class="control is-expanded">
                    <input class="input" type="number" name="bt_sst_timer_seconds" min="0" max="59" placeholder="SS" value="<?= esc_attr($saved_data['bt_sst_timer_seconds']); ?>">
                  </p>
                </div>
              </div>

                <!-- Free Shipping -->
              <div class="field">
                <label class="label">Offer Type</label>
                <div class="control">
                  <label class="radio mr-3">
                    <input type="radio" name="free_shipping" value="yes" <?= checked($saved_data['free_shipping'], 'yes', false); ?>> Free Shipping
                  </label>
                  <label class="radio">
                    <input type="radio" name="free_shipping" value="no" <?= checked($saved_data['free_shipping'], 'no', false); ?>> Discount
                  </label>
                </div>
              </div>

              <!-- Discount -->
              <div class="field">
                <label class="label">Discount (%)</label>
                <div class="control">
                  <input class="input" type="number" name="discount_percentage" min="0" max="100"
                        value="<?= esc_attr($saved_data['discount_percentage']); ?>" placeholder="e.g. 10%">
                </div>
              </div>


            <div class="premium_features" style="position: relative;padding: 20px;">
                <!-- Location -->
                <div class="field">
                  <label class="label">Location</label>
                  <div class="control">
                    <div class="select is-fullwidth">
                      <select name="bt_sst_timer_location" >
                        <?php foreach ($locations as $value => $label) : ?>
                          <option value="<?= esc_attr($value); ?>" <?= selected($saved_data['bt_sst_timer_location'], $value, false); ?>>
                            <?= esc_html($label); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Set Timing Cookie -->
                <div class="field" style="display:none">
                  <label class="label">Set Timing Cookie</label>
                  <div class="control">
                    <label class="radio mr-3">
                      <input type="radio" name="set_timing_cookie" value="yes" <?= checked($saved_data['set_timing_cookie'], 'yes', false); ?>> Yes
                    </label>
                    <label class="radio">
                      <input type="radio" name="set_timing_cookie" value="no" <?= checked($saved_data['set_timing_cookie'], 'no', false); ?>> No
                    </label>
                  </div>
                </div>
              
                <!-- Timer Container Color -->
                <div class="field">
                  <label class="label">Timer Container Color</label>
                  <div class="control">
                    <input  type="color" name="selected_color_timer_container"
                          value="<?= $saved_data['selected_color_timer_container'] ?? '#000000'; ?>">
                  </div>
                </div>

                <!-- Countdown Color -->
                <div class="field">
                  <label class="label">Countdown Text Color</label>
                  <div class="control">
                    <input  type="color" name="selected_color_timer_count_down"
                          value="<?= $saved_data['selected_color_timer_count_down'] ?? '#000000'; ?>">
                  </div>
                </div>

                <!-- Location -->
                <div class="field">
                  <label class="label">Shortcode</label>
                  <div class="control">
                    <input class="input" type="text" readonly
                          value="[bt_free_shipping_timer]"
                          style="cursor: not-allowed;">
                  </div>
                </div>

                <?php if (!$is_premium) : ?>
                    <div class="is-overlay " style="background:rgba(0, 0, 0, 0.63);color:white;padding:20px;">
                      <div class="columns is-vcentered" style="height: 100%;">
                        <div class="column">
                          <div class="has-text-centered ">
                            <strong>
                              <a style="color:white" target="_blank" href="https://shipment-tracker-for-woocommerce.bitss.tech/pricing-plans/">Buy Premium</a>
                            </strong> to activate all options.
                            
                          </div>
                        </div>
                      </div>
                    </div>     
                  <?php endif; ?>     

            </div>
								
							
            
              <!-- Submit -->
              <div class="field mt-4">
                <button id="bt_sst_save_timer_form_data" type="button" class="button is-link is-fullwidth is-medium">
                  Save Settings
                </button>
              </div>

            </form>
          </div>
        </div>
      </div>

      <!-- Right Column: Timer Preview -->
      <div class="column is-half">
        <div class="box has-background-light">
          <div class="panel-heading title is-5">Live Timer Preview</div>
          <?php include plugin_dir_path(dirname(__FILE__)) . '../../public/timer/bt_sst_shipment_tracker_timer.php'; ?>
        </div>
      </div>
    </div>
  </div>
</div>


<script>

    jQuery(document).ready(function ($) {
        updateTimeFromInputs();
        toggleDiscountField();
    });
</script>
<style>
  .ql-snow {
    border : 0 !important; 
  }
</style>
<style>
    .bt_sst_timer_page_timer {
        display: flex;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        margin: 10px 0;
    }

    .bt_sst_timer_page_box {
        margin: auto;
        background: red;
        color: white;
        padding: 5px 5px;
        border-radius: 5px;
        text-align: center;
        width: 42px;
    }

    .bt_sst_timer_page_progress-container {
        width: 80%;
        height: 10px;
        background: #ddd;
        border-radius: 5px;
        margin: 10px auto;
        position: relative;
    }

    .bt_sst_timer_page_progress-bar {
        height: 100%;
        background: blue;
        border-radius: 5px;
        width: 100%;
        transition: width 1s linear;
    }

    .bt_sst_timer_page_stock {
        font-size: 18px;
        font-weight: bold;
    }

    .bt_sst_timer_page_stock span {
        color: purple;
        font-weight: bold;
    }

    .bt_sst_timer_heading h2 {
        color: purple;
    }

    .bt_sst_timer_hour_min_sec {
        color: #c700008a;
        font-size: 15px;
    }

    .bt_sst_timer_colon {
        font-size: 50px;
        font-weight: bold;
        align-self: center;
        color: red;
        position: relative;
        top: -20px;
    }

    .bt_sst_timer_container {
        width: fit-content;
        padding: 10px;
        margin: auto;
    }
</style>