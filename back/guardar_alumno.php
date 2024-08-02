<?php
require('conexion.php');

// Verificar conexión
if ($con === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Consultas para obtener las opciones de cada selección
$query_instituciones = "SELECT idInstitucion, Nombre FROM Instituciones";
$result_instituciones = sqlsrv_query($con, $query_instituciones);

$query_ciudades = "SELECT idCiudad, Nombre FROM Ciudades";
$result_ciudades = sqlsrv_query($con, $query_ciudades);

$query_grados = "SELECT idGradoAcademico, Nombre FROM GradoAcademico";
$result_grados = sqlsrv_query($con, $query_grados);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $genero = $_POST['genero'];
    $idInstitucion = $_POST['institucion'];
    $idCiudad = $_POST['ciudad'];
    $idGrado = $_POST['grado'];

    // Consulta para insertar el alumno en la base de datos
    $query_insert = "INSERT INTO Alumnos (idEstado, Nombres, Apellidos, Genero, idCiudad, idInstitucion, idGradoAcademico) 
                     VALUES (1, ?, ?, ?, ?, ?, ?)";
    $params = array($nombre, $apellido, $genero, $idCiudad, $idInstitucion, $idGrado);
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
    <title>Agregar Alumno</title>
    <link rel="stylesheet" href="../css/agregar.css">
</head>
<body>

    

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required><br>

        <label for="genero">Género:</label>
        <select id="genero" name="genero" required>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
        </select><br>

        <label for="institucion">Institución:</label>
        <select id="institucion" name="institucion" required>
            <?php while($row = sqlsrv_fetch_array($result_instituciones, SQLSRV_FETCH_ASSOC)) { ?>
                <option value="<?php echo $row['idInstitucion']; ?>"><?php echo $row['Nombre']; ?></option>
            <?php } ?>
        </select><br>

        <label for="ciudad">Ciudad:</label>
        <select id="ciudad" name="ciudad" required>
            <?php while($row = sqlsrv_fetch_array($result_ciudades, SQLSRV_FETCH_ASSOC)) { ?>
                <option value="<?php echo $row['idCiudad']; ?>"><?php echo $row['Nombre']; ?></option>
            <?php } ?>
        </select><br>

        <label for="grado">Grado:</label>
        <select id="grado" name="grado" required>
            <?php while($row = sqlsrv_fetch_array($result_grados, SQLSRV_FETCH_ASSOC)) { ?>
                <option value="<?php echo $row['idGradoAcademico']; ?>"><?php echo $row['Nombre']; ?></option>
            <?php } ?>
        </select><br>

        <button type="submit">Agregar Alumno</button>
    </form>

</body>
</html>
