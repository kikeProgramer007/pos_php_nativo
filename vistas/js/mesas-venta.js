/*=============================================
SELECTOR DE MESAS EN VENDER
=============================================*/
(function ($) {
	"use strict";

	var $wrap = $("#selectorMesaVenta");
	if (!$wrap.length) {
		return;
	}

	var mesasTemporales = [];
	var personasTemporal = null;
	var mesaMarcada = false;
	var personaMarcada = false;
	var modoMultiple = false;
	var mesasCache = [];
	var filtroActual = "todas";
	var MAX_MESAS_MULTIPLE = 5;

	var SVG_MESA = '<i class="ico-mesa" aria-hidden="true"></i>';
	var SVG_PERSONAS =
		'<svg class="ico-personas" viewBox="0 0 24 24" aria-hidden="true">' +
		'<circle cx="9" cy="8" r="3.2"/>' +
		'<path d="M3.5 18.5c.4-3.2 2.7-5 5.5-5s5.1 1.8 5.5 5"/>' +
		'<circle cx="16.5" cy="8.5" r="2.6"/>' +
		'<path d="M14.2 18.5c.2-2.2 1.6-3.6 3.6-3.6 1.8 0 3.1 1.2 3.5 3.1"/>' +
		"</svg>";

	function escapeHtml(str) {
		return String(str == null ? "" : str)
			.replace(/&/g, "&amp;")
			.replace(/</g, "&lt;")
			.replace(/>/g, "&gt;")
			.replace(/"/g, "&quot;");
	}

	function numeroDesdeNombreMesa(nombre) {
		var m = String(nombre == null ? "" : nombre).match(/(\d+)/);
		return m ? m[1] : "";
	}

	function nombreMesasTexto(lista) {
		var nums = (lista || [])
			.map(function (m) {
				return numeroDesdeNombreMesa(m.nombre || m.numero || m.id);
			})
			.filter(function (n) {
				return n !== "";
			});
		if (!nums.length) {
			return "";
		}
		return "Mesa: " + nums.join(",");
	}

	function capacidadTotal() {
		return mesasTemporales.reduce(function (acc, m) {
			return acc + (Number(m.capacidad) || 0);
		}, 0);
	}

	function htmlResumenVenta(nombreMesa, personas, adicional) {
		var persLabel = Number(personas) === 1 ? "persona" : "personas";
		var extra = adicional ? ' <span class="text-muted">(adicional)</span>' : "";
		return (
			'<span class="resumen-item">' +
			SVG_MESA +
			" " +
			escapeHtml(nombreMesa) +
			"</span>" +
			'<span class="resumen-sep">·</span>' +
			'<span class="resumen-item">' +
			SVG_PERSONAS +
			" " +
			escapeHtml(String(personas)) +
			" " +
			persLabel +
			"</span>" +
			extra
		);
	}

	function formaRequiereMesa() {
		var forma = $("#formaAtencion").val();
		return forma === "1" || forma === "3";
	}

	function actualizarVisibilidadSelector() {
		if (formaRequiereMesa()) {
			$wrap.addClass("visible");
		} else {
			$wrap.removeClass("visible");
			limpiarSeleccionMesa();
			cerrarOffcanvas();
		}
	}

	function limpiarSeleccionMesa() {
		$("#idMesaSeleccionada").val("");
		$("#nombreMesaSeleccionada").val("");
		$("#cantidadPersonasMesa").val("");
		$("#idAtencionMesaSeleccionada").val("");
		$("#idsMesasSeleccionadasExtra").val("");
		$("#textoMesasSeleccionadas").val("");
		$("#resumenMesaSeleccionada").empty();
		mesasTemporales = [];
		personasTemporal = null;
		mesaMarcada = false;
		personaMarcada = false;
	}

	function limpiarSeleccionEnOffcanvas() {
		limpiarSeleccionMesa();
		renderMesas();
		renderOpcionesPersonas(0);
		actualizarResumenTemporal();
		actualizarAccionesMesaOcupada();
	}

	function confirmarYEjecutarLiberacion(idMesa, nombre, tieneDeuda) {
		var texto = "La mesa quedará libre para otra venta.";
		if (tieneDeuda) {
			texto = "Aún hay cuenta pendiente. Cobre primero o no podrá liberar.";
		}

		swal({
			title: "¿Liberar " + nombre + "?",
			text: texto,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#00a65a",
			confirmButtonText: "Sí, liberar",
			cancelButtonText: "Cancelar"
		}).then(function (result) {
			if (!result || !result.value) {
				return;
			}

			var datos = new FormData();
			datos.append("liberarMesaDesdeSelector", idMesa);
			$("#btnLiberarMesaSelector").prop("disabled", true);

			$.ajax({
				url: "ajax/mesas.ajax.php",
				method: "POST",
				data: datos,
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function (resp) {
					$("#btnLiberarMesaSelector").prop("disabled", false);
					if (!resp || !resp.ok) {
						swal({
							type: "error",
							title: "No se liberó",
							text: (resp && resp.mensaje) || "No se pudo liberar la mesa."
						});
						return;
					}
					swal({
						type: "success",
						title: "Mesa liberada",
						text: resp.mensaje || (nombre + " quedó disponible.")
					});
					limpiarSeleccionMesa();
					cargarMesasSelector();
				},
				error: function () {
					$("#btnLiberarMesaSelector").prop("disabled", false);
					swal({
						type: "error",
						title: "Error",
						text: "No se pudo comunicar con el servidor."
					});
				}
			});
		});
	}

	function liberarMesaDesdeSelector() {
		var ocupada = mesaOcupadaSeleccionada();
		if (!ocupada) {
			return;
		}
		var idMesa = ocupada.id;
		var nombre = ocupada.nombre || "mesa";

		var datosDetalle = new FormData();
		datosDetalle.append("detalleAtencionMesa", idMesa);
		$("#btnLiberarMesaSelector").prop("disabled", true);

		$.ajax({
			url: "ajax/mesas.ajax.php",
			method: "POST",
			data: datosDetalle,
			cache: false,
			contentType: false,
			processData: false,
			dataType: "json",
			success: function (detalle) {
				$("#btnLiberarMesaSelector").prop("disabled", false);
				if (detalle && detalle.ok && detalle.tiene_deuda) {
					swal({
						type: "warning",
						title: "Hay venta por cobrar",
						text:
							"No se puede liberar " +
							nombre +
							" mientras existan cuentas pendientes (Bs " +
							Number(detalle.total_pendiente || 0).toFixed(2) +
							"). Cobre la cuenta primero.",
						showCancelButton: true,
						confirmButtonColor: "#00a65a",
						cancelButtonColor: "#3c8dbc",
						confirmButtonText: "Cobrar",
						cancelButtonText: "Cerrar"
					}).then(function (result) {
						if (!result || !result.value) {
							return;
						}
						var ventaPend = primeraVentaPendiente(detalle.ventas);
						window.location = ventaPend
							? urlVerVentaPendiente(ventaPend, idMesa)
							: "index.php?ruta=ventas&estadoPago=PENDIENTE&idMesa=" +
							  encodeURIComponent(idMesa);
					});
					return;
				}
				confirmarYEjecutarLiberacion(idMesa, nombre, false);
			},
			error: function () {
				$("#btnLiberarMesaSelector").prop("disabled", false);
				confirmarYEjecutarLiberacion(idMesa, nombre, false);
			}
		});
	}

	function actualizarBotonConfirmar() {
		if (modoMultiple) {
			$("#btnConfirmarSelectorMesa").show();
		} else {
			$("#btnConfirmarSelectorMesa").hide();
		}
	}

	function mesaOcupadaSeleccionada() {
		if (modoMultiple || mesasTemporales.length !== 1) {
			return null;
		}
		var m = mesasTemporales[0];
		if (m && m.idAtencion) {
			return m;
		}
		return null;
	}

	function actualizarAccionesMesaOcupada() {
		var ocupada = mesaOcupadaSeleccionada();
		var $btnVer = $("#btnVerMesaSelector");
		var $btnLib = $("#btnLiberarMesaSelector");
		if (ocupada) {
			$("#lblVerMesaSelector").text("Ver " + ocupada.nombre);
			$("#lblLiberarMesaSelector").text("Liberar " + ocupada.nombre);
			$btnVer.attr("data-id-mesa", ocupada.id).attr("data-nombre-mesa", ocupada.nombre).show();
			$btnLib.attr("data-id-mesa", ocupada.id).attr("data-nombre-mesa", ocupada.nombre).show();
		} else {
			$btnVer.hide().removeAttr("data-id-mesa").removeAttr("data-nombre-mesa");
			$btnLib.hide().removeAttr("data-id-mesa").removeAttr("data-nombre-mesa");
			$("#lblVerMesaSelector").text("Ver mesa");
			$("#lblLiberarMesaSelector").text("Liberar mesa");
		}
	}

	var ticketBlobUrlActual = null;
	var ventasAtencionCache = [];
	var detalleAtencionCache = null;
	var idMesaVerActual = null;

	function liberarTicketBlobUrl() {
		if (ticketBlobUrlActual) {
			URL.revokeObjectURL(ticketBlobUrlActual);
			ticketBlobUrlActual = null;
		}
		$("#iframeTicketVerMesa").attr("src", "about:blank");
	}

	function formatoBs(valor) {
		return "Bs " + Number(valor || 0).toFixed(2);
	}

	function formatearFechaAtencion(fecha) {
		if (!fecha) {
			return "—";
		}
		var d = new Date(String(fecha).replace(" ", "T"));
		if (isNaN(d.getTime())) {
			return String(fecha);
		}
		var dd = ("0" + d.getDate()).slice(-2);
		var mm = ("0" + (d.getMonth() + 1)).slice(-2);
		var yyyy = d.getFullYear();
		var hh = ("0" + d.getHours()).slice(-2);
		var mi = ("0" + d.getMinutes()).slice(-2);
		return dd + "/" + mm + "/" + yyyy + " - " + hh + ":" + mi;
	}

	/** Solo lleva a Administrar ventas filtrando esa cuenta pendiente (NO usar idVenta: anula la venta). */
	function urlVerVentaPendiente(venta, idMesa) {
		var params = ["ruta=ventas", "estadoPago=PENDIENTE"];
		var mesaId = idMesa || (venta && venta.id_mesa) || "";
		if (mesaId) {
			params.push("idMesa=" + encodeURIComponent(mesaId));
		}
		if (venta && venta.codigo != null && venta.codigo !== "") {
			var ticket = String(venta.codigo).replace(/^0+/, "") || String(venta.codigo);
			params.push("codigoTicket=" + encodeURIComponent(ticket));
		}
		return "index.php?" + params.join("&");
	}

	function primeraVentaPendiente(ventas) {
		var encontrada = null;
		(ventas || []).forEach(function (v) {
			if (!encontrada && String(v.estado_pago || "").toUpperCase() === "PENDIENTE") {
				encontrada = v;
			}
		});
		return encontrada;
	}

	function renderPedidoDetalle(venta) {
		var $card = $("#pedidoDetalleVerMesa");
		if (!venta) {
			$card.html('<div style="padding:16px;" class="text-muted">Sin pedidos en esta atención.</div>');
			$("#btnCobrarVerMesa").hide().attr("href", "#");
			return;
		}

		var ticket = String(venta.codigo || "").replace(/^0+/, "") || venta.codigo;
		var estado = String(venta.estado_pago || "").toUpperCase();
		var badge =
			estado === "PENDIENTE"
				? '<span class="label label-warning">Pendiente</span>'
				: '<span class="label label-success">Pagada</span>';

		var filas = "";
		var items = venta.items || [];
		if (!items.length) {
			filas =
				'<tr><td colspan="4" class="text-center text-muted">Sin productos</td></tr>';
		} else {
			items.forEach(function (it) {
				filas +=
					"<tr>" +
					"<td>" +
					escapeHtml(it.producto || "") +
					"</td>" +
					'<td class="text-center">' +
					escapeHtml(String(it.cantidad || 0)) +
					"</td>" +
					'<td class="text-right">' +
					formatoBs(it.precio_venta) +
					"</td>" +
					'<td class="text-right">' +
					formatoBs(it.subtotal) +
					"</td>" +
					"</tr>";
			});
		}

		$card.html(
			'<div class="mesa-ver-pedido-head">' +
				"<div>" +
				"<strong>Ticket #" +
				escapeHtml(ticket) +
				"</strong>" +
				"<small>" +
				escapeHtml(formatearFechaAtencion(venta.fecha)) +
				" · Cajero: " +
				escapeHtml(venta.cajero || "—") +
				"</small>" +
				"</div>" +
				badge +
				"</div>" +
				'<div class="mesa-ver-pedido-body">' +
				'<table class="table table-condensed" style="margin:0;">' +
				"<thead><tr>" +
				"<th>Producto</th><th class='text-center'>Cant.</th>" +
				"<th class='text-right'>Precio</th><th class='text-right'>Subtotal</th>" +
				"</tr></thead>" +
				"<tbody>" +
				filas +
				"</tbody></table>" +
				"</div>" +
				'<div class="mesa-ver-pedido-foot">' +
				"<span>Total del ticket</span>" +
				"<span>" +
				formatoBs(venta.total) +
				"</span>" +
				"</div>"
		);

		if (estado === "PENDIENTE") {
			$("#btnCobrarVerMesa")
				.attr("href", urlVerVentaPendiente(venta, idMesaVerActual))
				.attr("title", "Ver esta cuenta pendiente en Administrar ventas")
				.show();
		} else {
			$("#btnCobrarVerMesa").hide().attr("href", "#");
		}
	}

	function cargarTicketEnIframe(idVenta) {
		liberarTicketBlobUrl();
		$("#cargandoTicketVerMesa").addClass("visible");

		fetch("extensiones/tcpdf/pdf/factura.php?codigo=" + encodeURIComponent(idVenta), {
			method: "GET",
			headers: { "Content-Type": "application/json" }
		})
			.then(function (response) {
				if (!response.ok) {
					throw new Error("HTTP " + response.status);
				}
				return response.json();
			})
			.then(function (data) {
				$("#cargandoTicketVerMesa").removeClass("visible");
				if (!data || !data.success || !data.facturaBase64) {
					swal({
						type: "warning",
						title: "Ticket",
						text: "No se pudo generar la vista del ticket."
					});
					return;
				}
				var binary = atob(data.facturaBase64);
				var bytes = new Uint8Array(binary.length);
				for (var i = 0; i < binary.length; i++) {
					bytes[i] = binary.charCodeAt(i);
				}
				var blob = new Blob([bytes], { type: "application/pdf" });
				ticketBlobUrlActual = URL.createObjectURL(blob);
				$("#iframeTicketVerMesa").attr("src", ticketBlobUrlActual);
			})
			.catch(function () {
				$("#cargandoTicketVerMesa").removeClass("visible");
				swal({
					type: "error",
					title: "Error",
					text: "No se pudo cargar el ticket."
				});
			});
	}

	function seleccionarVentaVerMesa(idVenta) {
		var venta = null;
		ventasAtencionCache.forEach(function (v) {
			if (String(v.id) === String(idVenta)) {
				venta = v;
			}
		});
		$("#listaTicketsVerMesa .btn").removeClass("btn-primary active").addClass("btn-default");
		$('#listaTicketsVerMesa .btn[data-id-venta="' + idVenta + '"]')
			.removeClass("btn-default")
			.addClass("btn-primary active");
		renderPedidoDetalle(venta);
		if (venta) {
			cargarTicketEnIframe(venta.id);
		}
	}

	function verAtencionDesdeSelector() {
		var ocupada = mesaOcupadaSeleccionada();
		if (!ocupada) {
			return;
		}
		var idMesa = ocupada.id;
		var nombre = ocupada.nombre || "mesa";
		idMesaVerActual = idMesa;
		var datos = new FormData();
		datos.append("detalleAtencionMesa", idMesa);

		$.ajax({
			url: "ajax/mesas.ajax.php",
			method: "POST",
			data: datos,
			cache: false,
			contentType: false,
			processData: false,
			dataType: "json",
			success: function (resp) {
				if (!resp || !resp.ok) {
					swal({
						type: "error",
						title: "Error",
						text: (resp && resp.mensaje) || "No se pudo cargar la atención."
					});
					return;
				}

				detalleAtencionCache = resp;
				ventasAtencionCache = resp.ventas || [];

				var capacidad = (resp.mesa && resp.mesa.capacidad) || ocupada.capacidad || "—";
				var personasTxt = capacidad + (Number(capacidad) === 1 ? " persona" : " personas");
				var inicio =
					resp.atencion && resp.atencion.fecha_apertura
						? formatearFechaAtencion(resp.atencion.fecha_apertura)
						: "—";

				$("#tituloVerAtencionMesaVenta").text(resp.nombre_mesa || nombre);
				$("#metaVerAtencionMesaVenta").text(
					personasTxt +
						"  ·  Mesero: " +
						(resp.mesero || "s/n") +
						"  ·  Inicio: " +
						inicio
				);
				$("#verMesaTotalConsumido").text(formatoBs(resp.total_consumido));
				$("#verMesaTotalPagado").text(formatoBs(resp.total_pagado));
				$("#verMesaTotalPendiente").text(formatoBs(resp.total_pendiente));
				$("#tituloPedidosVerMesa").text(
					"Pedidos de la atención (" + (resp.cantidad_pedidos || ventasAtencionCache.length) + ")"
				);
				// No permitir liberar si hay venta(s) por cobrar
				if (resp.tiene_deuda) {
					$("#btnLiberarDesdeVerMesa").hide();
				} else {
					$("#btnLiberarDesdeVerMesa").show();
				}

				var $tabs = $("#listaTicketsVerMesa");
				$tabs.empty();
				liberarTicketBlobUrl();

				if (!ventasAtencionCache.length) {
					renderPedidoDetalle(null);
					$tabs.html('<span class="text-muted">No hay tickets para mostrar.</span>');
					$("#modalVerAtencionMesaVenta").modal("show");
					return;
				}

				if (ventasAtencionCache.length > 1) {
					ventasAtencionCache.forEach(function (v) {
						var ticket = String(v.codigo || "").replace(/^0+/, "") || v.codigo;
						var estado = String(v.estado_pago || "").toUpperCase();
						var $btn = $('<button type="button" class="btn btn-default btn-sm"></button>');
						$btn.attr("data-id-venta", v.id);
						$btn.text(
							"Ticket #" + ticket + " · " + (estado === "PENDIENTE" ? "Pendiente" : "Pagada")
						);
						$btn.on("click", function () {
							seleccionarVentaVerMesa(v.id);
						});
						$tabs.append($btn);
					});
				}

				var priorizar =
					ventasAtencionCache.find(function (v) {
						return String(v.estado_pago || "").toUpperCase() === "PENDIENTE";
					}) || ventasAtencionCache[ventasAtencionCache.length - 1];

				$("#modalVerAtencionMesaVenta").modal("show");
				seleccionarVentaVerMesa(priorizar.id);
			},
			error: function () {
				swal({
					type: "error",
					title: "Error",
					text: "No se pudo comunicar con el servidor."
				});
			}
		});
	}

	function abrirOffcanvas() {
		filtroActual = "todas";
		modoMultiple = false;
		$("#chkMesasMultiples").prop("checked", false);
		$("#mesaFiltroEstado .btn-filtro-mesa").removeClass("active");
		$("#mesaFiltroEstado .btn-filtro-mesa[data-filtro='todas']").addClass("active");
		actualizarBotonConfirmar();
		$("#mesaOffcanvasBackdrop").addClass("open");
		$("#mesaOffcanvas").addClass("open").attr("aria-hidden", "false");
		cargarMesasSelector();
	}

	function cerrarOffcanvas() {
		$("#mesaOffcanvasBackdrop").removeClass("open");
		$("#mesaOffcanvas").removeClass("open").attr("aria-hidden", "true");
	}

	function etiquetaEstado(estado) {
		if (estado === "disponible") {
			return "Disponible";
		}
		if (estado === "ocupada") {
			return "Ocupada";
		}
		if (estado === "deshabilitada") {
			return "Deshabilitada";
		}
		return estado;
	}

	function mesasFiltradas() {
		if (filtroActual === "disponibles") {
			return mesasCache.filter(function (m) {
				return String(m.estado).toLowerCase() === "disponible";
			});
		}
		if (filtroActual === "ocupadas") {
			return mesasCache.filter(function (m) {
				return String(m.estado).toLowerCase() === "ocupada";
			});
		}
		return mesasCache.slice();
	}

	function actualizarResumenTemporal() {
		var mesaTxt = mesasTemporales.length ? nombreMesasTexto(mesasTemporales) : "—";
		var persTxt = personaMarcada && personasTemporal ? String(personasTemporal) : "—";
		$("#mesaOffcanvasResumen").html(
			"<span>Selección actual</span>" +
			'<span class="resumen-item">' + SVG_MESA + " <strong></strong></span>" +
			'<span class="resumen-item">' + SVG_PERSONAS + " Personas: <strong></strong></span>"
		);
		$("#mesaOffcanvasResumen .ico-mesa, #mesaOffcanvasResumen .ico-personas").css("color", "#3c8dbc");
		var $strongs = $("#mesaOffcanvasResumen strong");
		$strongs.eq(0).text(mesaTxt);
		$strongs.eq(1).text(persTxt);
	}

	function renderOpcionesPersonas(capacidad, preferida, marcarActiva) {
		var $grid = $("#mesaPersonasGrid");
		var $pill = $("#mesaCapacidadPill");
		$grid.empty();

		if (!capacidad || capacidad < 1) {
			$pill.removeClass("visible").text("");
			$grid
				.addClass("mesa-personas-grid--vacio")
				.html('<p class="mesa-personas-vacio">Seleccione mesa y cantidad de personas.</p>');
			actualizarResumenTemporal();
			return;
		}

		$grid.removeClass("mesa-personas-grid--vacio");
		var etiquetaCap =
			mesasTemporales.length > 1
				? "Capacidad combinada: " + capacidad + " personas"
				: "Capacidad de la mesa: " + capacidad + (capacidad === 1 ? " persona" : " personas");
		$pill.addClass("visible").text(etiquetaCap);

		var elegida = null;
		if (preferida != null && preferida !== "") {
			elegida = Number(preferida);
		} else if (personaMarcada && personasTemporal) {
			elegida = Number(personasTemporal);
		}
		if (elegida != null) {
			if (elegida > capacidad) {
				elegida = capacidad;
			}
			if (elegida < 1) {
				elegida = 1;
			}
			if (marcarActiva) {
				personasTemporal = elegida;
			}
		}

		for (var i = 1; i <= capacidad; i++) {
			var $btn = $('<button type="button" class="mesa-persona-btn"></button>');
			$btn.attr("data-personas", i);
			$btn.text(i);
			if (marcarActiva && elegida === i) {
				$btn.addClass("active");
			}
			$grid.append($btn);
		}

		actualizarResumenTemporal();
	}

	function refrescarPersonasPorMesas() {
		var cap = capacidadTotal();
		if (!cap) {
			renderOpcionesPersonas(0);
			return;
		}
		renderOpcionesPersonas(cap, personaMarcada ? personasTemporal : null, !!personaMarcada);
	}

	function idsSeleccionados() {
		return mesasTemporales.map(function (m) {
			return String(m.id);
		});
	}

	function renderMesas() {
		var $grid = $("#mesaGridSelector");
		var lista = mesasFiltradas();
		$grid.empty();

		if (!lista.length) {
			var msg = "No hay mesas para este filtro.";
			if (!mesasCache.length) {
				msg = "No hay mesas registradas.<br>Regístrelas en Administración → Mesas.";
			}
			$grid.html('<div class="mesa-offcanvas-vacio">' + msg + "</div>");
			return;
		}

		var seleccionados = idsSeleccionados();

		lista.forEach(function (mesa) {
			var estado = String(mesa.estado || "").toLowerCase();
			var idAtencion = mesa.id_atencion_abierta ? Number(mesa.id_atencion_abierta) : 0;
			var seleccionable =
				estado === "disponible" || (estado === "ocupada" && idAtencion > 0 && !modoMultiple);
			var $card = $('<div class="mesa-card-selector"></div>');
			$card.addClass(estado);
			$card.attr("data-id", mesa.id);
			$card.attr("data-nombre", mesa.nombre);
			$card.attr("data-capacidad", mesa.capacidad);
			$card.attr("data-estado", estado);
			$card.attr("data-id-atencion", idAtencion || "");
			$card.attr("data-personas-atencion", mesa.personas_atencion || "");
			if (!seleccionable) {
				$card.attr("aria-disabled", "true");
			} else if (estado === "ocupada") {
				$card.css("cursor", "pointer");
			}
			if (seleccionados.indexOf(String(mesa.id)) !== -1) {
				$card.addClass("seleccionada");
			}
			$card.html(
				'<span class="mesa-card-check"><i class="fa fa-check"></i></span>' +
				'<div class="mesa-card-icono">' + SVG_MESA + "</div>" +
				'<div class="mesa-card-nombre"></div>' +
				'<div class="mesa-card-meta">' + SVG_PERSONAS + ' <span class="cap"></span></div>' +
				'<span class="mesa-card-estado"></span>'
			);
			$card.find(".mesa-card-nombre").text(mesa.nombre);
			$card.find(".mesa-card-meta .cap").text(mesa.capacidad + " pers.");
			$card.find(".mesa-card-estado").first().text(etiquetaEstado(estado));
			$grid.append($card);
		});

		refrescarPersonasPorMesas();
		actualizarAccionesMesaOcupada();
	}

	function intentarCerrarSiCompleto() {
		if (modoMultiple) {
			return;
		}
		// Mesa ocupada solo sirve para Ver/Liberar; no confirma la venta
		if (mesasTemporales.length === 1 && mesasTemporales[0].idAtencion) {
			return;
		}
		if (mesaMarcada && personaMarcada && mesasTemporales.length === 1 && personasTemporal) {
			confirmarSeleccion(true);
		}
	}

	function aplicarSeleccionMesa(mesaObj) {
		if (modoMultiple) {
			var idx = -1;
			mesasTemporales.forEach(function (m, i) {
				if (String(m.id) === String(mesaObj.id)) {
					idx = i;
				}
			});
			if (idx >= 0) {
				mesasTemporales.splice(idx, 1);
			} else {
				if (mesasTemporales.length >= MAX_MESAS_MULTIPLE) {
					swal({
						type: "warning",
						title: "Máximo " + MAX_MESAS_MULTIPLE + " mesas",
						text: "Desmarque una mesa para elegir otra."
					});
					return;
				}
				mesasTemporales.push(mesaObj);
			}
			mesaMarcada = mesasTemporales.length > 0;
			if (!mesaMarcada) {
				personaMarcada = false;
				personasTemporal = null;
			}
		} else {
			mesasTemporales = [mesaObj];
			mesaMarcada = true;
		}

		renderMesas();
		intentarCerrarSiCompleto();
	}

	/** Aviso al tocar mesa ocupada: solo informar. Liberar/Ver siguen en el offcanvas. */
	function avisarMesaOcupada(mesaObj) {
		var nombre = mesaObj.nombre || "Mesa";
		// Marca la ocupada para que aparezcan Ver / Liberar en el toolbar (no cierra el offcanvas)
		mesaObj.pedidoAdicional = false;
		mesasTemporales = [mesaObj];
		mesaMarcada = true;
		personaMarcada = false;
		personasTemporal = null;
		renderMesas();
		actualizarAccionesMesaOcupada();

		swal({
			type: "info",
			title: nombre + " está ocupada",
			text: "Seleccione otra mesa disponible. Si desea liberarla, use el botón Liberar del panel.",
			confirmButtonColor: "#3c8dbc",
			confirmButtonText: "Cerrar",
			allowOutsideClick: true
		});
	}

	function seleccionarCard($card) {
		if ($card.is("[aria-disabled=true]")) {
			return;
		}
		var estado = String($card.attr("data-estado") || "").toLowerCase();
		var idAtencion = Number($card.attr("data-id-atencion") || 0);
		if (modoMultiple) {
			if (estado !== "disponible") {
				swal({
					type: "info",
					title: "Solo mesas disponibles",
					text: "En modo múltiple solo puede unir mesas disponibles."
				});
				return;
			}
		} else if (estado !== "disponible" && !(estado === "ocupada" && idAtencion > 0)) {
			return;
		}

		var mesaObj = {
			id: $card.attr("data-id"),
			nombre: $card.attr("data-nombre"),
			capacidad: Number($card.attr("data-capacidad")) || 1,
			idAtencion: idAtencion > 0 ? idAtencion : null,
			pedidoAdicional: false
		};

		// Mesa ocupada: offcanvas abierto, aviso simple; Liberar desde el botón del panel
		if (!modoMultiple && estado === "ocupada" && idAtencion > 0) {
			avisarMesaOcupada(mesaObj);
			return;
		}

		aplicarSeleccionMesa(mesaObj);
	}

	function confirmarSeleccion(silencioso) {
		var requiereMesas = modoMultiple ? 2 : 1;
		if (mesasTemporales.length < requiereMesas) {
			if (!silencioso) {
				swal({
					type: "warning",
					title: modoMultiple ? "Seleccione al menos 2 mesas" : "Seleccione una mesa",
					text: modoMultiple
						? "En modo múltiple elija entre 2 y " + MAX_MESAS_MULTIPLE + " mesas disponibles."
						: "Elija una mesa disponible."
				});
			}
			return false;
		}
		if (!modoMultiple && mesasTemporales[0] && mesasTemporales[0].idAtencion) {
			if (!silencioso) {
				swal({
					type: "info",
					title: "Mesa ocupada",
					text: "Seleccione otra mesa disponible. Para liberar use el botón Liberar del panel.",
					confirmButtonText: "Cerrar"
				});
			}
			return false;
		}
		if (modoMultiple && mesasTemporales.length > MAX_MESAS_MULTIPLE) {
			if (!silencioso) {
				swal({
					type: "warning",
					title: "Máximo " + MAX_MESAS_MULTIPLE + " mesas",
					text: "No puede unir más de " + MAX_MESAS_MULTIPLE + " mesas."
				});
			}
			return false;
		}
		if (!personaMarcada || !personasTemporal) {
			if (!silencioso) {
				swal({
					type: "warning",
					title: "Cantidad de personas",
					text: "Seleccione cuántas personas ocuparán la(s) mesa(s)."
				});
			}
			return false;
		}

		var cap = capacidadTotal();
		var personas = Number(personasTemporal) || 1;
		if (personas < 1 || personas > cap) {
			if (!silencioso) {
				swal({
					type: "warning",
					title: "Capacidad excedida",
					text: "Máximo " + cap + " persona(s) para la selección actual."
				});
			}
			return false;
		}

		var m1 = mesasTemporales[0];
		var extras = mesasTemporales.slice(1);
		$("#idMesaSeleccionada").val(m1.id);
		$("#nombreMesaSeleccionada").val(m1.nombre);
		$("#idAtencionMesaSeleccionada").val(m1.idAtencion || "");
		$("#cantidadPersonasMesa").val(personas);
		$("#idsMesasSeleccionadasExtra").val(
			extras
				.map(function (m) {
					return m.id;
				})
				.join(",")
		);

		var nombres = nombreMesasTexto(mesasTemporales);
		$("#textoMesasSeleccionadas").val(nombres);
		$("#resumenMesaSeleccionada").html(
			htmlResumenVenta(nombres, personas, !!(m1.pedidoAdicional && extras.length === 0))
		);
		$("#resumenMesaSeleccionada .ico-mesa, #resumenMesaSeleccionada .ico-personas").css(
			"color",
			"#3c8dbc"
		);
		cerrarOffcanvas();
		return true;
	}

	function cargarMesasSelector() {
		var $grid = $("#mesaGridSelector");
		$grid.html('<div class="mesa-offcanvas-vacio">Cargando mesas...</div>');
		mesasTemporales = [];
		mesaMarcada = false;
		personaMarcada = false;
		personasTemporal = null;
		renderOpcionesPersonas(0);
		actualizarResumenTemporal();

		var datos = new FormData();
		datos.append("listarMesasSelector", "1");

		$.ajax({
			url: "ajax/mesas.ajax.php",
			method: "POST",
			data: datos,
			cache: false,
			contentType: false,
			processData: false,
			dataType: "json",
			success: function (resp) {
				if (!resp || !resp.ok) {
					mesasCache = [];
					$grid.html('<div class="mesa-offcanvas-vacio">No se pudieron cargar las mesas.</div>');
					return;
				}
				mesasCache = resp.mesas || [];
				renderMesas();

				var id1 = $("#idMesaSeleccionada").val();
				var extrasCsv = $("#idsMesasSeleccionadasExtra").val() || "";
				var pers = $("#cantidadPersonasMesa").val();
				if (id1) {
					var idsRestore = [id1];
					String(extrasCsv)
						.split(",")
						.forEach(function (id) {
							id = String(id || "").trim();
							if (id && idsRestore.indexOf(id) === -1) {
								idsRestore.push(id);
							}
						});
					var encontradas = [];
					idsRestore.forEach(function (id) {
						var raw = null;
						mesasCache.forEach(function (m) {
							if (String(m.id) === String(id)) {
								raw = m;
							}
						});
						if (raw) {
							encontradas.push({
								id: raw.id,
								nombre: raw.nombre,
								capacidad: Number(raw.capacidad) || 1,
								idAtencion: raw.id_atencion_abierta
									? Number(raw.id_atencion_abierta)
									: null,
								pedidoAdicional: false
							});
						}
					});
					if (encontradas.length) {
						if (encontradas.length > 1) {
							modoMultiple = true;
							$("#chkMesasMultiples").prop("checked", true);
							actualizarBotonConfirmar();
						}
						mesasTemporales = encontradas;
						mesaMarcada = true;
						if (pers) {
							personasTemporal = Number(pers);
							personaMarcada = true;
						}
						renderMesas();
					}
				}
			},
			error: function () {
				mesasCache = [];
				$grid.html('<div class="mesa-offcanvas-vacio">Error al cargar las mesas.</div>');
			}
		});
	}

	window.validarMesaParaVenta = function () {
		if (!$wrap.length || !$wrap.hasClass("visible")) {
			return true;
		}
		if (!formaRequiereMesa()) {
			return true;
		}
		var idMesa = $("#idMesaSeleccionada").val();
		var personas = Number($("#cantidadPersonasMesa").val() || 0);
		if (!idMesa) {
			swal({
				type: "warning",
				title: "Seleccione una mesa",
				text: "Debe elegir mesa y cantidad de personas antes de cobrar."
			});
			return false;
		}
		if (personas < 1) {
			swal({
				type: "warning",
				title: "Cantidad de personas",
				text: "Indique la cantidad de personas."
			});
			return false;
		}
		return true;
	};

	$("#btnAbrirSelectorMesa").on("click", function () {
		abrirOffcanvas();
	});

	$("#btnCerrarSelectorMesa, #btnCancelarSelectorMesa, #mesaOffcanvasBackdrop").on("click", function () {
		cerrarOffcanvas();
	});

	$("#chkMesasMultiples").on("change", function () {
		modoMultiple = $(this).is(":checked");
		mesasTemporales = [];
		mesaMarcada = false;
		personaMarcada = false;
		personasTemporal = null;
		actualizarBotonConfirmar();
		renderMesas();
	});

	$("#mesaFiltroEstado").on("click", ".btn-filtro-mesa", function () {
		var $btn = $(this);
		filtroActual = $btn.attr("data-filtro") || "todas";
		$("#mesaFiltroEstado .btn-filtro-mesa").removeClass("active");
		$btn.addClass("active");
		renderMesas();
	});

	$("#mesaGridSelector").on("click", ".mesa-card-selector", function () {
		seleccionarCard($(this));
	});

	$("#mesaPersonasGrid").on("click", ".mesa-persona-btn", function () {
		if (!mesasTemporales.length) {
			swal({
				type: "warning",
				title: "Seleccione una mesa",
				text: "Elija la mesa (o mesas) y luego la cantidad de personas."
			});
			return;
		}
		var n = Number($(this).attr("data-personas")) || 1;
		var cap = capacidadTotal();
		if (n > cap) {
			return;
		}
		personasTemporal = n;
		personaMarcada = true;
		$("#mesaPersonasGrid .mesa-persona-btn").removeClass("active");
		$(this).addClass("active");
		actualizarResumenTemporal();
		intentarCerrarSiCompleto();
	});

	$("#btnLimpiarSelectorMesa").on("click", function () {
		limpiarSeleccionEnOffcanvas();
	});

	$("#btnVerMesaSelector").on("click", function () {
		verAtencionDesdeSelector();
	});

	$("#btnLiberarMesaSelector").on("click", function () {
		liberarMesaDesdeSelector();
	});

	$("#modalVerAtencionMesaVenta").on("hidden.bs.modal", function () {
		liberarTicketBlobUrl();
		ventasAtencionCache = [];
		detalleAtencionCache = null;
		idMesaVerActual = null;
	});

	$("#btnLiberarDesdeVerMesa").on("click", function () {
		var idMesa = idMesaVerActual;
		if (!idMesa) {
			return;
		}
		var encontrada = null;
		mesasCache.forEach(function (m) {
			if (String(m.id) === String(idMesa)) {
				encontrada = m;
			}
		});
		if (encontrada) {
			mesasTemporales = [
				{
					id: encontrada.id,
					nombre: encontrada.nombre,
					capacidad: Number(encontrada.capacidad) || 1,
					idAtencion: encontrada.id_atencion_abierta
						? Number(encontrada.id_atencion_abierta)
						: null,
					pedidoAdicional: true
				}
			];
			mesaMarcada = true;
			actualizarAccionesMesaOcupada();
			renderMesas();
		}
		$("#modalVerAtencionMesaVenta").one("hidden.bs.modal", function () {
			liberarMesaDesdeSelector();
		});
		$("#modalVerAtencionMesaVenta").modal("hide");
	});

	$("#btnConfirmarSelectorMesa").on("click", function () {
		confirmarSeleccion(false);
	});

	$("#formaAtencion").on("change.mesasVenta", actualizarVisibilidadSelector);

	function initTooltipsMesas() {
		if (typeof $.fn.tooltip !== "function") {
			return;
		}
		$("#selectorMesaVenta [data-toggle='tooltip'], #mesaOffcanvas [data-toggle='tooltip']")
			.tooltip({ container: "body", trigger: "hover" });
	}

	$(document).ready(function () {
		actualizarVisibilidadSelector();
		actualizarBotonConfirmar();
		initTooltipsMesas();
		var idPrev = $("#idMesaSeleccionada").val();
		var nomPrev = $("#nombreMesaSeleccionada").val();
		var persPrev = $("#cantidadPersonasMesa").val();
		if (idPrev && nomPrev) {
			var nombres =
				$("#textoMesasSeleccionadas").val() ||
				nombreMesasTexto([{ nombre: nomPrev }]) ||
				nomPrev;
			$("#resumenMesaSeleccionada").html(htmlResumenVenta(nombres, persPrev || "1", false));
			$("#resumenMesaSeleccionada .ico-mesa, #resumenMesaSeleccionada .ico-personas").css(
				"color",
				"#3c8dbc"
			);
		}
	});
})(jQuery);
