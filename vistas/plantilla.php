<?php

session_start();
// Obtener la fecha actual en PHP en formato YYYY-MM-DD
$fechaActual = date("Y-m-d");
?>

 
  <?php
  // Verificar si la ruta es "login" para mostrar la página de inicio de sesión
  if(isset($_GET["ruta"]) && $_GET["ruta"] == "login") {
    // Incluir solo lo necesario para la página de login
    include "vistas/plantilla_header.php";
    include "vistas/modulos/login.php";
    include "vistas/plantilla_footer.php";
    exit();
  }

  if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok"){

   include "vistas/plantilla_header.php";
   echo '<div class="wrapper">';

    /*=============================================
    CABEZOTE
    =============================================*/

    include "modulos/cabezote.php";

    /*=============================================
    MENU
    =============================================*/

    include "modulos/menu.php";

    /*=============================================
    CONTENIDO
    =============================================*/

    if(isset($_GET["ruta"])){

      if($_GET["ruta"] == "inicio" ||
         $_GET["ruta"] == "login" ||
         $_GET["ruta"] == "usuarios" ||
         $_GET["ruta"] == "agregar-usuario" ||
         $_GET["ruta"] == "arqueo-de-caja" ||
         $_GET["ruta"] == "categorias" ||
         $_GET["ruta"] == "agregar-usuario" ||
         $_GET["ruta"] == "agregar-categoria" ||
         $_GET["ruta"] == "agregar-producto" ||
         $_GET["ruta"] == "agregar-cliente" ||
         $_GET["ruta"] == "agregar-mesero" ||
         $_GET["ruta"] == "agregar-gasto" ||
         $_GET["ruta"] == "agregar-proveedor" ||
         $_GET["ruta"] == "editar-categoria" ||
         $_GET["ruta"] == "productos" ||
         $_GET["ruta"] == "productos-eliminados" ||
         $_GET["ruta"] == "usuarios-eliminados" ||
         $_GET["ruta"] == "categorias-eliminados" ||
         $_GET["ruta"] == "clientes-eliminados" ||
         $_GET["ruta"] == "meseros-eliminados" ||
         $_GET["ruta"] == "proveedor-eliminados" ||
         $_GET["ruta"] == "clientes" ||
         $_GET["ruta"] == "meseros" ||
         $_GET["ruta"] == "ventas" ||
         $_GET["ruta"] == "ventas-eliminadas" ||
         $_GET["ruta"] == "crear-venta" ||
         $_GET["ruta"] == "editar-venta" ||
         $_GET["ruta"] == "compras" ||
         $_GET["ruta"] == "gastos" ||
         $_GET["ruta"] == "compras-eliminadas" ||
         $_GET["ruta"] == "crear-compra" ||
         $_GET["ruta"] == "reportes" ||
         $_GET["ruta"] == "reporte-compra" ||
         $_GET["ruta"] == "reporte-venta" ||
         $_GET["ruta"] == "reporte-top-meseros-ventas" ||
         $_GET["ruta"] == "reporte-top-productos" ||
         $_GET["ruta"] == "reporte-categoria" ||
         $_GET["ruta"] == "ver-productos-faltantes" ||
         $_GET["ruta"] == "ganancias-ventas" ||
         $_GET["ruta"] == "proveedor" ||
         $_GET["ruta"] == "salir"){

        include "modulos/".$_GET["ruta"].".php";

      }else{

        include "modulos/404.php";

      }

    }else{

      include "modulos/inicio.php";

    }

    /*=============================================
    FOOTER
    =============================================*/

    include "modulos/footer.php";

    echo '</div>';
    include "vistas/plantilla_footer.php";
  }else{
    header("Location: pagina/");
    exit();
  }

  ?>

