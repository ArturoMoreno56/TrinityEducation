<?php

require('conexion.php');

// Verificar conexión
if ($con === false) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir ID del alumno a borrar
    $idAlumno = $_POST['idAlumno'];

    // Consulta para borrar el alumno de la base de datos
    $query_delete = "DELETE FROM Alumnos WHERE idAlumno = ?";
    $params = array($idAlumno);
    $result_delete = sqlsrv_query($con, $query_delete, $params);

    if ($result_delete === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        // Redireccionar si la eliminación fue exitosa
        header("Location: ../mis_alumnos.php");
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
    <title>Borrar Alumno</title>
    <link rel="stylesheet" href="../css/Style_main.css">
</head>
<body>
    <header> 

    </header>
    <h1>Borrar Alumno</h1>

    <?php 
    $query = "SELECT * FROM Alumnos";
    $resultado = sqlsrv_query($con, $query);
    while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) { ?>
        <form action="borrar.php" method="post">
            <input type="hidden" id="idAlumno" name="idAlumno" value="<?php echo $row['idAlumno']; ?>">
            <p><?php echo $row['Nombres'] . ' ' . $row['Apellidos']; ?></p>
            <button class='Boton_borrar_Alumno' type="submit">Borrar Alumno</button>
        </form>
    <?php } ?>
                
</body>
</html>
