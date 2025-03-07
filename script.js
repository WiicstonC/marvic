// script.js

document.addEventListener('DOMContentLoaded', function () {
    // Ejemplo de funcionalidad para los filtros de la sección CREACIONES
    document.querySelectorAll('.btn-filter').forEach(btn => {
      btn.addEventListener('click', function() {
        const filter = this.getAttribute('data-filter');
        console.log("Filtrando por:", filter);
        // Aquí se podría implementar la lógica de filtrado de productos
      });
    });
  });
  