/*=============================================
CARGAR LA TABLA DINÁMICA DE VENTAS
=============================================*/

 /* $.ajax({

	url: "ajax/datatable-ventas.ajax.php",
	success:function(respuesta){
		
		console.log("respuesta", respuesta);

}
 })*/


/*=============================================
QUITAR PRODUCTOS DE LA VENTA Y RECUPERAR BOTÓN
=============================================*/

var idQuitarProducto = [];
var editarQRManual = false;

localStorage.removeItem("quitarProducto");

// Función para contar cuántas veces aparece un producto en la venta
function contarProductoEnVenta(idProducto) {
    var contador = 0;
    $(".nuevoProducto .nuevaDescripcionProducto").each(function() {
        if($(this).attr("idProducto") == idProducto) {
            contador++;
        }
    });
    return contador;
}
// funcion para sumar la cantidad de productos en la venta con el mismo id (UNIDADES REALES)
function sumarCantidadProductos(idProducto) {
    var suma = 0;
    $(".nuevoProducto .nuevaCantidadProducto").each(function() {
        if($(this).attr("data-idProducto") == idProducto) {
            if (window.PresentacionesVenta) {
                suma += PresentacionesVenta.unidadesDeInput($(this));
            } else {
                suma += parseInt($(this).val() || 0);
            }
        }
    });
    return suma;
}	

$(".formularioVenta").on("click", "button.quitarProducto", function(){
    var idProducto = $(this).attr("idProducto");
    
    // Eliminar el elemento
    var $fila = $(this).closest('.linea-venta');
    if (!$fila.length) $fila = $(this).closest('.row');
    $fila.remove();

    // Contar cuántas veces sigue apareciendo el producto
    var apariciones = contarProductoEnVenta(idProducto);

    // Solo habilitar el botón si no quedan apariciones del producto
    if(apariciones === 0) {
        // Habilitar el botón en el catálogo
        $("button.recuperarBoton[idProducto='"+idProducto+"']").removeClass('disabled');
        $("button.recuperarBoton[idProducto='"+idProducto+"']").attr('disabled', false);
        
        // Notificar al catálogo que el producto fue eliminado
        if(typeof catalogoProductos !== 'undefined') {
            catalogoProductos.productosAgregados.delete(idProducto);
            catalogoProductos.renderizarCatalogo();
        }
    }
	
    if($(".nuevoProducto").children().length == 0){
        $("#nuevoImpuestoVenta").val(0);
        $("#nuevoTotalVenta").val(0);
        $("#totalVenta").val(0);
        $("#nuevoTotalVenta").attr("total",0);
        $("#nuevoTotalItems").val("0.00");
        $("#totalItems").val("0");
        $("#nuevoTotalDescuento").val("0.00");
        $("#totalDescuento").val("0");
        if ($("#vistaTotalItems").length) {
            $("#vistaTotalItems").text("Bs 0.00");
            $("#vistaTotalDescuento").text("- Bs 0.00");
            $("#vistaTotalVenta").text("Bs 0.00");
        }
        $("#listaProductos").val("");
    } else {
        // SUMAR TOTAL DE PRECIOS
        sumarTotalPrecios();
        calcularPago();
        // AGRUPAR PRODUCTOS EN FORMATO JSON
        listarProductos();
		if (window.PromocionesVenta && typeof PromocionesVenta.recalcular === "function") {
			PromocionesVenta.recalcular(function() {
				listarProductos();
				calcularPago();
			});
		}
    }
});


/*=============================================
AGREGANDO PRODUCTOS DESDE EL BOTÓN PARA DISPOSITIVOS
=============================================*/

var numProducto = 0;

$(".btnAgregarProducto").click(function(){

	numProducto ++;

	var datos = new FormData();
	datos.append("traerProductos", "ok");

	$.ajax({

		url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){
      	    
      	    	$(".nuevoProducto").append(

          	'<div class="row" style="padding:5px 15px">'+

			  '<!-- Descripción del producto -->'+
	          
	          '<div class="col-xs-6" style="padding-right:0px">'+
	          
	            '<div class="input-group">'+
	              
	              '<span class="input-group-addon" style="padding: 0px 4px 0px 4px;" ><button type="button" class="btn btn-danger btn-xs quitarProducto" idProducto><i class="fa fa-times"></i></button></span>'+

	              '<select class="form-control input-sm nuevaDescripcionProducto" id="producto'+numProducto+'" idProducto name="nuevaDescripcionProducto" required>'+

	              '<option>Seleccione el producto</option>'+

	              '</select>'+  

	            '</div>'+

	          '</div>'+

	          '<!-- Cantidad del producto -->'+

	          '<div class="col-xs-3 ingresoCantidad">'+
	            '<div class="cantidad-stepper">'+
	              '<button type="button" class="btn btn-default btn-sm btn-cantidad-ajuste btn-minus" data-action="decrementar" title="Disminuir cantidad"><i class="fa fa-minus"></i></button>'+
	              '<input type="number" class="form-control input-sm nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="1" stock required>'+
	              '<button type="button" class="btn btn-success btn-sm btn-cantidad-ajuste btn-plus" data-action="incrementar" title="Aumentar cantidad"><i class="fa fa-plus"></i></button>'+
	            '</div>'+
	          '</div>' +

	          '<!-- Precio del producto -->'+

	          '<div class="col-xs-3 ingresoPrecio" style="padding-left:0px">'+

	            '<div class="input-group">'+

	              '<span class="input-group-addon"><i>Bs</i></span>'+
	                 
	              '<input type="text" class="form-control input-sm nuevoPrecioProducto" precioReal="" name="nuevoPrecioProducto" readonly required>'+
				  '<input type="hidden" precioRealCompra="" name="nuevoPrecioCompraProducto"  class="nuevoPrecioCompraProducto" value=""  >'+
	            '</div>'+
	             
	          '</div>'+

	        '</div>');


	        // AGREGAR LOS PRODUCTOS AL SELECT 

	         respuesta.forEach(funcionForEach);

	         function funcionForEach(item, index){

	         	if(item.stock != 0){

		         	$("#producto"+numProducto).append(

						'<option idProducto="'+item.id+'" value="'+item.descripcion+'">'+item.descripcion+'</option>'
		         	)

		         }

	         }

	         // SUMAR TOTAL DE PRECIOS
    		sumarTotalPrecios()
    		calcularPago();

	        // PONER FORMATO AL PRECIO DE LOS PRODUCTOS

	        $(".nuevoPrecioProducto").number(true, 2);

      	}


	})

})

/*=============================================
SELECCIONAR PRODUCTO
=============================================*/

$(".formularioVenta").on("change", "select.nuevaDescripcionProducto", function(){

	var nombreProducto = $(this).val();
	var nuevaDescripcionProducto = $(this).parent().parent().parent().children().children().children(".nuevaDescripcionProducto");
	var nuevoPrecioProducto = $(this).parent().parent().parent().children(".ingresoPrecio").children().children(".nuevoPrecioProducto");
	var nuevoPrecioCompraProducto = $(this).parent().parent().parent().children(".ingresoPrecio").children().children(".nuevoPrecioCompraProducto");
	var nuevaCantidadProducto = $(this).parent().parent().parent().children(".ingresoCantidad").children(".nuevaCantidadProducto");
	var formaAtencion = $("#formaAtencionDetalle");

	var datos = new FormData();
    datos.append("nombreProducto", nombreProducto);


	  $.ajax({

     	url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){
      	    
      	    $(nuevaDescripcionProducto).attr("idProducto", respuesta["id"]);
      	    $(nuevaCantidadProducto).attr("stock", respuesta["stock"]);
      	    $(nuevaCantidadProducto).attr("data-inventariable", Number(respuesta["inventariable"]) === 1 ? 1 : 0);
      	    $(nuevoPrecioProducto).val(respuesta["precio_venta"]);
      	    $(nuevoPrecioProducto).attr("precioReal", respuesta["precio_venta"]);
      	    $(nuevoPrecioProducto).attr("precioOriginal", respuesta["precio_venta"]);
			$(nuevaCantidadProducto).val(1);
			$(formaAtencion).val(1);
      	    $(nuevoPrecioCompraProducto).attr("precioRealCompra", respuesta["precio_compra"]);
  	        // AGRUPAR PRODUCTOS EN FORMATO JSON
	        listarProductos();
			sumarTotalPrecios();
            calcularPago();
			if (window.PromocionesVenta && typeof PromocionesVenta.recalcular === "function") {
				PromocionesVenta.recalcular(function() {
					listarProductos();
					calcularPago();
				});
			}
      	}

      })
})


/*=============================================
MODIFICAR LA CANTIDAD
=============================================*/

function actualizarCantidadProducto($input, opciones) {
	opciones = opciones || {};
	var row = $input.closest(".linea-venta");
	if (!row.length) row = $input.closest(".row");
	var idProducto = row.find(".nuevaDescripcionProducto").attr("idProducto");
	var precio = row.find(".nuevoPrecioProducto");
	var cantidadMinima = Number($input.attr("min")) || 1;
	var qtyPresentaciones = Number($input.val()) || 0;
	var factor = 1;
	if (window.PresentacionesVenta) {
		factor = PresentacionesVenta.factorDeFila(row);
	} else {
		factor = parseInt($input.attr("data-factor"), 10) || 1;
	}

	if(qtyPresentaciones < cantidadMinima){
		qtyPresentaciones = cantidadMinima;
		$input.val(qtyPresentaciones);
	}

	var refrescarLineaSinPromo = function() {
		factor = window.PresentacionesVenta
			? PresentacionesVenta.factorDeFila(row)
			: (parseInt($input.attr("data-factor"), 10) || 1);
		qtyPresentaciones = Number($input.val()) || cantidadMinima;
		if (qtyPresentaciones < cantidadMinima) {
			qtyPresentaciones = cantidadMinima;
			$input.val(qtyPresentaciones);
		}
		var und = qtyPresentaciones * factor;
		var precioOriginalLocal = Number(precio.attr("precioOriginal") || precio.attr("precioReal") || 0);
		precio.attr("precioOriginal", precioOriginalLocal);
		precio.attr("precioReal", precioOriginalLocal);
		var sub = und * precioOriginalLocal;
		precio.val(parseFloat(sub).toFixed(2));
		row.find(".lv-precio-unit").text("Bs " + precioOriginalLocal.toFixed(2));
		row.find(".lv-desc").addClass("es-vacio").text("—");
		row.find(".lv-total-linea").text("Bs " + sub.toFixed(2));
		if (window.PresentacionesVenta) {
			PresentacionesVenta.actualizarEtiqueta(row);
		}
		return und;
	};

	var unidadesLinea = refrescarLineaSinPromo();

    var unidadesTotalesProducto = unidadesLinea;
    var apariciones = contarProductoEnVenta(idProducto);
	if(apariciones > 1){
		unidadesTotalesProducto = sumarCantidadProductos(idProducto);
	}

	/*SI LA CANTIDAD ES SUPERIOR AL STOCK REGRESAR VALORES INICIALES*/
	var esInventariable = Number($input.attr("data-inventariable"));
	if(esInventariable !== 0 && Number(unidadesTotalesProducto) > Number($input.attr("stock"))){

		// Cambio de presentación: volver al selector anterior (ej. Unidad)
		if (typeof opciones.revertirPresentacion === "function") {
			opciones.revertirPresentacion();
			refrescarLineaSinPromo();
		} else {
			$input.val(cantidadMinima);
			refrescarLineaSinPromo();
			// Si con qty mínima y la presentación actual aún supera stock → forzar Unidad
			if (window.PresentacionesVenta && sumarCantidadProductos(idProducto) > Number($input.attr("stock"))) {
				PresentacionesVenta.forzarUnidadEnFila(row);
				$input.val(cantidadMinima);
				refrescarLineaSinPromo();
			}
		}

		listarProductos();
		var avisarStock = function() {
			swal({
				title: "La cantidad supera el Stock",
				text: "¡Sólo hay "+$input.attr("stock")+" unidades!",
				type: "error",
				confirmButtonText: "¡Cerrar!"
			});
		};
		if (window.PromocionesVenta && typeof PromocionesVenta.recalcular === "function") {
			PromocionesVenta.recalcular(function() {
				listarProductos();
				calcularPago();
				avisarStock();
			});
		} else {
			sumarTotalPrecios();
			calcularPago();
			avisarStock();
		}
		return;
	}

	sumarTotalPrecios();
	calcularPago();
    listarProductos();
	if (window.PromocionesVenta && typeof PromocionesVenta.recalcular === "function") {
		PromocionesVenta.recalcular(function() {
			listarProductos();
			calcularPago();
		});
	}
}

$(".formularioVenta").on("input", "input.nuevaCantidadProducto", function(){
	actualizarCantidadProducto($(this));
})


/*=============================================
---------SUMAR TODOS LOS PRECIOS-----------------
=============================================*/

function sumarTotalPrecios(){
    var sumaTotalItems = 0;
    var sumaTotalDescuento = 0;

    $(".nuevoPrecioProducto").each(function() {
        var $precio = $(this);
        var cant = 0;
        var $cantInput = $precio.closest(".linea-venta").find(".nuevaCantidadProducto");
        if (!$cantInput.length) $cantInput = $precio.closest(".row").find(".nuevaCantidadProducto");
        if (window.PresentacionesVenta) {
            cant = PresentacionesVenta.unidadesDeInput($cantInput);
        } else {
            cant = Number($cantInput.val()) || 0;
        }
        var precioOriginal = Number($precio.attr("precioOriginal") || $precio.attr("precioReal") || 0);
        var subtotalOriginal = precioOriginal * cant;
        sumaTotalItems += subtotalOriginal;

        var descLinea = 0;
        try {
            var promo = JSON.parse($precio.attr("data-promo") || "{}");
            if (promo && Number(promo.descuento_total) > 0) {
                descLinea = Number(promo.descuento_total);
            }
        } catch (e) {}

        if (descLinea <= 0) {
            var subtotalFinalAttr = Number($precio.attr("data-subtotal-final"));
            if (!isNaN(subtotalFinalAttr) && subtotalOriginal > subtotalFinalAttr) {
                descLinea = subtotalOriginal - subtotalFinalAttr;
            }
        }

        if (descLinea > subtotalOriginal) {
            descLinea = subtotalOriginal;
        }
        sumaTotalDescuento += descLinea;
    });

    sumaTotalItems = Math.round((sumaTotalItems + Number.EPSILON) * 100) / 100;
    sumaTotalDescuento = Math.round((sumaTotalDescuento + Number.EPSILON) * 100) / 100;
    var totalNeto = Math.round((sumaTotalItems - sumaTotalDescuento + Number.EPSILON) * 100) / 100;
    if (totalNeto < 0) totalNeto = 0;

	$("#nuevoCambioEfectivo").val("");
    $("#nuevoValorEfectivo").val("");

    if ($("#nuevoTotalItems").length) {
        $("#nuevoTotalItems").val(sumaTotalItems.toFixed(2));
        $("#totalItems").val(sumaTotalItems.toFixed(2));
    }

    if ($("#nuevoTotalDescuento").length) {
        $("#nuevoTotalDescuento").val(sumaTotalDescuento.toFixed(2));
        $("#totalDescuento").val(sumaTotalDescuento.toFixed(2));
    }

    $("#nuevoTotalVenta").val(totalNeto.toFixed(2));
    $("#totalVenta").val(totalNeto.toFixed(2));
    $("#nuevoTotalVenta").attr("total", totalNeto);

    if ($("#vistaTotalItems").length) {
        $("#vistaTotalItems").text("Bs " + sumaTotalItems.toFixed(2));
        $("#vistaTotalDescuento").text("- Bs " + sumaTotalDescuento.toFixed(2));
        $("#vistaTotalVenta").text("Bs " + totalNeto.toFixed(2));
    }
}

/* Los totales visibles son spans; los inputs ocultos siguen alimentando el flujo de venta */

$(document).ready(function() {
  if ($("#filaDescuentoResumen").length && typeof $.fn.tooltip === "function") {
    $("#filaDescuentoResumen").tooltip({
      container: "body",
      placement: "left"
    });
  }
  if (typeof sumarTotalPrecios === "function" && $("#vistaTotalItems").length) {
    sumarTotalPrecios();
  }
});

/*=============================================
SELECCIONAR MÉTODO DE PAGO
=============================================*/

$("#nuevoMetodoPago").change(function(){

	var metodo = $(this).val();

	if(metodo == "Efectivo"){

		$(this).parent().parent().removeClass("col-xs-6");

		$(this).parent().parent().addClass("col-xs-4");

		$(this).parent().parent().parent().children(".cajasMetodoPago").html(

			 '<div class="col-xs-4">'+ 

			 	'<div class="input-group">'+ 

			 		'<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+ 

			 		'<input type="text" class="form-control" id="nuevoValorEfectivo" placeholder="000000" required>'+

			 	'</div>'+

			 '</div>'+

			 '<div class="col-xs-4" id="capturarCambioEfectivo" style="padding-left:0px">'+

			 	'<div class="input-group">'+

			 		'<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+

			 		'<input type="text" class="form-control" id="nuevoCambioEfectivo" placeholder="000000" readonly required>'+

			 	'</div>'+

			 '</div>'

		 )

		// Agregar formato al precio

		$('#nuevoValorEfectivo').number( true, 2);
      	$('#nuevoCambioEfectivo').number( true, 2);


      	// Listar método en la entrada
      	listarMetodos()

	}else{

		$(this).parent().parent().removeClass('col-xs-4');

		$(this).parent().parent().addClass('col-xs-6');

		 $(this).parent().parent().parent().children('.cajasMetodoPago').html(

		 	'<div class="col-xs-6" style="padding-left:0px">'+
                        
                '<div class="input-group">'+
                     
                  '<input type="number" min="0" class="form-control" id="nuevoCodigoTransaccion" placeholder="Código transacción"  required>'+
                       
                  '<span class="input-group-addon"><i class="fa fa-lock"></i></span>'+
                  
                '</div>'+

              '</div>')

	}

	

})|

/*=============================================
CAMBIO EN EFECTIVO
=============================================*/
// $(".formularioVenta").on("input", "input#nuevoValorEfectivo", function() {

//     var efectivo = $(this).val();
//     var totalVenta = Number($('#nuevoTotalVenta').val());
//     var cambio = Number(efectivo) - totalVenta;

//     // Asegurarse de que el cambio no sea negativo
//     cambio = cambio < 0 ? 0 : cambio;

//     var nuevoCambioEfectivo = $(this).closest('.cajasMetodoPago')
//         .find('#nuevoCambioEfectivo');

//     nuevoCambioEfectivo.val(cambio.toFixed(2)); // Asegura dos decimales

// });



/*=============================================
ACTIVAR / DESACTIVAR EDICIÓN QR
=============================================*/
$(".formularioVenta").on("click", ".btnEditarQR", function() {

    editarQRManual = !editarQRManual;

    if (editarQRManual) {
        $("#nuevoValorQR").prop("readonly", false).focus();
        $(this).html('<i class="fa fa-lock" aria-hidden="true"></i>');
    } else {
        $("#nuevoValorQR").prop("readonly", true);
          $(this).html('<i class="fa fa-pencil" aria-hidden="true"></i>');
       
    }

});


/*=============================================
CALCULAR QR Y CAMBIO
=============================================*/
function calcularPago(formatear = true) {

    var tipoPago = $("#tipoPago").val();
    var totalVenta = Number($("#nuevoTotalVenta").val()) || 0;
    var efectivo = Number($("#nuevoValorEfectivo").val()) || 0;
    var qr = Number($("#nuevoValorQR").val()) || 0;

    var cambio = 0;

    if (tipoPago == "1") {
        // EFECTIVO
        qr = 0;
        cambio = efectivo - totalVenta;

        if (formatear) {
            $("#nuevoValorEfectivo").prop("readonly", false);
            $("#nuevoValorQR").prop("readonly", true);
            $("#nuevoValorQR").val("0.00");
            // No dejar un "0" real en el campo: el placeholder ya muestra la guía
            var efectivoActual = String($("#nuevoValorEfectivo").val() || "").trim();
            if (efectivoActual === "0" || efectivoActual === "0.00") {
                $("#nuevoValorEfectivo").val("");
            }
        }
    } else if (tipoPago == "2") {
        // QR
        efectivo = 0;
        qr = totalVenta;

        if (formatear) {
            $("#nuevoValorEfectivo").val("");
            $("#nuevoValorEfectivo").prop("readonly", true);
            $("#nuevoValorQR").prop("readonly", true);
            $("#nuevoValorQR").val(qr.toFixed(2));
        }
        cambio = qr - totalVenta;

    } else if (tipoPago == "4") {
        // MIXTO
        qr = totalVenta - efectivo;

        if (qr < 0) {
            qr = 0;
        }
        if (formatear) {
            $("#nuevoValorEfectivo").prop("readonly", false);
            $("#nuevoValorQR").prop("readonly", true);
            $("#nuevoValorQR").val(qr.toFixed(2));
            var efectivoMixto = String($("#nuevoValorEfectivo").val() || "").trim();
            if (efectivoMixto === "0" || efectivoMixto === "0.00") {
                $("#nuevoValorEfectivo").val("");
            }
        }

        cambio = (efectivo + qr) - totalVenta;

    }

    if (cambio < 0) {
        cambio = 0;
    }

    $("#nuevoCambioEfectivo").val(cambio.toFixed(2));
}


/*=============================================
EVENTOS
=============================================*/
$(".formularioVenta").on("input", "#nuevoValorEfectivo, #nuevoValorQR", function() {
    var limpio = String(this.value || "").replace(/[^0-9.]/g, "");
    var partes = limpio.split(".");
    if (partes.length > 2) {
        limpio = partes[0] + "." + partes.slice(1).join("");
    }
    // Evitar ceros a la izquierda tipo "0200" (mantener "0" y "0.xx")
    if (/^0\d+/.test(limpio)) {
        limpio = limpio.replace(/^0+/, "");
        if (limpio === "" || limpio.charAt(0) === ".") {
            limpio = "0" + limpio;
        }
    }
    if (this.value !== limpio) {
        this.value = limpio;
    }

    // En mixto, actualizar QR restante mientras escribe efectivo
    if (this.id === "nuevoValorEfectivo" && $("#tipoPago").val() === "4") {
        var total = Number($("#nuevoTotalVenta").val()) || 0;
        var efectivo = Number($(this).val()) || 0;
        var restante = total - efectivo;
        if (restante < 0) restante = 0;
        $("#nuevoValorQR").val(restante.toFixed(2));
    }

    calcularPago(false); // no reformatear campos mientras escribe
});

$(".formularioVenta").on("focus", "#nuevoValorEfectivo", function() {
    var valor = String(this.value || "").trim();
    if (valor === "0" || valor === "0.00") {
        this.value = "";
    } else {
        this.select();
    }
});

$(".formularioVenta").on("change", "#tipoPago", function() {

    editarQRManual = false;

    $("#nuevoValorQR").prop("readonly", true);
    $(".btnEditarQR").html('<i class="fa fa-pencil" aria-hidden="true"></i>');

    calcularPago();
});

$(document).ready(function() {
    $("#nuevoValorEfectivo").prop("readonly", false);
    $("#nuevoValorQR").prop("readonly", true);
    calcularPago();
});
/*=============================================
CAMBIO TRANSACCIÓN
=============================================*/
$(".formularioVenta").on("change", "input#nuevoCodigoTransaccion", function(){

	// Listar método en la entrada
     listarMetodos()


})

/*=============================================
LISTAR TODOS LOS PRODUCTOS EN FORMATO JSON DENTRO DEL INPUT (OPTIMIZADO)
=============================================*/
function listarProductos() {
    const listaProductos = [];
    
    // Iterar usando each() para mejor manejo de elementos
    $(".nuevaDescripcionProducto").each(function() {
        const $producto = $(this);
        let $row = $producto.closest('.linea-venta');
        if (!$row.length) $row = $producto.closest('.row');
        
        // Elementos específicos del producto actual
        const $cantidad = $row.find('.nuevaCantidadProducto');
        const $precio = $row.find('.nuevoPrecioProducto');
        const $precioCompra = $row.find('.nuevoPrecioCompraProducto');
        const $nota = $row.find('.nota-producto');
        const $descAdicional = $row.find('.nota-adicional');
        const $formaAtencion = $row.find('select[name="formaAtencionDetalle"]');

        // Obtener preferencias usando map() + get() para mejor rendimiento
		var preferencias = $nota.find('option:selected')
		.map((i, op) => op.textContent)
		.get()
		.join(',') || null;

        listaProductos.push({
            id: $producto.attr('idProducto'),
            idDetalle: $producto.attr('data-idDetalle') || null,
            descripcion: $producto.val(),
            cantidad: (function() {
                if (window.PresentacionesVenta) {
                    return PresentacionesVenta.unidadesDeInput($cantidad);
                }
                return $cantidad.val();
            })(),
            cantidad_presentaciones: (function() {
                if (window.PresentacionesVenta) {
                    return PresentacionesVenta.qtyPresentaciones($cantidad);
                }
                return Number($cantidad.val()) || 1;
            })(),
            unidades_por_presentacion: parseInt($cantidad.attr('data-factor'), 10) || 1,
            id_presentacion: (function() {
                var idp = parseInt($cantidad.attr('data-id-presentacion'), 10) || 0;
                return idp > 0 ? idp : null;
            })(),
            nombre_presentacion: $cantidad.attr('data-nombre-presentacion') || 'Unidad',
            stock: $cantidad.attr('stock'),
            precioCompra: $precioCompra.attr('precioRealCompra'),
            preferencias: preferencias,
            nota_adicional: $descAdicional.val() || null,
            forma_atencion: $formaAtencion.val() || null,
            precioOriginal: (function() {
                var orig = Number($precio.attr('precioOriginal') || $precio.attr('precioReal') || 0);
                return orig;
            })(),
            precio: (function() {
                try {
                    var promo = JSON.parse($precio.attr('data-promo') || '{}');
                    if (promo && promo.precio_unitario_final != null && Number(promo.descuento_total) > 0) {
                        return Number(promo.precio_unitario_final);
                    }
                } catch (e) {}
                return Number($precio.attr('precioOriginal') || $precio.attr('precioReal') || 0);
            })(),
            total: (function() {
                try {
                    var promo = JSON.parse($precio.attr('data-promo') || '{}');
                    if (promo && promo.subtotal_final != null && Number(promo.descuento_total) > 0) {
                        return Number(promo.subtotal_final);
                    }
                } catch (e) {}
                var cant = window.PresentacionesVenta
                    ? PresentacionesVenta.unidadesDeInput($cantidad)
                    : (Number($cantidad.val()) || 0);
                var orig = Number($precio.attr('precioOriginal') || $precio.attr('precioReal') || 0);
                return Math.round((orig * cant + Number.EPSILON) * 100) / 100;
            })(),
            promo: (function() {
                try { return JSON.parse($precio.attr('data-promo') || '{}'); } catch (e) { return {}; }
            })()
        });
    });
    $("#listaProductos").val(JSON.stringify(listaProductos));
}
/*=============================================
LISTAR MÉTODO DE PAGO
=============================================*/

function listarMetodos(){

	var listaMetodos = "";

	if($("#nuevoMetodoPago").val() == "Efectivo"){

		$("#listaMetodoPago").val("Efectivo");

	}else{

		$("#listaMetodoPago").val($("#nuevoMetodoPago").val()+"-"+$("#nuevoCodigoTransaccion").val());

	}

}


/*=============================================
BOTON EDITAR VENTA
=============================================*/
$(".tablas").on("click", ".btnEditarVenta", function(){

	var idVenta = $(this).attr("idVenta");

	window.location = "index.php?ruta=editar-venta&idVenta="+idVenta;

})


/*=============================================
BORRAR VENTA
=============================================*/
$(".tablas").on("click", ".btnEliminarVenta", function(){

	var idVenta = $(this).attr("idVenta");
  
	swal({
		  title: '¿Está seguro de anular la venta?',
		  text: "¡Si no lo está puede cancelar la accíón!",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, anular venta!'
		}).then(function(result){
		  if (result.value) {
			  window.location = "index.php?ruta=ventas&idVenta="+idVenta;
		  }
  
	})
  
  })


/*=============================================
IMPRIMIR FACTURA
=============================================*/

 $(".tablas").on("click", ".btnVerFactura", async function() {
    var codigoVenta = $(this).attr("codigoVenta");

    // Pedir el PDF al servidor PHP     
        const response = await fetch(
            `extensiones/tcpdf/pdf/facturaComanda.php?codigo=${codigoVenta}`,{
                  method: 'GET',
                 headers: { 'Content-Type': 'application/json' },
            }
        );

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();
		console.log('📋 Respuesta del servidor:', data);

        if (!data.success) {
            alert('Error al generar los PDFs');
            return;
        }
        await mostrarVenta(data.facturaComandaBase64);
});
/*=============================================
IMPRIMIR FACTURA
=============================================*/

$(".tablas").on("click", ".btnImprimirFactura", async function(){

	var idVenta = $(this).attr("codigoVenta");

    swal({
        title: "Tipo de impresión",
        input: "select",
        inputOptions: {
            "1": "Caja y Cocina",
            "2": "Caja (Ticket)",
            "5": "Caja (Ticket + Comanda)",
            "3": "Cocina"
        },
        inputPlaceholder: "Seleccione",
        showCancelButton: true,
        confirmButtonText: "Imprimir",
        cancelButtonText: "Cancelar",
        width: 350,
        padding: 20,
        confirmButtonClass: 'btn btn-warning btn-sm swal-btn-margin',
        cancelButtonClass: 'btn btn-default btn-sm swal-btn-margin',
        inputClass: 'form-control input-sm swal-select-bootstrap',
        buttonsStyling:false,
        inputValidator: function(value) {
            return new Promise(function(resolve) {
            if (value) {
                resolve();
            } else {
                resolve("Seleccione una opción");
            }
            });
        },
        }).then( async function(result) {
        if (result.value) {
            // Llamar a la función de impresión
          await imprimirVentaSegunTipo(idVenta, result.value,false);
        }
        });
  })

 $(".tablas").on("click", ".btnImprimirFacturass", async function() {
    var codigoVenta = $(this).attr("codigoVenta");
  const response = await fetch(
        `extensiones/tcpdf/pdf/facturaComanda.php?codigo=${codigoVenta}`,{
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
        }
    );
    if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status}`);
    }
    const data = await response.json();
    console.log('📋 Respuesta del servidor:', data);
    if (!data.success) {
        alert('Error al generar los PDFs');
        return;
    }
    await mostrarVenta(data.facturaComandaBase64);

    try {
			
        // 2️⃣ Imprimir FACTURA (CAJA)
        const printCaja = await fetch('http://localhost:3000/print-pdf', {
             method: 'POST',
             headers: { 'Content-Type': 'application/json' },
             body: JSON.stringify({
                 pdfBase64: data.facturaBase64,
                 printerName: 'IMPRESORA-CAJA'
             })
         });

         if (!printCaja.ok) {
             console.warn('⚠️ Advertencia: No se pudo imprimir en caja. Continuando...');
         }

        // 3️⃣ Imprimir COMANDA (COCINA)
        const printCocina = await fetch('http://localhost:3000/print-pdf', {
             method: 'POST',
             headers: { 'Content-Type': 'application/json' },
             body: JSON.stringify({
                 pdfBase64: data.comandaBase64,
                 printerName: 'IMPRESORA-COCINA'
             })
         });

         if (!printCocina.ok) {
             console.warn('⚠️ Advertencia: No se pudo imprimir en cocina. Continuando...');
         }

        console.log('✅ Impresión enviada correctamente');

    } catch (error) {
        console.error('❌ Error de impresión:', error);
    }
});


var popupWindow2 = null;
async function mostrarVenta(base64) {
    // Tamaño de la ventana emergente
    var width = 1000;
    var height = 450;
    // Configuración de la ventana emergente
    var left = (screen.width / 2) - (width / 2);
    var top = (screen.height / 3) - (height / 3);
    var windowFeatures = `menubar=no,toolbar=no,status=no,width=${width},height=${height},left=${left},top=${top}`;

    try {
        // Convertir base64 a blob
        const binaryString = atob(base64);
        const bytes = new Uint8Array(binaryString.length);
        for (let i = 0; i < binaryString.length; i++) {
            bytes[i] = binaryString.charCodeAt(i);
        }
        const blob = new Blob([bytes], { type: 'application/pdf' });

        // Crear URL del blob
        const blobUrl = URL.createObjectURL(blob);

        // Si la ventana ya existe y está abierta, solo traerla al frente
        if (popupWindow2 && !popupWindow2.closed) {
            popupWindow2.focus();
        } else {
            // Si no existe o fue cerrada, abrir una nueva
            popupWindow2 = window.open(blobUrl, "_blank", windowFeatures);
            if (popupWindow2) {
                popupWindow2.focus();
            }
        }

    } catch (error) {
        console.error('❌ Error al mostrar la venta:', error);
        alert('Error al procesar el PDF: ' + error.message);
    }
}

async function imprimirVentaSegunTipo(codigoVenta, idParameterImpresion = null, recargarPagina = true, idsDetalle = null) {
    var idTipoImpresion = idParameterImpresion;
    if (!idTipoImpresion){
     idTipoImpresion = $("#idTipoImpresion").val();  
    }
    
    try {
      
        if (idTipoImpresion == 1) {//Caja y Cocina
            await imprimirCajaCocina(codigoVenta, idsDetalle);
        } else if (idTipoImpresion == 2) {//Caja (Ticket)
            await imprimirSoloCaja(codigoVenta, idsDetalle);
        } else if (idTipoImpresion == 5) {//Caja (Ticket + Comanda) ambos en IMPRESORA-CAJA
            await imprimirAmbosEnCaja(codigoVenta, idsDetalle);
        } else if (idTipoImpresion == 3) {//Cocina
            await imprimirSoloCocina(codigoVenta, idsDetalle);
        } else if (idTipoImpresion == 4) {//Sin Imprimir
            await imprimirSoloCaja(codigoVenta, idsDetalle, false);
        }
    }catch (error) {
        console.error('❌ Error al determinar el tipo de impresión:', error);
    }finally {
        if (recargarPagina){
            setTimeout(() => {
            window.location.href = "crear-venta";
        }, 500);
        }
    }
}

function construirUrlImpresion(baseUrl, codigoVenta, idsDetalle = null) {
    var url = `${baseUrl}?codigo=${codigoVenta}`;
    if (idsDetalle) {
        url += `&idsDetalle=${idsDetalle}`;
    }
    return url;
}

async function imprimirAmbosEnCaja(codigoVenta, idsDetalle = null) {
    const response = await fetch(
        construirUrlImpresion('extensiones/tcpdf/pdf/facturaComanda.php', codigoVenta, idsDetalle), {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        }
    );
    if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status}`);
    }
    const data = await response.json();
    console.log('📋 Respuesta del servidor:', data);
    if (!data.success) {
        alert('Error al generar los PDFs');
        return;
    }

    await mostrarVenta(data.facturaComandaBase64);

    try {
        const printResponse = await fetch('http://localhost:3000/print-pdf', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                pdfBase64: data.facturaComandaBase64,
                printerName: 'IMPRESORA-CAJA'
            })
        });

        if (!printResponse.ok) {
            console.warn('⚠️ Advertencia: No se pudo imprimir en caja. Continuando...');
        }
        console.log('✅ Impresión enviada correctamente (ticket + comanda en caja)');
    } catch (error) {
        console.error('❌ Error de impresión:', error);
    }
}

 async function imprimirCajaCocina(codigoVenta, idsDetalle = null) {
    // 1 Pedir los PDFs al servidor PHP
    const response = await fetch(
        construirUrlImpresion('extensiones/tcpdf/pdf/facturaComanda.php', codigoVenta, idsDetalle),{
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
        }
    );
    if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status}`);
    }
    const data = await response.json();
    console.log('📋 Respuesta del servidor:', data);
    if (!data.success) {
        alert('Error al generar los PDFs');
        return;
    }
    await mostrarVenta(data.facturaComandaBase64);

    try {
			
        // 2️⃣ Imprimir FACTURA (CAJA)
        const printCaja = await fetch('http://localhost:3000/print-pdf', {
             method: 'POST',
             headers: { 'Content-Type': 'application/json' },
             body: JSON.stringify({
                 pdfBase64: data.facturaBase64,
                 printerName: 'IMPRESORA-CAJA'
             })
         });

         if (!printCaja.ok) {
             console.warn('⚠️ Advertencia: No se pudo imprimir en caja. Continuando...');
         }

        // 3️⃣ Imprimir COMANDA (COCINA)
        const printCocina = await fetch('http://localhost:3000/print-pdf', {
             method: 'POST',
             headers: { 'Content-Type': 'application/json' },
             body: JSON.stringify({
                 pdfBase64: data.comandaBase64,
                 printerName: 'IMPRESORA-COCINA'
             })
         });

         if (!printCocina.ok) {
             console.warn('⚠️ Advertencia: No se pudo imprimir en cocina. Continuando...');
         }

        console.log('✅ Impresión enviada correctamente');

    } catch (error) {
        console.error('❌ Error de impresión:', error);
    }
}

async function imprimirSoloCaja(codigoVenta, idsDetalle = null, imprimir = true) {
    // 1 Pedir solo el PDF del ticket/factura de caja
    const response = await fetch(
        construirUrlImpresion('extensiones/tcpdf/pdf/factura.php', codigoVenta, idsDetalle),{
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
        }
    );
    if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status}`);
    }
    const data = await response.json();
    console.log('📋 Respuesta del servidor:', data);
    if (!data.success) {
        alert('Error al generar el PDF de caja');
        return;
    }
    await mostrarVenta(data.facturaBase64);

    if (!imprimir) {
        return;
    }
    try {
        // Imprimir SOLO FACTURA (CAJA)
        const printResponse = await fetch('http://localhost:3000/print-pdf', {
             method: 'POST',    
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    pdfBase64: data.facturaBase64,
                    printerName: 'IMPRESORA-CAJA'
                })
         });

         if (!printResponse.ok) {
             console.warn('⚠️ Advertencia: No se pudo imprimir. Continuando...');
         }
        console.log('✅ Impresión enviada correctamente');
    } catch (error) {
        console.error('❌ Error de impresión:', error);
    }
}

async function imprimirSoloCocina(codigoVenta, idsDetalle = null) {
    // Pedir el PDF al servidor PHP
    const response = await fetch(
        construirUrlImpresion('extensiones/tcpdf/pdf/comanda.php', codigoVenta, idsDetalle), {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
        }
    );  
    const data = await response.json();
    if (!data.success) {
        alert('Error al generar el PDF');
        return;
    }
    await mostrarVenta(data.comandaBase64);
    try {

        // Imprimir COMANDA (COCINA)
            await fetch('http://localhost:3000/print-pdf', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    pdfBase64: data.comandaBase64,
                    printerName: 'IMPRESORA-COCINA'
                })
            });
        console.log('✅ Impresión enviada correctamente');
  
    } catch (error) {
        console.error('❌ Error de impresión:', error);
    }       
}

/*=============================================
FUNCIÓN PARA INICIALIZAR LA TABLA
=============================================*/
function cargarTablaVentas(fechaInicial, fechaFinal, estadoPago, idMesero) {

	// Destruir la tabla si ya está inicializada
	if ($.fn.DataTable.isDataTable('.tablaVentasRealizadas')) {
	  $('.tablaVentasRealizadas').DataTable().destroy();
	}
  
	// Inicializar la DataTable con los parámetros de fecha
	$('.tablaVentasRealizadas').DataTable({
	  "ajax": {
		"url": "ajax/datatable-ventas-realizadas.ajax.php",
		"type": "GET",
		"data": {
		  fechaInicial: fechaInicial,
		  fechaFinal: fechaFinal,
		  estadoPago: estadoPago || "todos",
		  idMesero: idMesero || "0",
		  perfilOculto: $("#perfilOculto").val()
		}
	  },
	  "deferRender": true,
	  "retrieve": true,
	  "processing": true,
	  "language": {
		"sProcessing": "Procesando...",
		"sLengthMenu": "Mostrar _MENU_ registros",
		"sZeroRecords": "No se encontraron resultados",
		"sEmptyTable": "Ningún dato disponible en esta tabla",
		"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
		"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
		"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
		"sSearch": "Buscar:",
		"oPaginate": {
		  "sFirst": "Primero",
		  "sLast": "Último",
		  "sNext": "Siguiente",
		  "sPrevious": "Anterior"
		}
	  }
	});
  }
  
  /*=============================================
  RANGO DE FECHAS
  =============================================*/
  $('#daterange-btn').daterangepicker(
	{
	  ranges: {
		'Hoy': [moment(), moment()],
		'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
		'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
		'Este mes': [moment().startOf('month'), moment().endOf('month')],
		'Último mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	  },
	  startDate: moment(),
	  endDate: moment()
	},
	function (start, end) {
	  $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
  
	  var fechaInicial = start.format('YYYY-MM-DD');
	  var fechaFinal = end.format('YYYY-MM-DD');
  
	  var capturarRango = $("#daterange-btn span").html();
	  localStorage.setItem("capturarRango", capturarRango);
  
	  // Llamada a la función optimizada para cargar la tabla
	  cargarTablaVentas(fechaInicial, fechaFinal, $("#filtroEstadoPago").val(), $("#filtroMesero").val());
	}
  );
  
  $("#filtroEstadoPago, #filtroMesero").on("change", function() {
	if ($.fn.DataTable.isDataTable('.tablaVentasRealizadas')) {
		$('.tablaVentasRealizadas').DataTable().ajax.reload();
		return;
	}
	var fechaInicial = null;
	var fechaFinal = null;
	var rango = localStorage.getItem("capturarRango");
	if (rango && rango !== "Hoy") {
	  var fechas = $("#daterange-btn").data('daterangepicker');
	  if (fechas) {
		fechaInicial = fechas.startDate.format('YYYY-MM-DD');
		fechaFinal = fechas.endDate.format('YYYY-MM-DD');
	  }
	} else if (rango === "Hoy") {
	  var d = new Date();
	  var dia = ("0" + d.getDate()).slice(-2);
	  var mes = ("0" + (d.getMonth() + 1)).slice(-2);
	  var año = d.getFullYear();
	  fechaInicial = año + "-" + mes + "-" + dia;
	  fechaFinal = fechaInicial;
	}
	cargarTablaVentas(fechaInicial, fechaFinal, $("#filtroEstadoPago").val(), $("#filtroMesero").val());
  });
  
  /*=============================================
  CAPTURAR HOY
  =============================================*/
  $(".daterangepicker.opensleft .ranges li").on("click", function () {
	var textoHoy = $(this).attr("data-range-key");
  

	
	if (textoHoy == "Hoy") {
	  var d = new Date();
	  var dia = ("0" + d.getDate()).slice(-2);
	  var mes = ("0" + (d.getMonth() + 1)).slice(-2);
	  var año = d.getFullYear();
  
	  var fechaInicial = año + "-" + mes + "-" + dia;
	  var fechaFinal = fechaInicial; // Hoy será el mismo valor para ambas fechas
  
	  localStorage.setItem("capturarRango", "Hoy");
  
	  // Llamada a la función optimizada para cargar la tabla
	  cargarTablaVentas(fechaInicial, fechaFinal, $("#filtroEstadoPago").val(), $("#filtroMesero").val());
	}
  });
  
  
/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/

$(".daterangepicker.opensleft .range_inputs .cancelBtn").on("click", function(){

	localStorage.removeItem("capturarRango");
	localStorage.clear();
	window.location = "ventas";
})

/*=============================================
DUPLICAR PRODUCTO
=============================================*/
$(document).on("click", "button[title='Duplicar Producto']", function() {
    var $productoRow = $(this).closest('.linea-venta');
    if (!$productoRow.length) $productoRow = $(this).closest('.row');
    var idProducto = $productoRow.find('.nuevaDescripcionProducto').attr('idProducto');
    var stockOriginal = parseInt($productoRow.find('.nuevaCantidadProducto').attr('stock'));
    var esInventariable = Number($productoRow.find('.nuevaCantidadProducto').attr('data-inventariable'));
    var cantidadTotal = 0;
    
    // Calcular la cantidad total actual del producto en la venta (UNIDADES)
    $('.nuevaCantidadProducto').each(function() {
        var $f = $(this).closest('.linea-venta');
        if (!$f.length) $f = $(this).closest('.row');
        if($f.find('.nuevaDescripcionProducto').attr('idProducto') === idProducto) {
            if (window.PresentacionesVenta) {
                cantidadTotal += PresentacionesVenta.unidadesDeInput($(this));
            } else {
                cantidadTotal += parseInt($(this).val() || 0);
            }
        }
    });
    
    // Verificar si hay suficiente stock (1 presentación adicional = factor unidades)
    var factorDup = parseInt($productoRow.find('.nuevaCantidadProducto').attr('data-factor'), 10) || 1;
    if(esInventariable !== 0 && (cantidadTotal + factorDup) > stockOriginal) {
        swal({
            title: "No hay suficiente stock",
            text: "Solo quedan " + stockOriginal + " unidades disponibles",
            type: "error",
            confirmButtonText: "¡Cerrar!"
        });
        return;
    }
    
    // Al duplicar, asegurarse de que el botón del catálogo permanezca deshabilitado
    $("button.recuperarBoton[idProducto='"+idProducto+"']").addClass('disabled');
    $("button.recuperarBoton[idProducto='"+idProducto+"']").attr('disabled', true);

    // Presentación ACTUAL de la fila origen (.val()), no el atributo HTML selected
    var $selOrigen = $productoRow.find(".select-presentacion-venta");
    var idPresOrigen = $selOrigen.length ? String($selOrigen.val() || "0") : "0";
    var $optOrigen = $selOrigen.find("option").filter(function () {
        return String($(this).val()) === idPresOrigen;
    }).first();
    var factorOrigen = parseInt(
        $optOrigen.attr("data-factor") ||
        $productoRow.find(".nuevaCantidadProducto").attr("data-factor"),
        10
    ) || 1;
    var nombreOrigen = $optOrigen.attr("data-nombre") ||
        $productoRow.find(".nuevaCantidadProducto").attr("data-nombre-presentacion") ||
        "Unidad";
    
    // Clonar la fila
    var $nuevoProducto = $productoRow.clone();
    
    // Limpiar los elementos Select2 del clon
    $nuevoProducto.find('.select2-container').remove();
    $nuevoProducto.find('.nota-producto').removeClass('select2-hidden-accessible');
    
    // Limpiar los valores de las notas y preferencias
    $nuevoProducto.find('.nota-producto').val([]);
    $nuevoProducto.find('.nota-adicional').val('');
    
    // Establecer cantidad inicial en 1 presentación y actualizar el nuevoStock
    var $cantidadInput = $nuevoProducto.find('.nuevaCantidadProducto');
    $cantidadInput.val(1);
    $cantidadInput.attr('stock', stockOriginal);

    // Forzar la misma presentación del origen (evita que el clone restaure Balde por el attr selected)
    var $selNuevo = $nuevoProducto.find(".select-presentacion-venta");
    if ($selNuevo.length) {
        $selNuevo.find("option").each(function () {
            this.selected = String(this.value) === idPresOrigen;
        });
        $selNuevo.val(idPresOrigen);
        $selNuevo.data("valorAnterior", idPresOrigen);
    }
    if (window.PresentacionesVenta) {
        PresentacionesVenta.aplicarPresentacionEnFila(
            $nuevoProducto,
            idPresOrigen,
            nombreOrigen,
            factorOrigen
        );
    } else {
        $cantidadInput.attr("data-factor", factorOrigen);
        $cantidadInput.attr("data-id-presentacion", parseInt(idPresOrigen, 10) > 0 ? idPresOrigen : "");
        $cantidadInput.attr("data-nombre-presentacion", nombreOrigen);
    }

    // Mantener precio unitario ORIGINAL; el subtotal visible = unidades × original
    var $precioDup = $nuevoProducto.find('.nuevoPrecioProducto');
    var precioUnitario = Number($precioDup.attr('precioOriginal') || $precioDup.attr('precioReal') || 0);
    $precioDup.attr('precioOriginal', precioUnitario);
    $precioDup.attr('precioReal', precioUnitario);
    $precioDup.removeAttr('data-promo data-subtotal-final data-precio-final');
    var undDup = window.PresentacionesVenta
        ? PresentacionesVenta.unidadesDeInput($cantidadInput)
        : factorOrigen;
    $precioDup.val(parseFloat(precioUnitario * undDup).toFixed(2));
    $nuevoProducto.find('.lv-precio-unit').text('Bs ' + precioUnitario.toFixed(2));
    $nuevoProducto.find('.lv-desc').addClass('es-vacio').text('—');
    $nuevoProducto.find('.lv-total-linea').text('Bs ' + (precioUnitario * undDup).toFixed(2));
    $nuevoProducto.find('.promo-aplicada-info').remove();
    $nuevoProducto.removeAttr('data-idDetalle');
    $nuevoProducto.find('.nuevaDescripcionProducto').removeAttr('data-idDetalle');
    if (window.PresentacionesVenta) {
        PresentacionesVenta.actualizarEtiqueta($nuevoProducto);
    }

    // Insertar el nuevo producto después del original
    $productoRow.after($nuevoProducto);

    $nuevoProducto.find('.select2-container').remove();
    $nuevoProducto.find('.nota-producto').removeClass('select2-hidden-accessible').removeAttr('data-select2-id aria-hidden tabindex');
    $nuevoProducto.find('.nota-producto').val([]);
    $nuevoProducto.find('.nota-adicional').val('');
    $nuevoProducto.find('.btn-abrir-notas').removeClass('tiene-notas');

    if (typeof inicializarSelect2NotasEnFila === 'function') {
      inicializarSelect2NotasEnFila($nuevoProducto);
    }
    if (typeof actualizarEstadoBotonNotas === 'function') {
      actualizarEstadoBotonNotas($nuevoProducto);
    }

    listarProductos();
	if (window.PromocionesVenta && typeof PromocionesVenta.recalcular === "function") {
		PromocionesVenta.recalcular(function() {
			listarProductos();
			calcularPago();
		});
	} else {
		sumarTotalPrecios();
		calcularPago();
	}
});

/*=============================================
CAMBIO EN LA FORMA DE ATENCIÓN INDIVIDUAL
=============================================*/
$(".formularioVenta").on("change", "select[name='formaAtencionDetalle']", function(){
    // Actualizar la lista de productos cuando se cambia la forma de atención individual
    listarProductos();
});

/*=============================================
CAMBIO EN LA FORMA DE ATENCIÓN GENERAL
=============================================*/
$("#formaAtencion").change(function() {
    var nuevaFormaAtencion = $(this).val();
    var selectores = $(".nuevoProducto select[name='formaAtencionDetalle']");
    
    // Actualizar todos los selectores de forma de atención en los productos
    selectores.each(function() {
        switch(nuevaFormaAtencion) {
            case "1": // En Mesa
                $(this).val("1"); 
                $(this).prop("disabled", true); // Usar prop en lugar de attr en el select directamente
                break;
            case "2": // Para Llevar
                $(this).val("2"); 
                $(this).prop("disabled", true); // Usar prop en lugar de attr en el select directamente
                break;
            case "3": // Mixto
                $(this).prop("disabled", false); // Habilitar el selector
                break;
        }
    });
    
    // Actualizar la lista de productos
    listarProductos();
});

// Asegurar que el estado inicial sea correcto cuando se carga la página
$(document).ready(function() {
    // Obtener el valor inicial del selector general
    var formaAtencionInicial = $("#formaAtencion").val();
    
    // Aplicar el estado inicial a los selectores existentes
    $(".nuevoProducto select[name='formaAtencionDetalle']").each(function() {
        if(formaAtencionInicial === "1") { // En Mesa
            $(this).val("1");
            $(this).prop("disabled", true);
        } else if(formaAtencionInicial === "2") { // Para Llevar
            $(this).val("2");
            $(this).prop("disabled", true);
        } else { // Mixto
            $(this).prop("disabled", false);
        }
    });
});

/*=============================================
COBRAR CUENTA PENDIENTE
=============================================*/
function actualizarCamposCobro() {
    var tipo = $("#tipoPagoCobro").val();
    if (tipo === "1") {
        $("#grupoEfectivoCobro").show();
        $("#grupoQRCobro").hide();
        $("#grupoCambioCobro").show();
        $("#nuevoValorEfectivoCobro").prop("readonly", false);
        $("#nuevoValorQRCobro").prop("readonly", true);
    } else if (tipo === "2") {
        $("#grupoEfectivoCobro").hide();
        $("#grupoQRCobro").show();
        $("#grupoCambioCobro").hide();
        $("#nuevoValorEfectivoCobro").prop("readonly", true);
        $("#nuevoValorQRCobro").prop("readonly", true);
    } else if (tipo === "4") {
        $("#grupoEfectivoCobro").show();
        $("#grupoQRCobro").show();
        $("#grupoCambioCobro").show();
        $("#nuevoValorEfectivoCobro").prop("readonly", false);
        $("#nuevoValorQRCobro").prop("readonly", true);
    }
}

function calcularCambioCobro() {
    var total = Number($("#totalVentaCobro").val()) || 0;
    var efectivo = Number($("#nuevoValorEfectivoCobro").val()) || 0;
    var qr = Number($("#nuevoValorQRCobro").val()) || 0;
    var tipo = $("#tipoPagoCobro").val();
    var cambio = 0;

    if (tipo === "1") {
        qr = 0;
        $("#nuevoValorQRCobro").val("0.00");
        cambio = efectivo - total;
    } else if (tipo === "2") {
        efectivo = 0;
        qr = total;
        $("#nuevoValorEfectivoCobro").val("");
        $("#nuevoValorQRCobro").val(qr.toFixed(2));
        cambio = 0;
    } else if (tipo === "4") {
        qr = total - efectivo;
        if (qr < 0) {
            qr = 0;
        }
        $("#nuevoValorQRCobro").val(qr.toFixed(2));
        cambio = (efectivo + qr) - total;
        if (cambio < 0) cambio = 0;
    }

    $("#nuevoCambioEfectivoCobro").val(cambio > 0 ? cambio.toFixed(2) : "0.00");
}

$(".tablas").on("click", ".btnCobrarCuenta", function() {
    $("#idVentaCobrar").val($(this).attr("idVenta"));
    $("#totalVentaCobro").val($(this).attr("totalVenta"));
    $("#ticketCobrar").val($(this).attr("codigoVenta"));
    $("#nuevoValorEfectivoCobro").val($(this).attr("totalVenta"));
    $("#nuevoValorQRCobro").val("0");
    $("#tipoPagoCobro").val("1");
    actualizarCamposCobro();
    calcularCambioCobro();
    $("#modalCobrarCuenta").modal("show");
});

$(".tablas").on("click", ".btnCobrarCajaCerrada", function() {
    var ticket = $(this).attr("codigoVenta") || "";
    swal({
        type: "warning",
        title: "Caja cerrada",
        text: "No se puede cobrar esta venta porque la caja asociada ya fue cerrada" + (ticket ? " (Ticket " + String(ticket).replace(/^0+/, "") + ")." : "."),
        showConfirmButton: true,
        confirmButtonText: "Entendido"
    });
});

$("#tipoPagoCobro").on("change", function() {
    actualizarCamposCobro();
    calcularCambioCobro();
});

$("#nuevoValorEfectivoCobro").on("input", function() {
    var limpio = String(this.value || "").replace(/[^0-9.]/g, "");
    var partes = limpio.split(".");
    if (partes.length > 2) {
        limpio = partes[0] + "." + partes.slice(1).join("");
    }
    if (this.value !== limpio) {
        this.value = limpio;
    }
    calcularCambioCobro();
});

$("#nuevoValorQRCobro").on("input", function() {
    var limpio = String(this.value || "").replace(/[^0-9.]/g, "");
    var partes = limpio.split(".");
    if (partes.length > 2) {
        limpio = partes[0] + "." + partes.slice(1).join("");
    }
    if (this.value !== limpio) {
        this.value = limpio;
    }
    calcularCambioCobro();
});

$("#btnConfirmarCobro").on("click", function() {
    var total = Number($("#totalVentaCobro").val()) || 0;
    var efectivoRaw = String($("#nuevoValorEfectivoCobro").val() || "").trim();
    var qrRaw = String($("#nuevoValorQRCobro").val() || "").trim();
    var efectivo = Number(efectivoRaw.replace(",", "."));
    var qr = Number(qrRaw.replace(",", "."));
    var tipo = $("#tipoPagoCobro").val();

    function esMontoValido(raw, num) {
        if (raw === "" || !/^\d+(\.\d+)?$/.test(raw.replace(",", "."))) {
            return false;
        }
        return Number.isFinite(num) && num >= 0;
    }

    if (tipo === "1") {
        if (!esMontoValido(efectivoRaw, efectivo)) {
            swal({ type: "warning", title: "Pago inválido", text: "Ingrese un monto numérico válido en efectivo." });
            return;
        }
        if (efectivo < total) {
            swal({ type: "warning", title: "El pago en efectivo debe ser igual o mayor al total" });
            return;
        }
    }
    if (tipo === "2") {
        if (!esMontoValido(qrRaw, qr)) {
            swal({ type: "warning", title: "Pago inválido", text: "Ingrese un monto numérico válido en QR." });
            return;
        }
        if (qr < total) {
            swal({ type: "warning", title: "El pago en QR debe ser igual o mayor al total" });
            return;
        }
    }
    if (tipo === "4") {
        if (!esMontoValido(efectivoRaw, efectivo) || !esMontoValido(qrRaw, qr)) {
            swal({ type: "warning", title: "Pago inválido", text: "Ingrese montos numéricos válidos en efectivo y QR." });
            return;
        }
        if ((efectivo + qr) < total) {
            swal({ type: "warning", title: "La suma del efectivo y el QR debe ser igual o mayor al total" });
            return;
        }
    }

  var formData = new FormData();
  formData.append("cobrarCuentaPendiente", "1");
  formData.append("idVentaCobrar", $("#idVentaCobrar").val());
  formData.append("idVendedorCobro", $("#idVendedorCobro").val());
  formData.append("totalVentaCobro", $("#totalVentaCobro").val());
  formData.append("tipoPagoCobro", tipo);
  formData.append("nuevoValorEfectivoCobro", efectivo);
  formData.append("nuevoValorQRCobro", qr);
  formData.append("nuevoCambioEfectivoCobro", $("#nuevoCambioEfectivoCobro").val());

  $("#btnConfirmarCobro").prop("disabled", true);

  $.ajax({
    url: "ajax/ventas.ajax.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(respuesta) {
      $("#btnConfirmarCobro").prop("disabled", false);
      if (respuesta.status === "ok") {
        $("#modalCobrarCuenta").modal("hide");
        swal({
          type: "success",
          title: respuesta.mensaje,
          showConfirmButton: true,
          confirmButtonText: "Cerrar"
        }).then(function() {
          if ($.fn.DataTable.isDataTable('.tablaVentasRealizadas')) {
            $('.tablaVentasRealizadas').DataTable().ajax.reload();
          }
        });
      } else {
        swal({
          type: "error",
          title: "Error",
          text: respuesta.mensaje || "No se pudo cobrar la cuenta"
        });
      }
    },
    error: function() {
      $("#btnConfirmarCobro").prop("disabled", false);
      swal({ type: "error", title: "Error de comunicación al cobrar la cuenta" });
    }
  });
});