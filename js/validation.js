$(document).ready(function() {
    if (localStorage.getItem("time_updated") == "updated") {
        $("#success-message").css("display", "block");
        $("#alert-time-form").css("display", "none");
    } else {
        $("#success-message").css("display", "none");
        $("#alert-time-form").css("display", "block");
    }
});

function send_alert_time() {
    $.post(
        "php/register.php",
        Object.assign({ time: $("input:radio[name='time']:checked").val() }),
        function(data) {
            localStorage.setItem("time_updated", "updated");
            $("#success-message").css("display", "block");
            $("#alert-time-form").css("display", "none");
        },
        "json"
    );
}

function on_call_me_now() {
    $.post(
        "php/register.php",
        Object.assign({ time: "instant_call" }),
        function(data) {
            localStorage.setItem("time_updated", "updated");
            $("#success-message").css("display", "block");
            $("#alert-time-form").css("display", "none");
        },
        "json"
    );
}