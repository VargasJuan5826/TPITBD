<?php
// Iniciar sesión
session_start();

// Verificar si el usuario no ha iniciado sesión
if (!isset($_SESSION['username'])) {
    // Si no ha iniciado sesión, redirigir al formulario de inicio de sesión
    header('Location: login.php');
    exit();
}

// Obtener los datos de conexión desde $_SESSION['db']
$dbData = $_SESSION['db'];

// Crear una nueva conexión utilizando los datos almacenados
$db = new mysqli($dbData['servername'], $dbData['username'], $dbData['password'], $dbData['database']);

// Verificar si hay errores de conexión
if ($db->connect_error) {
    die('Error al conectar a la base de datos: ' . $db->connect_error);
}

// Variable para almacenar el resultado de la consulta
$insertResult = null;

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el período a facturar del formulario
    $periodoAFacturar = $_POST['periodoAFacturar'];

    // Llamar al procedimiento almacenado GenerateFactura
    $generateFacturaQuery = "CALL GenerateFactura('$periodoAFacturar', @cont, @importeTotal)";
    $db->query($generateFacturaQuery);

    // Obtener el resultado de las variables de salida
    $resultQuery = $db->query("SELECT @cont, @importeTotal");
    $result = $resultQuery->fetch_assoc();
    $cont = $result['@cont'];
    $importeTotal = $result['@importeTotal'];

    // Verificar si se generaron facturas
    if ($cont > 0) {
        $insertResult = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Generar Factura</title>
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

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="date"] {
            width: 95%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout {
            padding: 10px;
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
    <h1>Generar Factura</h1>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="periodoAFacturar">Período a facturar:</label>
        <input type="date" id="periodoAFacturar" name="periodoAFacturar" required>

        <input type="submit" value="Generar Factura">
    </form>
    <?php if ($insertResult) { ?>
        <p>Se han generado <?php echo $cont; ?> facturas exitosamente.</p>
        <p>Importe total: $<?php echo $importeTotal; ?></p>
    <?php } ?>
    <div class="logout">
            <a href="menu.php">Volver</a>
    </div>
</div>
</body>
</html>
