<?php
// Configuración para HybridAuth (ajusta las URLs y credenciales según tu entorno)
return [
    "callback"  => "http://tu-dominio.com/callback.php",
    "providers" => [
        "Google" => [
            "enabled" => true,
            "keys"    => [
                "id"     => "TU_GOOGLE_CLIENT_ID",
                "secret" => "TU_GOOGLE_CLIENT_SECRET"
            ],
            "scope"   => "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email"
        ],
        // Para Hotmail, usamos el proveedor Microsoft
        "Microsoft" => [
            "enabled" => true,
            "keys"    => [
                "id"     => "TU_MICROSOFT_CLIENT_ID",
                "secret" => "TU_MICROSOFT_CLIENT_SECRET"
            ],
            "scope"   => "User.Read"
        ],
        // Para iCloud se usa Apple; la configuración puede requerir pasos adicionales.
        "Apple" => [
            "enabled" => true,
            "keys"    => [
                "id"     => "TU_APPLE_CLIENT_ID",
                "secret" => "TU_APPLE_CLIENT_SECRET"  // Este valor se genera mediante JWT
            ],
            // Agrega otros parámetros que exija el proveedor Apple si fuera necesario.
        ]
    ]
];
