<header class="main-header">
  
  <!-- LOGOTIPO -->
  <a href="inicio" class="logo">
    <!-- logo mini -->
    <span class="logo-mini">
      <img src="vistas/img/plantilla/Logo_POS.png" class="img-responsive" style="padding:2px">
    </span>
    <!-- logo normal -->
    <span class="logo-lg"><b>Pollos </b>Rossy</span>
  </a>

  <!-- BARRA DE NAVEGACIÓN -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Botón de navegación -->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <!-- PERFIL DE USUARIO -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Dropdown de usuario -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <?php
            if ($_SESSION["foto"] != "") {
              echo '<img src="' . $_SESSION["foto"] . '" class="user-image" alt="User Image">';
            } else {
              echo '<img src="vistas/img/usuarios/default/anonymous.png" class="user-image" alt="User Image">';
            }
            ?>
            <span class="hidden-xs"><?php echo $_SESSION["nombre"]; ?></span>
          </a>
          <!-- Dropdown-toggle -->
          <ul class="dropdown-menu">
            <!-- User image and info -->
            <li class="user-header">
              <img src="<?php echo $_SESSION['foto'] ? $_SESSION['foto'] : 'vistas/img/usuarios/default/anonymous.png'; ?>" class="img-circle" alt="User Image">
              <p>
                <?php echo $_SESSION["nombre"]; ?>
                <small><?php echo $_SESSION["perfil"]; ?></small>
              </p>
            </li>
            <!-- Opciones de usuario -->
            <li class="user-body">
              <!-- Botón de Volver -->
              <div class="pull-left">
                <a href="inicio" class="btn btn-default btn-flat">Volver</a>
              </div>
              <!-- Botón de Cerrar sesión -->
              <div class="pull-right">
                <a href="salir" class="btn btn-default btn-flat">Cerrar sesión</a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>

<!-- CSS para mejorar el diseño del perfil -->
<style>
  .user-image {
    border-radius: 50%;
    width: 30px;
    height: 30px;
  }

  .dropdown-menu > .user-header {
    background-color: #3c8dbc;
    color: #fff;
    padding: 10px;
    text-align: center;
  }

  .user-header img {
    border-radius: 50%;
    width: 60px;
    height: 60px;
  }

  .user-header p {
    margin-top: 10px;
    font-size: 14px;
  }

  .btn-default.btn-flat {
    padding: 5px 10px;
    font-size: 14px;
  }

  .pull-left {
    float: left !important;
  }

  .pull-right {
    float: right !important;
  }
</style>