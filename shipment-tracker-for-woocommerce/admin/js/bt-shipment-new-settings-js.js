    
document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
});

function fetchShipmentData() {
    jQuery.ajax({
        url: bt_sync_shipment_track_data.ajax_url,
        type: 'POST',
        data: {
        action: 'bt_shipment_data'
    },
        success: function (response) {
            if (response.success) {
                document.getElementById('orders_received_last_x_days').innerText = response.data.orders_received_last_x_days;
                document.getElementById('orders_waiting_to_be_shipped').innerText = response.data.orders_waiting_to_be_shipped.orders_waiting_to_be_shipped;
                //document.getElementById('orders_not_delivered_after_10_days').innerText = response.data.orders_not_delivered_after_10_days.orders_not_delivered_after_10_days;
                document.getElementById('orders_in_transit').innerText = response.data.orders_in_transit.count;
             
                document.getElementById('get_delivered_orders_count').innerText = response.data.get_delivered_orders_count.count;
                document.getElementById('get_delayed_orders_list').innerText = response.data.get_delayed_orders_list.delayed_orders;
                var msg = "Hi, You've got " + response.data.orders_received_last_x_days + " Orders in last 30 days, out of which " + response.data.orders_waiting_to_be_shipped.orders_waiting_to_be_shipped + " orders are waiting to be shipped. ";
                document.getElementById('bt_sst_introduction_message').innerText = msg;
            }
        },
        error: function () {
            console.error('Error fetching shipment data');
        }
		});
	}
  
    var bt_sst_timer_heading = "";
    var bt_sst_timer_page_stock = "";
    if(jQuery('.bt_sst_timer_heading').length){
        // Quill page settings 
        bt_sst_timer_heading = new Quill('.bt_sst_timer_heading', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }]
                ]
            }
        });

        bt_sst_timer_page_stock = new Quill('.bt_sst_timer_page_stock', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }]
                ]
            }
        });    
    }


    let timeLeft = 0;
    let totalTime = 0;
    let timer;
    function updateTimerDisplay() {
        let hours = Math.floor(timeLeft / 3600);
        let minutes = Math.floor((timeLeft % 3600) / 60);
        let seconds = timeLeft % 60;

        jQuery("#bt_sst_timer_page_hours").text(hours.toString().padStart(2, '0'));
        jQuery("#bt_sst_timer_page_minutes").text(minutes.toString().padStart(2, '0'));
        jQuery("#bt_sst_timer_page_seconds").text(seconds.toString().padStart(2, '0'));

        let progressPercentage = totalTime > 0 ? (timeLeft / totalTime) * 100 : 0;
        jQuery("#bt_sst_timer_page_progress-bar").css("width", progressPercentage + "%");

        if (timeLeft <= 0) {
            clearInterval(timer);
            jQuery(".bt_sst_timer_page_stock").text("Offer has been closed!");
        }
    }

    function startTimer() {
        clearInterval(timer); // Clear previous timer
        timer = setInterval(function () {
            if (timeLeft > 0) {
                timeLeft--;
                updateTimerDisplay();
            } else {
                clearInterval(timer);
                jQuery(".bt_sst_timer_page_stock").text("Offer has been closed!");
            }
        }, 1000);
    }

    function updateTimeFromInputs() {
        let hours = parseInt(jQuery("input[name='bt_sst_timer_hours']").val()) || 0;
        let minutes = parseInt(jQuery("input[name='bt_sst_timer_minutes']").val()) || 0;
        let seconds = parseInt(jQuery("input[name='bt_sst_timer_seconds']").val()) || 0;

        timeLeft = (hours * 3600) + (minutes * 60) + seconds;
        totalTime = timeLeft; // Update total time for progress calculation
        updateTimerDisplay();
        startTimer(); // Restart timer with new values
    }

    
    function getQuillHtmlWithInlineStyles(quill) {
        let tempDiv = document.createElement("div");
        tempDiv.innerHTML = quill.root.innerHTML; // Get Quill content

        // Convert all class-based styles to inline styles
        tempDiv.querySelectorAll("*").forEach(el => {
            let computedStyles = window.getComputedStyle(el);
            el.style.cssText = computedStyles.cssText; // Apply computed styles as inline styles
        });

        return tempDiv.innerHTML; // Return the fully styled HTML
    }

    function toggleDiscountField() {
        if (jQuery("input[name='free_shipping']:checked").val() === "no") {
            jQuery("input[name='discount_percentage']").closest(".field").show();
        } else {
            jQuery("input[name='discount_percentage']").closest(".field").hide();
        }
    }

        jQuery(document).ready(function ($) {
            
            // Update timer in real-time when inputs change
            jQuery("input[name='bt_sst_timer_hours'], input[name='bt_sst_timer_minutes'], input[name='bt_sst_timer_seconds']").on('input', function () {
                updateTimeFromInputs();
            });

            jQuery("input[name='selected_color_timer_count_down']").on('input', function () {
                jQuery('.bt_sst_timer_page_box').css('color', jQuery(this).val());

            });

            jQuery("input[name='selected_color_timer_container']").on('input', function () {
                jQuery('.bt_sst_timer_page_box').css('background', jQuery(this).val());
                jQuery('.bt_sst_timer_colon').css('color', jQuery(this).val());
            });

            jQuery("#bt_sst_save_timer_form_data").on("click", function () {
                const bt_sst_timer_heading_html = getQuillHtmlWithInlineStyles(bt_sst_timer_heading);
                // alert(bt_sst_timer_heading_html);
                const bt_sst_timer_page_stock_sub = getQuillHtmlWithInlineStyles(bt_sst_timer_page_stock);
                jQuery("#bt_sst_quill_editer_html").val(bt_sst_timer_heading_html);
                jQuery("#bt_sst_quill_editer_html_subheading").val(bt_sst_timer_page_stock_sub);
                jQuery('#bt_sst_quill_editer_form').submit();
            });



            jQuery("input[name='free_shipping']").change(function () {
                toggleDiscountField();
            });
        });






  angular.module('bt-sst-settings', ['toastr'])
    .controller('MainController', function ($scope, $http, toastr) {
        $scope.fetch_weight = false;
        $scope.fetch_auto_pincode = false;
        $scope.tracking_page_status = false;
        $scope.estimate_delivery_checker = false;
        $scope.timer_setting_update = false;
        $scope.fetch_dynamic_ship_method = false;
        $scope.primary_shipping_method_setting_update = '';
        $scope.tracking_page_url = '';
        $scope.loading = false;
        $scope.primary_shipping_provider_url = '';
        $scope.google_api_key = "";
        $scope.product_url_etd = false;
        $scope.product_url_timer = false;
        // initialize page data 
        $scope.init = function () {
            $scope.loading = true;
            var post_Data = {
                action: "bt_sst_get_tracking_settings_data",
            };

            $http({
                method: 'POST',
                url: bt_sync_shipment_track_data.ajax_url,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: post_Data
            }).then(function (response) {
                var data = response.data.data;
                $scope.tracking_page_status = data.tracking_page_status;
                $scope.estimate_delivery_checker = data.estimate_delivery_checker;
                $scope.timer_setting_update = data.timer_setting_update;
                $scope.fetch_auto_pincode = data.fetch_auto_pincode;
                $scope.fetch_weight = data.fetch_weight;
                $scope.fetch_dynamic_ship_method = data.fetch_dynamic_ship_method;
                $scope.google_api_key = data.google_api_key;
                $scope.primary_shipping_method_setting_update = data.primary_shipping_method_setting_update;
                $scope.tracking_page_url = data.tracking_page_url;
                $scope.loading = false;
                $scope.primary_shipping_provider_url = data.primary_shipping_provider_url;
                $scope.product_url_etd = data.product_url_etd;
                $scope.product_url_timer = data.product_url_timer;
            }, function (error) {
                $scope.loading = false;
                toastr.error("Error: " + error.statusText, 'Error');
            });
        };

        //update tracking page
        $scope.updateTrackingPageSettings = function () {
            console.log($scope.tracking_page_status);

            var postData = {
                action: "bt_sst_update_tracking_settings",
                type: $scope.tracking_page_status,
                value: "create_tracking_page"
            };

            $http({
                method: 'POST',
                url: bt_sync_shipment_track_data.ajax_url,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: postData
            }).then(function (response) {
                var data = response.data.data;
                console.log(data);
                $scope.tracking_page_url = data.tracking_url;
                toastr.success('Settings have been updated successfully.');
            }, function (error) {
                toastr.error("Error: " + error.statusText, 'Error');
            });
        }

        // update etd settings
        $scope.updateETDSettings = function () {

            var postData = {
                action: "bt_sst_update_tracking_settings",
                type: $scope.estimate_delivery_checker,
                value: "etd"
            };

            $http({
                method: 'POST',
                url: bt_sync_shipment_track_data.ajax_url,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: postData
            }).then(function (response) {
                var data = response.data.data;
                // console.log(data);
                $scope.product_url_etd = data.product_url_etd;
                toastr.success('Settings have been updated successfully.');
            }, function (error) {
                toastr.error("Error: " + error.statusText, 'Error');
            });
        }

        // update timer settings  
        $scope.updateTimerSettings = function () {

            var postData = {
                action: "bt_sst_update_tracking_settings",
                type: $scope.timer_setting_update,
                value: "timer"
            };

            $http({
                method: 'POST',
                url: bt_sync_shipment_track_data.ajax_url,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: postData
            }).then(function (response) {
                var data = response.data.data;
                // console.log(data);
                $scope.product_url_timer = data.product_url_timer;
                toastr.success('Settings have been updated successfully.');
            }, function (error) {
                toastr.error("Error: " + error.statusText, 'Error');
            });
        }

        // update ato fetch pin settings  
        $scope.updateAutoFetchPinSettings = function () {

            var postData = {
                action: "bt_sst_update_tracking_settings",
                type: $scope.fetch_auto_pincode,
                value: "fetch_auto_pincode"
            };

            $http({
                method: 'POST',
                url: bt_sync_shipment_track_data.ajax_url,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: postData
            }).then(function (response) {
                // var data = response.data.data;
                // console.log(data);
                // $scope.tracking_page_url = data.tracking_url;
                toastr.success('Settings have been updated successfully.');
            }, function (error) {
                toastr.error("Error: " + error.statusText, 'Error');
            });
        }

        // update fetch_weight settings  
        $scope.updateFetchWeightSettings = function () {

            var postData = {
                action: "bt_sst_update_tracking_settings",
                type: $scope.fetch_weight,
                value: "fetch_weight"
            };

            $http({
                method: 'POST',
                url: bt_sync_shipment_track_data.ajax_url,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: postData
            }).then(function (response) {
                // var data = response.data.data;
                // console.log(data);
                // $scope.tracking_page_url = data.tracking_url;
                toastr.success('Settings have been updated successfully.');
            }, function (error) {
                toastr.error("Error: " + error.statusText, 'Error');
            });
        }

        // update fetch_weight settings  
        $scope.updateDynamicShipMethodSettings = function () {

            var postData = {
                action: "bt_sst_update_tracking_settings",
                type: $scope.fetch_dynamic_ship_method,
                value: "fetch_dynamic_ship_method"
            };

            $http({
                method: 'POST',
                url: bt_sync_shipment_track_data.ajax_url,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: postData
            }).then(function (response) {
                // var data = response.data.data;
                // console.log(data);
                // $scope.tracking_page_url = data.tracking_url;
                toastr.success('Settings have been updated successfully.');
            }, function (error) {
                toastr.error("Error: " + error.statusText, 'Error');
            });
        }

        // update primary shipping method settings 
        $scope.updatePrimaryShippingMethodSettings = function () {

            var postData = {
                action: "bt_sst_update_tracking_settings",
                type: $scope.primary_shipping_method_setting_update,
                value: "primary_shipping_method"
            };

            $http({
                method: 'POST',
                url: bt_sync_shipment_track_data.ajax_url,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: postData
            }).then(function (response) {
                var data = response.data.data;
                // console.log(data);
                $scope.primary_shipping_provider_url = data.primary_shipping_provider_url;
                toastr.success('Settings have been updated successfully.');
            }, function (error) {
                toastr.error("Error: " + error.statusText, 'Error');
            });
        }

        // update google api settings settings  
        $scope.updateGoogleApiSettings = function () {

            var postData = {
                action: "bt_sst_update_tracking_settings",
                type: $scope.google_api_key,
                value: "google_api_key"
            };

            $http({
                method: 'POST',
                url: bt_sync_shipment_track_data.ajax_url,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                transformRequest: function (obj) {
                    var str = [];
                    for (var p in obj)
                        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                    return str.join("&");
                },
                data: postData
            }).then(function (response) {
                var data = response.data.data;
                // console.log(data);
                // $scope.primary_shipping_provider_url = data.primary_shipping_provider_url;
                toastr.success('Settings have been updated successfully.');
            }, function (error) {
                toastr.error("Error: " + error.statusText, 'Error');
            });
        }


        $scope.init();
    });

   jQuery('.bt_sst_show_overlay_sms_popup').on('click', function (e) {
        e.preventDefault();
        jQuery('#thankYouModal').addClass("is-active");
    });

    jQuery(document).ready(function() {
        jQuery('#bt_sst_remove_sms_key_btn').on('click', function(e) {
            if (!confirm('Are you sure you want to remove the API key?')) {
                e.preventDefault();
            }
        });
    });
