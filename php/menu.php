<?php
// Iniciar sesión
session_start();

// Verificar si el usuario no ha iniciado sesión
if (!isset($_SESSION['username'])) {
    // Si no ha iniciado sesión, redirigir al inicio de sesión
    header('Location: login.php');
    exit();
}

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Menú</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .button {
            display: block;
            width: 95%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            margin-bottom: 10px;
        }

        .button:hover {
            background-color: #45a049;
        }

        .logout {
            text-align: center;
            font-weight: bold;
        }

        .logout a {
            color: #000;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Menú</h1>
        <a href="medicion.php" class="button">Realizar Medición</a>
        <a href="facturar.php" class="button">Facturar</a>
        <div class="logout">
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
</body>
</html>