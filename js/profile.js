$(document).ready(function () {
  let token = localStorage.getItem("session");
  console.log(token);

  if (!token) {
    window.location.href = "login.html";
    return;
  }

  loadProfile();

  function loadProfile() {
    $.ajax({
      url: "php/getProfile.php",
      method: "GET",
      data: { token: token },
      success: function (res) {
        let user = JSON.parse(res);
        $("#name").val(user.name);
        $("#age").val(user.age);
        $("#dob").val(user.dob);
        $("#contact").val(user.contact);
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
        age: $("#age").val(),
        dob: $("#dob").val(),
        contact: $("#contact").val(),
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
  window.location.href = "login.html";
});
