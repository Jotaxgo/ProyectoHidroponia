// import jquery from 'jquery';
// window.$ = jquery;

// import select2 from 'select2';
// select2();

// import './bootstrap';

// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

// Alpine.start();

import './bootstrap';

// 1. Importar jQuery y hacerlo global para que Select2 lo encuentre
import jquery from 'jquery';
window.$ = jquery;

// 2. Importar Select2
import select2 from 'select2';
select2();

// 3. Importar Alpine (viene por defecto con Breeze)
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// 4. LÓGICA PARA ACTIVAR SELECT2 EN NUESTRO FILTRO
// Espera a que toda la página HTML esté cargada
document.addEventListener('DOMContentLoaded', function() {
    // Busca si existe un elemento con el ID 'filtro-dueño' en la página actual
    if (document.querySelector('#filtro-dueño')) {
        // Si existe, activa Select2 en él
        $('#filtro-dueño').select2();
    }
});