/*=============================================
EDITAR MESERO
=============================================*/
$(".tabla-gastos").on("click", ".btnEditarGasto", function(){

	var idGasto = $(this).attr("idGasto");

	var datos = new FormData();
    datos.append("idGasto", idGasto);

    $.ajax({

      url:"ajax/gastos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
      	 $("#idGasto").val(respuesta["id"]);
	       $("#editarIdTipoGasto").val(respuesta["id_tipo_gasto"]);
	       $("#editarDescripcion").val(respuesta["descripcion"]);
         $("#editarFecha").val(respuesta["fecha"]);
	       $("#editarMonto").val(respuesta["monto"]);
	       $("#editarTipoPago").val(respuesta["forma_pago"]);
	    }

  	})

})

/*=============================================
ELIMINAR MESERO
=============================================*/
$(".tabla-gastos").on("click", ".btnEliminarGasto", function(){

	var idGasto = $(this).attr("idGasto");
	
	swal({
        title: '¿Está seguro de borrar al Gasto?',
        text: "¡Si no lo está puede cancelar la acción!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar el Gasto!'
      }).then(function(result){
        if (result.value) {
          
            window.location = "index.php?ruta=gastos&idGasto="+idGasto;
        }

  })

});

