$(document).ready(function () {
  let token = localStorage.getItem("session");
  const user = JSON.parse(localStorage.getItem("user"));

  if (!token) {
    window.location.href = "login.html";
    return;
  }

  loadProfile();

  function loadProfile() {
    $.ajax({
      url: "php/profile.php",
      method: "GET",
      data: { token: token, id: user.id },
      dataType: "json",
      success: function (res) {
        const user = res.user;

        $("#name-text").text(user.name ?? "no name");
        $("#email-text").text(user.email ?? "no mail");
        $("#dob-text").text(user.age ?? "00");
        $("#contact-text").text(user.contact ?? " - ");

        $("#name").val(user.name ?? "no name");
        $("#email").val(user.email ?? "no mail");
        $("#age").val(user.age ?? "00");
        $("#contact").val(user.contact ?? "no number");
      },
    });
  }

  //handle update the user details
  $("#updateBtn").click(function () {
    $.ajax({
      url: "php/updateProfile.php",
      method: "POST",
      data: {
        token: token,
        id: user.id,
        name: $("#name").val(),
        email: $("#email").val(),
        age: $("#age").val(),
        contact: $("#contact").val(),
      },
      success: function (res) {
        alert(res.message);
        loadProfile();
      },
      error: function (res) {
        alert(res.responseJSON.message);
        loadProfile();
      },
    });
  });

  $("#logoutBtn").on("click", function () {
    if (token) {
      $.ajax({
        url: "php/logout.php",
        type: "POST",
        data: { token: token },
        success: function (res) {
          localStorage.removeItem("session");
          localStorage.removeItem("user");
          window.location.href = "login.html";
        },
        error: function () {
          // even if backend fails, still clear local
          localStorage.removeItem("session");
          localStorage.removeItem("user");
          window.location.href = "login.html";
        },
      });
    } else {
      window.location.href = "index.html";
    }
  });
});
