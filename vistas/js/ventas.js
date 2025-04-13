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
AGREGANDO PRODUCTOS A LA VENTA DESDE LA TABLA
=============================================*/

/*=============================================
CUANDO CARGUE LA TABLA CADA VEZ QUE NAVEGUE EN ELLA
=============================================*/



/*=============================================
QUITAR PRODUCTOS DE LA VENTA Y RECUPERAR BOTÓN
=============================================*/

var idQuitarProducto = [];

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

$(".formularioVenta").on("click", "button.quitarProducto", function(){
    var idProducto = $(this).attr("idProducto");
    
    // Eliminar el elemento
    $(this).closest('.row').remove();

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
        $("#listaProductos").val("");
    } else {
        // SUMAR TOTAL DE PRECIOS
        sumarTotalPrecios();
        // AGRUPAR PRODUCTOS EN FORMATO JSON
        listarProductos();
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
	            
	             '<input type="number" class="form-control input-sm nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="0" stock nuevoStock required>'+

	          '</div>' +

	          '<!-- Precio del producto -->'+

	          '<div class="col-xs-3 ingresoPrecio" style="padding-left:0px">'+

	            '<div class="input-group">'+

	              '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+
	                 
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
      	    $(nuevaCantidadProducto).attr("nuevoStock", Number(respuesta["stock"])-1);
      	    $(nuevoPrecioProducto).val(respuesta["precio_venta"]);
      	    $(nuevoPrecioProducto).attr("precioReal", respuesta["precio_venta"]);
			$(nuevaCantidadProducto).val(1);
			$(formaAtencion).val(1);
      	    $(nuevoPrecioCompraProducto).attr("precioRealCompra", respuesta["precio_compra"]);
  	        // AGRUPAR PRODUCTOS EN FORMATO JSON
	        listarProductos()
			sumarTotalPrecios()
      	}

      })
})


/*=============================================
MODIFICAR LA CANTIDAD
=============================================*/

$(".formularioVenta").on("change", "input.nuevaCantidadProducto", function(){
	var precio = $(this).parent().parent().children(".ingresoPrecio").children().children(".nuevoPrecioProducto");

	var precioFinal = $(this).val() * precio.attr("precioReal");
	
	precio.val(parseFloat(precioFinal).toFixed(2));

	var nuevoStock = Number($(this).attr("stock")) - $(this).val();

	$(this).attr("nuevoStock", nuevoStock);

	if(Number($(this).val()) > Number($(this).attr("stock"))){

		/*=============================================
		SI LA CANTIDAD ES SUPERIOR AL STOCK REGRESAR VALORES INICIALES
		=============================================*/

		$(this).val(1);

		var precioFinal = $(this).val() * precio.attr("precioReal");

		precio.val(precioFinal);

		sumarTotalPrecios();

		swal({
	      title: "La cantidad supera el Stock",
	      text: "¡Sólo hay "+$(this).attr("stock")+" unidades!",
	      type: "error",
	      confirmButtonText: "¡Cerrar!"
	    });

	    return;

	}

	// SUMAR TOTAL DE PRECIOS----------------
	sumarTotalPrecios()
	        
    // AGRUPAR PRODUCTOS EN FORMATO JSON------------------
    listarProductos()

})


/*=============================================
---------SUMAR TODOS LOS PRECIOS-----------------
=============================================*/

function sumarTotalPrecios(){
    var precioItem = $(".nuevoPrecioProducto");
    var sumaTotalPrecio = 0;

    // Sumar directamente los valores sin usar reduce
    precioItem.each(function() {
        sumaTotalPrecio += Number($(this).val()) || 0;
    });
    
    $("#nuevoTotalVenta").val(sumaTotalPrecio);
    $("#totalVenta").val(sumaTotalPrecio);
    $("#nuevoTotalVenta").attr("total", sumaTotalPrecio);
}




/*=============================================
BORRAR LUEGO-----------------------
=============================================*/






/*=============================================
FORMATO AL PRECIO FINAL
=============================================*/

$("#nuevoTotalVenta").number(true, 2);

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
$(".formularioVenta").on("input", "input#nuevoValorEfectivo", function() {

    var efectivo = $(this).val();
    var totalVenta = Number($('#nuevoTotalVenta').val());
    var cambio = Number(efectivo) - totalVenta;

    // Asegurarse de que el cambio no sea negativo
    cambio = cambio < 0 ? 0 : cambio;

    var nuevoCambioEfectivo = $(this).closest('.cajasMetodoPago')
        .find('#nuevoCambioEfectivo');

    nuevoCambioEfectivo.val(cambio.toFixed(2)); // Asegura dos decimales

});


/*=============================================
CAMBIO TRANSACCIÓN
=============================================*/
$(".formularioVenta").on("change", "input#nuevoCodigoTransaccion", function(){

	// Listar método en la entrada
     listarMetodos()


})

/*=============================================
LISTAR TODOS LOS PRODUCTOS EN FORMATO JSON DENTRO DEL INPUT
=============================================*/
var listaProductos = [];
function listarProductos(){
	listaProductos = []; // Limpiar el array antes de llenarlo
	var descripcion = $(".nuevaDescripcionProducto");
	var cantidad = $(".nuevaCantidadProducto");
	var precio = $(".nuevoPrecioProducto");
	var precioCompra = $(".nuevoPrecioCompraProducto");
	var nota = $(".nota-producto");
	var descripcionAdicional = $(".descripcion-producto");
	var formaAtencionDetalle = $("select[name='formaAtencionDetalle']"); // Cambio aquí para seleccionar todos los selects

	for(var i = 0; i < descripcion.length; i++){
		// Obtener los textos de las opciones seleccionadas
		var preferenciasSeleccionadas = [];
		if($(nota[i]).length > 0) {
			$(nota[i]).find("option:selected").each(function() {
				preferenciasSeleccionadas.push($(this).text());
			});
		}

		listaProductos.push({ 
			"id" : $(descripcion[i]).attr("idProducto"), 
			"descripcion" : $(descripcion[i]).val(),
			"cantidad" : $(cantidad[i]).val(),
			"stock" : $(cantidad[i]).attr("nuevoStock"),
			"precio" : $(precio[i]).attr("precioReal"),
			"precioCompra" : $(precioCompra[i]).attr("precioRealCompra"),
			"total" : $(precio[i]).val(),
			"preferencias" : preferenciasSeleccionadas.join(",") || null,
			"nota_adicional" : $(descripcionAdicional[i]).val() || null,
			"forma_atencion" : $(formaAtencionDetalle[i]).val() || null // Obtener el valor del select específico
		});
	}

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

// Variable global para mantener la referencia de la ventana emergente
var popupWindow = null;

$(".tablas").on("click", ".btnImprimirFactura", function() {
    var codigoVenta = $(this).attr("codigoVenta");

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
    popupWindow = window.open("extensiones/tcpdf/pdf/factura.php?codigo=" + codigoVenta, "_blank", windowFeatures);
});


/*=============================================
FUNCIÓN PARA INICIALIZAR LA TABLA
=============================================*/
function cargarTablaVentas(fechaInicial, fechaFinal) {

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
		  fechaFinal: fechaFinal
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
	  cargarTablaVentas(fechaInicial, fechaFinal);
	}
  );
  
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
	  cargarTablaVentas(fechaInicial, fechaFinal);
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
    var $productoRow = $(this).closest('.row');
    var idProducto = $productoRow.find('.nuevaDescripcionProducto').attr('idProducto');
    var stockOriginal = parseInt($productoRow.find('.nuevaCantidadProducto').attr('stock'));
    var cantidadTotal = 0;
    
    // Calcular la cantidad total actual del producto en la venta
    $('.nuevaCantidadProducto').each(function() {
        if($(this).closest('.row').find('.nuevaDescripcionProducto').attr('idProducto') === idProducto) {
            cantidadTotal += parseInt($(this).val() || 0);
        }
    });
    
    // Verificar si hay suficiente stock
    if(cantidadTotal >= stockOriginal) {
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
    
    // Clonar la fila
    var $nuevoProducto = $productoRow.clone();
    
    // Limpiar los elementos Select2 del clon
    $nuevoProducto.find('.select2-container').remove();
    $nuevoProducto.find('.nota-producto').removeClass('select2-hidden-accessible');
    
    // Limpiar los valores de las notas y preferencias
    $nuevoProducto.find('.nota-producto').val(null);
    $nuevoProducto.find('.descripcion-producto').val('');
    
    // Establecer cantidad inicial en 1 y actualizar el nuevoStock
    var $cantidadInput = $nuevoProducto.find('.nuevaCantidadProducto');
    $cantidadInput.val(1);
    $cantidadInput.attr('stock', stockOriginal);
    $cantidadInput.attr('nuevoStock', stockOriginal - (cantidadTotal + 1));
    
    // Actualizar el precio según la cantidad
	var precioUnitario = $nuevoProducto.find('.nuevoPrecioProducto').attr('precioReal');
	$nuevoProducto.find('.nuevoPrecioProducto').val(parseFloat(precioUnitario).toFixed(2));

    // Insertar el nuevo producto después del original
    $productoRow.after($nuevoProducto);
    
    // Reinicializar Select2 en el nuevo elemento
    var $newSelect = $nuevoProducto.find('.nota-producto');
    $newSelect.select2({
        theme: "classic",
        multiple: true,
        width: '100%',
        dropdownParent: $nuevoProducto.find('.nota-dropdown'),
        language: {
            noResults: function() {
                return "No hay resultados";
            }
        }
    });
    
    // Actualizar los totales
    sumarTotalPrecios();
    listarProductos();

    // Restaurar los valores seleccionados
    var selectedPreferences = $productoRow.find('.nota-producto').val() || [];
    var notaAdicional = $productoRow.find('.descripcion-producto').val() || '';
    $newSelect.val(selectedPreferences).trigger('change');
    $nuevoProducto.find('.descripcion-producto').val(notaAdicional);
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