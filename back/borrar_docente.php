<?php

require('back/conexion.php');

// Verificar conexión
if ($con === false) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $idDocente = $_POST['idDocente'];


    $query_delete = "DELETE FROM Docentes WHERE idDocente = ?";
    $params = array($idDocente);
    $result_delete = sqlsrv_query($con, $query_delete, $params);

    if ($result_delete === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {

        header("Location: ../lista_docentes.php");
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
    <title>Borrar Docente</title>
    <link rel="stylesheet" href="../css/Style_main.css">
</head>
<body>
    <header> 

    </header>
    <h1>Borrar Docente</h1>

    <?php 
    $query = "SELECT * FROM Docentes";
    $resultado = sqlsrv_query($con, $query);
    while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) { ?>
        <form action="borrar_docente.php" method="post">
            <input type="hidden" id="idDocente" name="idDocente" value="<?php echo $row['idDocente']; ?>">
            <p><?php echo $row['Nombres'] . ' ' . $row['Apellidos']; ?></p>
            <button class='Boton_borrar_Docente' type="submit">Borrar Docente</button>
        </form>
    <?php } ?>
                
</body>
</html>
