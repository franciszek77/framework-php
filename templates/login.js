// Obtener la URL base de la API desde una variable global (definida en PHP)
const MyEndpoint = {
  root: window.MyEndpointRoot || "/framework-php/public/api/",
};

$(document).ready(function () {
  $("#loginButton").on("click", function () {
    $.ajax({
      url: MyEndpoint.root + "login",
      type: "POST",
      data: {
        username: $("#username").val(),
        password: $("#password").val(),
      },
      dataType: "json",
      success: function (response) {
        $("#loginResponse").html(
          '<div class="alert alert-' +
            (response.success ? "success" : "danger") +
            '">' +
            response.message +
            "</div>"
        );
        if (response.success) {
          setTimeout(
            () => (window.location.href = "/framework-php/public/dashboardd"),
            1000
          );
        }
      },
      error: function (xhr, status, error) {
        $("#loginResponse").html(
          '<div class="alert alert-danger">Error: ' + error + "</div>"
        );
      },
    });
  });

  $("#queryButton").on("click", function () {
    $.ajax({
      url: MyEndpoint.root + "project_query/v1/query/",
      type: "POST",
      data: {
        query_id: $("#query_id").val(),
      },
      dataType: "json",
      success: function (response) {
        $("#queryResponse").html(
          '<div class="alert alert-' +
            (response.success ? "success" : "danger") +
            '">' +
            response.message +
            (response.data ? JSON.stringify(response.data) : "") +
            "</div>"
        );
      },
      error: function (xhr, status, error) {
        $("#queryResponse").html(
          '<div class="alert alert-danger">Error: ' + error + "</div>"
        );
      },
    });
  });
});
