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
        alert("Registration successful");
        window.location = "index.html";
      },
      error: function () {
        alert("Registration failed");
      },
    });
  });
});
