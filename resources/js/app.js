// resources/js/app.js

import * as bootstrap from 'bootstrap'; // Importamos el módulo Bootstrap
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// Variable global para la instancia de Toast (se inicializa una sola vez)
let copyToastInstance;
let toastMessageEl;

document.addEventListener('DOMContentLoaded', function () {
    
    // Inicialización Única del Toast
    const toastEl = document.getElementById('copyToast');

    if (toastEl) {
        copyToastInstance = new bootstrap.Toast(toastEl);
        toastMessageEl = document.getElementById('toast-message'); // Elemento para actualizar el mensaje
    }

    // Listener para los botones de copiado
    document.querySelectorAll('.copy-command-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            
            // 1. Obtener y copiar el comando
            let command = this.dataset.command;
            let copyText = "php kev " + command;
            navigator.clipboard.writeText(copyText);
            
            // 2. Mostrar el Toast
            if (copyToastInstance) {
                // Actualizamos el mensaje con el comando que se copió
                toastMessageEl.textContent = `Comando 'php kev ${command}' copiado.`;

                // Finalmente, mostramos el Toast
                copyToastInstance.show(); 
            }

            // ⚠️ Nota: Hemos eliminado el código de setTimeout/classList.replace
            // ya que el Toast cumple esa función de feedback.
        });
    });
});