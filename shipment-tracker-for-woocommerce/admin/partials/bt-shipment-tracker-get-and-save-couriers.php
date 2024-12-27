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
<div id="bt_sst_manual_courier_popup" class="bt_sst_modal" style="display:none;">
    <div class="bt_sst_modal_background"></div>
    <div class="bt_sst_modal_content">
        <div class="bt_sst_box">
            <span class="bt_sst_modal_close" aria-label="close">x</span>
            <h3 class="bt_sst_title">Add New Courier</h3>
            <form class="bt_sst_manual_courier_form">
                <div class="bt_sst_field">
                    <label for="bt_sst_manual_courier_company_name">Company Name*</label>
                    <input type="text" id="bt_sst_manual_courier_company_name" placeholder="Company Name" name="company_name">
                </div>

                <div class="bt_sst_field">
                    <label for="bt_sst_manual_courier_region_coverage">Region Coverage*</label>
                    <input type="text" id="bt_sst_manual_courier_region_coverage" placeholder="Region Coverage" name="region_coverage">
                </div>

                <div class="bt_sst_field">
                    <label for="bt_sst_manual_courier_company_url">Company Website URL*</label>
                    <input type="url" id="bt_sst_manual_courier_company_url" placeholder="https://#" name="company_url">
                </div>

                <div class="bt_sst_field">
                    <label for="bt_sst_manual_courier_tracking_url">Tracking URL*</label>
                    <input type="url" id="bt_sst_manual_courier_tracking_url" placeholder="https://#" name="tracking_url">
                    <div>Variables: #awb#, #orderid#</div>
                </div>

                <div class="bt_sst_field">
                    <button type="button" id="bt_sst_manual_courier_save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
