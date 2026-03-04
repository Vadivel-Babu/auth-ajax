$(document).ready(function () {
  let token = localStorage.getItem("session");

  if (token) {
    window.location.href = "index.html";
    return;
  }
  $("#loginBtn").click(function () {
    $.ajax({
      url: "php/login.php",
      method: "POST",
      data: {
        email: $("#email").val(),
        password: $("#password").val(),
      },
      success: function (res) {
        console.log(res);

        if (res.status === "success") {
          localStorage.setItem("session", res.token);
          localStorage.setItem("user", JSON.stringify(res.user));
          window.location.href = "index.html";
        }
      },
      error: function (res) {
        console.log(res);

        alert(res.responseJSON.message);
      },
    });
  });
});
