<?php
   // echo json_encode($bt_shipment_tracking);exit;
    $bt_shipping_manual_tracking_url = $bt_shipment_tracking->tracking_url;
    $bt_shipment_tracking = (array)$bt_shipment_tracking;

    $cour_n = carbon_get_theme_option("bt_sst_manual_courier_name");
    $awb_n = carbon_get_theme_option("bt_sst_manual_awb_number");
    if(!isset($order_id)){
        $order_id=isset($_GET['post']) ? $_GET['post'] : sanitize_text_field($_GET['id']);
    }
    $awb_n = isset($awb_n) ? $awb_n : '#order_id#';
    $awb_n = str_ireplace('#order_id#', $order_id, $awb_n);

    $tracking_page_id = get_option( '_bt_sst_tracking_page' );
    $full_url="";
    if($tracking_page_id){
        $link = get_permalink( $tracking_page_id );
        $separator = (strpos($link, '?') !== false) ? '&' : '?';
        $full_url = $link . $separator . 'order=' . $order_id;
    }
?>

<input type="hidden" name="order_id" value="<?php echo esc_attr( $order_id ) ?>"/>
<p class="form-field ">
    <label for="bt_manual_awb_number">AWB Number *</label><br>
    <input type="text" class="short" style="" name="bt_manual_awb_number" id="bt_manual_awb_number" value="<?php echo (isset($bt_shipment_tracking['awb'])&& !empty($bt_shipment_tracking['awb'])) ? $bt_shipment_tracking['awb'] : $awb_n; ?>" placeholder="<?php echo esc_attr($awb_n) ?>">
</p>
<p class="form-field ">
    <label for="bt_manual_awb_number_coriure">Courier *</label><br>
    <select style="min-width:80%" class="bt_manual_awb_number_coriure" name="bt_manual_awb_number_coriure">
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
        require_once WC_ABSPATH  . 'wp-content/plugins/woocommerce/includes/admin/wc-meta-box-functions.php';
        woocommerce_wp_select([
            'class'             => 'select short',
            'style'             => 'width:100%;',
            'id'       => 'bt_manual_shipping_status',
            'label'    => __( 'Shipping Status *', 'woocommerce' ),
            'selected' => true,
            'value'    => isset($bt_shipment_tracking['current_status'])?$bt_shipment_tracking['current_status']:"",
            'options' => apply_filters( 'bt_sst_shipping_statuses', BT_SHIPPING_STATUS )
        ]);
    }
// echo "<pre>"; print_r($bt_shipment_tracking); die;
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
<?php
include plugin_dir_path(__FILE__).'bt-shipment-tracker-get-and-save-couriers.php';
?>
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
  
        // jQuery(document).on('click', '.bt_manual_awb_number_coriure', function () {
        //     jQuery('.bt_manual_awb_number_coriure').select2({
        //                 placeholder: "Select Courier Company",
        //                 allowClear: true
        //             });
        // });
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
                    var bt_shipment_tracking = '<?php echo $bt_shipment_tracking['courier_name'] ?>';
                    var default_cor_name = '<?php echo $cour_n ?>';
                    
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
                            let selecteddata = '';
                            // console.log(company.company_name);
                            if (bt_shipment_tracking === company.company_name) {
                                selecteddata = 'selected';
                            }
                            else if (default_cor_name === company.company_name) {
                                selecteddata = 'selected';
                            }
                            $optgroup.append(
                                `<option ${selecteddata} value="${company.company_name}" data-tracking-url="${company.tracking_url}">
                                    ${company.company_name}
                                </option>`
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
        // });

        jQuery(document).on('change', '.bt_manual_awb_number_coriure', function () {
            const selectedOption = $(this).find('option:selected');
            const trackingUrl = selectedOption.data('tracking-url');
            jQuery('#bt_manual_tracking_link').val(trackingUrl);

        });

    });
</script>