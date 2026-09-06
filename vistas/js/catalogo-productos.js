class CatalogoProductos {
  constructor() {
    this.productos = [];
    this.categorias = [];
    this.paginaActual = 1;
    this.registrosPorPagina = 20;
    this.categoriaSeleccionada = 'todos';
    this.terminoBusqueda = '';
    this.productosAgregados = new Set();
    
    this.inicializar();
  }

  async inicializar() {
    await this.cargarCategorias();
    await this.cargarProductos();
    this.inicializarEventos();
    this.renderizarCatalogo();
  }

  async cargarCategorias() {
    try {
      const response = await $.ajax({
        url: "ajax/tabladinamica/tabla-categoria.ajax.php",
        method: "GET",
        dataType: "json"
      });

      this.categorias = response.data.map(categoria => ({
        id: categoria[4],
        nombre: categoria[1]
      }));
      this.renderizarFiltrosCategorias();
    } catch (error) {
      console.error('Error al cargar categorías:', error);
      swal({
        type: "error",
        title: "Error al cargar las categorías",
        text: "Por favor, intente nuevamente",
        showConfirmButton: true,
        confirmButtonText: "Cerrar"
      });
    }
  }

  async cargarProductos() {
    try {
      const response = await $.ajax({
        url: "ajax/datatable-ventas.ajax.php",
        method: "GET",
        dataType: "json"
      });

      this.productos = response.data.map(producto => {
        const tempDiv = document.createElement('div');
        
        // Extraer imagen
        tempDiv.innerHTML = producto[1];
        const imagenSrc = tempDiv.querySelector('img').src;
        
        // Extraer ID del producto
        tempDiv.innerHTML = producto[5];
        const idProducto = tempDiv.querySelector('button').getAttribute('idProducto');

        const inventariable = Number(producto[8] !== undefined ? producto[8] : 1);
        const stockRaw = Number(producto[9] !== undefined ? producto[9] : 0);

        return {
          id: idProducto,
          imagen: imagenSrc,
          codigo: producto[2],
          descripcion: producto[3],
          stock: stockRaw,
          precio_venta: producto[6],
          categoria_id: String(producto[7]),
          inventariable: inventariable
        };
      });

      this.renderizarCatalogo();
    } catch (error) {
      console.error('Error al cargar productos:', error);
      swal({
        type: "error",
        title: "Error al cargar los productos 2",
        text: "Por favor, intente nuevamente",
        showConfirmButton: true,
        confirmButtonText: "Cerrar"
      });
    }
  }

  inicializarEventos() {
    $('#registrosPorPagina').on('change', (e) => {
      this.registrosPorPagina = parseInt(e.target.value);
      this.paginaActual = 1;
      this.renderizarCatalogo();
    });

    $('#btnAnterior').on('click', () => {
      if (this.paginaActual > 1) {
        this.paginaActual--;
        this.renderizarCatalogo();
      }
    });

    $('#btnSiguiente').on('click', () => {
      const totalPaginas = Math.ceil(this.productosFiltrados.length / this.registrosPorPagina);
      if (this.paginaActual < totalPaginas) {
        this.paginaActual++;
        this.renderizarCatalogo();
      }
    });

    $('#buscarProducto').on('input', (e) => {
      this.terminoBusqueda = e.target.value.toLowerCase();
      this.paginaActual = 1;
      this.renderizarCatalogo();
      this.actualizarBotonLimpiarBusqueda();
    });

    $('#btnLimpiarBusquedaProducto').on('click', () => {
      $('#buscarProducto').val('').focus();
      this.terminoBusqueda = '';
      this.paginaActual = 1;
      this.renderizarCatalogo();
      this.actualizarBotonLimpiarBusqueda();
    });

    this.actualizarBotonLimpiarBusqueda();

    $(document).on('click', '.btn-categoria', (e) => {
      const categoria = $(e.target).data('categoria');
      $('.btn-categoria').removeClass('active');
      $(e.target).addClass('active');
      this.categoriaSeleccionada = categoria.toString();
      this.paginaActual = 1;
      this.renderizarCatalogo();
    });

    // Modificar el evento de click para agregar productos
    $(document).on('click', '.btn-agregar:not(.disabled)', (e) => {
      const boton = $(e.currentTarget);
      const idProducto = boton.attr('idProducto');
      
      // Deshabilitar el botón y agregar a la lista de productos agregados
      boton.addClass('disabled').prop('disabled', true);
      this.productosAgregados.add(idProducto);
      
      const datos = new FormData();
      datos.append("idProducto", idProducto);
      $.ajax({
        url: "ajax/productos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
          if (Number(respuesta.stock) <= 0) {
            boton.removeClass('disabled').prop('disabled', false);
            catalogoProductos.productosAgregados.delete(idProducto);
            swal({
              title: "Producto agotado",
              type: "error",
              confirmButtonText: "¡Cerrar!"
            });
            return;
          }
          agregarProductoAVenta(respuesta);
        }
      });
    });

    $(document).on('click', '.menu-disponibilidad-item', (e) => {
      e.preventDefault();
      e.stopPropagation();
      const $item = $(e.currentTarget);
      const $menu = $item.closest('.dropdown-disponibilidad');
      if ($menu.data('busy')) {
        return;
      }
      const idProducto = String($item.data('id'));
      const nuevoDisponible = Number($item.data('disponible')) === 1 ? 1 : 0;
      const producto = this.productos.find(p => String(p.id) === idProducto);
      if (!producto || Number(producto.inventariable) === 1) {
        return;
      }

      const stockActual = parseInt(producto.stock, 10) || 0;
      const yaDisponible = stockActual > 0;
      if ((nuevoDisponible === 1 && yaDisponible) || (nuevoDisponible === 0 && !yaDisponible)) {
        return;
      }

      $menu.data('busy', true);
      $.ajax({
        url: "ajax/productos.ajax.php",
        method: "POST",
        dataType: "json",
        data: {
          accion: "marcarDisponibilidad",
          idProductoDisponibilidad: idProducto,
          disponible: nuevoDisponible
        },
        success: (resp) => {
          if (!resp || resp.status !== "ok") {
            swal({
              type: "error",
              title: (resp && resp.mensaje) ? resp.mensaje : "No se pudo actualizar",
              confirmButtonText: "Cerrar"
            });
            return;
          }
          producto.stock = Number(resp.stock);
          this.renderizarCatalogo();
        },
        error: () => {
          swal({
            type: "error",
            title: "Error de comunicación",
            confirmButtonText: "Cerrar"
          });
        },
        complete: () => {
          $menu.data('busy', false);
        }
      });
    });
  }

  actualizarBotonLimpiarBusqueda() {
    var $btn = $('#btnLimpiarBusquedaProducto');
    var tieneTexto = ($('#buscarProducto').val() || '').trim().length > 0;
    $btn.toggleClass('is-visible', tieneTexto);
  }

  renderizarFiltrosCategorias() {
    const contenedor = $('#filtrosCategorias');
    contenedor.empty();
    // Agregar botón "Todos"
    contenedor.append(`
      <button class="btn-categoria active" data-categoria="todos">
        Todos
      </button>
    `);
 
    // Agregar botón para cada categoría
    this.categorias.forEach(categoria => {
      contenedor.append(`
        <button class="btn-categoria ${categoria.id ===  this.categoriaSeleccionada.toString() ? 'active' : ''}" data-categoria="${categoria.id}">
          ${categoria.nombre}
        </button>
      `);
    });
  }
  
  get productosFiltrados() {
    return this.productos.filter(producto => {
      const coincideCategoria = this.categoriaSeleccionada === 'todos' || 
                              producto.categoria_id === this.categoriaSeleccionada;
      const coincideBusqueda = producto.descripcion.toLowerCase().includes(this.terminoBusqueda);
      return coincideCategoria && coincideBusqueda;
    });
  }

  renderizarCatalogo() {
    const productosFiltrados = this.productosFiltrados;
    const inicio = (this.paginaActual - 1) * this.registrosPorPagina;
    const fin = inicio + this.registrosPorPagina;
    const productosActuales = productosFiltrados.slice(inicio, fin);
    
    const contenedor = $('#catalogoProductos');
    contenedor.empty();

    productosActuales.forEach(producto => {
      const stock = parseInt(producto.stock, 10) || 0;
      const inventariable = Number(producto.inventariable) === 1;
      const estaDisponible = stock > 0;
      const estaAgregado = this.productosAgregados.has(producto.id);
      const puedeAgregar = !estaAgregado && estaDisponible;
      
      const btnClass = puedeAgregar
        ? 'btn-agregar'
        : (estaDisponible ? 'btn-agregar btn-agregar-en-venta disabled' : 'btn-agregar btn-agregar-agotado disabled');

      let stockClass = estaDisponible ? 'badge bg-green' : 'badge bg-red';
      let etiquetaStock;
      if (inventariable) {
        etiquetaStock = estaDisponible ? `Disponible ${stock}` : 'Agotado';
      } else {
        etiquetaStock = estaDisponible ? 'Disponible' : 'Agotado';
      }

      const menuHtml = !inventariable
        ? `<div class="dropdown dropdown-disponibilidad">
              <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-menu-disponibilidad"
                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                      title="Opciones">
                <i class="fa fa-ellipsis-v"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-right">
                <li class="${estaDisponible ? 'disabled' : ''}">
                  <a href="#" class="menu-disponibilidad-item" data-id="${producto.id}" data-disponible="1">
                    <i class="fa fa-check-circle text-green"></i> Marcar como disponible
                  </a>
                </li>
                <li class="${!estaDisponible ? 'disabled' : ''}">
                  <a href="#" class="menu-disponibilidad-item" data-id="${producto.id}" data-disponible="0">
                    <i class="fa fa-times-circle text-red"></i> Marcar como agotado
                  </a>
                </li>
              </ul>
           </div>`
        : '';

      contenedor.append(`<div class="col-producto-catalogo">
        <div class="thumbnail">
          <div class="first">
            <div class="card-producto-header">
              <span class="${stockClass}">${etiquetaStock}</span>
              ${menuHtml}
            </div>
          </div>
          <div class="card-producto-img">
            <img src="${producto.imagen}"
                 alt="${producto.descripcion}"
                 class="thumbnail-image"
                 onerror="this.src='vistas/img/productos/default/d.webp'">
          </div>
          <div class="caption">
            <div class="card-producto-meta">
              <span class="dress-name" title="${producto.descripcion}">${producto.descripcion}</span>
              <span class="new-price">Bs ${producto.precio_venta || '0.00'}</span>
            </div>
            <button class="btn btn-default btn-sm btn-block ${btnClass}"
               href="javascript:void(0)"
               role="button"
               ${puedeAgregar ? '' : 'disabled'}
               idProducto="${producto.id}">
               <i class="fa fa-plus"></i> ${puedeAgregar ? 'Agregar' : (estaDisponible ? 'En venta' : 'Agotado')}
            </button>
          </div>
        </div>
      </div>`);
    });

    this.actualizarPaginacion(productosFiltrados.length);
  }

  actualizarPaginacion(totalProductos) {
    const totalPaginas = Math.ceil(totalProductos / this.registrosPorPagina);
    const inicio = (this.paginaActual - 1) * this.registrosPorPagina + 1;
    const fin = Math.min(inicio + this.registrosPorPagina - 1, totalProductos);

    // Actualizar estructura de paginación
    const paginacionContainer = $('.catalogo-paginacion');
    paginacionContainer.empty();

    // Agregar controles de paginación
    paginacionContainer.append(`
      <div class="paginacion-controles-wrapper">
        <div class="registros-por-pagina">
          <span>Mostrar</span>
          <select id="registrosPorPagina">
            <option value="20" ${this.registrosPorPagina === 20 ? 'selected' : ''}>20</option>
            <option value="40" ${this.registrosPorPagina === 40 ? 'selected' : ''}>40</option>
            <option value="60" ${this.registrosPorPagina === 60 ? 'selected' : ''}>60</option>
          </select>
          <span>registros</span>
        </div>
        <div class="paginacion-controles">
          <button id="btnAnterior" ${this.paginaActual === 1 ? 'disabled' : ''}>Anterior</button>
          <div class="paginacion-paginas">
            ${this.generarBotonesPaginas(totalPaginas)}
          </div>
          <button id="btnSiguiente" ${this.paginaActual === totalPaginas ? 'disabled' : ''}>Siguiente</button>
        </div>
      </div>
      <div class="paginacion-info">
        Mostrando registros del ${inicio} al ${fin} de un total de ${totalProductos}
      </div>
    `);

    // Reinicializar eventos después de actualizar la estructura
    this.inicializarEventosPaginacion();
  }

  generarBotonesPaginas(totalPaginas) {
    let html = '';
    for (let i = 1; i <= totalPaginas; i++) {
      html += `
        <button class="btn-pagina ${i === this.paginaActual ? 'active' : ''}"
                onclick="catalogoProductos.irAPagina(${i})">
          ${i}
        </button>
      `;
    }
    return html;
  }

  inicializarEventosPaginacion() {
    $('#registrosPorPagina').on('change', (e) => {
      this.registrosPorPagina = parseInt(e.target.value);
      this.paginaActual = 1;
      this.renderizarCatalogo();
    });

    $('#btnAnterior').on('click', () => {
      if (this.paginaActual > 1) {
        this.paginaActual--;
        this.renderizarCatalogo();
      }
    });

    $('#btnSiguiente').on('click', () => {
      const totalPaginas = Math.ceil(this.productosFiltrados.length / this.registrosPorPagina);
      if (this.paginaActual < totalPaginas) {
        this.paginaActual++;
        this.renderizarCatalogo();
      }
    });
  }

  irAPagina(pagina) {
    this.paginaActual = pagina;
    this.renderizarCatalogo();
  }
}

// Inicializar el catálogo cuando el documento esté listo
let catalogoProductos;
$(document).ready(() => {
  catalogoProductos = new CatalogoProductos();
}); 