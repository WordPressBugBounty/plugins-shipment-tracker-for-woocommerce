<?php
$countries = WC()->countries->get_shipping_countries();
$plugin_public_url = plugin_dir_url(dirname(__FILE__));
$shop_country = WC()->countries->get_base_country();
$bt_sst_product_page_delivery_checker_label = carbon_get_theme_option('bt_sst_product_page_delivery_checker_label');
if ($bt_sst_product_page_delivery_checker_label == '') {
    $display_check = 'none';
} else {
    $display_check = 'bloxk';
}


?>
<div class="bt_sync_shimpent_track_pincode_checker_wrap print_x">
    <div class="delivery-checker-header" style="display: <?php echo $display_check; ?>;">

        <div style="display:flex; align-items: center;">
            <img class="delivery-checker-header-img" width="25px"
                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAACsUlEQVR4nO2ZPWgUQRTHf4p6oF5OkhQKIoiFMaXYiIVEbbRQz8Paxu/YipZ2AREDtgZiYWdvo0bQ+FFoUsXEIwgmiIpg44Eg6sqDt7AsO7Ozt3N3icwPHhy7b97//vtmdmfvIBAIBAKBcmwAxoEvQNSF+ArcBip45k6XDKTjlm8j33tkRDrjlaiHEYxkUeQKPgLOAUPAJg35fB54sho60gQOOtQaARZXqpHnQH+BegPAdEEjtpzPwH1gVxkjTYsJ21UdcOyM68WMgB/A0XaN2KZT3vQ4VNJIzA7ggR77CRzAgEnksWlAapyNKQ9GhLXAhB5fBgbJwCRyNis5Y5yNC56MCOuAZ3ruIbCGFCaR3elEwzgbQx6NCNuBb3r+KilMIlXMDDsaqXo2IhwD/gK/gP2UMHIGaGnOHN03ItzUnI/Ju6rr1NoITCbOT+qT3caeDhlZD7zUvHt5RmTbkeS0Hm9pV1y42CEjwk69Hf8B9mIRkb1Tmhu6Plx52kEj6Auh5N4lR0j2Tu1yJKd2npHjDhr7NFd2EVahRd1uFEUeWB9KGokKxG+XItMFzQwmFmK3jESuRaQzhx2nk0snemYk0pjSbYfcVjdrDOuxvIW9ooxEniMYIXSE/2dqzQEndCMocRKY75CRJaCR0KoDCz6MvANqGbfbWkkzWUaWDL8T9OvbobHWJwdB6YSJumcjDYtWvHFNh2znGXMQtL1k9Xk2Um1D63r8t8JYTmdsxWtdNFLLmIbX9McJK/GeSRa2iVOa84JyvNI6MlVNNDRHvlchRnXgvGGxbwHea84lynFF6yxYFntTcy4XLS7TbiYhUNd52qediE280dwyyPhZrbesCzvWaiRMvG1Xa1vCTFZI4a0lTSS1Zi1aM2W1Ktr61/rO3tI5PeqhE0W0Kp61AoEAq4x//SIceOLZ/iwAAAAASUVORK5CYII="
                alt="delivery--v1">
            <div style="margin:5px;" class="bt_sync_shimpent_track_pincode_checker_label_text">
                <?php
                echo wp_kses(apply_filters('bt_sst_product_page_delivery_checker_label_text', carbon_get_theme_option('bt_sst_product_page_delivery_checker_label')), 'post');
                ?>
            </div>
        </div>
        <div class="bt_sync_shimpent_track_pincode_checker_shipping_to_text">
            <?php
            if (sizeof($countries) > 1) {
                $bt_sync_shimpent_track_pincode_checker_shipping_to_text = '(<b id="bt_sst_delivery_base_country">' . $shop_country . '</b>)' . (sizeof($countries) > 1 ? ' <a href="#" id="primr_x_openCountryModalBtn">
                🖉
                </a>' : '');
            } else {
                $bt_sync_shimpent_track_pincode_checker_shipping_to_text = '';
            }
            echo wp_kses(apply_filters('bt_sync_shimpent_track_pincode_checker_shipping_to_text', $bt_sync_shimpent_track_pincode_checker_shipping_to_text), 'post');
            ?>
        </div>
    </div>
    <div class="delivery-checker-body">
        <div id="bt_sync_shimpent_track_pincode_checker">
            <div id="delivery-form" class="delivery-form" style="">
                <div class="form-group">
                    <div class="input-wrapper">
                        <img class="input-icon"
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAAByElEQVR4nO2Wu0oDURCGN42XRtFKjdhbik+gryBYe+nFyogiKb2diTn/bFxSpdNGYsBG8Q0U8QJCbFTQRmyUSFRQVo4aDTHuOZtsYuMPA8suZz7+OTPDWta/fhERtQspxwSQEcxZYn5Q8fm8Scyj86urbVZQIqJmwTwrmO+J2fUM4E4AM+pMVdBF5i4C9rTAkhDAYTwe76kIuiBltwCu/UKL4oocJ+y/vMBBFdCC8/1oKtVkDBbAXLXQIvi0qdt2o0YyDeDOqNsJGA8M+u16xKTMGU2iZwFMqY5/73rmiHqnAadNHJ9pwJEfZz7gv4OZsyaOc15JYrFYZ+mZJdvu0DjO6cHMT15JVHl/OHacsMZxXgsm4NJvqdXIaMAXesdAWtdcCu6zuTa04JhtDwc9TsQ8pAVLKRsJuA0MCtxEo9EGy0RBLhGj5VGQ67ohwbwbgNttlesrsYmklC0EHFcBPq34j4QcJyyAE9/lZT4qN+++tJBMthKw5eNOM6paVhByXTdEzJMEPHq4zBMw4ftOTbSSSPSqLVQGfK6+WbWUkLKfmF+LSvsipOyrKbQgAawXuV2z6qVlYPALLOVA3cDux4LZqWhB/LXeAHFxT8vrnIHLAAAAAElFTkSuQmCC"
                            alt="marker--v1">
                        <input id="pin_input_box" class="form-input" placeholder="Enter a Pincode" type="text">
                        <input id="bt_sst_delivery_estimate_country" value="<?php echo esc_attr($shop_country) ?>"
                            type="hidden">
                    </div>
                    <?php wp_nonce_field('get_pincode_data_product_page', 'get_pincode_data_product_page_nounce'); ?>
                    <button id="pin_input_btn" class="search-btn" type="button">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAADLklEQVR4nO1ZO2tUURA+Nj7wASKitkbU1kaw0Khhf4GlGkQhJJD/oGiRaG9vBEEskyWPVgvfpSY2NnYmATWbM5v7DY7MzaK7c282G+85uxvdD06zsN/Md+bMOTNzneuhh+5DReQYMd8i4DExvydgyQOJLgIWifkdARPEfLMictR1Gwi46JnLHmBillaWB+CZp3ySXOi0/45ETnjm2Vad31AU8zSJ9HVEhGce9EClqIi6CK145mttFUHA3SYOfSRgjICBqsgpEdmrqypyWn8jYNwD8xuKAu60S8S9DZx4TcClVnl8kpwj5hcdEeOZB3MisEbMQyKyY6t8+h9iHq5x2Ly5Gi+xbU7o9Zok54ty681FwLLNGRI5Hsb7emPmdtJdDCHiN3+S9KfvTWNUyi70O5FzloeCGlE7zCOZI5aE2yyNRtkYePU3OdFizrwxUZkMVnboK2x2qd9Fgt58tgJYETlSnFhrp0biDy4yPLBgTsCNwqS1ArD+phoL4m1zm/eNzUfFSbWKbSQdCOJtM5tAyUTkbQjSpXrSqshJFxla2pjN+1qY1L66IrLPRYbaMHlZjSFkv4sMETlgX3n3LxwtD3wuTJq2p51P9pchSCeMkPEg3ja3+cBE5GGMB3HeRYYHPtXbXAWuFCbVaYctUbSIdJGQdpGmyv4ucigIuU47bDcYsWi0OTkRzEDa+GTL+GEXGMQ8au1U1tbOBDWSjmwaQ56EnElp1WsbKwKehOL/Y0ikL20/Gw0thyjp08bNtLo6mfwhctjFgM6dMh3c+i6OFBg+jGYisb6ei8hOFws6qskxmlaoBFze4u3UmNjZScq0iOzuhBiN0ELaTwAlHcppAairNqAr6WNn34lNxExGjYzOnTI5E2l55ikR2RVNjM6dcgYTW3MS+EnMzzzzXEfFKHRko0cgUwE0F0AEPF1NkrPKobngmWc2EVOOLkah0w4dFGiPXUv+xXSQB3gCvuioRwtAz3z9m8hB+/+uElMUmtg5JZEYMTNRb7M2i5ntiWk35D+MzJyI7HHdDmntNmvv98cYkfHMt912guSI2XYi8sRsWxEmZ+J8LO2hB9cUvwAgwNgEokieZAAAAABJRU5ErkJggg=="
                            width="35px" alt="search--v1">

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
            <div id="primr_x_countryModal" class="primr_x_modal">
                <div class="primr_x_modal-content">
                    <span class="primr_x_modal-close">&times;</span>
                    <input style="width: 80%" type="text" id="primr_x_countrySearchInput"
                        onkeyup="primr_x_filterCountryList()" placeholder="Search for countries..." />

                    <ul id="primr_x_countryList">
                        <?php foreach ($countries as $code => $country) { ?>
                            <li>
                                <a class="bt_sst_country_select" href="#" data-country="<?php echo esc_attr($code) ?>">
                                    <?php echo esc_html($country) ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

        <?php } ?>
    </div>
</div>
<?php ?>