<?php
session_start();


if (isset($_SESSION['nombre_usuario'])) {
    $nombre_usuario = $_SESSION['nombre_usuario'];
} else {

    header('Location: ../index.html');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


     <select id="id_examen" name="id_examen">
            <?php while ($examen = sqlsrv_fetch_array($result_examenes, SQLSRV_FETCH_ASSOC)) { ?>
                <option value="<?php echo $examen['ID_Examen']; ?>"><?php echo $examen['Nombre_Examen']; ?></option>
            <?php } ?>
        </select><br>
</body>
</html>