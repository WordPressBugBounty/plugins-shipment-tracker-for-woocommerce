// jQuery(document).ready(function ($) {
//     let timeLeft = jQuery("#bt_sst_total_seconds").val();
//     let totalTime = timeLeft;

//     function updateTimer() {
//         let hours = Math.floor(timeLeft / 3600);
//         let minutes = Math.floor((timeLeft % 3600) / 60);
//         let seconds = timeLeft % 60;

//         $("#bt_sst_timer_page_hours").text(hours.toString().padStart(2, '0'));
//         $("#bt_sst_timer_page_minutes").text(minutes.toString().padStart(2, '0'));
//         $("#bt_sst_timer_page_seconds").text(seconds.toString().padStart(2, '0'));

//         let progressPercentage = (timeLeft / totalTime) * 100;
//         $("#bt_sst_timer_page_progress-bar").css("width", progressPercentage + "%");

//         if (timeLeft <= 0) {
//             clearInterval(timer);
//             $(".bt_sst_timer_page_stock").text("Sale Ended!");
//         } else {
//             timeLeft--;
//         }
//     }

//     let timer = setInterval(updateTimer, 1000);
//     updateTimer(); // Run immediately on page load
// });

jQuery(document).ready(function ($) {
    // Function to get cookie value
    function getCookie(name) {
        let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? decodeURIComponent(match[2]) : null;
    }

    // Function to set cookie
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + encodeURIComponent(value) + "; path=/" + expires;
    }

    let startTime = getCookie("bt_sst_start_time");
    let totalSeconds = parseInt($("#bt_sst_total_seconds").val(), 10);

    let currentTime = Math.floor(Date.now() / 1000); // Current timestamp in seconds

    if (startTime) {
        let elapsedTime = currentTime - parseInt(startTime, 10);
        timeLeft = Math.max(0, totalSeconds - elapsedTime); // Ensure it doesn't go negative
    } else {
        timeLeft = totalSeconds;
        setCookie("bt_sst_start_time", currentTime, null, 5); // Store start time in cookie (1-day expiry)
    }

    let totalTime = totalSeconds;

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
            $(".bt_sst_timer_page_stock").text("Offer has been closed!");
        } else {
            timeLeft--;
        }
    }

    let timer = setInterval(updateTimer, 1000);
    updateTimer(); // Run immediately on page load
});
