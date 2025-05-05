<?php
// Incluye el archivo de conexión
include 'conexion.php';

// Crea una instancia de la conexión
$conexion = Conexion::conectar();

// Consulta para obtener categorías
$sqlCategorias = "SELECT id, categoria FROM categorias";
$stmtCategorias = $conexion->prepare($sqlCategorias);
$stmtCategorias->execute();
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener productos filtrados según selección del usuario
$sql = "SELECT descripcion, precio_venta, stock, imagen FROM productos WHERE 1=1";

if (isset($_GET['categoria'])) {
    $sql .= " AND id_categoria = :categoria";
}
if (isset($_GET['buscar'])) {
    $sql .= " AND descripcion LIKE :buscar";
}

$stmt = $conexion->prepare($sql);

if (isset($_GET['categoria'])) {
    $stmt->bindParam(':categoria', $_GET['categoria'], PDO::PARAM_INT);
}
if (isset($_GET['buscar'])) {
    $buscarTermino = "%" . $_GET['buscar'] . "%";
    $stmt->bindParam(':buscar', $buscarTermino, PDO::PARAM_STR);
}

$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<head>
    <title>Catálogo - Pollos Rosyy</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logomini.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/templatemo.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
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
    <script>
        // Función para desplegar y ocultar los filtros
        function toggleFilter(filterId) {
            var filterElement = document.getElementById(filterId);
            filterElement.style.display = filterElement.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>

<body>


    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="pollos-rossy-titulo navbar-brand text-success logo h1 align-self-center" href="index.php">Pollos Rossy</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="align-self-center collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="nosotros.php">Nosotros</a></li>
                        <li class="nav-item"><a class="nav-link" href="catalogo.php">Catálogo</a></li>
                        <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- <div class="envios-gratis">
        <span class="texto">Envíos Gratis</span>
        <span class="condiciones">en Santa Cruz por compras mayores a Bs. 900</span>
        <div class="icono-camion">
            <img src="../vistas/img/camion-de-envio.png" alt="Camioncito de envío">
        </div>
    </div> -->
    
    <!-- Fin Header -->

    <!-- Start Content -->
    <div class="container py-5">
        <div class="row">
            <!-- Filtros de Categoría, Marca y Búsqueda -->
            <div class="col-lg-3">
                <h1 class="h2 pb-4">Buscar por</h1>
                <form method="GET" action="catalogo.php">
                    <!-- Categoría -->
                    <h4 style="cursor: pointer;">Categoría</h4>
                    <div id="filter-categorias" style="display: block;">
                        <?php foreach ($categorias as $categoria): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="categoria" value="<?= $categoria['id'] ?>" id="cat_<?= $categoria['id'] ?>">
                                <label class="form-check-label" for="cat_<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['categoria']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Buscar por Descripción -->
                    <h4>Buscar</h4>
                    <input type="text" name="buscar" class="form-control mb-3" placeholder="Buscar producto...">

                    <!-- Botón de Filtrar -->
                    <button type="submit" class="btn btn-primary mt-3">Aplicar</button>
                </form>
            </div>

            <!-- Catálogo de Productos -->
            <div class="col-lg-9">
                <h1 class="h2 pb-4">Catálogo de Productos</h1>
                <div class="row">
                    <?php if (count($productos) > 0): ?>
                        <?php foreach ($productos as $producto): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <img src="../<?= htmlspecialchars($producto['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($producto['descripcion']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($producto['descripcion']) ?></h5>
                                        <p><strong>Precio:</strong> <?= number_format($producto['precio_venta'], 2) ?> Bs</p>
                                        <p><strong>Stock:</strong> <?= htmlspecialchars($producto['stock']) ?></p>
                                        <p class="text-center">
                                            <a class="btn btn-success" href="https://wa.me/59175620296?text=Hola%20Quiero%20comprar%20este%20producto:%20<?=urlencode(htmlspecialchars($producto['descripcion']))?>%20Precio:%20<?=urlencode(number_format($producto['precio_venta'],2))?>%20Bs" target="_blank">
                                                <i class="fab fa-whatsapp"></i> Pedir
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay productos disponibles en el catálogo.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->

    <!-- Footer -->
    <footer class="bg-dark" id="tempaltemo_footer">
        <!-- Código del footer -->
    </footer>
    <!-- End Footer -->

    <!-- Scripts -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
<!----------------------------------------------------- FOOOTER------------------------------------>
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





</html>
