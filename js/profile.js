$(document).ready(function () {
  let token = localStorage.getItem("session");
  const user = JSON.parse(localStorage.getItem("user"));
  console.log(user);

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
      success: function (res) {
        let user = res.user;
        $("#name-text").text(user.name ?? "no name");
        $("#email-text").text(user.email ?? "no mail");
        $("#dob-text").text(user.dob ?? "00.00.0000");
        $("#contact-text").text(user.contact ?? "+00 00000 00000");

        $("#name").val(user.name ?? "no name");
        $("#email").val(user.email ?? "no mail");
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
      },
      success: function () {
        alert("Profile updated");
      },
    });
  });
});

//handle logout
$("#logoutBtn").click(function () {
  localStorage.removeItem("session");
  localStorage.removeItem("user");
  window.location.href = "login.html";
});
