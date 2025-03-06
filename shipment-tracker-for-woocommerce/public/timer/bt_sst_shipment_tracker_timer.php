<?php
if (!isset($total_seconds)) {
    $total_seconds = 59 * 60;
}
?>
<div class="bt_sst_timer_container">
<div class="bt_sst_timer_heading">
    <h2>Hurry sale ends in</h2>
</div>
<input type="hidden" id="bt_sst_total_seconds" value="<?php echo $total_seconds; ?>">
<div class="bt_sst_timer_page_timer">
    <div>
        <div class="bt_sst_timer_page_box" id="bt_sst_timer_page_hours">00</div>
        <div class="bt_sst_timer_hour_min_sec">Hours</div>
    </div>
    <div class="bt_sst_timer_colon">:</div>
    <div>
        <div class="bt_sst_timer_page_box" id="bt_sst_timer_page_minutes">00</div>
        <div class="bt_sst_timer_hour_min_sec">Minutes</div>
    </div>
    <div class="bt_sst_timer_colon">:</div>
    <div>
        <div class="bt_sst_timer_page_box" id="bt_sst_timer_page_seconds">00</div>
        <div class="bt_sst_timer_hour_min_sec">Seconds</div>
    </div>
</div>

<div class="bt_sst_timer_page_progress-container">
    <div class="bt_sst_timer_page_progress-bar" id="bt_sst_timer_page_progress-bar"></div>
</div>

<p class="bt_sst_timer_page_stock">Only <span id="bt_sst_timer_page_stock">few</span> left in stock</p>
</div>
