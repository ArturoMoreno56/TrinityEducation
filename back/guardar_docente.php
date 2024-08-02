<?php
require('conexion.php');

// Verificar conexión
if ($con === false) {
    die(print_r(sqlsrv_errors(), true));
}

// nose las tablas xddddddddddddddddddd, para obtener estos campos
$query_usuarios = "SELECT idUsuario, Nombre FROM Usuarios";
$result_usuarios = sqlsrv_query($con, $query_usuarios);

$query_estados = "SELECT idEstado, Nombre FROM Estados";
$result_estados = sqlsrv_query($con, $query_estados);

//

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $idUsuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $genero = $_POST['genero'];
    $idEstado = $_POST['estado'];

    // Consulta para insertar el docente en la base de datos
    $query_insert = "INSERT INTO Docentes (idUsuario, Nombres, Apellidos, Genero, idEstado) 
                     VALUES (?, ?, ?, ?, ?)";
    $params = array($idUsuario, $nombre, $apellido, $genero, $idEstado);
    $result_insert = sqlsrv_query($con, $query_insert, $params);

    if ($result_insert === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        // Redireccionar si la inserción fue exitosa
        header("Location: ../inicio_admin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Docente</title>
    <link rel="stylesheet" href="../css/agregar.css">
</head>
<body>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="usuario">Usuario:</label>
        <select id="usuario" name="usuario" required>
            <?php while($row = sqlsrv_fetch_array($result_usuarios, SQLSRV_FETCH_ASSOC)) { ?>
                <option value="<?php echo $row['idUsuario']; ?>"><?php echo $row['NombreUsuario']; ?></option>
            <?php } ?>
        </select><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required><br>

        <label for="genero">Género:</label>
        <select id="genero" name="genero" required>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
        </select><br>

        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
            <?php while($row = sqlsrv_fetch_array($result_estados, SQLSRV_FETCH_ASSOC)) { ?>
                <option value="<?php echo $row['idEstado']; ?>"><?php echo $row['Nombre']; ?></option>
            <?php } ?>
        </select><br>

        <button type="submit">Agregar Docente</button>
    </form>

</body>
</html>
