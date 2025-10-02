<?php
    // echo $check_skip_or_not; die;
    $class_is_active = "";
    if(!$check_active_registration && $check_skip_or_not===false){
        $class_is_active = 'is-active';
    }

    $current_user = wp_get_current_user();
    $default_first_name = !empty($current_user->first_name) ? $current_user->first_name : $current_user->display_name;
                        
?>

<!-- Modal Structure -->
<div class="modal <?php echo $class_is_active ?>" id="thankYouModal">
    <div class="modal-background"></div>
    <div class="modal-card" style="width: 600px; max-width: 90%;">
        <header class="modal-card-head has-background-link-light">
            <p class="modal-card-title  has-text-weight-semibold is-size-6" style="width: 95%;">
                🎉 Thanks for installing Shipment Tracker for WooCommerce!
            </p>
            <button class="delete" aria-label="close" id="closeModal"></button>
        </header>

        <section class="modal-card-body p-5">
            <div class=" notification is-info is-light is-size-6 mb-4">
							<strong>Register now & do more:</strong>
							<ul style="margin-left: 1.2em; list-style: disc; font-size: 14px;">
								<li>Enable SMS & WhatsApp order updates for your customers</li>
                            	<li>Reduce support queries & improve satisfaction</li>
								<li>Quick, free registration</li>
                                <li>🎁 <strong>BONUS:</strong> Sign up now and get <strong>50 messaging credits FREE</strong></li>
							</ul>
            </div>

            <div class="columns is-mobile is-multiline is-centered">
                <div class="column is-6">
                    <div class="field">
                        <div class="control has-icons-left">
                            <input class="input bt_sst_first_name" type="text" placeholder="Your Good Name" value="<?php echo esc_attr($default_first_name); ?>">
                            <span class="icon is-small is-left">
                                <i data-lucide="user" class="icon-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <div class="control has-icons-left">
                            <input class="input bt_sst_user_mobile" type="text" placeholder="Your Mobile Number">
                            <span class="icon is-small is-left">
                                <i data-lucide="smartphone" class="icon-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="field">
                        <div class="control  has-text-centered ">
                            * Your website url and admin email will be used for registration.
                        </div>
                    </div>
                </div>
            </div>

            <div class="buttons is-centered mt-4">
                 <button class="button is-light is-medium" id="bt_sst_no_thanks">
                    Skip
                </button>
                <button class="button  is-link  is-medium" id="bt_sst_signin">
                    Register →
                </button>
               
            </div>

            <p class="has-text-centered is-size-7 mt-4">
                
            </p>

            <hr class="my-4" />

            <div class="has-text-centered">
                <a target="_blank" href="https://shipment-tracker-for-woocommerce.bitss.tech/">Visit Plugin Website</a> |
                <a target="_blank" href="https://wa.me/+919462242982">Get in Touch</a>
            </div>
        </section>
    </div>
</div>

<script>
    jQuery('#closeModal, #closeModal2').on('click', function () {
        jQuery('#thankYouModal').removeClass('is-active');
    });

    jQuery('#bt_sst_signin').on('click', function () {
       

        var first_name = jQuery('.bt_sst_first_name').val();
        var user_mobile = jQuery('.bt_sst_user_mobile').val();

        if (!first_name || !user_mobile) {
            alert("Please enter all values.");
            return;
        }

         //code to add loading class in button
        jQuery(this).addClass('is-loading');

        jQuery.get(
            "<?php echo admin_url('admin-ajax.php'); ?>",
            {
                action: 'register_for_sms',
                value: 'register_for_sms',
                first_name: first_name,
                user_mobile: user_mobile
            },
            function (res) {
                jQuery('#bt_sst_signin').removeClass('is-loading');
                if (res.status) {
                    //jQuery('#thankYouModal').removeClass('is-active');
                    jQuery('#thankYouModal .modal-card-body').html(res.message + '<br><br>Reloading in 5 seconds...');
                    setTimeout(() => {
                        window.location.reload();
                    }, 5000);
                } else {
                    alert('An error occurred.');
                }
            },
            'json'
        );
    });
    jQuery('#bt_sst_no_thanks').on('click', function () {
        jQuery.ajax({
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            type: 'GET',
            dataType: 'json',
            data: {
                action: 'update_check_skip_or_not',
            },
            success: function (res) {
                if (res.success) {
                    jQuery('#thankYouModal').removeClass('is-active');
                } else {
                    alert('An error occurred.');
                }
            },
            error: function () {
                alert('AJAX request failed.');
            }
        });
    });

</script>
<style>
    /* reset wp default button styles */
.button.is-link:focus{
    background: #485fc7 !important;;
    border: black;
}
</style>