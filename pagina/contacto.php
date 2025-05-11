<?php
// Incluye el archivo de conexión desde la carpeta modelos
require_once "../modelos/conexion.php";

// Obtenemos la conexión usando la clase Conexion de la carpeta modelos y el archivo conexion.php //
$conexion = Conexion::conectar();
//fin de la conexión

// Consulta para obtener categorías
$sqlCategorias = "SELECT id, categoria AS nombre FROM categorias";
$stmtCategorias = $conexion->prepare($sqlCategorias);
$stmtCategorias->execute();
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contacto</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logomini.png">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/templatemo.css">
    <link rel="stylesheet" href="assets/css/custom.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">

    <!-- Load map styles -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
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

            <a class=" pollos-rossy-titulo navbar-brand text-success logo h1 align-self-center" href="index.php">
                POLLOS ROSSY
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
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
                        <li class="nav-item"><a class="nav-link" href="http://localhost/A/pos_php_nativo/">Login</a></li>
                    </ul>
                </div>
                <div class="navbar align-self-center d-flex">
                    <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputMobileSearch" placeholder="Search ...">
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
    <div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="w-100 pt-1 mb-5 text-right">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="get" class="modal-content modal-body border-0 p-0">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="inputModalSearch" name="q" placeholder="Search ...">
                    <button type="submit" class="input-group-text bg-success text-light">
                        <i class="fa fa-fw fa-search text-white"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>


<!-- Start Content Page -->
<div class="container-fluid bg-light py-5">
    <div class="col-md-6 m-auto text-center">
        <h1 class="h1">"Conoce nuestra ubicación y visítanos cuando gustes."</h1>
        <p>
        Estamos aquí para ofrecerte los mejores platos de pollo. Si tienes alguna pregunta o deseas más información sobre nuestros menús, promociones o servicios, no dudes en ponerte en contacto con nosotros..
        </p>
    </div>
</div>

<!-- Start Map Section -->
<div id="mapid" style="width: 100%; height: 400px; margin-bottom: 20px;"></div>

<!-- Leaflet CSS and JavaScript for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Configuración del mapa con Leaflet
        var mymap = L.map('mapid').setView([-17.874676, -63.190912], 15);

        // Agregar capa de mapa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: 'Ferretería Ferrojmaxs | Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
        }).addTo(mymap);

        // Añadir marcador con un popup informativo
        L.marker([-17.84306871790899, -63.19665746463213]).addTo(mymap)
            .bindPopup("<b>Pollos Rossy</b><br>Tu lugar de confianza para disfrutar el mejor pollo a la brasa y broaster.")
            .openPopup();

        // Deshabilitar el zoom con la rueda del ratón
        mymap.scrollWheelZoom.disable();
    });
</script>

<!-- Start Contact -->
<div class="container py-5">
    <div class="row py-5">
        <div class="col-md-9 m-auto">
            <h2 class="text-center mb-4">Envíanos tus Preguntas  Sugerencias  y Reclamos</h2>
            <form id="formContacto" role="form">
                <div class="row">
                    <div class="form-group col-md-6 mb-3">
                        <label for="name">Nombre (opcional)</label>
                        <input type="text" class="form-control mt-1" id="name" name="name" placeholder="Tu Nombre" >
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="subject">Asunto</label>
                        <input type="text" class="form-control mt-1" id="subject" name="subject" placeholder="Asunto" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="message">Mensaje</label>
                    <textarea class="form-control mt-1" id="message" name="message" placeholder="Escribe tu mensaje aquí" rows="8" required></textarea>
                </div>
                <div class="row">
                    <div class="col text-end mt-2">
                        <button type="submit" class="btn btn-success btn-lg px-3">
                            <i class="fab fa-whatsapp"></i> Enviar
                        </button>
                    </div>
                </div>
            </form>
            <script>
                document.getElementById('formContacto').addEventListener('submit', function(e) {
                    e.preventDefault();
                    var nombre = document.getElementById('name').value.trim();
                    var asunto = document.getElementById('subject').value.trim();
                    var mensaje = document.getElementById('message').value.trim();
                    var texto = `Hola, soy ${nombre}.%0AAsunto: ${asunto}%0AMensaje: ${mensaje}`;
                    var url = `https://wa.me/59175620296?text=${texto}`;
                    window.open(url, '_blank');
                });
            </script>
        </div>
    </div>
</div>
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