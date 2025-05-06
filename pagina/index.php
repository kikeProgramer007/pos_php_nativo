<?php
// Incluye el archivo de conexión
include 'conexion.php';

// Crea una instancia de la conexión
$conexion = Conexion::conectar();

// Obtener todas las categorías
$sqlCategorias = "SELECT id, categoria FROM categorias";
$stmtCategorias = $conexion->prepare($sqlCategorias);
$stmtCategorias->execute();
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

// Para cada categoría, obtener un producto (el más reciente)
$productosDestacados = [];
foreach ($categorias as $cat) {
    if ($cat['id'] == 1 || $cat['id'] == 2) {
        $sqlProd = "SELECT descripcion, precio_venta, imagen, stock FROM productos WHERE id_categoria = :id_categoria AND descripcion LIKE :like_text AND estado=1 ORDER BY id DESC LIMIT 1";
        $stmtProd = $conexion->prepare($sqlProd);
        $likeText = '%eco pecho%';
        $stmtProd->bindParam(':id_categoria', $cat['id'], PDO::PARAM_INT);
        $stmtProd->bindParam(':like_text', $likeText, PDO::PARAM_STR);
    } else {
        $sqlProd = "SELECT descripcion, precio_venta, imagen, stock FROM productos WHERE id_categoria = :id_categoria AND estado=1 ORDER BY id DESC LIMIT 1";
        $stmtProd = $conexion->prepare($sqlProd);
        $stmtProd->bindParam(':id_categoria', $cat['id'], PDO::PARAM_INT);
    }
    $stmtProd->execute();
    $producto = $stmtProd->fetch(PDO::FETCH_ASSOC);
    if ($producto) {
        $producto['categoria'] = $cat['categoria'];
        $productosDestacados[] = $producto;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Pollos Rossy</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logomini.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/templatemo.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
    <!-- Google Fonts Modernas -->
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animate On Scroll -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <!-- CSS Moderno personalizado -->
    <link rel="stylesheet" href="assets/css/modern.css">
    <style>
        .envios-gratis {
            background-color: #f8f9fa;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            font-size: 18px;
            color: #333;
        }
        .envios-gratis .texto {
            font-weight: bold;
            color: #007bff;
        }
        .envios-gratis .condiciones {
            color: #555;
            margin-left: 5px;
        }
        .envios-gratis .icono-camion {
            margin-left: 15px;
        }
        .envios-gratis img {
            height: 30px;
        }
        .pollos-rossy-titulo {
            font-family: Impact, Arial, sans-serif !important;
            font-style: normal !important;
            font-size: 48px;
            color: #fff200 !important;
            text-shadow:
                4px 4px 0 #000,
                8px 8px 15px #ff3300;
            letter-spacing: 2px;
            font-weight: bold;
            padding: 5px 0;
            display: inline-block;
        }
        .mover-logo {
            margin-left: 0;
            display: inline-block;
        }
        @media (min-width: 992px) { /* lg en Bootstrap */
            .mover-logo {
                margin-left: 80px;
            }
        }
        .btn-primary.btn-lg {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255,204,51,0.4);}
            70% { box-shadow: 0 0 0 10px rgba(255,204,51,0);}
            100% { box-shadow: 0 0 0 0 rgba(255,204,51,0);}
        }
        .btn-primary.btn-lg:hover {
            animation: none;
            transform: scale(1.07);
        }
    </style>
</head>

<body>
   
  <!-- Barra Superior -->
  <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="templatemo_nav_top">
        <div class="container text-light">
            <div class="w-10 d-flex justify-content-between">
                <div>
                   
                </div>
                <div>
                  
                </div>
            </div>
        </div>
    </nav>
   <!-- Header -->
   <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="pollos-rossy-titulo navbar-brand text-success logo h1 align-self-center" href="index.php">POLLOS ROSSY</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="align-self-center collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="nosotros.php">Nosotros</a></li>
                        <li class="nav-item"><a class="nav-link" href="catalogo.php">Menú</a></li>
                        <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
                        <li class="nav-item"><a class="nav-link" href="http://localhost/A/pos_php_nativo/">Login</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
  <!-- Opciones de Venta -->
  <div class="container my-3">
    <div class="row text-center">
        <div class="col-md-6">
            <h5>Venta Telefónica</h5>
            <a href="tel:+59178069757" class="text-decoration-none text-dark">
                <i class="fa fa-phone fa-lg"></i> +591 75620296
            </a>
        </div>
        <div class="col-md-6">
            <h5>Venta por WhatsApp</h5>
            <a href="https://wa.me/59178069757" target="_blank" class="text-decoration-none text-dark">
                <i class="fab fa-whatsapp fa-lg" style="color: green;"></i> +591 75620296
            </a>
        </div>
    </div>
</div>

<!-- Banner Principal -->
<div id="template-mo-zay-hero-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <ol class="carousel-indicators">
        <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="1"></li>
        <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="container">
                <div class="row p-5">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                        <img class="img-fluid" src="./assets/img/banner_img_01.jpg" alt="">
                    </div>
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left align-self-center">
                        <h1 class="h1 text-black"><b>¡Bienvenido!</b></h1>
                  
                            <p>El sabor que buscas está aquí. Ven y disfruta de nuestros deliciosos pollos brasa y broaster, preparados al momento para ti.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Segundo Slide -->
        <div class="carousel-item">
            <div class="container">
                <div class="row p-5">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                    <img class="img-fluid" src="./assets/img/banner_img_03.jpg" alt="">
                   
                    </div>
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left">
                            <h1 class="h1">Calidad y Variedad</h1>
                            <p>Ofrecemos pollo brasa y broaster con recetas únicas y los mejores ingredientes, para que disfrutes cada bocado.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tercer Slide -->
        <div class="carousel-item">
            <div class="container">
                <div class="row p-5">
                    <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                    <img class="img-fluid" src="./assets/img/banner_img_02.jpg" alt="">
                    </div>
                    <div class="col-lg-6 mb-0 d-flex align-items-center">
                        <div class="text-align-left">
                        <h1 class="h1">El Sabor que Mereces</h1>
                          <p>Disfrutá de nuestro delicioso pollo con atención rápida y cordial. ¡Porque tu satisfacción es nuestra prioridad!</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev text-decoration-none w-auto ps-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="prev">
        <i class="fas fa-chevron-left"></i>
    </a>
    <a class="carousel-control-next text-decoration-none w-auto pe-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="next">
        <i class="fas fa-chevron-right"></i>
    </a>
</div>
<!-- Fin Banner Principal -->


    <!-- Categorías del Mes -->
    <section class="container py-5">
        <div class="row text-center py-3">
            <div class="col-lg-6 m-auto mb-4">
                <h1 class="h1">Conoce algunos de nuestros productos</h1>
                <p>Explora nuestras deliciosas opciones de pollos a la brasa y broaster, preparadas con los mejores ingredientes para satisfacer tu apetito.</p>
                <hr class="separador-productos">
            </div>
        </div>
        <div class="row">
            <?php foreach ($productosDestacados as $producto): ?>
                <div class="col-12 col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100">
                        <img src="../<?= htmlspecialchars($producto['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($producto['descripcion']) ?>">
                        <div class="card-body">
                            <ul class="list-unstyled d-flex justify-content-between mb-2 align-items-center">
                                <li>
                                    <i class="text-warning fa fa-star"></i>
                                    <i class="text-warning fa fa-star"></i>
                                    <i class="text-warning fa fa-star"></i>
                                    <i class="text-warning fa fa-star"></i>
                                    <i class="text-warning fa fa-star"></i>
                                </li>
                            </ul>
                            <span class="badge bg-primary mb-2"><?= htmlspecialchars($producto['categoria']) ?></span>
                            <h5 class="card-title"><?= htmlspecialchars($producto['descripcion']) ?></h5>
                            <p><strong>Cantidad:</strong> <?= isset($producto['stock']) ? htmlspecialchars($producto['stock']) : 'N/D' ?></p>
                            <span class="precio-destacado"><?= number_format($producto['precio_venta'], 2) ?> Bs</span>
                            <p class="text-center">
                                <a class="btn btn-success" href="https://wa.me/59175620296?text=Hola%20Quiero%20comprar%20este%20producto:%20<?=urlencode(htmlspecialchars($producto['descripcion']))?>%20Precio:%20<?=urlencode(number_format($producto['precio_venta'],2))?>%20Bs" target="_blank">
                                    <i class="fab fa-whatsapp"></i> Pedir
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row">
            <div class="col-12 text-center mt-4">
                <a href="catalogo.php" class="btn btn-primary btn-lg">Explorar más</a>
            </div>
        </div>
    </section>
    <!-- Fin Categorías del Mes -->

    <!-- Proveedores -->
    <!-- Start Brands  PROOOOVEEEDORES-->
    <section class="bg-light py-5">
      <div class="container my-4">
        <div class="row text-center py-3">
          <div class="col-lg-6 m-auto">
          <h1 class="h1">Nuestros Proveedores</h1>
          <p>Contamos con proveedores de confianza que nos brindan los mejores ingredientes, garantizando la calidad y frescura de nuestros productos.</p>

          </div>
          <div class="col-lg-9 m-auto tempaltemo-carousel">
            <div class="row d-flex flex-row">
              <!--Controls-->
              <div class="col-1 align-self-center">
                <a
                  class="h1"
                  href="#multi-item-example"
                  role="button"
                  data-bs-slide="prev"
                >
                  <i class="text-light fas fa-chevron-left"></i>
                </a>
              </div>
              <!--End Controls-->

              <!--Carousel Wrapper-->
              <div class="col">
                <div
                  class="carousel slide carousel-multi-item pt-2 pt-md-0"
                  id="multi-item-example"
                  data-bs-ride="carousel"
                >
                  <!--Slides-->
                  <div class="carousel-inner product-links-wap" role="listbox">
                    <!--First slide-->
                    <div class="carousel-item active">
                      <div class="row">
                        <div class="col-3 p-md-5">
                          <a href="#"
                            ><img
                              class="img-fluid brand-img"
                              src="assets/img/cocacola.png"
                              alt="Brand Logo"
                          /></a>
                        </div>
                        <div class="col-3 p-md-5">
                          <a href="#"
                            ><img
                              class="img-fluid brand-img"
                              src="assets/img/kris.png"
                              alt="Brand Logo"
                          /></a>
                        </div>
                        <div class="col-3 p-md-5">
                          <a href="#"
                            ><img
                              class="img-fluid brand-img"
                              src="assets/img/servilletaperlita.png"
                              alt="Brand Logo"
                          /></a>
                        </div>
                        <div class="col-3 p-md-5">
                          <a href="#"
                            ><img
                              class="img-fluid brand-img"
                              src="assets/img/sofia.png"
                              alt="Brand Logo"
                          /></a>
                        </div>
                      </div>
                    </div>
                    <div class="carousel-item">
                      <div class="row">
                        <div class="col-3 p-md-5">
                          <a href="#"
                            ><img
                              class="img-fluid brand-img"
                              src="assets/img/sprite.png"
                              alt="Brand Logo"
                          /></a>
                        </div>
                        <div class="col-3 p-md-5">
                          <a href="#"
                            ><img
                              class="img-fluid brand-img"
                              src="assets/img/pepsi.jpg"
                              alt="Brand Logo"
                          /></a>
                        </div>
                        <div class="col-3 p-md-5">
                          <a href="#"
                            ><img
                              class="img-fluid brand-img"
                              src="assets/img/mendocina.jpeg"
                              alt="Brand Logo"
                          /></a>
                        </div>
                        <div class="col-3 p-md-5">
                          <a href="#"
                            ><img
                              class="img-fluid brand-img"
                              src="assets/img/fanta.jpg"
                              alt="Brand Logo"
                          /></a>
                        </div>
                      </div>
                    </div>
                  
                  </div>
       
                </div>
              </div>
       

              <!--Controls-->
              <div class="col-1 align-self-center">
                <a
                  class="h1"
                  href="#multi-item-example"
                  role="button"
                  data-bs-slide="next"
                >
                  <i class="text-light fas fa-chevron-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
<div id="particles-js"></div>

<!----------------------------------------------------- FOOOTER------------------------------------>
<!-- Footer -->
<footer class="bg-dark text-light" id="tempaltemo_footer">
    <div class="container py-5">
        <div class="row">
            <!-- Información de Contacto -->
            <div class="col-md-3">
                <h2 class="h2 text-success border-bottom pb-3 border-light logo">Pollos Rossy</h2>
                <ul class="list-unstyled">
                    <li>
                        <i class="fas fa-map-marker-alt fa-fw"></i>
                        Av, Santos Dumont,Refineria, Santa Cruz de la Sierra, Bolivia
                    </li>
                    <li>
                        <i class="fa fa-phone fa-fw"></i>
                        <a class="text-decoration-none text-light" href="tel:+59178025898">+591 75620296</a>
                    </li>
                    
                    <li>
                        <i class="fab fa-whatsapp fa-fw" style="color: green;"></i>
                        <a class="text-decoration-none text-light" href="https://wa.me/75620296" target="_blank">WhatsApp: +591 75620296</a>
                    </li>
                </ul>
            </div>

            <!-- Categorías -->
            <div class="col-md-4 text-center">
                <h2 class="h2 text-light border-bottom pb-3 border-light">Categorías</h2>
                <ul class="list-unstyled">
                    <?php foreach ($categorias as $categoria): ?>
                        <li>
                            <a class="text-decoration-none text-light" href="catalogo.php?categoria=<?= $categoria['id'] ?>">
                                <?= htmlspecialchars($categoria['categoria']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        

            <!-- Ubicación en Google Maps -->
            <div class="col-md-5">
                <h2 class="h2 text-light border-bottom pb-3 border-light">¡Visítanos!</h2>
                <p class="text-light">Ubicados en la Zona sur Av.Santos Dumont  Refineria, Santa Cruz de la Sierra, Bolivia.</p>
                <div class="embed-responsive embed-responsive-16by9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d388.4883143541366!2d-63.191764318838985!3d-17.874329108173338!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93f1eb002d18730b%3A0x6cb9e0ef8428c685!2sPollos%20Rossy!5e1!3m2!1ses!2sbo!4v1746467158436!5m2!1ses!2sbo" 
                        width="100%" 
                        height="200" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- Redes Sociales y Suscripción -->
        <div class="row text-light mb-4">
            <div class="col-12 mb-3">
                <div class="w-100 my-3 border-top border-light"></div>
            </div>
            <div class="col-auto me-auto">
                <ul class="list-inline footer-icons">
                    <li class="list-inline-item border border-light rounded-circle text-center">
                        <a class="text-light text-decoration-none" href="http://facebook.com/" target="_blank"><i class="fab fa-facebook-f fa-lg fa-fw"></i></a>
                    </li>
                    <li class="list-inline-item border border-light rounded-circle text-center">
                        <a class="text-light text-decoration-none" href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram fa-lg fa-fw"></i></a>
                    </li>
                    <li class="list-inline-item border border-light rounded-circle text-center">
                        <a class="text-light text-decoration-none" href="https://twitter.com/" target="_blank"><i class="fab fa-twitter fa-lg fa-fw"></i></a>
                    </li>
                    <li class="list-inline-item border border-light rounded-circle text-center">
                        <a class="text-light text-decoration-none" href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin fa-lg fa-fw"></i></a>
                    </li>
                </ul>
            </div>
           
        </div>
    </div>

    <div class="w-100 bg-black py-3">
        <div class="container">
            <div class="row pt-2">
                <div class="col-12 text-center">
                    <p class="text-light">
                        &copy; 2025 Pollos Rossy 
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>


    <!-- Start Script -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/particles.min.js"></script>
<script>
    particlesJS.load('particles-js', 'assets/js/particles-config.json', function() {
        console.log('Configuración de partículas cargada');
    });
</script>

    <!-- End Script -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
      AOS.init();
    </script>
  </body>
</html>
