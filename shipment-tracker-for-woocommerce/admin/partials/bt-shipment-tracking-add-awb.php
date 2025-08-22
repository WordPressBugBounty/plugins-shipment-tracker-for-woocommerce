<div id="bt_sst_check_already_exist">
<div id="bt_sst_awbPopup" class="bt_sst_popup">
    <div class="bt_sst_popup-content">
        <span id="bt_sst_closePopup" class="bt_sst_close">&times;</span>
        <h2>Courier Details</h2>
        <div id="">
            <div>
                <div>
                    <label for="">AWB:</label>
                </div>
                <input type="text" id="bt_sst_ship24_awb_field" name="bt_sst_ship24_awb_field" class="bt_sst_ship24_input">
            </div>
            <div id="bt_sst_ship24_corier_data">
                <div>
                    <label for="">Courier:</label>
                </div>
                <select style="width:100%" name="bt_sst_ship24_couriers_list" id="bt_sst_ship24_couriers_name" class="bt_sst_ship24_input">
                    <option value="">Select Courier</option>
                    <?php
                    $coriure_name = get_option('_bt_sst_ship24_active_courier_companies');

                    if(is_array($coriure_name) && count($coriure_name)>1){
$courierCodeName = [];
foreach ($coriure_name as $key => $courier) {
    $courierCodeName = [
        'corier_code' => $courier['courierCode'],
        'corier_name' => $courier['courierName'],
    ];
    $courierCodeAndName = json_encode($courierCodeName);
    ?>
    <option value='<?php echo esc_attr($courierCodeAndName)?>' data-courierName='<?php echo esc_attr($courier['courierName'])?>'><?php echo esc_attr($courier['courierName'])?></option>
<?php } }else{?>
                    <option value="">Loading.........</option>  
                    <?php } ?>                                   
                </select>
            
                    <option value="">Loading.........</option>                                     
                </select>
            </div>
            <br>
            <button type="button" id="save_manual" class="button save_order" href='#'>SAVE</button>
        </div>
    </div>
</div>
<script>
    jQuery('#bt_sst_ship24_couriers_name').select2({
        placeholder: "Select Courier",
        allowClear: true
    });
        
        jQuery('#bt_sst_closePopup').click(function () {
            jQuery('#bt_sst_awbPopup').fadeOut();
        });
      
        jQuery('#bt_sst_ship24_couriers_name').click(function () {
            var optionCount = jQuery(this).find('option').length;
            if (optionCount === 2) {
                jQuery.ajax({
                    method: "POST",
                    url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
                    dataType: "json",
                    data: {
                        'status': true,
                        'action': 'get_coriers_name_for_ship24'
                    },
                    success: function (response) {
                        if(response.data==false){
                            alert('Please Enter API Key');
                        }

                        if (response.success && response.data && !response.data[0]?.message) {
                            var html = '<div>' +
                                '    <div>Courier:<div><select class="bt_sst_ship24_input" name="bt_sst_ship24_couriers_list" id="bt_sst_ship24_couriers_name_select">' +
                                '        <option value="">Select Courier</option>';
                            var coriercode_coriername;
                            jQuery.each(response.data, function (index, courier) {
                                coriercode_coriername = {
                                    corier_code: courier.courierCode,
                                    corier_name: courier.courierName
                                };
                                var corier_code_and_name = JSON.stringify(coriercode_coriername);
                                html += "<option value='" + corier_code_and_name + "' data-courierName='" + courier.courierName + "'>" + courier.courierName + "</option>";
                                corier_code_and_name = "";
                            });
                            html += '    </select>' +
                                '</div>';
                            jQuery('#bt_sst_ship24_corier_data').html(html);

                            // Initialize Select2 on the new select element
                            jQuery('#bt_sst_ship24_couriers_name_select').select2({
                                placeholder: "Select Courier",
                                allowClear: true
                            });
                        }else{
                            alert(response.data[0].message);
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Something went wrong! Error: ' + errorThrown);
                        return false;
                    }
                });
            }
        });
        jQuery('#save_manual').click(function () {
            jQuery('#save_manual').text('Loading...');
            jQuery('#save_manual').text('Loading...').prop('disabled', true);
            var current_awb = jQuery("#bt_sst_ship24_awb_field").val();
            var corier_code_and_name = jQuery("#bt_sst_ship24_couriers_name_select").val();
            if(!corier_code_and_name){
                var corier_code_and_name = jQuery("#bt_sst_ship24_couriers_name").val();
            }
            var courierObject = JSON.parse(corier_code_and_name);
            var corier_code = courierObject.corier_code;
            var corier_name = courierObject.corier_name;
            console.log(corier_code+" "+corier_name)
                jQuery.ajax({
                    method: "POST",
                    url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
                    dataType: "json",
                    data: {
                        'order_id': '<?php echo esc_js($order_id); ?>',
                        'awb_number': current_awb,
                        'corier_code': corier_code,
                        'corier_name': corier_name,                       
                        'action': 'save_order_awb_number'
                    }, success: function (response) {
                        window.location.reload();
                        jQuery("#bt_sst_awbPopup").hide();
                        // bt_st_show_info("Yor tracking data will update soon.");

                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert('Something went wrong! Error: ' + errorThrown);
                        return false;
                    }
                });
        });
        // function bt_st_show_info(info_text){
        //     //jQuery('#bt_notify_popup').attr("href","#TB_inline?&width=200&height=150&inlineId=bt_notify_popup_content");
        //     jQuery('#bt_notify_popup_content_title').text(info_text);
        //     jQuery('#bt_notify_popup').trigger("click");
        // }
</script>
<style>
    .bt_sst_popup {
        display: none;
        position: fixed;
        z-index: 10000;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .bt_sst_popup-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        position: relative;
    }

    .bt_sst_close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
    }
    .bt_sst_ship24_input{
        width: 100%;
    }
</style>
</div>