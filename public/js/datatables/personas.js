function inicializarDataTablePersonas() {
  const tabla = document.querySelector("#tablaPersonas");
  if (tabla && !tabla.classList.contains("dt-loaded")) {
    new DataTable(tabla, {
      dom: "Bfrtip",
      buttons: [{
        extend: "csvHtml5",
        text: "Exportar a CSV",
        title: "personas"
      }],
      language: {
        search: "Buscar:",
        lengthMenu: "Mostrar _MENU_ registros",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        paginate: {
          first: "Primero",
          last: "Último",
          next: "Siguiente",
          previous: "Anterior"
        },
        zeroRecords: "No se encontraron registros coincidentes",
        infoEmpty: "Mostrando 0 a 0 de 0 registros",
        infoFiltered: "(filtrado de _MAX_ registros en total)"
      }
    });
    tabla.classList.add("dt-loaded");
  }
}
window.inicializarDataTablePersonas = inicializarDataTablePersonas;