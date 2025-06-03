// Centraliza la carga AJAX de vistas del menú lateral y la inicialización de DataTables
document.addEventListener("DOMContentLoaded", function () {
  const navbarOptions = document.querySelectorAll(
    "#navbar-options .nav-link[data-vista]"
  );

  navbarOptions.forEach((enlace) => {
    enlace.addEventListener("click", function (event) {
      event.preventDefault();
      const destino = enlace.getAttribute("data-vista");
      if (destino) {
        fetch(destino)
          .then((response) => response.text())
          .then((data) => {
            document.querySelector("#contenido").innerHTML = data;

            // Limpia DataTable anterior si existe (para todas las tablas)
            const tablas = [
              "#tablaAlquileres",
              "#tablaUsuarios",
              "#tablaClientes",
              "#tablaPersonas",
              "#tablaReservas",
              // Agrega aquí los IDs de todas tus tablas DataTable
            ];
            tablas.forEach((selector) => {
              const tabla = document.querySelector(selector);
              if (tabla) {
                if (
                  window.jQuery &&
                  $.fn.DataTable &&
                  $.fn.DataTable.isDataTable(tabla)
                ) {
                  $(tabla).DataTable().destroy();
                }
                tabla.classList.remove("dt-loaded");
              }
            });

            setTimeout(() => {
              if (
                document.querySelector("#tablaClientes") &&
                typeof inicializarDataTableClientes === "function"
              ) {
                inicializarDataTableClientes();
              }
              if (
                document.querySelector("#tablaPersonas") &&
                typeof inicializarDataTablePersonas === "function"
              ) {
                inicializarDataTablePersonas();
              }
              if (
                document.querySelector("#tablaAlquileres") &&
                typeof inicializarDataTableAlquileres === "function"
              ) {
                inicializarDataTableAlquileres();
              }
              if (
                document.querySelector("#tablaUsuarios") &&
                typeof inicializarDataTableUsuarios === "function"
              ) {
                inicializarDataTableUsuarios();
              }
              // Agrega aquí más inicializadores si tienes más tablas
            }, 0);
          })
          .catch((error) => console.error("Error al cargar la vista:", error));
      }
    });
  });
});
