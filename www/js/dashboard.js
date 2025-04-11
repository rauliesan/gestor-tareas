document.addEventListener('DOMContentLoaded', function() {
    // Filtrar tareas por estado
    const filtrosBtns = document.querySelectorAll('.btn-filter');
    const tareasCards = document.querySelectorAll('.tarea-card');
    
    if (filtrosBtns.length && tareasCards.length) {
        filtrosBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Actualizar botón activo
                filtrosBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const filtro = this.getAttribute('data-filter');
                
                // Mostrar u ocultar tareas según el filtro
                tareasCards.forEach(card => {
                    if (filtro === 'todas' || card.getAttribute('data-estado') === filtro) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }
    
    // Confirmar eliminación de tarea
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    if (deleteButtons.length) {
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(event) {
                const tareaId = this.getAttribute('data-id');
                const confirmar = confirm('¿Estás seguro de que deseas eliminar esta tarea?');
                
                if (!confirmar) {
                    event.preventDefault();
                }
            });
        });
    }
    
    // Mostrar mensaje en caso de que exista
    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('mensaje');
    const error = urlParams.get('error');
    
    if (mensaje || error) {
        let alertElement = document.createElement('div');
        alertElement.className = mensaje ? 'alert alert-success' : 'alert alert-error';
        
        switch (mensaje) {
            case 'tarea_creada':
                alertElement.textContent = 'La tarea ha sido creada correctamente.';
                break;
            case 'tarea_actualizada':
                alertElement.textContent = 'La tarea ha sido actualizada correctamente.';
                break;
            case 'tarea_eliminada':
                alertElement.textContent = 'La tarea ha sido eliminada correctamente.';
                break;
        }
        
        switch (error) {
            case 'id_invalido':
                alertElement.textContent = 'ID de tarea no válido.';
                break;
            case 'tarea_no_encontrada':
                alertElement.textContent = 'La tarea solicitada no existe o no tienes permisos para acceder a ella.';
                break;
        }
        
        // Insertar el mensaje en el DOM
        const dashboardHeader = document.querySelector('.dashboard-header');
        dashboardHeader.insertAdjacentElement('afterend', alertElement);
        
        // Eliminar el mensaje después de 5 segundos
        setTimeout(() => {
            alertElement.remove();
        }, 5000);
    }
});