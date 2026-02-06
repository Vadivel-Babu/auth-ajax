$(document).ready(function () {
  let token = localStorage.getItem("session");

  if (token) {
    window.location.href = "index.html";
    return;
  }
  $("#loginBtn").click(function () {
    let email = $("#email").val().trim();
    let password = $("#password").val().trim();

    if (email === "" || password === "") {
      alert("Email and Password are required");
      return;
    }

    $.ajax({
      url: "php/login.php",
      method: "POST",
      data: {
        email: $("#email").val(),
        password: $("#password").val(),
      },
      success: function (res) {
        let data = JSON.parse(res);
        console.log(data);

        if (data.status === "success") {
          localStorage.setItem("session", data.token);
          window.location.href = "index.html";
        } else {
          alert("Invalid login");
        }
      },
    });
  });
});
