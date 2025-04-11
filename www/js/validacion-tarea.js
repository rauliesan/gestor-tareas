document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('tareaForm');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            // Reiniciar mensajes de error
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(message => message.textContent = '');
            
            let hasErrors = false;
            
            // Validar título
            const titulo = document.getElementById('titulo');
            if (!titulo.value.trim()) {
                document.getElementById('tituloError').textContent = 'El título es obligatorio.';
                hasErrors = true;
            }
            
            // Si hay errores, no enviar el formulario
            if (hasErrors) {
                event.preventDefault();
            }
        });
    }
});