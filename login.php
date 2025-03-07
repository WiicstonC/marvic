<?php
require_once 'vendor/autoload.php'; // Asegúrate de haber instalado HybridAuth con Composer
$config = require 'hybridauth_config.php';

use Hybridauth\Hybridauth;

if (!isset($_GET['provider'])) {
    die("Proveedor no especificado.");
}

$providerName = $_GET['provider'];

try {
    $hybridauth = new Hybridauth($config);
    $adapter = $hybridauth->authenticate($providerName);
    $userProfile = $adapter->getUserProfile();

    // Inicia la sesión y guarda los datos del usuario
    session_start();
    $_SESSION['user'] = [
        'name'        => $userProfile->displayName,
        'email'       => $userProfile->email,
        'provider'    => $providerName,
        'profile_img' => $userProfile->photoURL
    ];

    // Desconecta el adaptador
    $adapter->disconnect();

    // Redirige a la página principal
    header("Location: index.php");
    exit();

} catch (\Exception $e) {
    // En caso de error se muestra el mensaje (en producción se recomienda manejarlo de forma segura)
    echo "Error en la autenticación: " . $e->getMessage();
}
