document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registroForm');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            // Reiniciar mensajes de error
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(message => message.textContent = '');
            
            let hasErrors = false;
            
            // Validar email
            const email = document.getElementById('email');
            if (!email.value.trim()) {
                document.getElementById('emailError').textContent = 'El email es obligatorio.';
                hasErrors = true;
            } else if (!isValidEmail(email.value)) {
                document.getElementById('emailError').textContent = 'El formato del email no es válido.';
                hasErrors = true;
            }
            
            // Validar nombre
            const nombre = document.getElementById('nombre');
            if (!nombre.value.trim()) {
                document.getElementById('nombreError').textContent = 'El nombre es obligatorio.';
                hasErrors = true;
            }
            
            // Validar contraseña
            const password = document.getElementById('password');
            if (!password.value) {
                document.getElementById('passwordError').textContent = 'La contraseña es obligatoria.';
                hasErrors = true;
            } else if (password.value.length < 6) {
                document.getElementById('passwordError').textContent = 'La contraseña debe tener al menos 6 caracteres.';
                hasErrors = true;
            }
            
            // Validar confirmación de contraseña
            const passwordConfirm = document.getElementById('password_confirm');
            if (password.value !== passwordConfirm.value) {
                document.getElementById('passwordConfirmError').textContent = 'Las contraseñas no coinciden.';
                hasErrors = true;
            }
            
            // Si hay errores, no enviar el formulario
            if (hasErrors) {
                event.preventDefault();
            }
        });
    }
    
    // Validar formato de email
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});