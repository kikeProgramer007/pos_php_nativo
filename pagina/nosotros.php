<?php
// Incluye el archivo de conexión
include 'conexion.php';

// Crea una instancia de la conexión
$conexion = Conexion::conectar();

// Consulta para obtener categorías
$sqlCategorias = "SELECT id, categoria AS nombre FROM categorias";
$stmtCategorias = $conexion->prepare($sqlCategorias);
$stmtCategorias->execute();
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Nosotros</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="apple-touch-icon" href="assets/img/apple-icon.png" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logomini.png">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/templatemo.css" />
    <link rel="stylesheet" href="assets/css/custom.css" />

    <!-- Load fonts style after rendering the layout styles -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap"
    />
    <link rel="stylesheet" href="assets/css/fontawesome.min.css" />
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
            color: #ff6600;
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
        /* Degradado naranja a rojo más oscuro y combinado */
        .bg-naranja-rojo {
            background: linear-gradient(90deg, #ff9800 0%, #ff7043 50%, #c62828 100%) !important;
        }
        .text-naranja-rojo {
            background: linear-gradient(90deg, #ff9800 0%, #ff7043 50%, #c62828 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }
        .icono-naranja-rojo {
            color: #ff9800;
            background: linear-gradient(90deg, #ff9800 0%, #ff7043 50%, #c62828 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        .pollos-rossy-titulo {
            font-family: 'Bangers', Impact, Arial, sans-serif;
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
    </style>
  </head>

  <body>
   

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
      <div class="container d-flex justify-content-between align-items-center">
      <a class="pollos-rossy-titulo navbar-brand text-success logo h1 align-self-center" href="index.php">
                Pollos ROSSY
            </a>

        <button
          class="navbar-toggler border-0"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#templatemo_main_nav"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div
          class="align-self-center collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-between"
          id="templatemo_main_nav"
        >
          <div class="flex-fill">
            <ul
              class="nav navbar-nav d-flex justify-content-between mx-lg-auto"
            >
              <li class="nav-item">
                <a class="nav-link" href="index.php">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="nosotros.php">Nosotros</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="catalogo.php">Menú</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="contacto.php">Contacto</a>
              </li>
            </ul>
          </div>
          
          <div class="navbar align-self-center d-flex">
            <div
              class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3"
            >
              <div class="input-group">
                <input
                  type="text"
                  class="form-control"
                  id="inputMobileSearch"
                  placeholder="Search ..."
                />
                <div class="input-group-text">
                  <i class="fa fa-fw fa-search"></i>
                </div>
              </div>
            </div>
           
          
            
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
            <h5>Pedidos por WhatsApp</h5>
            <a href="https://wa.me/59178069757" target="_blank" class="text-decoration-none text-dark">
                <i class="fab fa-whatsapp fa-lg" style="color: green;"></i> +591 75620296
            </a>
        </div>
    </div>
</div>
    <!-- Modal -->
    <div
      class="modal fade bg-white"
      id="templatemo_search"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="w-100 pt-1 mb-5 text-right">
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <form
          action=""
          method="get"
          class="modal-content modal-body border-0 p-0"
        >
          <div class="input-group mb-2">
            <input
              type="text"
              class="form-control"
              id="inputModalSearch"
              name="q"
              placeholder="Search ..."
            />
            <button
              type="submit"
              class="input-group-text bg-success text-light"
            >
              <i class="fa fa-fw fa-search text-white"></i>
            </button>
          </div>
        </form>
      </div>
    </div>

    <section class="">
      <div class="container">
        <div class="row align-items-center py-5">
          <div class="col-md-8" style="color: #111;">
            <h1>Sobre nosotros</h1>
            <p>
            En Pollos Rossy, nos especializamos en ofrecer el mejor sabor en pollos a la brasa y broaster, preparados con ingredientes frescos y recetas caseras que conquistan cada paladar. Nuestro compromiso es brindar una experiencia deliciosa en cada plato, combinando calidad, buena atención y un ambiente acogedor para disfrutar en familia o con amigos. Con años de trayectoria, nos hemos ganado la confianza de nuestros clientes en Santa Cruz de la Sierra, convirtiéndonos en una opción preferida para quienes buscan sabor auténtico y atención cordial. ¡Vení a visitarnos y descubrí por qué Pollos Rossy es sinónimo de sabor!
            </p>
          </div>
          <div class="col-md-4">
            <img src="assets/img/nosotros.jpg" class="img-fluid" alt="Sobre Nosotros Pollos Rossy">
          </div>
        </div>
      </div>
    </section>
    <!-- Close Banner -->

    <section class="container py-5">
    <div class="row text-center pt-5 pb-3">
        <div class="col-lg-6 m-auto">
            <h1 class="h1">Nuestros Servicios</h1>
            <p>
            En Pollos Rossy, te ofrecemos una experiencia sabrosa con nuestros pollos a la brasa y broaster, acompañados de guarniciones como papas fritas, arroz  fideo. Podés disfrutar tu comida en nuestro local o pedirla para llevar. Además, contamos con una amplia variedad de sodas, jugos frios y naturales para complementar tu almuerzo o cena. 
            </p>
        </div>
    </div>
    <div class="row">
        <!-- Servicio de Entrega -->
        <div class="col-md-6 col-lg-3 pb-5">
            <div class="h-100 py-5 services-icon-wap shadow">
                <div class="h1 text-center icono-naranja-rojo">
                    <i class="fa fa-truck fa-lg"></i>
                </div>
                <h2 class="h5 mt-4 text-center">Entrega a Domicilio</h2>
                <p class="text-center px-3">Nuestro Delivery Lleva tus productos directamente a tu hogar o lugar de trabajo para mayor comodidad y rapidez 
               <a href="https://wa.me/59175620296" target="_blank"> (75620296)</a>.
              </p>

            </div>
        </div>

        <!-- Asesoramiento Personalizado -->
        <div class="col-md-6 col-lg-3 pb-5">
            <div class="h-100 py-5 services-icon-wap shadow">
                <div class="h1 text-center icono-naranja-rojo">
                    <i class="fa fa-shield-alt"></i>
                </div>
                <h2 class="h5 mt-4 text-center">Calidad Garantizada</h2>
                <p class="text-center px-3">Trabajamos con ingredientes frescos y procesos higiénicos para ofrecerte comida deliciosa y segura cada día.</p>
            </div>
        </div>

        <!-- Garantía de Productos -->
        <div class="col-md-6 col-lg-3 pb-5">
            <div class="h-100 py-5 services-icon-wap shadow">
                <div class="h1 text-center icono-naranja-rojo">
                <i class="fa fa-money-bill-wave"></i>


                </div>
                <h2 class="h5 mt-4 text-center">Precios Accesibles</h2>
                <p class="text-center px-3">Ofrecemos precios justos, pensados para que disfrutes sin preocuparte por el bolsillo.</p>
            </div>
        </div>

        <!-- Ambiente Familiar -->
        <div class="col-md-6 col-lg-3 pb-5">
            <div class="h-100 py-5 services-icon-wap shadow">
                <div class="h1 text-center icono-naranja-rojo">
                <i class="fa fa-smile"></i>
                </div>
                <h2 class="h5 mt-4 text-center">Ambiente Familiar</h2>
                <p class="text-center px-3">Contamos con un espacio cómodo y acogedor para que disfrutes tu comida en familia o con amigos.</p>
            </div>
        </div>
    </div>
</section>

    <!-- Proveedores -->

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
                                <?= htmlspecialchars($categoria['nombre']) ?>
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
    <!-- End Script -->
  </body>
</html>
