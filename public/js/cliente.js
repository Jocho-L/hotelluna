/* document.addEventListener("click", function (e) {
  const btn = e.target.closest("#btnRegistrar");  // Verifica que el clic se dio en el botón correcto

  if (btn) {
    e.preventDefault(); // Previene el comportamiento por defecto del enlace

    const destino = btn.getAttribute("data-vista");
    console.log("🧭 Cargando vista desde:", destino); // Verificar si este log aparece en consola

    fetch(destino)
      .then(response => {
        if (!response.ok) {
          throw new Error("No se pudo cargar: " + response.statusText); // Captura si hubo error en la carga
        }
        return response.text();  // Recupera la respuesta como texto
      })
      .then(html => {
        const contenedor = document.getElementById("contenido");
        if (contenedor) {
          contenedor.innerHTML = html;  // Cambia el contenido del div con id "contenido"
        } else {
          console.error("❌ No se encontró el contenedor #contenido");
        }
      })
      .catch(err => {
        console.error("🚨 Error al cargar la vista:", err);
      });
  }
});
 */