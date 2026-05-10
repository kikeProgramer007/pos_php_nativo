class ValidadorCaja {
    constructor() {
        this.verificarEstadoCaja();
    }

    async verificarEstadoCaja() {
        try {
            const response = await fetch(`ajax/arqueo.ajax.php?accion=verificarCaja&idUsuario=${idUsuario}`);
            const data = await response.json();
            
            if (!data || data.error) {
                this.mostrarModalCajaCerrada();
                this.deshabilitarFuncionalidades();
            }
        } catch (error) {
            console.error('Error al verificar el estado de la caja:', error);
            this.mostrarError('Error al verificar el estado de la caja');
        }
    }

    mostrarModalCajaCerrada() {
        swal({
            title: 'Caja Cerrada',
            text: 'La caja está cerrada. Es necesario realizar la apertura de caja antes de continuar. ¿Desea redirigirse a la vista de arqueo de caja para abrirla?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, ir a apertura de caja',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                window.location.href = 'arqueo-de-caja';
            }
        });
        // Agregar IDs después de que el modal se renderice
        swal.getConfirmButton().id = 'btnConfirmarApertura';
        swal.getCancelButton().id = 'btnCancelarApertura';
    }

    deshabilitarFuncionalidades() {
        // Deshabilitar elementos del formulario
        const elementos = [
            'nuevoVendedor',
            'seleccionarMesero',
            'cliente',
            'formaAtencion',
            'nuevoValorEfectivo',
            'tipoPago',
            'nota'
        ];

        elementos.forEach(id => {
            const elemento = document.getElementById(id);
            if (elemento) {
                elemento.disabled = true;
            }
        });

        // Deshabilitar botones
        const botones = document.querySelectorAll('button');
        botones.forEach(boton => {
            if (boton.id !== 'btnCancelar' && boton.id !== 'btnConfirmarApertura' && boton.id !== 'btnCancelarApertura') {
                boton.disabled = true;
            }
        });

        // Deshabilitar la tabla de productos
        const tabla = document.querySelector('.tablaVentas');
        if (tabla) {
            tabla.style.pointerEvents = 'none';
            tabla.style.opacity = '0.6';
        }
    }

    mostrarError(mensaje) {
        swal({
            type: 'error',
            title: 'Error',
            text: mensaje
        });
    }
}

// Inicializar el validador cuando el documento esté listo
document.addEventListener('DOMContentLoaded', () => {
    const validadorCaja = new ValidadorCaja();
}); 