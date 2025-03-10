jQuery(document).ready(function ($) {
    let timeLeft = jQuery("#bt_sst_total_seconds").val();
    let totalTime = timeLeft;

    function updateTimer() {
        let hours = Math.floor(timeLeft / 3600);
        let minutes = Math.floor((timeLeft % 3600) / 60);
        let seconds = timeLeft % 60;

        $("#bt_sst_timer_page_hours").text(hours.toString().padStart(2, '0'));
        $("#bt_sst_timer_page_minutes").text(minutes.toString().padStart(2, '0'));
        $("#bt_sst_timer_page_seconds").text(seconds.toString().padStart(2, '0'));

        let progressPercentage = (timeLeft / totalTime) * 100;
        $("#bt_sst_timer_page_progress-bar").css("width", progressPercentage + "%");

        if (timeLeft <= 0) {
            clearInterval(timer);
            $(".bt_sst_timer_page_stock").text("Sale Ended!");
        } else {
            timeLeft--;
        }
    }

    let timer = setInterval(updateTimer, 1000);
    updateTimer(); // Run immediately on page load
});