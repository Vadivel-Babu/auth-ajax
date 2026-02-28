$(document).ready(function () {
  $("#registerBtn").click(function () {
    $.ajax({
      url: "php/register.php",
      method: "POST",
      data: {
        name: $("#name").val(),
        email: $("#email").val(),
        password: $("#password").val(),
      },
      success: function (res) {
        alert(res.message);
        window.location = "index.html";
      },
      error: function (res) {
        if (res.responseJSON.message) {
          alert(res.responseJSON.message);
        } else {
          alert("registeration failed");
        }
      },
    });
  });
});
