<?php
$countries = WC()->countries->get_shipping_countries();
$plugin_public_url = plugin_dir_url(dirname(__FILE__));
$shop_country = WC()->countries->get_base_country();
$post_code_auto_fill = carbon_get_theme_option('bt_sst_enable_auto_postcode_fill');
if (defined('DOING_AJAX') && DOING_AJAX) {

    wp_register_script('bt-sync-shipment-tracking-public', '/wp-content/plugins/shipment-tracker-for-woocommerce/public/js/bt-sync-shipment-tracking-public.js', array('jquery'), 'v3.4.1', true);
    // wp_register_style('bt-sync-shipment-tracking-public-css', '/wp-content/plugins/shipment-tracker-for-woocommerce/public/css/bt-sync-shipment-tracking-public.css', array(), 'v3.4.1', 'all');

    $script_data = array(
        "ajax_url" => admin_url('admin-ajax.php'),
        "bt_sst_autofill_post_code" => $post_code_auto_fill,
        "plugin_public_url" => dirname(plugin_dir_url(__FILE__)),
        "pincode_checkout_page_nonce" => wp_create_nonce('get_data_by_pincode_checkout_page')
    );
    wp_localize_script('bt-sync-shipment-tracking-public', 'bt_sync_shipment_tracking_data', $script_data);

    // wp_print_styles('bt-sync-shipment-tracking-public-css');
    wp_print_scripts('bt-sync-shipment-tracking-public');
}

?>
<div class="bt_sync_shimpent_track_pincode_checker_wrap print_x">
    <div class="delivery-checker-header">
        <div style="display:flex; align-items: center;">
            <img class="delivery-checker-header-img" style="margin-right: 4px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAACZklEQVR4nO2Yu28TQRDGVwISKMAUCJFIFFBS0QdR0lDwqPgXeImaLh0NNNBRQUvHH0DHIyCR0JBAbQXEQ0IC345z38CHJqyRiezjHvadT9pPmsJa3Xz+7czeju1cVFRUVFRUBYkqaw3giwfukdzXbhD9Ex6469osAc6Eynx2bZeEqkwlqVSMCNJmyTRaqwkNvYo/5nhdfxLVR33yRL6kUz4XVT09kCTA+ZkE8aqXxq25wWfyuFd9HGD6Pk1Pu1lR1kbIiDWSuwR4GFrtww/ysGsjiInkblF9Gir5xODcsOpqp1GeRdc8eVSArwHmZmtBTH3grAd+eQA+TU+5JlUFxCTAnXBeut/JQ66tICT3iOpKgHnwz4PTbqdJgpiEPGZ3iwd+9ra2TrYWxCTA7XC/3HdNKBME6I67LHfKp+lSAHnvZhBkucT4gkYm0SxP2sVnMKEyua+FWQMpqggyCcWKVG0tknv7qjdE9VW4hBJRfSmq10nOlzafgJfkBUnIRa/6JuONsdYjFyYBkpTwygViOzBI7IG3feAcyf0W9rPTAxvh+VWSc1VAynpJHhArZ0i8TrIzArQzZHClCkhZL/kLAmxuJ0jTpRGGz23Ndmfcl0qACyHZs4oghb1sjBmM8wZy63+3ppV3XHKSB4qOFBP3ApZtcW4bJlSmRPJOjSCdHQBdg7CxxmVpUO6s/5MS4GLR1qrdS1SvhQO4MeoAfiMP2ggdkl8uyTB9L1rbqa4Fg3d22KxPLWx3hhK/LvL6bcQrIRcHBmNitUceqQJRmxfJ+VD6FQ/0LET1haherVqJJr2ioqJc+/QbwF1YsIhmRzoAAAAASUVORK5CYII=" alt="in-transit--v1">
            Check delivery options in your location
        </div>
        <div class="bt_sync_shimpent_track_pincode_checker_shipping_to_text">
            <?php
            if (sizeof($countries) > 1) {
                $bt_sync_shimpent_track_pincode_checker_shipping_to_text = '(<b id="bt_sst_delivery_base_country">' . $shop_country . '</b>)' . (sizeof($countries) > 1 ? ' <a href="#" id="myBtn">
                change
                </a>' : '');
            } else {
                $bt_sync_shimpent_track_pincode_checker_shipping_to_text = '';
            }
            echo wp_kses(apply_filters('bt_sync_shimpent_track_pincode_checker_shipping_to_text', $bt_sync_shimpent_track_pincode_checker_shipping_to_text), 'post');
            ?>
        </div>
    </div>
    <div style="margin:5px;" class="bt_sync_shimpent_track_pincode_checker_label_text">
        <?php
        echo wp_kses(apply_filters('bt_sst_product_page_delivery_checker_label_text', carbon_get_theme_option('bt_sst_product_page_delivery_checker_label')), 'post');
        ?>
    </div>
    <div class="delivery-checker-body">
        <div id="bt_sync_shimpent_track_pincode_checker">
            <div id="delivery-form" class="delivery-form" style="">
                <div class="form-group">
                    <div class="input-wrapper">
                        <img class="input-icon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAAByElEQVR4nO2Wu0oDURCGN42XRtFKjdhbik+gryBYe+nFyogiKb2diTn/bFxSpdNGYsBG8Q0U8QJCbFTQRmyUSFRQVo4aDTHuOZtsYuMPA8suZz7+OTPDWta/fhERtQspxwSQEcxZYn5Q8fm8Scyj86urbVZQIqJmwTwrmO+J2fUM4E4AM+pMVdBF5i4C9rTAkhDAYTwe76kIuiBltwCu/UKL4oocJ+y/vMBBFdCC8/1oKtVkDBbAXLXQIvi0qdt2o0YyDeDOqNsJGA8M+u16xKTMGU2iZwFMqY5/73rmiHqnAadNHJ9pwJEfZz7gv4OZsyaOc15JYrFYZ+mZJdvu0DjO6cHMT15JVHl/OHacsMZxXgsm4NJvqdXIaMAXesdAWtdcCu6zuTa04JhtDwc9TsQ8pAVLKRsJuA0MCtxEo9EGy0RBLhGj5VGQ67ohwbwbgNttlesrsYmklC0EHFcBPq34j4QcJyyAE9/lZT4qN+++tJBMthKw5eNOM6paVhByXTdEzJMEPHq4zBMw4ftOTbSSSPSqLVQGfK6+WbWUkLKfmF+LSvsipOyrKbQgAawXuV2z6qVlYPALLOVA3cDux4LZqWhB/LXeAHFxT8vrnIHLAAAAAElFTkSuQmCC" alt="marker--v1">
                        <input id="pin_input_box" class="form-input" placeholder="Enter a Pincode" type="text">
                        <input id="bt_sst_delivery_estimate_country" value="<?php echo esc_attr($shop_country) ?>"
                            type="hidden">
                    </div>
                    <?php wp_nonce_field('get_pincode_data_product_page', 'get_pincode_data_product_page_nounce'); ?>
                    <button id="pin_input_btn" class="search-btn" type="button">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAADLklEQVR4nO1ZO2tUURA+Nj7wASKitkbU1kaw0Khhf4GlGkQhJJD/oGiRaG9vBEEskyWPVgvfpSY2NnYmATWbM5v7DY7MzaK7c282G+85uxvdD06zsN/Md+bMOTNzneuhh+5DReQYMd8i4DExvydgyQOJLgIWifkdARPEfLMictR1Gwi46JnLHmBillaWB+CZp3ySXOi0/45ETnjm2Vad31AU8zSJ9HVEhGce9EClqIi6CK145mttFUHA3SYOfSRgjICBqsgpEdmrqypyWn8jYNwD8xuKAu60S8S9DZx4TcClVnl8kpwj5hcdEeOZB3MisEbMQyKyY6t8+h9iHq5x2Ly5Gi+xbU7o9Zok54ty681FwLLNGRI5Hsb7emPmdtJdDCHiN3+S9KfvTWNUyi70O5FzloeCGlE7zCOZI5aE2yyNRtkYePU3OdFizrwxUZkMVnboK2x2qd9Fgt58tgJYETlSnFhrp0biDy4yPLBgTsCNwqS1ArD+phoL4m1zm/eNzUfFSbWKbSQdCOJtM5tAyUTkbQjSpXrSqshJFxla2pjN+1qY1L66IrLPRYbaMHlZjSFkv4sMETlgX3n3LxwtD3wuTJq2p51P9pchSCeMkPEg3ja3+cBE5GGMB3HeRYYHPtXbXAWuFCbVaYctUbSIdJGQdpGmyv4ucigIuU47bDcYsWi0OTkRzEDa+GTL+GEXGMQ8au1U1tbOBDWSjmwaQ56EnElp1WsbKwKehOL/Y0ikL20/Gw0thyjp08bNtLo6mfwhctjFgM6dMh3c+i6OFBg+jGYisb6ei8hOFws6qskxmlaoBFze4u3UmNjZScq0iOzuhBiN0ELaTwAlHcppAairNqAr6WNn34lNxExGjYzOnTI5E2l55ikR2RVNjM6dcgYTW3MS+EnMzzzzXEfFKHRko0cgUwE0F0AEPF1NkrPKobngmWc2EVOOLkah0w4dFGiPXUv+xXSQB3gCvuioRwtAz3z9m8hB+/+uElMUmtg5JZEYMTNRb7M2i5ntiWk35D+MzJyI7HHdDmntNmvv98cYkfHMt912guSI2XYi8sRsWxEmZ+J8LO2hB9cUvwAgwNgEokieZAAAAABJRU5ErkJggg==" width="35px" alt="search--v1">
                        
                    </button>
                </div>
                <div id="pincode-error" class="error-message" style="display: none;">Please enter a valid 6-digit
                    pincode</div>
            </div>
            <div id="loading-spinner" class="loading-spinner" style="display: none;">
                <div class="spinner"></div>
                <div>Checking delivery options...</div>
            </div>
        </div>
        <div id="data_of_pin" class="delivery-estimate"></div>
        <?php if (sizeof($countries) > 1) { ?>
            <!-- Trigger/Open The Modal -->


            <!-- The Modal -->
            <div id="myModal" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <input style="width: 80%" type="text" id="myInput" onkeyup="myFunction()"
                        placeholder="Search for names..">

                    <ul id="myUL">
                        <?php foreach ($countries as $code => $country) { ?>
                            <li> <a class="bt_sst_country_select" href="#"
                                    data-country="<?php echo esc_attr($code) ?>"><?php echo esc_html($country) ?></a> </li>
                        <?php } ?>

                    </ul>
                </div>

            </div>
            <style>
                /* The Modal (background) */
                .bt_sync_shimpent_track_pincode_checker_wrap.print_x .modal {
                    display: none;
                    /* Hidden by default */
                    position: fixed;
                    /* Stay in place */
                    z-index: 999;
                    /* Sit on top */
                    left: 0;
                    top: 0;
                    width: 100%;
                    /* Full width */

                    height: 100%;
                    /* Full height */
                    overflow: auto;
                    /* Enable scroll if needed */
                    background-color: rgb(0, 0, 0);
                    /* Fallback color */
                    background-color: rgba(0, 0, 0, 0.4);
                    /* Black w/ opacity */
                }
            </style>

            <script>
                // const targetNode = document.getElementById('data_of_pin');

                // document.getElementById('pin_input_btn').addEventListener('click', function() {
                //     document.getElementsById('delivery-form').style = 'display:none'
                //     document.getElementById('loading-spinner').style = 'display:block'
                // });

                //     const observer = new MutationObserver((mutationsList, observer) => {
                //         //   document.getElementsByClassName('delivery-form').style = 'display:block'
                //     document.getElementById('loading-spinner').style = 'display:none'
                //     });

                //     const config = { childList: true, subtree: true, characterData: true };

                //     observer.observe(targetNode, config);



                // Get the modal
                var modal = document.getElementById("myModal");

                // Get the button that opens the modal
                var btn = document.getElementById("myBtn");

                // Get the <span> element that closes the modal
                var span = document.getElementsByClassName("close")[0];

                // When the user clicks on the button, open the modal
                btn.onclick = function (e) {
                    e.preventDefault();
                    modal.style.display = "block";
                }

                // When the user clicks on <span> (x), close the modal
                span.onclick = function () {
                    modal.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function (event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                } 
            </script>


            <script>
                function myFunction() {
                    // Declare variables
                    var input, filter, ul, li, a, i, txtValue;
                    input = document.getElementById('myInput');
                    filter = input.value.toUpperCase();
                    ul = document.getElementById("myUL");
                    li = ul.getElementsByTagName('li');

                    // Loop through all list items, and hide those who don't match the search query
                    for (i = 0; i < li.length; i++) {
                        a = li[i].getElementsByTagName("a")[0];
                        txtValue = a.textContent || a.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            li[i].style.display = "";
                        } else {
                            li[i].style.display = "none";
                        }
                    }
                }



                const boxes = document.querySelectorAll('.bt_sst_country_select');

                boxes.forEach(box => {
                    box.addEventListener('click', function handleClick(e) {
                        e.preventDefault();
                        modal.style.display = "none";
                        var base_country = '<?php echo esc_js($shop_country) ?>';
                        var selected_country_code = this.dataset.country;
                        var selected_country = this.textContent;
                        document.getElementById("bt_sst_delivery_estimate_country").value = selected_country_code;
                        if (base_country != selected_country_code) {

                            document.getElementById("pin_input_box").value = selected_country;
                            document.getElementById("bt_sst_delivery_base_country").textContent = selected_country;
                        } else {
                            document.getElementById("pin_input_box").value = "";
                            document.getElementById("bt_sst_delivery_base_country").textContent = base_country;
                        }
                    });
                });



            </script>
        <?php } ?>
    </div>
</div>
<?php ?>

<style>
    .bt_sync_shimpent_track_pincode_checker_wrap.print_x {
        width: 100%;
        max-width: 500px;
        /* margin: 20px auto; */
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        background: white;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x #bt_sync_shimpent_track_pincode_checker {
        width: 97% !important;
        border: none !important;
        background-color: none !important;
        max-width: unset !important;
        min-width: unset !important;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-checker-header {
        align-items: center;
        justify-content: space-between;
        display: flex;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        padding: 18px 20px;
        font-size: 18px;
        font-weight: 600;
    }
</style>
<style>
    /* The Modal (background) */
    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 999;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */

        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }


    /* Modal Content/Box */
    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .modal-content {
        background-color: #fefefe;
        margin: 13% auto;
        /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        /* Could be more or less, depending on screen size */
        max-width: 350px;
        position: relative;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .modal-content ul {
        max-height: 40vh;
        overflow: scroll;
    }

    /* The Close Button */
    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .close {
        color: #000;
        position: absolute;
        font-size: 28px;
        font-weight: bold;
        top: -20px;
        right: -10px;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .close:hover,
    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<style>
    .bt_sync_shimpent_track_pincode_checker_wrap.print_x #myInput {
        background-image: url('<?php echo esc_url($plugin_public_url); ?>images/searchicon.png');
        background-position: 10px 12px;
        /* Position the search icon */
        background-repeat: no-repeat;
        /* Do not repeat the icon image */
        width: 100%;
        /* Full-width */
        font-size: 16px;
        /* Increase font-size */
        padding: 12px 20px 12px 40px;
        /* Add some padding */
        border: 1px solid #ddd;
        /* Add a grey border */
        margin-bottom: 12px;
        /* Add some space below the input */
        background-color: white;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x #myUL {
        /* Remove default list styling */
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x #myUL li a {
        border: 1px solid #ddd;
        /* Add a border to all links */
        margin-top: -1px;
        /* Prevent double borders */
        background-color: #f6f6f6;
        /* Grey background color */
        padding: 12px;
        /* Add some padding */
        text-decoration: none;
        /* Remove default text underline */
        font-size: 18px;
        /* Increase the font-size */
        color: black;
        /* Add a black text color */
        display: block;
        /* Make it into a block element to fill the whole list */
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x #myUL li a:hover:not(.header) {
        background-color: #eee;
        /* Add a hover effect to all links, except for headers */
    }
</style>

<style>
 

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-checker-widget {
        width: 100%;
        max-width: 500px;
        margin: 20px auto;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        background: white;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-checker-header {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        padding: 18px 20px;
        font-size: 18px;
        font-weight: 600;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-checker-body {
        padding: 20px;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-estimate {
        align-items: center;
        margin-bottom: 20px;
        padding: 15px;
        background-color: #f9fafb;
        border-radius: 8px;
        border-left: 4px solid #4f46e5;
        display: none;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-icon {
        font-size: 24px;
        margin-right: 15px;
        color: #4f46e5;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-info {
        flex: 1;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-location {
        font-weight: 600;
        margin-bottom: 5px;
        color: #111827;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-timeframe {
        font-size: 14px;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-cutoff {
        font-size: 13px;
        color: #4f46e5;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed #e5e7eb;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-form {
        width: 100%
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .form-group {
        display: flex;
        width: 100%;
        gap: 10px;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .input-wrapper {
        position: relative;
        flex: 1;
        min-width: 0;
        /* Fix flexbox overflow */
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        width: 20px;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .form-input {
        width: 100% !important;
        padding: 10px 12px 10px 40px !important;
        border: 1px solid #d1d5db;
        border-radius: 6px !important;
        font-size: 14px !important;
        transition: border-color 0.3s !important;
        margin: 0;
        height: unset;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .form-input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-form .form-group .search-btn {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        border: none;
        padding: 0 20px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 80px;
        justify-content: center;
        margin: 0;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-form .form-group .search-btn img{
        width: 20px;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .search-btn:hover {
        opacity: 0.9;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .search-btn i {
        font-size: 14px;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .loading-spinner {
        display: none;
        text-align: center;
        padding: 15px;
        width: 100%;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .spinner {
        border: 3px solid rgba(79, 70, 229, 0.1);
        border-radius: 50%;
        border-top: 3px solid #4f46e5;
        width: 24px;
        height: 24px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .error-message {
        color: #dc2626;
        font-size: 13px;
        margin-top: 5px;
        display: none;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .change-location {
        color: #4f46e5;
        font-size: 13px;
        text-decoration: underline;
        cursor: pointer;
        display: inline-block;
        margin-top: 10px;
    }

    .bt_sync_shimpent_track_pincode_checker_wrap.print_x .change-location:hover {
        color: #4338ca;
    }

    @media (max-width: 600px) {
        .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-checker-widget {
            margin: 10px;
            border-radius: 8px;
        }

        .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-checker-header {
            padding: 15px;
            font-size: 16px;
        }

        .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-checker-header .delivery-checker-header-img {
            margin-right: 10px;
            width: 20px;
        }

        .bt_sync_shimpent_track_pincode_checker_wrap.print_x .delivery-checker-body {
            padding: 15px;
        }

        .bt_sync_shimpent_track_pincode_checker_wrap.print_x .form-group {
            flex-direction: row;
            /* Keep row layout on mobile */
        }

        .bt_sync_shimpent_track_pincode_checker_wrap.print_x .search-btn {
            padding: 0 15px;
            min-width: 60px;
        }

        .bt_sync_shimpent_track_pincode_checker_wrap.print_x .search-btn span {
            display: none;
            /* Hide "CHECK" text on mobile */
        }

        .bt_sync_shimpent_track_pincode_checker_wrap.print_x .search-btn i {
            margin-right: 0;
        }
    }
</style>