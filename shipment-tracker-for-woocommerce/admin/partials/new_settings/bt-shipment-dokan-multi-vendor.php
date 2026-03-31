<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ── Option key ────────────────────────────────────────────────────────────────
define( 'BT_SST_DOKAN_SETTINGS_KEY', 'bt_sst_dokan_vendor_settings' );

// ── Shipping providers supported ──────────────────────────────────────────────
$bt_sst_shipping_providers = [
    ''           => 'None (disabled)',
    'shiprocket' => 'Shiprocket',
    'delhivery'  => 'Delhivery',
    'ekart' => 'Ekart',
    'shipmozo'   => 'Shipmozo',
];

// ── Load all saved vendor settings ───────────────────────────────────────────
$all_vendor_settings = get_option( BT_SST_DOKAN_SETTINGS_KEY, [] );
if ( ! is_array( $all_vendor_settings ) ) {
    $all_vendor_settings = [];
}

// ── Handle POST (save single vendor) ─────────────────────────────────────────
$bt_sst_save_notice    = '';
$bt_sst_saved_vendor   = 0;
if ( isset( $_POST['bt_sst_dokan_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bt_sst_dokan_nonce'] ) ), 'bt_sst_dokan_save' ) ) {
    $vendor_id         = absint( $_POST['selected_vendor'] ?? 0 );
    $shipping_provider = sanitize_text_field( $_POST['shipping_provider'] ?? '' );
    $pickup_location   = sanitize_text_field( $_POST['pickup_location']  ?? '' );

    if ( $vendor_id > 0 ) {
        $all_vendor_settings[ $vendor_id ] = [
            'shipping_provider' => $shipping_provider,
            'pickup_location'   => $pickup_location,
        ];
        update_option( BT_SST_DOKAN_SETTINGS_KEY, $all_vendor_settings );
        $bt_sst_save_notice  = 'success';
        $bt_sst_saved_vendor = $vendor_id;
    } else {
        $bt_sst_save_notice = 'error';
    }
}

// ── Handle DELETE ─────────────────────────────────────────────────────────────
if ( isset( $_POST['bt_sst_dokan_delete_nonce'] )
    && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bt_sst_dokan_delete_nonce'] ) ), 'bt_sst_dokan_delete' )
    && isset( $_POST['bt_sst_delete_vendor_id'] )
) {
    $del_id = absint( $_POST['bt_sst_delete_vendor_id'] );
    if ( isset( $all_vendor_settings[ $del_id ] ) ) {
        unset( $all_vendor_settings[ $del_id ] );
        update_option( BT_SST_DOKAN_SETTINGS_KEY, $all_vendor_settings );
    }
    wp_safe_redirect( remove_query_arg( 'vendor_id' ) );
    exit;
}

// ── Fetch all Dokan vendors ───────────────────────────────────────────────────
$vendors = get_users( [
    'role__in' => [ 'seller', 'vendor', 'dc_vendor' ],
    'orderby'  => 'display_name',
    'order'    => 'ASC',
] );

// ── Pass saved settings + providers to JS ─────────────────────────────────────
$saved_settings_json   = wp_json_encode( $all_vendor_settings );
$providers_json        = wp_json_encode( $bt_sst_shipping_providers );
?>

<style>
/* ── Card grid ────────────────────────────────────────────────────────────── */
.bt-sst-vendor-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

/* ── Single vendor card ───────────────────────────────────────────────────── */
.bt-sst-vendor-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    transition: box-shadow .2s, transform .2s;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.bt-sst-vendor-card:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,.12);
    transform: translateY(-2px);
}

/* Card header strip */
.bt-sst-card-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: .85rem;
}

/* Avatar circle */
.bt-sst-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #e8f4fd;
    color: #0f3460;
    font-weight: 700;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 2px solid rgba(255,255,255,.25);
    text-transform: uppercase;
}

.bt-sst-card-vendor-name {
    color: #fff;
    font-weight: 600;
    font-size: .95rem;
    line-height: 1.3;
    word-break: break-word;
}
.bt-sst-card-vendor-email {
    color: rgba(255,255,255,.6);
    font-size: .78rem;
}

/* Configured badge */
.bt-sst-badge-configured {
    margin-left: auto;
    background: #22c55e;
    color: #fff;
    font-size: .65rem;
    font-weight: 600;
    letter-spacing: .04em;
    padding: 2px 8px;
    border-radius: 999px;
    white-space: nowrap;
    flex-shrink: 0;
}
.bt-sst-badge-unconfigured {
    margin-left: auto;
    background: rgba(255,255,255,.18);
    color: rgba(255,255,255,.75);
    font-size: .65rem;
    font-weight: 600;
    letter-spacing: .04em;
    padding: 2px 8px;
    border-radius: 999px;
    white-space: nowrap;
    flex-shrink: 0;
}

/* Card body */
.bt-sst-card-body {
    padding: 1.1rem 1.25rem;
    display: flex;
    flex-direction: column;
    gap: .9rem;
    flex: 1;
}

/* Field label */
.bt-sst-field-label {
    font-size: .8rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: .3rem;
    display: block;
}

/* Selects & inputs inside cards */
.bt-sst-vendor-card select,
.bt-sst-vendor-card input[type="text"] {
    width: 100%;
    padding: .45rem .7rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: .87rem;
    color: #1f2937;
    background: #f9fafb;
    transition: border-color .15s, box-shadow .15s;
    -webkit-appearance: none;
    appearance: none;
}
.bt-sst-vendor-card select:focus,
.bt-sst-vendor-card input[type="text"]:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.18);
    background: #fff;
}

/* Loader row */
.bt-sst-pickup-loader {
    display: none;
    align-items: center;
    gap: .5rem;
    font-size: .8rem;
    color: #6b7280;
    padding: .3rem 0;
}
.bt-sst-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid #d1d5db;
    border-top-color: #3b82f6;
    border-radius: 50%;
    animation: bt-spin .7s linear infinite;
    flex-shrink: 0;
}
@keyframes bt-spin { to { transform: rotate(360deg); } }

/* Hint text */
.bt-sst-hint {
    font-size: .75rem;
    color: #9ca3af;
    margin-top: .25rem;
}

/* Card footer buttons */
.bt-sst-card-footer {
    display: flex;
    gap: .6rem;
    padding: .9rem 1.25rem;
    border-top: 1px solid #f3f4f6;
    background: #fafafa;
}
.bt-sst-btn-save {
    flex: 1;
    background: #0f3460;
    color: #fff;
    border: none;
    padding: .5rem 1rem;
    border-radius: 8px;
    font-size: .85rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s, transform .1s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
}
.bt-sst-btn-save:hover { background: #1a4a8a; }
.bt-sst-btn-save:active { transform: scale(.97); }
.bt-sst-btn-save svg { width: 15px; height: 15px; }

.bt-sst-btn-delete {
    background: #fff;
    color: #ef4444;
    border: 1px solid #fca5a5;
    padding: .5rem .8rem;
    border-radius: 8px;
    font-size: .85rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s, border-color .2s;
    display: flex;
    align-items: center;
    gap: .35rem;
}
.bt-sst-btn-delete:hover { background: #fff1f1; border-color: #ef4444; }
.bt-sst-btn-delete svg { width: 14px; height: 14px; }

/* Success flash on card */
.bt-sst-card-saved-flash {
    display: none;
    align-items: center;
    gap: .4rem;
    font-size: .8rem;
    font-weight: 600;
    color: #16a34a;
    padding: .3rem 0;
}

/* Search / filter bar */
.bt-sst-search-wrap {
    position: relative;
    max-width: 320px;
}
.bt-sst-search-wrap input {
    width: 100%;
    padding: .5rem .85rem .5rem 2.2rem;
    border: 1px solid #d1d5db;
    border-radius: 999px;
    font-size: .88rem;
    background: #fff;
    transition: border-color .15s, box-shadow .15s;
}
.bt-sst-search-wrap input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.18);
}
.bt-sst-search-icon {
    position: absolute;
    left: .7rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    pointer-events: none;
}

/* Responsive tweak for narrow screens (WP admin sidebar) */
@media (max-width: 960px) {
    .bt-sst-vendor-grid { grid-template-columns: 1fr; }
}
</style>

<div class="wrap" style="max-width:1100px;">

    <!-- ── Header ──────────────────────────────────────────────────────────── -->
    <nav class="panel is-dark">
        <div class="panel-heading">
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <div>
                            <p class="title has-text-white">Shipment Tracker For WooCommerce</p>
                            <p class="heading has-text-grey-light">Dokan Multi-Vendor Settings</p>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item">
                        <a href="#" class="js-modal-trigger" data-target="modal-bt-company-about">
                            <figure class="image is-48x48">
                                <img alt="Bitss Techniques"
                                    src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) ) . '../images/Bitss-Techniques-Logo-80x80.png'; ?>">
                            </figure>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav><!-- /panel -->

    <div class="content mt-4">

        <?php if ( empty( $vendors ) ) : ?>

            <!-- No vendors -->
            <div class="notification is-warning">
                <span class="icon"><i data-lucide="alert-triangle"></i></span>
                <strong>No vendors found.</strong>
                Please make sure the <strong>Dokan Multi-Vendor</strong> plugin is active and at least one vendor account exists.
            </div>

        <?php else : ?>

            <!-- ── Global save notice ──────────────────────────────────────── -->
            <?php if ( $bt_sst_save_notice === 'success' ) : ?>
                <div class="notification is-success is-light" id="bt-sst-global-notice">
                    <button class="delete" onclick="this.parentElement.remove()"></button>
                    <strong>Settings saved successfully!</strong>
                </div>
            <?php elseif ( $bt_sst_save_notice === 'error' ) : ?>
                <div class="notification is-danger is-light">
                    <button class="delete" onclick="this.parentElement.remove()"></button>
                    <strong>Error:</strong> Could not save — missing vendor ID.
                </div>
            <?php endif; ?>

            <!-- ── Toolbar: heading + search ──────────────────────────────── -->
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem; margin-bottom:.25rem;">
                <p class="subtitle mb-0 p-0" style="font-weight:700; font-size:1.05rem; margin:0;">
                    <span class="icon-text">
                        <span>All Vendors &nbsp;<span style="font-weight:400; color:#9ca3af; font-size:.88rem;">(<?php echo count( $vendors ); ?>)</span></span>
                    </span>
                </p>
                <div class="bt-sst-search-wrap">
                    <svg class="bt-sst-search-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="bt-sst-vendor-search" placeholder="Search vendors…" autocomplete="off">
                </div>
            </div>
            <p class="help mb-0">Configure a shipping provider and pickup location for each vendor individually.</p>

            <!-- ── Vendor cards grid ───────────────────────────────────────── -->
            <div class="bt-sst-vendor-grid" id="bt-sst-vendor-grid">

                <?php foreach ( $vendors as $vendor ) :
                    $vid           = $vendor->ID;
                    $vsettings     = $all_vendor_settings[ $vid ] ?? [];
                    $saved_provider = $vsettings['shipping_provider'] ?? '';
                    $saved_pickup   = $vsettings['pickup_location']   ?? '';
                    $is_configured  = ! empty( $saved_provider );

                    // Pull avatar initials from display name
                    $name_parts = preg_split('/\s+/', trim( $vendor->display_name ) );
                    $initials   = strtoupper( substr( $name_parts[0], 0, 1 ) . ( isset( $name_parts[1] ) ? substr( $name_parts[1], 0, 1 ) : '' ) );
                ?>

                <div class="bt-sst-vendor-card"
                    data-vendor-id="<?php echo esc_attr( $vid ); ?>"
                    data-vendor-name="<?php echo esc_attr( strtolower( $vendor->display_name ) ); ?>"
                    data-vendor-email="<?php echo esc_attr( strtolower( $vendor->user_email ) ); ?>">

                    <!-- Card header -->
                    <div class="bt-sst-card-header">
                        <div class="bt-sst-avatar"><?php echo esc_html( $initials ); ?></div>
                        <div style="min-width:0; flex:1;">
                            <div class="bt-sst-card-vendor-name"><?php echo esc_html( $vendor->display_name ); ?></div>
                            <div class="bt-sst-card-vendor-email"><?php echo esc_html( $vendor->user_email ); ?></div>
                        </div>
                        <?php if ( $is_configured ) : ?>
                            <span class="bt-sst-badge-configured">✓ Configured</span>
                        <?php else : ?>
                            <span class="bt-sst-badge-unconfigured">Not set</span>
                        <?php endif; ?>
                    </div>

                    <!-- Card body = inline form -->
                    <form method="post" class="bt-sst-card-form">
                        <?php wp_nonce_field( 'bt_sst_dokan_save', 'bt_sst_dokan_nonce' ); ?>
                        <input type="hidden" name="selected_vendor" value="<?php echo esc_attr( $vid ); ?>">

                        <div class="bt-sst-card-body">

                            <!-- Shipping Provider -->
                            <div>
                                <label class="bt-sst-field-label" for="bt_sst_provider_<?php echo esc_attr( $vid ); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; margin-right:3px;"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                    Shipping Provider
                                </label>
                                <select id="bt_sst_provider_<?php echo esc_attr( $vid ); ?>"
                                        name="shipping_provider"
                                        class="bt-sst-provider-select"
                                        data-vendor-id="<?php echo esc_attr( $vid ); ?>"
                                        data-saved-pickup="<?php echo esc_attr( $saved_pickup ); ?>">
                                    <?php foreach ( $bt_sst_shipping_providers as $pval => $plabel ) : ?>
                                        <option value="<?php echo esc_attr( $pval ); ?>"
                                            <?php selected( $saved_provider, $pval ); ?>>
                                            <?php echo esc_html( $plabel ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Pickup Location -->
                            <div class="bt-sst-pickup-field-wrap">
                                <label class="bt-sst-field-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; margin-right:3px;"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    Pickup Location
                                </label>

                                <!-- Loader -->
                                <div class="bt-sst-pickup-loader" id="bt_sst_loader_<?php echo esc_attr( $vid ); ?>">
                                    <div class="bt-sst-spinner"></div>
                                    <span>Fetching pickup locations…</span>
                                </div>

                                <!-- Dynamic dropdown (shown when API returns results) -->
                                <div id="bt_sst_pickup_select_wrap_<?php echo esc_attr( $vid ); ?>" style="display:none;">
                                    <select id="bt_sst_pickup_select_<?php echo esc_attr( $vid ); ?>"
                                            name="pickup_location"
                                            class="bt-sst-pickup-select">
                                        <option value="">— Select Pickup Location —</option>
                                    </select>
                                </div>

                                <!-- Manual text fallback -->
                                <div id="bt_sst_pickup_manual_wrap_<?php echo esc_attr( $vid ); ?>" style="display:none;">
                                    <input type="text"
                                        id="bt_sst_pickup_manual_<?php echo esc_attr( $vid ); ?>"
                                        name="pickup_location"
                                        placeholder="e.g. Primary, Mumbai Warehouse"
                                        maxlength="200"
                                        value="<?php echo esc_attr( $saved_pickup ); ?>">
                                    <p class="bt-sst-hint" id="bt_sst_manual_hint_<?php echo esc_attr( $vid ); ?>">
                                        Enter name as configured in provider portal.
                                    </p>
                                </div>

                                <!-- Placeholder when no provider selected -->
                                <p class="bt-sst-hint" id="bt_sst_pickup_hint_<?php echo esc_attr( $vid ); ?>">
                                    Select a shipping provider to load pickup locations.
                                </p>
                            </div>

                        </div><!-- /card-body -->

                        <!-- Card footer: save + delete -->
                        <div class="bt-sst-card-footer">
                            <button type="submit" class="bt-sst-btn-save">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Save
                            </button>

                            <?php if ( $is_configured ) : ?>
                            <button type="button" class="bt-sst-btn-delete bt-sst-delete-vendor"
                                data-vendor-id="<?php echo esc_attr( $vid ); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                Remove
                            </button>
                            <?php endif; ?>
                        </div>

                    </form><!-- /card form -->

                </div><!-- /card -->

                <?php endforeach; ?>

            </div><!-- /grid -->

            <!-- No-results message for search -->
            <div id="bt-sst-no-results" style="display:none; text-align:center; padding:2.5rem; color:#9ca3af;">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; margin:0 auto .75rem; opacity:.5;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                No vendors match your search.
            </div>

            <!-- Hidden delete form -->
            <form method="post" id="bt-sst-dokan-delete-form" style="display:none;">
                <?php wp_nonce_field( 'bt_sst_dokan_delete', 'bt_sst_dokan_delete_nonce' ); ?>
                <input type="hidden" name="bt_sst_delete_vendor_id" id="bt_sst_delete_vendor_id" value="">
            </form>

            <!-- ── Help / Info box ──────────────────────────────────────────── -->
            <div class="box mt-5">
                <p class="subtitle is-6" style="font-weight:bold;">ℹ️ How does this work?</p>
                <div class="content">
                    <ul>
                        <li><strong>Shipping Provider:</strong> Sets which shipping aggregator is used for this vendor's shipments.</li>
                        <li><strong>Pickup Location:</strong> The pickup address registered in your provider
                            (e.g. Shiprocket → Settings → Pickup Addresses). Overrides the global pickup location for vendor orders.</li>
                    </ul>
                </div>
            </div>

        <?php endif; // vendors found ?>

    </div><!-- /content -->
</div><!-- /wrap -->

<script>
(function ($) {
    // ── Data from PHP ──────────────────────────────────────────────────────────
    var savedSettings = <?php echo $saved_settings_json; ?>;
    var savedVendorId = '<?php echo esc_js( (string) $bt_sst_saved_vendor ); ?>';
    var ajaxUrl       = (typeof bt_sync_shipment_track_data !== 'undefined')
                        ? bt_sync_shipment_track_data.ajax_url
                        : '<?php echo esc_js( admin_url('admin-ajax.php') ); ?>';
    var nonce         = (typeof bt_sync_shipment_track_data !== 'undefined')
                        ? bt_sync_shipment_track_data.dokan_pickup_nonce
                        : '';

    // ── Fetch pickup locations for ONE card ────────────────────────────────────
    function fetchPickupLocations(vendorId, provider, savedPickup) {
        var $loader     = $('#bt_sst_loader_'            + vendorId);
        var $selectWrap = $('#bt_sst_pickup_select_wrap_' + vendorId);
        var $select     = $('#bt_sst_pickup_select_'     + vendorId);
        var $manualWrap = $('#bt_sst_pickup_manual_wrap_' + vendorId);
        var $manual     = $('#bt_sst_pickup_manual_'     + vendorId);
        var $hint       = $('#bt_sst_pickup_hint_'       + vendorId);
        var $manualHint = $('#bt_sst_manual_hint_'       + vendorId);

        // Reset
        $loader.hide();
        $selectWrap.hide();
        $manualWrap.hide();
        $hint.hide();

        // Disable all name="pickup_location" inputs/selects in this card to avoid double-submit
        $selectWrap.find('select').prop('disabled', true);
        $manualWrap.find('input').prop('disabled', true);

        if (!provider) {
            $hint.show();
            return;
        }

        $loader.css('display', 'flex');

        $.ajax({
            url: ajaxUrl,
            method: 'POST',
            data: {
                action:   'bt_sst_get_dokan_pickup_locations',
                provider: provider,
                nonce:    nonce,
            },
            success: function (res) {
                $loader.hide();

                if (res.success && res.data.locations && res.data.locations.length > 0) {
                    $select.empty().append('<option value="">— Select Pickup Location —</option>');
                    $.each(res.data.locations, function (i, loc) {
                        var opt = $('<option>').val(loc.value).text(loc.label);
                        if (savedPickup && loc.value === savedPickup) {
                            opt.prop('selected', true);
                        }
                        $select.append(opt);
                    });
                    $select.prop('disabled', false);
                    $selectWrap.show();
                } else {
                    var msg = (res.success && res.data.locations && res.data.locations.length === 0)
                        ? 'No pickup locations found. Enter manually.'
                        : (res.data && res.data.message ? res.data.message : 'Could not fetch locations. Enter manually.');
                    $manualHint.text(msg);
                    $manual.val(savedPickup || '').prop('disabled', false);
                    $manualWrap.show();
                }
            },
            error: function () {
                $loader.hide();
                $manualHint.text('Network error. Enter pickup location manually.');
                $manual.val(savedPickup || '').prop('disabled', false);
                $manualWrap.show();
            }
        });
    }

    $(document).ready(function () {

        // ── On load: initialise each vendor card ───────────────────────────────
        $('.bt-sst-vendor-card').each(function () {
            var vid         = $(this).data('vendor-id').toString();
            var $provider   = $(this).find('.bt-sst-provider-select');
            var provider    = $provider.val();
            var savedPickup = $provider.data('saved-pickup') || '';
            fetchPickupLocations(vid, provider, savedPickup);
        });

        // ── Provider change on a card ──────────────────────────────────────────
        $(document).on('change', '.bt-sst-provider-select', function () {
            var vid      = $(this).data('vendor-id').toString();
            var provider = $(this).val();
            fetchPickupLocations(vid, provider, '');
        });

        // ── Delete vendor ──────────────────────────────────────────────────────
        $(document).on('click', '.bt-sst-delete-vendor', function (e) {
            e.preventDefault();
            if (!confirm('Remove settings for this vendor? This cannot be undone.')) return;
            $('#bt_sst_delete_vendor_id').val($(this).data('vendor-id'));
            $('#bt-sst-dokan-delete-form').submit();
        });

        // ── Vendor search / filter ─────────────────────────────────────────────
        $('#bt-sst-vendor-search').on('input', function () {
            var q = $(this).val().trim().toLowerCase();
            var visible = 0;
            $('.bt-sst-vendor-card').each(function () {
                var name  = $(this).data('vendor-name')  || '';
                var email = $(this).data('vendor-email') || '';
                var show  = !q || name.indexOf(q) !== -1 || email.indexOf(q) !== -1;
                $(this).toggle(show);
                if (show) visible++;
            });
            $('#bt-sst-no-results').toggle(visible === 0);
        });

        // ── Lucide icons ───────────────────────────────────────────────────────
        if (typeof lucide !== 'undefined') lucide.createIcons();

        // ── Flash saved card ───────────────────────────────────────────────────
        if (savedVendorId) {
            var $card = $('.bt-sst-vendor-card[data-vendor-id="' + savedVendorId + '"]');
            if ($card.length) {
                $card[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                $card.css({ outline: '3px solid #22c55e', outlineOffset: '3px' });
                setTimeout(function () { $card.css({ outline: '', outlineOffset: '' }); }, 2500);
            }
        }
    });

})(jQuery);
</script>
