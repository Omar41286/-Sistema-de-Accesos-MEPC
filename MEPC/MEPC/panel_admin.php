<?php
include 'conexion_php_sqlS.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - MEPC</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom right, #0A192F, #3A4968);
        }
        .card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .btn-admin {
            background-color: #055d40;
            color: white;
            transition: background-color 0.3s;
        }
        .btn-admin:hover {
            background-color: #044830;
        }
    </style>
</head>
<body class="min-h-screen p-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-8 flex justify-between items-center">
            <img
                alt="MEPC Logo"
                class="h-16 w-auto"
                src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/MEPC-p8blHEpOjVDEGO1e1ZPx1nae2RpJq6.png"
            />
            <a
                class="inline-flex h-10 items-center justify-center rounded-md bg-red-600 px-8 text-sm font-medium text-white transition-colors hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2"
                href="logout.php"
            >
                Cerrar Sesión
            </a>
        </div>
        <div class="rounded-lg bg-white/95 p-8 shadow-xl">
            <h1 class="mb-8 text-center text-3xl font-bold text-white">Panel de Administración</h1>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <?php
                $menuItems = [
                    [
                        "icon" => "📞",
                        "title" => "Gestionar Usuarios",
                        "description" => "Administra los usuarios del sistema",
                        "href" => "gestionar_usuarios.php"
                    ],
                    [
                        "icon" => "📋",
                        "title" => "Gestionar Productos",
                        "description" => "Administra el catálogo de productos",
                        "href" => "gestionar_productos.php"
                    ],
                    [
                        "icon" => "👥",
                        "title" => "Lista de Usuarios",
                        "description" => "Consulta todos los usuarios registrados",
                        "href" => "listar_usuarios.php"
                    ],
                    [
                        "icon" => "📦",
                        "title" => "Lista de Productos",
                        "description" => "Consulta todos los productos disponibles",
                        "href" => "listar_productos.php"
                    ],
                    [
                        "icon" => "🏢",
                        "title" => "Inventario de Almacenes",
                        "description" => "Gestiona el inventario de los almacenes",
                        "href" => "inventario_admin.php"
                    ],
                    [
                        "icon" => "🔑",
                        "title" => "Ver Accesos",
                        "description" => "Consulta los registros de acceso",
                        "href" => "accesos.php"
                    ]
                ];

                foreach ($menuItems as $item) {
                    echo '<div class="card">';
                    echo '<div class="p-6">';
                    echo '<div class="mb-2 text-2xl">' . $item["icon"] . '</div>';
                    echo '<h2 class="mb-2 text-xl font-bold">' . $item["title"] . '</h2>';
                    echo '<p class="mb-4 text-sm text-gray-600">' . $item["description"] . '</p>';
                    echo '<a class="btn-admin inline-flex h-9 w-full items-center justify-center rounded-md px-4 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-[#055d40] focus:ring-offset-2" href="' . $item["href"] . '">Acceder</a>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>