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

            // INICIALIZAR GRÁFICOS SI ES LA VISTA DE REPORTES
            if (destino.includes("reportes/menu.php")) {
                let intentos = 0;
                function intentarGraficos() {
                    const ocupacion = document.getElementById('ocupacionChart');
                    const ingresosC = document.getElementById('ingresosChart');
                    if (ocupacion && ingresosC) {
                        // Solicita los datos dinámicos al backend
                        fetch('reportes/reportes_datos.php')
                            .then(res => res.json())
                            .then(datos => {
                                inicializarGraficosReportes(datos.meses, datos.ingresos, datos.personas);
                            })
                            .catch(() => {
                                // Si falla, usa datos de ejemplo
                                inicializarGraficosReportes();
                            });
                    } else if (intentos < 50) {
                        intentos++;
                        setTimeout(intentarGraficos, 100);
                    } else {
                        console.error("No se encontraron los canvas para los gráficos.");
                    }
                }
                intentarGraficos();
            }

            // INICIALIZAR FULLCALENDAR SI EXISTE EL DIV
            setTimeout(() => {
                const calendarEl = document.getElementById('calendar');
                console.log('calendarEl:', calendarEl); // Para depuración
                if (calendarEl && typeof FullCalendar !== 'undefined') {
                    // Limpia el contenido previo si lo hubiera
                    calendarEl.innerHTML = '';
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        height: 650,
                        events: 'calendario/eventos' // <-- Agrega esta línea para cargar eventos vía AJAX
                    });
                    calendar.render();
                }
            }, 0);

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
              if (
                document.querySelector("#tablaHabitaciones") &&
                typeof inicializarDataTableHabitaciones === "function"
              ) {
                inicializarDataTableHabitaciones();
              }
              // Agrega aquí más inicializadores si tienes más tablas
            }, 0);
          })
          .catch((error) => console.error("Error al cargar la vista:", error));
      }
    });
  });
});
