<?php
$vendors = get_users( array(
    'role'    => 'seller',
    'orderby' => 'display_name',
    'order'   => 'ASC'
) );
?>

<div class="wrap container is-max-desktop has-background-white mt-5 p-5">
    <div class="container">
        <nav class="panel is-dark">
            <div class="panel-heading">
                <div class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <div>
                                <p class="title has-text-white">Shipment Tracker For WooCommerce</p>
                                <p class="heading has-text-grey-light">
                                    Premium Shipment Tracking System (Free Version)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="content">
            <p class="subtitle" style="font-weight: bold;">Dokan Vendor Settings</p>

            <div class="box">
                <?php if ( ! empty( $vendors ) ) : ?>

                    <!-- Shipping Provider -->
                    <div class="field">
                        <label class="label">Shipping Provider</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="shipping_provider">
                                    <option value="">None</option>
                                    <option value="shiprocket">Shiprocket</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Vendor Dropdown -->
                    <div class="field">
                        <label class="label">Select Vendor</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="selected_vendor">
                                    <option value="">Select Vendor</option>

                                    <?php foreach ( $vendors as $vendor ) : ?>
                                        <option value="<?php echo esc_attr( $vendor->ID ); ?>">
                                            <?php echo esc_html( $vendor->display_name ); ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Pickup Location -->
                    <div class="field">
                        <label class="label">Select Pickup Location</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="pickup_location">
                                    <option value="">Select Pickup Location</option>
                                    <option value="warehouse">Warehouse</option>
                                    <option value="store">Store</option>
                                </select>
                            </div>
                        </div>
                    </div>

                <?php else : ?>
                    <p>No vendors found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>