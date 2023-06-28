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


// Consulta para obtener las cuentas con el nombre de usuario
$cuentasQuery = "CALL GetUsuarios()";
$cuentasResult = $db->query($cuentasQuery);

$cuentas = array();
while ($row = $cuentasResult->fetch_assoc()) {
    $cuenta = new stdClass();
    $cuenta->idCuenta = $row['idCuenta'];
    $cuenta->nombreUsuario = $row['nombreUsuario'];
    $cuentas[] = $cuenta;
}
$cuentasResult->close();
$db->next_result();

// Variable para almacenar el resultado de la consulta
$insertResult = null;

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores del formulario
    $periodo = $_POST['periodo'];
    $fechaLectura = $_POST['fechaLectura'];
    $lectura = $_POST['lectura'];
    $cuenta_id = $_POST['cuenta_id'];

    // Insertar la medición en la base de datos
    $insertQuery = "INSERT INTO Mediciones (periodo, fechaLectura, lectura, Cuenta_idCuenta) VALUES ('$periodo', '$fechaLectura', $lectura, $cuenta_id)";
    $insertResult = $db->query($insertQuery);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrar Medición</title>
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

        input[type="text"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        select {
            width: 100%;
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
    </style>
</head>
<body>
<div class="container">
    <h1>Registrar Medición</h1>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="periodo">Período:</label>
        <input type="date" id="periodo" name="periodo" required>

        <label for="fechaLectura">Fecha de Lectura:</label>
        <input type="date" id="fechaLectura" name="fechaLectura" required>

        <label for="lectura">Lectura:</label>
        <input type="number" id="lectura" name="lectura" required>

        <label for="cuenta_id">Cuenta:</label>
        <select id="cuenta_id" name="cuenta_id">
        <?php foreach ($cuentas as $cuenta) { ?>
            <option value="<?php echo $cuenta->idCuenta; ?>"><?php echo $cuenta->nombreUsuario; ?></option>
        <?php } ?>
        </select>

        <input type="submit" value="Registrar Medición">
    </form>
    <?php if ($insertResult) { ?>
        <p>La medición se ha guardado exitosamente.</p>
    <?php } ?>
</div>
</body>
</html>
