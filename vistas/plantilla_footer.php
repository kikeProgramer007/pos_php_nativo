<?php
if (!function_exists("assetUrl")) {
  require_once __DIR__ . "/../includes/assets.php";
}
?>
<!-- vinculamos  LAS CARPETAS -->
<script src="<?php echo assetUrl('vistas/js/cantidad-stepper.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/presentaciones-venta.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/producto-presentaciones.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/plantilla.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/usuarios.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/categorias.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/productos.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/clientes.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/meseros.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/mesas.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/compras.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/reportes.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/proveedor.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/ventas.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/mesas-venta.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/promociones.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/gastos.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/otros-ingresos.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/perfiles.js'); ?>"></script>
<!-- ------------------------- -->
<!-- vinculamos  LAS CARPETAS de la tablas dinamicas -->

<script src="<?php echo assetUrl('vistas/js/tabladinamica/usuariotabla.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/tabladinamica/categoriatabla.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/tabladinamica/clientetabla.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/tabladinamica/meserotabla.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/tabladinamica/proveedortabla.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/tabladinamica/comprastabla.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/tabladinamica/ventastabla.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/tabladinamica/gastostabla.js'); ?>"></script>
<script src="<?php echo assetUrl('vistas/js/tabladinamica/otrosingresostabla.js'); ?>"></script>
</body>
</html>
