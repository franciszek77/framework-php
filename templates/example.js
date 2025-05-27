jQuery(document).ready(function ($) {
  // Ejemplo de CRUD con AJAX para el endpoint /framework-php/public/api/example

  function createUser(username, password) {
    $.ajax({
      url: window.MyEndpointRoot + "example",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify({ username: username, password: password }),
      success: function (response) {
        console.log("Usuario creado:", response);
      },
      error: function (xhr) {
        console.error("Error al crear usuario:", xhr.responseText);
      },
    });
  }

  function readAllUsers() {
    $.ajax({
      url: window.MyEndpointRoot + "example",
      type: "GET",
      success: function (response) {
        console.log("Usuarios:", response.data);
      },
      error: function (xhr) {
        console.error("Error al consultar usuarios:", xhr.responseText);
      },
    });
  }

  function readUser(id) {
    $.ajax({
      url: window.MyEndpointRoot + "example?id=" + id,
      type: "GET",
      success: function (response) {
        console.log("Usuario:", response.data);
      },
      error: function (xhr) {
        console.error("Error al consultar usuario:", xhr.responseText);
      },
    });
  }

  function updateUser(id, username, password) {
    $.ajax({
      url: window.MyEndpointRoot + "example",
      type: "PUT",
      contentType: "application/json",
      data: JSON.stringify({ id: id, username: username, password: password }),
      success: function (response) {
        console.log("Usuario actualizado:", response);
      },
      error: function (xhr) {
        console.error("Error al actualizar usuario:", xhr.responseText);
      },
    });
  }

  function deleteUser(id) {
    $.ajax({
      url: window.MyEndpointRoot + "example",
      type: "DELETE",
      contentType: "application/json",
      data: JSON.stringify({ id: id }),
      success: function (response) {
        console.log("Usuario eliminado:", response);
      },
      error: function (xhr) {
        console.error("Error al eliminar usuario:", xhr.responseText);
      },
    });
  }

  // Ejemplo de uso (descomentar para probar)
  /*
    createUser("testuser", "testpass");
    readAllUsers();
    readUser(1);
    updateUser(1, "newuser", "newpass");
    deleteUser(1);
    */
});
