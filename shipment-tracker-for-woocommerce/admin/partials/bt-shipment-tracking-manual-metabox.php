<?php
   // echo json_encode($bt_shipment_tracking);exit;
    $bt_shipping_manual_tracking_url = $bt_shipment_tracking->tracking_url;
    $bt_shipment_tracking = (array)$bt_shipment_tracking;

    $cour_n = carbon_get_theme_option("bt_sst_manual_courier_name");
    $awb_n = carbon_get_theme_option("bt_sst_manual_awb_number");
    if(!isset($order_id)){
        $order_id=isset($_GET['post']) ? $_GET['post'] : sanitize_text_field($_GET['id']);
    }
    $awb_n = isset($awb_n) ? $awb_n : '';
    $awb_n = str_replace('#order_id#', $order_id, $awb_n);

    $tracking_page_id = get_option( '_bt_sst_tracking_page' );
    $full_url="";
    if($tracking_page_id){
        $link = get_permalink( $tracking_page_id );
        $separator = (strpos($link, '?') !== false) ? '&' : '?';
        $full_url = $link . $separator . 'order=' . $order_id;
    }
?>
<style>
.bt_sst_modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.bt_sst_modal_content {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px;
}

.bt_sst_modal_close {
    color:black;
    float: inline-end;
    font-size: 20px;
    cursor: pointer;
}

.bt_sst_field {
    margin-bottom: 15px;
}

.bt_sst_field label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.bt_sst_field input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.bt_sst_field button {
    background-color: #007BFF;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.bt_sst_field button:hover {
    background-color: #0056b3;
}

</style>
<input type="hidden" name="order_id" value="<?php echo esc_attr( $order_id ) ?>"/>
<p class="form-field ">
    <label for="bt_manual_awb_number">AWB Number *</label><br>
    <input required type="text" class="short" style="" name="bt_manual_awb_number" id="bt_manual_awb_number" value="<?php echo (isset($bt_shipment_tracking['awb'])&& !empty($bt_shipment_tracking['awb'])) ? $bt_shipment_tracking['awb'] : $awb_n; ?>" placeholder="<?php echo esc_attr($awb_n) ?>">
</p>
<p class="form-field ">
    <label for="bt_manual_awb_number_coriure">Courier *</label><br>
    <select required style="min-width:80%" class="bt_manual_awb_number_coriure" name="bt_manual_awb_number_coriure">
        <option value="<?php echo isset($bt_shipment_tracking['courier_name']) && !empty($bt_shipment_tracking['courier_name']) ? $bt_shipment_tracking['courier_name'] : ''; ?>" 
            selected>
            <?php echo !empty($bt_shipment_tracking['courier_name']) ? $bt_shipment_tracking['courier_name'] : 'Select Courier Company'; ?>
        </option>
        <option value="">Loading....</option>
    </select> 
    <button id="bt_sst_add_coriures" type="button" style="box-sizing: border-box;float: right;" class="button">+</button>  
</p>
<p class="form-field ">
    <?php
    if (class_exists('WooCommerce')) {
        require_once ABSPATH . 'wp-content/plugins/woocommerce/includes/admin/wc-meta-box-functions.php';
    }
    
        woocommerce_wp_select([
            'class'             => 'select short',
            'style'             => 'width:100%;',
            'id'       => 'bt_manual_shipping_status',
            'label'    => __( 'Shipping Status *', 'woocommerce' ),
            'selected' => true,
            'value'    => isset($bt_shipment_tracking['current_status'])?$bt_shipment_tracking['current_status']:"",
            'options' => apply_filters( 'bt_sst_shipping_statuses', BT_SHIPPING_STATUS )
        ]);

	?>
</p>
<p class="form-field ">
    <label for="bt_manual_etd">Estimated Delivery Date</label><br>
    <input type="date" class="short" style="" name="bt_manual_etd" id="bt_manual_etd" value="<?php echo isset($bt_shipment_tracking['etd']) ? $bt_shipment_tracking['etd'] : ''; ?>" placeholder="Enter expected delivery date">
</p>
<p class="form-field ">
    <label for="bt_manual_tracking_link">Courier's Tracking URL</label><br>
    <textarea style="padding:5px;" class="short" placeholder="Courier Company's Tracking URL"  name="bt_manual_tracking_link" id="bt_manual_tracking_link" ><?php echo !empty($bt_shipping_manual_tracking_url) ? $bt_shipping_manual_tracking_url : ''; ?></textarea>
    <small>Variables: #awb#, #orderid#</small>
</p>
<small>Website's Tracking URL</small><br>
<p class="form-field" style="border:1px solid #8c8f94; border-radius:4px; margin:3px 0; padding:5px;" >
    <?php if(!empty($full_url)) { ?>
    <small class="bt_sst_website_order_tracking_url short" ><?php echo esc_url($full_url);  ?></small>
    <a href="javascript:void(0);" class="bt-sst-copy-link-anchor short" style="text-decoration: none;"> Copy url </a>
    <?php } else{ ?>
        <small> *Please set tracking page in plugin settings.</small>
    <?php } ?>
   
</p><p><small>Tracking Page Shortcode: [bt_shipping_tracking_form_2]</small></p><br>
<span class="spinner"></span> <button type="button" id="bt_manual_save" class="button" href='#'>Save</button>
<!-- Modal Popup Form -->

<div id="bt_sst_manual_courier_popup" class="bt_sst_modal" style="display:none;">
    <div class="bt_sst_modal_background"></div>
    <div class="bt_sst_modal_content">
        <div class="bt_sst_box">
            <span class="bt_sst_modal_close" aria-label="close">x</span>
            <h3 class="bt_sst_title">Add New Courier</h3>
            <form id="bt_sst_manual_courier_form">
                <div class="bt_sst_field">
                    <label for="bt_sst_manual_courier_company_name">Company Name</label>
                    <input type="text" id="bt_sst_manual_courier_company_name" name="company_name">
                </div>

                <div class="bt_sst_field">
                    <label for="bt_sst_manual_courier_region_coverage">Region Coverage</label>
                    <input type="text" id="bt_sst_manual_courier_region_coverage" name="region_coverage">
                </div>

                <div class="bt_sst_field">
                    <label for="bt_sst_manual_courier_company_url">Company URL</label>
                    <input type="text" id="bt_sst_manual_courier_company_url" name="company_url">
                </div>

                <div class="bt_sst_field">
                    <label for="bt_sst_manual_courier_tracking_url">Tracking URL</label>
                    <input type="text" id="bt_sst_manual_courier_tracking_url" name="tracking_url">
                </div>

                <div class="bt_sst_field">
                    <button type="button" id="bt_sst_manual_courier_save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    jQuery('#bt_manual_save').click(function () {
        var bt_manual_courier_name = jQuery('.bt_manual_awb_number_coriure').val();
        if(bt_manual_courier_name.trim() == '' ) {
             alert('Courier name is required');
             return false;
        }
        if( jQuery('#bt_manual_awb_number').val().trim() == '' ) {
             alert('AWB is required');
             return false;
        }
        jQuery('#bt_manual_save').addClass("disabled");
        jQuery('#bt_sync-box .spinner').addClass("is-active");
        jQuery.ajax({
            method: "POST",
            url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
            dataType: "json",
            data: {
                'order_id': '<?php echo esc_js($order_id); ?>',
                'courier_name': bt_manual_courier_name,
                'awb_number':  jQuery('#bt_manual_awb_number').val(),
                'shipping_status': jQuery('#bt_manual_shipping_status').val(),
                'etd': jQuery('#bt_manual_etd').val(),
                'tracking_link': jQuery('#bt_manual_tracking_link').val(),
                'action': 'bt_tracking_manual'
            }, success: function (response) {
                jQuery('#bt_manual_save').removeClass("disabled");
                jQuery('#bt_sync-box .spinner').removeClass("is-active");
                if (response != null && response.status != false) {
                    location.reload();  //Reload the page if response received
                } else {
                    alert(response.response);
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                jQuery('#bt_manual_save').removeClass("disabled");
                jQuery('#bt_sync-box .spinner').removeClass("is-active");
                alert('Something went wrong! Error: ' + errorThrown);
                return false;
            }
        });
    });
    jQuery(document).ready(function($) {
        var copyAnchor = jQuery('.bt-sst-copy-link-anchor');
        var urlElement = jQuery('.bt_sst_website_order_tracking_url');
        // var tempInput = jQuery('.bt_sst_website_order_tracking_url:first').text();

        copyAnchor.on('click', function (e) {
            e.preventDefault();
           // debugger;
            // Create a temporary input element to copy the URL
            var tempInput = jQuery('<input>');
         
            jQuery('body').append(tempInput);
            tempInput.val(urlElement.text()); // Get the URL text
            // Select the content and copy to clipboard
            tempInput.select();
            document.execCommand('copy');

            // Remove the temporary input element
            tempInput.remove();

            // Provide feedback to the user
            copyAnchor.text('Copied!');
            setTimeout(function () {
                copyAnchor.text('Copy url');
            }, 2000);
        });
  
        jQuery(document).on('click', '.bt_manual_awb_number_coriure', function () {
            jQuery.ajax({
                url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
                type: 'POST',
                data: {
                    action: 'load_coriures_name_for_manual'
                },
                success: function(response) {
                    // Parse the JSON response
                    var data = JSON.parse(response);

                    // Group companies by region_coverage
                    var groupedData = {};

                    // Group the companies by region_coverage
                    data.forEach(function(company) {
                        var region = company.region_coverage || 'Other'; // Default to 'Other' if no region_coverage
                        if (!groupedData[region]) {
                            groupedData[region] = [];
                        }
                        groupedData[region].push(company);
                    });

                    // Clear the dropdown before adding new options
                    jQuery('.bt_manual_awb_number_coriure').empty();
                    jQuery('.bt_manual_awb_number_coriure').append('<option value="">Select Courier Company</option>');

                    // Loop through grouped data and append <optgroup> elements
                    for (var region in groupedData) {
                        var $optgroup = jQuery('<optgroup>', { label: region });
                        groupedData[region].forEach(function(company) {
                            $optgroup.append(
                                '<option value="' + company.company_name + '" data-tracking-url="' + company.tracking_url + '">' +
                                company.company_name +
                                '</option>'
                            );
                        });
                        jQuery('.bt_manual_awb_number_coriure').append($optgroup);
                    }

                    // Initialize Select2 with placeholder
                    jQuery('.bt_manual_awb_number_coriure').select2({
                        placeholder: "Select Courier Company",
                        allowClear: true
                    });
                },
                error: function() {
                    alert('Error loading company data.');
                }
            });
        });

        jQuery(document).on('change', '.bt_manual_awb_number_coriure', function () {
            const selectedOption = $(this).find('option:selected');
            const trackingUrl = selectedOption.data('tracking-url');
            jQuery('#bt_manual_tracking_link').val(trackingUrl);

        });

        jQuery(document).on("click", "#bt_sst_add_coriures", function() {
            jQuery("#bt_sst_manual_courier_popup").css('display', 'flex');
            jQuery("#bt_sst_manual_courier_popup").addClass("is-active");
        });

        jQuery(document).on("click", ".bt_sst_modal_close", function() {
            jQuery("#bt_sst_manual_courier_popup").css('display', 'none');
            jQuery("#bt_sst_manual_courier_popup").removeClass("is-active");
        });

        jQuery(document).on('click', '#bt_sst_manual_courier_save', function(e) {
            e.preventDefault();

            var formData = {
                company_name: jQuery('#bt_sst_manual_courier_company_name').val(),
                region_coverage: jQuery('#bt_sst_manual_courier_region_coverage').val(),
                company_url: jQuery('#bt_sst_manual_courier_company_url').val(),
                tracking_url: jQuery('#bt_sst_manual_courier_tracking_url').val(),
                action: 'bt_sst_save_manul_coriure_name',

            };

            jQuery.ajax({
                url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert('Courier saved successfully!');
                        $('#bt_sst_manual_courier_popup').hide();
                        location.reload();
                    } else {
                        alert('Error: ' + response.data.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Request failed: ' + error);
                }
            });
        });

    });
</script>
