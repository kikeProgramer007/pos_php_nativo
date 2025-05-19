// Constantes
const ESTADO = {
    ABIERTA: 'abierta',
    CERRADA: 'cerrada'
};

const CONFIG = {
    INTERVALO_ACTUALIZACION: 1000,
    DECIMALES: 2
};
var popupWindow = null;

class ArqueoCaja {
    constructor() {
        this.idArqueo = document.getElementById('idArqueo').value;
        this.estado = document.getElementById('estado_caja').value;
        this.userlogeado = document.getElementById('idVendedor').value;
        this.totalEfectivoEnCaja = 0;
        this.totales = {
            montoApertura: 0,
            montoVentas: 0,
            totalEfectivoEnCaja: 0,
            gastosOperativos: 0,
            montoCompras: 0,
            totalIngresos: 0,
            totalEgresos: 0,
            resultadoNeto: 0,
            diferencia: 0
        };
     
        this.inicializarEventos();
    }

    inicializarEventos() {
        document.addEventListener('DOMContentLoaded', () => {
            this.inicializarInputsCantidad();
            this.verificarEstadoCaja();
            this.inicializarSelectorCaja();
        });
        setInterval(() => this.actualizarFechaHora(), CONFIG.INTERVALO_ACTUALIZACION);
        this.actualizarFechaHora();
    }

    actualizarFechaHora() {
        const ahora = new Date();
        const formatoFecha = {
            año: ahora.getFullYear(),
            mes: String(ahora.getMonth() + 1).padStart(2, '0'),
            dia: String(ahora.getDate()).padStart(2, '0'),
            horas: String(ahora.getHours()).padStart(2, '0'),
            minutos: String(ahora.getMinutes()).padStart(2, '0'),
            segundos: String(ahora.getSeconds()).padStart(2, '0')
        };

        const fechaHoraActual = `${formatoFecha.año}-${formatoFecha.mes}-${formatoFecha.dia}T${formatoFecha.horas}:${formatoFecha.minutos}:${formatoFecha.segundos}`;
        document.getElementById("fecha_apertura_cierre").value = fechaHoraActual;
    }

    inicializarInputsCantidad() {
        const inputs = document.querySelectorAll('.cantidad-input');
        
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                this.calcularSubtotal(input);
                this.calcularTotal();
            });

            input.addEventListener('keypress', this.validarSoloNumeros);
        });
    }

    validarSoloNumeros(e) {
        if (!/\d/.test(e.key)) {
            e.preventDefault();
        }
    }

    calcularSubtotal(input) {
        const valor = parseFloat(input.dataset.valor);
        const cantidad = parseFloat(input.value) || 0;
        const subtotal = valor * cantidad;
        
        const idBase = input.id.replace('cantidad_', '');
        const totalElement = document.getElementById(`Total_${idBase}bs`);
        
        if (totalElement) {
            totalElement.textContent = subtotal.toFixed(CONFIG.DECIMALES);
        }
    }

    calcularTotal() {
        this.totales.totalEfectivoEnCaja = Array.from(document.querySelectorAll('.cantidad-input'))
            .reduce((total, input) => {
                const valor = parseFloat(input.dataset.valor);
                const cantidad = parseFloat(input.value) || 0;
                return total + (valor * cantidad);
            }, 0);

        this.actualizarTotalesEnPantalla();
    }

    actualizarTotalesEnPantalla() {
       
        if (this.estado === ESTADO.CERRADA) {
            document.getElementById('monto_apertura').textContent = this.totales.totalEfectivoEnCaja.toFixed(CONFIG.DECIMALES);
        }
        
        this.totales.montoApertura = parseFloat(document.getElementById('monto_apertura').textContent),
        this.totales.montoVentas = parseFloat(document.getElementById('monto_ventas').textContent),
        this.totales.gastosOperativos = parseFloat(document.getElementById('gastos_operativos').textContent),
        this.totales.montoCompras = parseFloat(document.getElementById('monto_compras').textContent)
        this.totales.totalIngresos = this.totales.montoApertura + this.totales.montoVentas;
        this.totales.totalEgresos = this.totales.gastosOperativos + this.totales.montoCompras;
        this.totales.resultadoNeto = this.totales.totalIngresos - this.totales.totalEgresos;
    
        // Diferencia simplificada: efectivo real vs. resultado esperado
        this.totales.diferencia = this.totales.totalEfectivoEnCaja - Math.abs(this.totales.resultadoNeto);

        this.actualizarElementos({
            'total_efectivo_en_caja_tabla': this.totales.totalEfectivoEnCaja,
            'total_efectivo_en_caja': this.totales.totalEfectivoEnCaja,
            'total_ingresos': this.totales.totalIngresos,
            'total_egresos': this.totales.totalEgresos,
            'resultado_neto': this.totales.resultadoNeto,
            'total_ganancia_perdida': this.totales.resultadoNeto,
            'efectivo_en_caja': this.totales.totalEfectivoEnCaja,
            'diferencia': this.totales.diferencia
        });
    }

    actualizarElementos(elementos) {
        Object.entries(elementos).forEach(([id, valor]) => {
            const elemento = document.getElementById(id);
            if (elemento) {
                if (elemento.tagName === 'INPUT') {
                    elemento.value = valor.toFixed(CONFIG.DECIMALES);
                } else {
                    elemento.textContent = valor.toFixed(CONFIG.DECIMALES);
                }
            }
        });
    }

    async verificarEstadoCaja() {
        try {
            const response = await fetch(`ajax/arqueo.ajax.php?accion=verificarCaja&idUsuario=${idUsuario}`);
            const data = await response.json();
            
            this.actualizarInterfazSegunEstado(data);
        } catch (error) {
            this.mostrarError('Error al verificar el estado de la caja');
        }
    }

    actualizarInterfazSegunEstado(data) {
        const elementos = {
            idArqueo: document.getElementById('idArqueo'),
            radioApertura: document.getElementById('radio_apertura'),
            radioCierre: document.getElementById('radio_cierre'),
            estadoCaja: document.getElementById('estado_caja'),
            botonCaja: document.getElementById('aperturar_cierre_caja'),
            idCaja: document.getElementById('idCaja'),
            nroTicket: document.getElementById('nro_ticket'),
            btnImprimirMovimientos: document.getElementById('imprimirMovimientos')
        };

        if (data) {
            this.configurarCajaAbierta(elementos, data);
        } else {
            this.configurarCajaCerrada(elementos);
        }
    }

    configurarCajaAbierta(elementos, data) {
        this.idArqueo = data.id;
        elementos.idArqueo.value = data.id;
        elementos.radioApertura.checked = true;
        elementos.radioCierre.checked = false;
        elementos.estadoCaja.value = ESTADO.ABIERTA;
        elementos.botonCaja.textContent = 'Cerrar Caja';
        elementos.botonCaja.classList.replace('btn-primary', 'btn-danger');
        elementos.idCaja.disabled = true;
        elementos.nroTicket.disabled = true;
        elementos.btnImprimirMovimientos.disabled = false;
        if(this.userlogeado != data.id_usuario){
            elementos.botonCaja.disabled = true;
        }

        this.mostrarDatosApertura(data);
    }

    configurarCajaCerrada(elementos) {
        this.estado = ESTADO.CERRADA;
        elementos.idArqueo.value = '0';
        elementos.radioApertura.checked = false;
        elementos.radioCierre.checked = true;
        elementos.estadoCaja.value = ESTADO.CERRADA;
        elementos.nroTicket.value = '0';
        elementos.botonCaja.textContent = 'Aperturar Caja';
        elementos.botonCaja.classList.replace('btn-danger', 'btn-primary');
        elementos.idCaja.disabled = false;
        elementos.nroTicket.disabled = false;
        elementos.btnImprimirMovimientos.disabled = true;
    }

    mostrarDatosApertura(datos) {
        const campos = {
            'nro_ticket': datos.nroTicket  || '0',
            'monto_apertura': datos.monto_apertura || '0.00',
            'idCaja': datos.id_caja,
            'monto_ventas': datos.monto_ventas || '0.00',
            'total_ingresos': datos.total_ingresos || '0.00',
            'gastos_operativos': datos.gastos_operativos || '0.00',
            'monto_compras': datos.monto_compras || '0.00',
            'total_egresos': datos.total_egresos || '0.00',
            'resultado_neto': datos.resultado_neto || '0.00',
            'total_ganancia_perdida': datos.resultado_neto || '0.00',
            'efectivo_en_caja': datos.efectivo_en_caja || '0.00',
            'idVendedor': datos.id_usuario || '0',
            'usuario': datos.nameUsuario || 'no encontrado'
        };
        Object.entries(campos).forEach(([id, valor]) => {
            const elemento = document.getElementById(id);
            if (elemento) {
                if (elemento.tagName === 'INPUT' || elemento.tagName === 'SELECT') {
                    elemento.value = valor;
                } else {
                    elemento.textContent = valor;
                }
            }
        });
    }

    inicializarSelectorCaja() {
        const selectCaja = document.getElementById('idCaja');
        if (selectCaja) {
            selectCaja.addEventListener('change', () => {
                const idCaja = selectCaja.value;
                if (idCaja) {
                    this.obtenerNroTicket(idCaja);
                } else {
                    document.getElementById('nro_ticket').value = '0';
                }
            });
        }
    }

    async obtenerNroTicket(idCaja) {
        try {
            const response = await fetch(`ajax/arqueo.ajax.php?accion=obtenerNroTicket&idCaja=${idCaja}`);
            const data = await response.json();
            
            document.getElementById('nro_ticket').value = data?.nroTicket ? parseInt(data.nroTicket) : '0';
        } catch (error) {
            this.mostrarError('No se pudo obtener el número de ticket');
            document.getElementById('nro_ticket').value = '0';
        }
    }

    validarAperturaCierreCaja() {
        var condicionalEfeCaj;
        if(this.estado === ESTADO.ABIERTA){
            condicionalEfeCaj={ 
                condicion: parseFloat(document.getElementById('total_efectivo_en_caja').value) < 1,
                mensaje: 'Debe ingresar un monto mayor a 0 en efectivo en caja' 
            }
        }else{
            condicionalEfeCaj={
                condicion:parseFloat(document.getElementById('total_efectivo_en_caja').value) < 0,
                mensaje: 'Debe ingresar un monto mayor o igual a 0 en el efectivo en caja'
            }
        }
        const validaciones = [
            { condicion: !document.getElementById('idVendedor').value, mensaje: 'El usuario es obligatorio' },
            { condicion: !document.getElementById('idCaja').value, mensaje: 'Debe seleccionar una caja' },
            { condicion: !document.getElementById('nro_ticket').value || parseFloat(document.getElementById('nro_ticket').value) < 0, mensaje: 'El número de ticket es obligatorio' },
            { condicion: !document.getElementById('total_efectivo_en_caja').value, mensaje: 'Debe ingresar el campo efectivo en caja' },
            condicionalEfeCaj
        ];
    
        const error = validaciones.find(v => v.condicion);
        if (error) {
            toastr.error(error.mensaje, 'Error');
            return false;
        }
        return true;
    }

    async guardarAperturaCierreCaja() {
        if (!this.validarAperturaCierreCaja()) return;

        const fechaAperturaCierre = document.getElementById('fecha_apertura_cierre').value;
        const idCaja = document.getElementById('idCaja').value;
        const nroTicket = document.getElementById('nro_ticket').value;

        const data = this.estado === ESTADO.CERRADA ? 
            this.prepararDatosApertura(fechaAperturaCierre, nroTicket, idCaja) :
            this.prepararDatosCierre(fechaAperturaCierre, nroTicket, idCaja);
        const confirmacion = await this.confirmarOperacion(this.estado);
        if (confirmacion.value) {
            await this.enviarDatos(data);
        }
    }
    
    async imprimirMovimientosEnCaja(id=null){
        var codigoVenta;
        if(id===null){
            codigoVenta = this.idArqueo;
        }else{
            codigoVenta = id
        }

        // Tamaño de la ventana emergente
        var width = 800;
        var height = 600;
    
        // Configuración de la ventana emergente
        var left = (screen.width / 2) - (width / 2);
        var top = (screen.height / 2) - (height / 2);
        var windowFeatures = `menubar=no,toolbar=no,status=no,width=${width},height=${height},left=${left},top=${top}`;
    
        // Cierra la ventana emergente existente si está abierta
        if (popupWindow && !popupWindow.closed) {
            popupWindow.close();
        }
    
        // Abre la URL en una nueva ventana (popup)
        popupWindow = window.open("extensiones/tcpdf/pdf/movimientos-caja.php?codigo=" + codigoVenta, "_blank", windowFeatures);
    
    }

    prepararDatosApertura(fechaAperturaCierre, nroTicket, idCaja) {
        return {
            accion: 'AperturarCaja',
            fechaApertura: fechaAperturaCierre,
            montoApertura: this.totales.montoApertura,
            nroTicket,
            totalIngresos: this.totales.totalIngresos,
            resultadoNeto: this.totales.resultadoNeto,
            estado: ESTADO.ABIERTA,
            idCaja,
            idUsuario
        };
    }

    prepararDatosCierre(fechaAperturaCierre, nroTicket, idCaja) {
        return {
            accion: 'CerrarCaja',
            idArqueo: this.idArqueo,
            idUsuario,
            idCaja,
            fechaCierre: fechaAperturaCierre,
            nroTicket,
            estado: ESTADO.CERRADA,
            ...this.obtenerCantidadesEfectivo(),
            totalIngresos: this.totales.totalIngresos,
            montoVentas: this.totales.montoVentas,
            totalEgresos: this.totales.totalEgresos,
            gastosOperativos: this.totales.gastosOperativos,
            montoCompras: this.totales.montoCompras,
            resultadoNeto: this.totales.resultadoNeto,
            totalEfectivoEnCaja: this.totales.totalEfectivoEnCaja,
            diferencia: this.totales.diferencia
        };
    }

    obtenerCantidadesEfectivo() {
        const denominaciones = ['200', '100', '50', '20', '10', '5', '2', '1', '05', '02'];
        return denominaciones.reduce((acc, denom) => {
            acc[`cantidad_${denom}`] = document.getElementById(`cantidad_${denom}`).value || "0";
            return acc;
        }, {});
    }

    async confirmarOperacion(estado) {
        const config = estado === ESTADO.CERRADA ? 
            { title: '¿Aperturar Caja?', text: '¿Está seguro de aperturar la caja?' } :
            { title: '¿Cerrar Caja?', text: '¿Está seguro de cerrar la caja?' };

        return swal({
            ...config,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, confirmar'
        });
    }

    async enviarDatos(data) {
        try {
            const response = await $.ajax({
                url: 'ajax/arqueo.ajax.php',
                type: 'POST',
                data
            });
   
            let respuesta;
            try {
                respuesta = JSON.parse(response);
            } catch (parseError) {
                console.error('Respuesta no válida del servidor:', response);
                throw new Error('La respuesta del servidor no tiene el formato esperado');
            }

            if (respuesta.status === "ok") {
                await this.mostrarExito(this.estado);
                
                // Redirigir a crear-venta.php solo si es una apertura exitosa
                if (this.estado === ESTADO.CERRADA) {
                    setTimeout(() => {
                        window.location.href = 'crear-venta';
                    }, 200);
                } else {
                    this.imprimirMovimientosEnCaja();
                    window.location.reload();
                }
            } else {
                throw new Error(respuesta.mensaje || 'Error desconocido en la operación');
            }
        } catch (error) {
            console.error('Error completo:', error);
            let mensajeError = error.message;
            if (error.responseText) {
                console.error('Respuesta del servidor:', error.responseText);
                mensajeError = 'Error en la comunicación con el servidor';
            }
            this.mostrarError('Error al procesar la operación: ' + mensajeError);
        }
    }

    mostrarExito(estado) {
        return swal({
            type: 'success',
            title: 'Operación exitosa',
            text: estado === ESTADO.CERRADA ? 'Caja aperturada correctamente' : 'Caja cerrada correctamente',
            showConfirmButton: false,
            timer: 1500
        });
    }

    mostrarError(mensaje) {
        swal({
            type: 'error',
            title: 'Error',
            text: mensaje
        });
    }
}

// Inicializar la aplicación
const arqueoCaja = new ArqueoCaja();