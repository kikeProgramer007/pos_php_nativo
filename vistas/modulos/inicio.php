<div class="content-wrapper text-uppercase">

  <section class="content-header">
    <h1>
      Bienvenido
      <small>Panel de Control</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Tablero</li>
    </ol>
  </section>

  <section class="content">

    <?php if ($_SESSION["perfil"] != "Administrador") { ?>
      <div class="welcome-container text-center">
        <!-- Texto grande arriba a la izquierda -->
        <div class="role-text">
          <?php
          if ($_SESSION["perfil"] == "Vendedor") {
            echo '<span> Cajero/a</span>';
          } elseif ($_SESSION["perfil"] == "Supervisor") {
            echo '<span>Supervisor</span>';
          }
          ?>
        </div>

        <h2 class="welcome-message">¡Hola, <?php echo $_SESSION['nombre']; ?>!</h2>
        <p class="lead">Estamos encantados de tenerte aquí.</p>

        <!-- Quitamos la imagen y movemos el botón aquí -->
        <?php if ($_SESSION["perfil"] == "Supervisor") { ?>
          <a href="ventas">
            <button class="btn btn-success btn-lg">Comienza a Supervisar</button>
          </a>
        <?php } else { ?>
          <a href="crear-venta">
            <button class="btn btn-success btn-lg">Comienza a Vender</button>
          </a>
        <?php } ?>
      </div>
    <?php } ?>

    <div class="row">
      <?php if ($_SESSION["perfil"] == "Administrador") { include "inicio/cajas-superiores.php"; } ?>
    </div> 

    <div class="row">
      <div class="col-lg-12">
        <?php if ($_SESSION["perfil"] == "Administrador") { include "reportes/grafico-ventas.php"; } ?>
      </div>

      <div class="col-lg-6">
        <?php if ($_SESSION["perfil"] == "Administrador") { include "reportes/productos-mas-vendidos.php"; } ?>
      </div>

      <div class="col-lg-6">
        <?php if ($_SESSION["perfil"] == "Administrador") { include "inicio/productos-recientes.php"; } ?>
      </div>
    </div>
  </section>
</div>

<style>
  .content-wrapper {
    background: linear-gradient(135deg, #f8f9fe 0%, #ffffff 100%);
    min-height: 100vh;
  }

  .welcome-container {
    position: relative;
    padding: 40px;
    background: #ffffff;
    border-radius: 20px;
    margin: 30px auto;
    box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
    max-width: 1000px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    text-align: center;
  }

  .welcome-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
    background: linear-gradient(45deg, rgba(46, 204, 113, 0.03), rgba(52, 152, 219, 0.03));
    border-radius: 20px;
    z-index: 0;
  }

  .welcome-container > * {
    position: relative;
    z-index: 1;
  }

  .role-text {
    position: absolute;
    top: 25px;
    left: 35px;
    font-size: 2.8rem;
    background: linear-gradient(45deg, #2ecc71, #27ae60);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 800;
    letter-spacing: -1px;
    text-transform: uppercase;
    animation: fadeInLeft 0.8s ease-out;
  }

  .welcome-message {
    color: #2c3e50;
    font-size: 3.5rem;
    margin: 60px 0 20px;
    font-weight: 800;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    animation: fadeInUp 0.8s ease-out;
    background: linear-gradient(45deg, #2c3e50, #34495e);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .lead {
    color: #7f8c8d;
    font-size: 1.8rem;
    margin-bottom: 30px;
    font-weight: 400;
    animation: fadeInUp 1s ease-out;
    line-height: 1.6;
  }

  .btn {
    padding: 18px 45px;
    font-size: 1.3rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    margin-top: 20px;
    border-radius: 50px;
    animation: fadeInUp 1.2s ease-out;
  }

  .btn-success {
    background: linear-gradient(45deg, #2ecc71, #27ae60);
    border: none;
    box-shadow: 0 4px 15px rgba(46, 204, 113, 0.4);
    position: relative;
    overflow: hidden;
  }

  .btn-success:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 8px 25px rgba(46, 204, 113, 0.5);
    background: linear-gradient(45deg, #27ae60, #219a52);
  }

  .btn-success::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    transform: rotate(45deg);
    transition: all 0.8s;
  }

  .btn-success:hover::after {
    left: 100%;
  }

  .breadcrumb {
    background-color: transparent;
    padding: 20px 0;
    margin: 0;
    font-size: 1rem;
    color: #95a5a6;
  }

  .breadcrumb a {
    color: #3498db;
    transition: all 0.3s ease;
    text-decoration: none;
    font-weight: 500;
  }

  .breadcrumb a:hover {
    color: #2980b9;
    transform: translateX(3px);
  }

  .content-header h1 {
    font-size: 3rem;
    background: linear-gradient(45deg, #2c3e50, #3498db);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 800;
    margin-bottom: 15px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
  }

  .content-header small {
    font-size: 1.5rem;
    color: #7f8c8d;
    font-weight: 400;
  }

  .responsive-image {
    width: 100%;
    max-width: 500px;
    height: auto;
    display: block;
    margin: 40px auto;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    animation: fadeInUp 1.4s ease-out;
  }

  .responsive-image:hover {
    transform: scale(1.03) rotate(1deg);
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.15);
  }

  @keyframes fadeInLeft {
    from {
      opacity: 0;
      transform: translateX(-30px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @media (max-width: 768px) {
    .welcome-container {
      padding: 30px 20px;
      margin: 15px;
    }
    
    .role-text {
      position: relative;
      top: 0;
      left: 0;
      text-align: center;
      margin-bottom: 30px;
      font-size: 2.2rem;
    }

    .welcome-message {
      font-size: 2.5rem;
      margin-top: 40px;
    }

    .lead {
      font-size: 1.4rem;
      margin-bottom: 25px;
    }

    .responsive-image {
      max-width: 350px;
    }

    .btn {
      padding: 15px 35px;
      font-size: 1.2rem;
      width: 100%;
      max-width: 300px;
      margin: 20px auto;
    }

    .content-header h1 {
      font-size: 2.2rem;
      text-align: center;
    }

    .content-header small {
      font-size: 1.2rem;
      display: block;
      text-align: center;
    }
  }

  /* Efectos de iluminación ambiental */
  .welcome-container::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
    opacity: 0;
    transition: opacity 0.5s;
    pointer-events: none;
  }

  .welcome-container:hover::after {
    opacity: 1;
  }
</style>
