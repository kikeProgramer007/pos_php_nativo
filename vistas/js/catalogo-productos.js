class CatalogoProductos {
  constructor() {
    this.productos = [];
    this.categorias = [];
    this.paginaActual = 1;
    this.registrosPorPagina = 12;
    this.categoriaSeleccionada = '2';
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
        
        // Extraer stock
        tempDiv.innerHTML = producto[4];
        const stock = parseInt(tempDiv.querySelector('button').textContent);
        
        // Extraer ID del producto
        tempDiv.innerHTML = producto[5];
        const idProducto = tempDiv.querySelector('button').getAttribute('idProducto');

        return {
          id: idProducto,
          imagen: imagenSrc,
          codigo: producto[2],
          descripcion: producto[3],
          stock: stock,
          precio_venta: producto[6],
          categoria_id: String(producto[7]) // Convertir a string para comparación consistente
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
    });

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
          agregarProductoAVenta(respuesta);
        }
      });
    });
  }

  renderizarFiltrosCategorias() {
    const contenedor = $('#filtrosCategorias');
    contenedor.empty();

    // Agregar botón "Todos"
    contenedor.append(`
      <button class="btn-categoria" data-categoria="todos">
        Todos
      </button>
    `);
 
    // Agregar botón para cada categoría
    this.categorias.forEach(categoria => {
      contenedor.append(`
        <button class="btn-categoria ${categoria.id === '2' ? 'active' : ''}" data-categoria="${categoria.id}">
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
      // Verificar si el producto está en la lista de agregados
      const estaAgregado = this.productosAgregados.has(producto.id);
      
      const btnClass = estaAgregado ? 'btn-agregar disabled' : 
                      (stock > 0 ? 'btn-agregar' : 'btn-agregar disabled');
      
      let stockClass;
      if (stock <= 10) {
          stockClass = 'pull-right badge bg-red';
      } else if (stock >= 11 && stock <= 15) {
          stockClass = 'pull-right badge bg-yellow';
      } else {
          stockClass = 'pull-right badge bg-green';
      }

      contenedor.append(`<div class="col-sm-3 col-md-3 col-lg-3" style="padding-left:0px">
        <div class="thumbnail" style="height: 100%;">
          <div class="first">
            <div class="d-flex justify-content-between"> 
              <span class="${stockClass}">CANTIDAD: ${producto.stock}</span> 
            </div>
          </div> 
          <div style="overflow: hidden;">
            <img src="${producto.imagen}" 
                 alt="${producto.descripcion}" 
                 class="thumbnail-image" 
                 style="object-fit: cover;"
                 onerror="this.src='vistas/img/productos/default/anonymous.png'">
          </div>
          <div class="caption" style=" display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div class="d-flex justify-content-between">
                <span class="dress-name" style="height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                  ${producto.descripcion}
                </span>
                <span class="new-price">Bs ${producto.precio_venta || '0.00'}</span> 
              </div>
            </div>
            <div style="margin-top: auto;">
              <button class="btn btn-success btn-sm w-100 ${btnClass}"  
                 href="javascript:void(0)" 
                 role="button" 
                 ${(stock > 0 && !estaAgregado) ? '' : 'disabled'} 
                 idProducto="${producto.id}">
                 <i class="fa fa-plus"></i> Agregar
              </button>
            </div>
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
            <option value="12" ${this.registrosPorPagina === 12 ? 'selected' : ''}>12</option>
            <option value="24" ${this.registrosPorPagina === 24 ? 'selected' : ''}>24</option>
            <option value="48" ${this.registrosPorPagina === 48 ? 'selected' : ''}>48</option>
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