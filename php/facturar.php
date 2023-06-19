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

// Variable para almacenar los detalles de la factura
$facturaDetalles = array();

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener la fecha del formulario
    $fecha = $_POST['fecha'];

    // Llamar al procedimiento almacenado GenerateFactura con la fecha como parámetro
    $callQuery = "CALL GenerateFactura('$fecha')";
    $callResult = $db->query($callQuery);

    // Verificar si se obtuvieron resultados
    if ($callResult) {
        // Obtener los resultados de la consulta
        while ($row = $callResult->fetch_assoc()) {
            $facturaDetalle = new stdClass();
            $facturaDetalle->idFactura = $row['idFactura'];
            $facturaDetalle->fechaEmision = $row['fechaEmision'];
            $facturaDetalle->consumo = $row['consumo'];
            $facturaDetalle->totalPagar = $row['totalPagar'];
            $facturaDetalle->mediciones_periodo = $row['mediciones_periodo'];
            $facturaDetalle->cuenta_idCuenta = $row['cuenta_idCuenta'];
            $facturaDetalle->nombreCliente = $row['nombreCliente'];
            // Agregar el detalle de la factura al array
            $facturaDetalles[] = $facturaDetalle;
        }
        $callResult->close();
        $db->next_result();
    } else {
        echo "Error al llamar al procedimiento almacenado: " . $db->error;
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

        input[type="date"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .period-form {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Generar Factura</h1>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="period-form">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" required>
            <input type="submit" value="Generar Factura">
        </form>
        </div>
        <div class="container3">
        </div>
        <div class="container2">
        <?php if (!empty($facturaDetalles)) { ?>
            <h2>Detalles de Factura</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Factura</th>
                        <th>Fecha de Emisión</th>
                        <th>Consumo</th>
                        <th>Total a Pagar</th>
                        <th>Período de Mediciones</th>
                        <th>ID Cuenta</th>
                        <th>Nombre del Cliente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($facturaDetalles as $facturaDetalle) { ?>
                        <tr>
                            <td><?php echo $facturaDetalle->idFactura; ?></td>
                            <td><?php echo $facturaDetalle->fechaEmision; ?></td>
                            <td><?php echo $facturaDetalle->consumo; ?></td>
                            <td><?php echo $facturaDetalle->totalPagar; ?></td>
                            <td><?php echo $facturaDetalle->mediciones_periodo; ?></td>
                            <td><?php echo $facturaDetalle->cuenta_idCuenta; ?></td>
                            <td><?php echo $facturaDetalle->nombreCliente; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
        </div>
</body>
</html>
