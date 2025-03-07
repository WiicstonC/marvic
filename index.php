<?php
session_start();

// Lógica simulada para el inicio de sesión mediante proveedores universales
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $provider = $_POST['provider'] ?? '';
    if (in_array($provider, ['Google', 'Hotmail', 'iCloud'])) {
        // Simulación de datos obtenidos desde el proveedor
        if ($provider == 'Google') {
            $userData = [
                'name'        => 'Juan Perez',
                'email'       => 'juan.perez@gmail.com',
                'provider'    => 'Google',
                'profile_img' => 'imagenes/google-profile.png'
            ];
        } elseif ($provider == 'Hotmail') {
            $userData = [
                'name'        => 'Maria Lopez',
                'email'       => 'maria.lopez@hotmail.com',
                'provider'    => 'Hotmail',
                'profile_img' => 'imagenes/hotmail-profile.png'
            ];
        } elseif ($provider == 'iCloud') {
            $userData = [
                'name'        => 'Carlos Ruiz',
                'email'       => 'carlos.ruiz@icloud.com',
                'provider'    => 'iCloud',
                'profile_img' => 'imagenes/icloud-profile.png'
            ];
        }
        $_SESSION['user'] = $userData;
        header("Location: index.php");
        exit();
    }
}

// Parámetros para paginación de productos
$totalProducts = 40; // Asumimos 20 productos disponibles
$limit = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) { $page = 1; }
$start = ($page - 1) * $limit;
$end = min($start + $limit, $totalProducts);

// Obtener parámetro de tracking para envío
$tracking = isset($_GET['tracking']) ? trim($_GET['tracking']) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mostrario de Impresión 3D - Marvic3D</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome para iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Archivo CSS externo -->
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Barra de navegación estilo Dynamic Island -->
  <nav class="navbar island-navbar">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <a class="navbar-brand" href="#">Marvic3D</a>
      <ul class="nav">
        <li class="nav-item"><a class="nav-link" href="#creaciones">CREACIONES</a></li>
        <li class="nav-item"><a class="nav-link" href="#quienes-somos">QUIENES SOMOS</a></li>
        <li class="nav-item"><a class="nav-link" href="#marvic3d">MARVIC3D</a></li>
        <li class="nav-item"><a class="nav-link" href="#estado-envio">ESTADO DE ENVIO</a></li>
      </ul>
            <!-- Menú desplegable para inicio de sesión / perfil -->
            <div>
        <?php if(isset($_SESSION['user'])): ?>
          <!-- Usuario autenticado: mostramos imagen de perfil y menú con detalles -->
          <div class="dropdown">
            <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="<?= htmlspecialchars($_SESSION['user']['profile_img']) ?>" alt="Perfil" class="profile-icon">
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownUser">
              <li class="dropdown-item-text">
                <strong><?= htmlspecialchars($_SESSION['user']['name']) ?></strong><br>
                <small><?= htmlspecialchars($_SESSION['user']['email']) ?></small>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Perfil</a></li>
              <li><a class="dropdown-item" href="#">Carrito de Compras</a></li>
              <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
            </ul>
          </div>
        <?php else: ?>
          <!-- Usuario no autenticado: mostramos imagen de "inicio de sesión" -->
          <div class="dropdown">
            <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownLogin" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="imagenes/login-icon.png" alt="Iniciar Sesión" class="login-icon">
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownLogin">
              <li>
                <form method="post" style="margin: 0;">
                  <input type="hidden" name="login" value="1">
                  <button type="submit" name="provider" value="Google" class="dropdown-item">
                    <i class="fab fa-google"></i> Google
                  </button>
                </form>
              </li>
              <li>
                <form method="post" style="margin: 0;">
                  <input type="hidden" name="login" value="1">
                  <button type="submit" name="provider" value="Hotmail" class="dropdown-item">
                    <i class="fas fa-envelope"></i> Hotmail
                  </button>
                </form>
              </li>
              <li>
                <form method="post" style="margin: 0;">
                  <input type="hidden" name="login" value="1">
                  <button type="submit" name="provider" value="iCloud" class="dropdown-item">
                    <i class="fab fa-apple"></i> iCloud
                  </button>
                </form>
              </li>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <!-- Sección CREACIONES -->
  <section id="creaciones" class="container section-container mt-4">
    <h2 class="mb-4">Nuestras Creaciones</h2>
    <!-- Filtros -->
    <div class="filters">
      <button class="btn btn-primary btn-filter" data-filter="nuevo">
        <i class="fas fa-star"></i> NUEVO
      </button>
      <button class="btn btn-secondary btn-filter" data-filter="mas-vendido">
        LO MÁS VENDIDO
      </button>
      <button class="btn btn-secondary btn-filter" data-filter="hogar">
        Hogar
      </button>
      <button class="btn btn-secondary btn-filter" data-filter="anime">
        Anime
      </button>
      <input type="text" class="form-control d-inline-block" placeholder="Agregar filtro..." style="width:200px;">
    </div>
    <!-- Cuadrícula de productos (4 columnas x 3 filas) -->
    <div class="product-grid">
      <?php 
      // Listado de productos desde la carpeta "productos" (nombres 01.jpg, 02.jpg, etc.)
      for ($i = $start + 1; $i <= $end; $i++):
        $imageNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
      ?>
        <div class="product-card">
          <img src="productos/<?= $imageNumber ?>.png" class="img-fluid" alt="Producto <?= $i ?>">
          <h5 class="mt-2">Producto <?= $i ?></h5>
          <p>Tipo: <?= ($i % 2 == 0) ? "Estético" : "Funcional"; ?></p>
          <p>Medidas: <?= rand(5,20) ?>cm x <?= rand(5,20) ?>cm x <?= rand(5,20) ?>cm</p>
          <p>Costo: $<?= number_format(rand(100, 500), 2) ?></p>
        </div>
      <?php endfor; ?>
    </div>
    <!-- Paginación: flechas para navegar entre páginas -->
    <div class="d-flex justify-content-between mt-3">
      <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="btn btn-outline-light"><i class="fas fa-arrow-left"></i> Anterior</a>
      <?php else: ?>
        <span></span>
      <?php endif; ?>
      <?php if ($end < $totalProducts): ?>
        <a href="?page=<?= $page + 1 ?>" class="btn btn-outline-light">Siguiente <i class="fas fa-arrow-right"></i></a>
      <?php endif; ?>
    </div>
  </section>

  <!-- Sección QUIENES SOMOS -->
  <section id="quienes-somos" class="container section-container mt-5">
    <h2>Quienes Somos</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="card text-dark">
          <img src="recursos/mision.png" class="card-img-top" alt="Misión">
          <div class="card-body">
            <h5 class="card-title">Misión</h5>
            <p class="card-text">Nuestra misión es innovar en el mundo de la impresión 3D, ofreciendo productos de alta calidad y soluciones personalizadas.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-dark">
          <img src="recursos/vision.png" class="card-img-top" alt="Misión">
          <div class="card-body">
            <h5 class="card-title">Visión</h5>
            <p class="card-text">Nuestra visión es ser líderes en tecnología de impresión 3D, transformando ideas en realidades tangibles.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-dark">
          <img src="recursos/valores.png" class="card-img-top" alt="Misión">
          <div class="card-body">
            <h5 class="card-title">Valores</h5>
            <p class="card-text">Nuestro ideal es fomentar la creatividad e innovación, ofreciendo soluciones que marquen la diferencia en el mercado.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Sección MARVIC3D -->
  <section id="marvic3d" class="container section-container mt-5">
    <h2>Marvic3D</h2>
    <!-- Carousel con imágenes de la tecnología utilizada -->
    <div id="techCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="recursos/bambua1.jpg" class="d-block w-100" alt="Tecnología 1">
        </div>
        <div class="carousel-item">
          <img src="recursos/PLA.png" class="d-block w-100" alt="Tecnología 2">
        </div>
        <div class="carousel-item">
          <img src="recursos/PETG.png" class="d-block w-100" alt="Tecnología 3">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#techCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#techCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
      </button>
    </div>
  </section>

  <!-- Sección ESTADO DE ENVIO -->
  <section id="estado-envio" class="container section-container mt-5 mb-5">
    <h2>Estado de Envío</h2>
    <form method="get" action="#estado-envio" class="mb-3">
      <div class="input-group">
        <input type="text" name="tracking" class="form-control" placeholder="Ingresa el # de envío">
        <button class="btn btn-outline-light" type="submit"><i class="fas fa-search"></i> Buscar</button>
      </div>
    </form>
    <?php if ($tracking !== ''): ?>
      <div class="alert alert-info" role="alert">
        Resultado para el envío: <strong><?= htmlspecialchars($tracking) ?></strong>
        <!-- Aquí se integraría la lógica de búsqueda real -->
      </div>
    <?php endif; ?>
  </section>

  <!-- Footer -->
  <footer class="footer bg-dark text-light py-4">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h5>Contacto</h5>
          <p>Dirección: Calle 90B Sur # 50 - 12 la madrid, Villavicencio, Colombia</p>
          <p>Teléfono: +57 315 735 5604</p>
        </div>
        <div class="col-md-4">
          <h5>Redes Sociales</h5>
          <p>
            <a href="#" class="text-light me-2"><i class="fab fa-whatsapp"></i> WhatsApp</a>
            <a href="#" class="text-light me-2"><i class="fab fa-instagram"></i> Instagram</a>
            <a href="#" class="text-light me-2"><i class="fab fa-tiktok"></i> TikTok</a>
            <a href="#" class="text-light"><i class="fas fa-envelope"></i> correo@empresa.com</a>
          </p>
        </div>
        <div class="col-md-4">
          <h5>Información</h5>
          <p>© <?= date("Y") ?> Marvic3D. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS y archivo JS externo -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="script.js"></script>
</body>
</html>
