window.values = {};

function validate_form() {
  window.values["lastname"] = $("#id_lastname").val();
  window.values["firstname"] = $("#id_firstname").val();
  window.values["email"] = $("#id_email").val();
  window.values["phone"] = $("#id_phone").val();
  window.values["address"] = $("#id_address").val();
  window.values["zip"] = $("#id_zip").val();
  window.values["city"] = $("#id_city").val();
  window.values["house_type"] = $("#id_house_type").val();
  var gold = parseInt(window.values["house_type"]) === 1;

  var phone = window.values["phone"].replace(/[^\d]/g, "");
  if (phone !== window.values["phone"]) {
    $("#id_phone").val(phone);
    window.values["phone"] = phone;
  }

  var btn = $("#id_send_data");
  btn.prop("disabled", true);

  $.post(
    "php/register.php",
    Object.assign(window.values, {referrer: window.location.href}, {device: WURFL.form_factor}),
    function(data) {
      if (data.status) {
        localStorage.removeItem('time_updated');
        window.values = {};
        var page = "merci.php";
        if (data.quality) {
          var q = parseInt(data.quality);
          page =
            q === 1
              ? "merci.php"
              : q === 2
              ? "merci.html"
              : "felicitations.html";
        }
        top.location.href = page;
      } else {
        alert(data.msg);
        btn.prop("disabled", false);
      }
    },
    "json"
  );
}