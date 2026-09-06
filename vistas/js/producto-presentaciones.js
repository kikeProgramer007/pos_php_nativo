/*=============================================
CRUD presentaciones en modal editar producto
=============================================*/
window.ProductoPresentacionesAdmin = {
  idProducto: 0,

  cargar: function (idProducto) {
    this.idProducto = Number(idProducto) || 0;
    var $bloque = $("#bloquePresentacionesProducto");
    if (!$bloque.length || !this.idProducto) {
      return;
    }
    $bloque.show();
    this.resetForm();
    $.post("ajax/producto_presentaciones.ajax.php", {
      accion: "listar",
      id_producto: this.idProducto
    }, function (resp) {
      var data = typeof resp === "string" ? JSON.parse(resp) : resp;
      ProductoPresentacionesAdmin.renderTabla(data.data || []);
    }).fail(function () {
      $("#tablaPresentacionesProducto tbody").html(
        "<tr><td colspan='5' class='text-center text-danger'>Error al cargar presentaciones</td></tr>"
      );
    });
  },

  renderTabla: function (filas) {
    var $tbody = $("#tablaPresentacionesProducto tbody");
    $tbody.empty();

    // Unidad implícita (informativa)
    $tbody.append(
      "<tr class='text-muted'>" +
      "<td><em>Unidad</em> <small>(automática)</small></td>" +
      "<td class='text-center'>1</td>" +
      "<td class='text-center'>-</td>" +
      "<td><span class='label label-info'>Implícita</span></td>" +
      "<td class='text-center'>-</td>" +
      "</tr>"
    );

    if (!filas.length) {
      $tbody.append(
        "<tr><td colspan='5' class='text-center text-muted'>Sin presentaciones adicionales</td></tr>"
      );
      return;
    }

    filas.forEach(function (p) {
      var estadoHtml = Number(p.estado) === 1
        ? "<span class='label label-success'>Activo</span>"
        : "<span class='label label-default'>Inactivo</span>";
      $tbody.append(
        "<tr data-id='" + p.id + "'>" +
        "<td>" + $("<div>").text(p.nombre).html() + "</td>" +
        "<td class='text-center'>" + p.cantidad_unidades + "</td>" +
        "<td class='text-center'>" + p.orden + "</td>" +
        "<td>" + estadoHtml + "</td>" +
        "<td class='text-center'>" +
          "<button type='button' class='btn btn-xs btn-warning btnEditarPresentacion' " +
            "data-id='" + p.id + "' data-nombre='" + $("<div>").text(p.nombre).html() + "' " +
            "data-unidades='" + p.cantidad_unidades + "' data-orden='" + p.orden + "' data-estado='" + p.estado + "'>" +
            "<i class='fa fa-pencil'></i></button> " +
          (Number(p.estado) === 1
            ? "<button type='button' class='btn btn-xs btn-danger btnDesactivarPresentacion' data-id='" + p.id + "'><i class='fa fa-ban'></i></button>"
            : "") +
        "</td>" +
        "</tr>"
      );
    });
  },

  resetForm: function () {
    $("#presId").val("0");
    $("#presNombre").val("");
    $("#presUnidades").val("");
    $("#presOrden").val("0");
    $("#presEstado").val("1");
    $("#btnGuardarPresentacion").html("<i class='fa fa-plus'></i> Agregar");
  },

  guardar: function () {
    var payload = {
      accion: "guardar",
      id: $("#presId").val() || 0,
      id_producto: this.idProducto,
      nombre: $("#presNombre").val(),
      cantidad_unidades: $("#presUnidades").val(),
      orden: $("#presOrden").val() || 0,
      estado: $("#presEstado").val()
    };
    $.post("ajax/producto_presentaciones.ajax.php", payload, function (resp) {
      var data = typeof resp === "string" ? JSON.parse(resp) : resp;
      if (data.status === "ok") {
        ProductoPresentacionesAdmin.cargar(ProductoPresentacionesAdmin.idProducto);
      } else {
        swal({ type: "error", title: data.mensaje || "No se pudo guardar" });
      }
    });
  },

  desactivar: function (id) {
    swal({
      title: "¿Desactivar presentación?",
      text: "Dejará de aparecer en ventas nuevas",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, desactivar",
      cancelButtonText: "Cancelar"
    }).then(function (result) {
      if (!result.value) return;
      $.post("ajax/producto_presentaciones.ajax.php", {
        accion: "desactivar",
        id: id,
        id_producto: ProductoPresentacionesAdmin.idProducto
      }, function (resp) {
        var data = typeof resp === "string" ? JSON.parse(resp) : resp;
        if (data.status === "ok") {
          ProductoPresentacionesAdmin.cargar(ProductoPresentacionesAdmin.idProducto);
        } else {
          swal({ type: "error", title: data.mensaje || "Error" });
        }
      });
    });
  }
};

$(document).on("click", "#btnGuardarPresentacion", function (e) {
  e.preventDefault();
  ProductoPresentacionesAdmin.guardar();
});

$(document).on("click", "#btnCancelarPresentacion", function (e) {
  e.preventDefault();
  ProductoPresentacionesAdmin.resetForm();
});

$(document).on("click", ".btnEditarPresentacion", function () {
  $("#presId").val($(this).data("id"));
  $("#presNombre").val($(this).data("nombre"));
  $("#presUnidades").val($(this).data("unidades"));
  $("#presOrden").val($(this).data("orden"));
  $("#presEstado").val(String($(this).data("estado")));
  $("#btnGuardarPresentacion").html("<i class='fa fa-save'></i> Actualizar");
});

$(document).on("click", ".btnDesactivarPresentacion", function () {
  ProductoPresentacionesAdmin.desactivar($(this).data("id"));
});

$("#modalEditarProducto").on("hidden.bs.modal", function () {
  $("#bloquePresentacionesProducto").hide();
  ProductoPresentacionesAdmin.resetForm();
});
