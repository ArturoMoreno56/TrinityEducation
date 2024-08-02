<?php


session_start();


if (isset($_SESSION['nombre_usuario'])) {
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $IdUsuario = $_SESSION['IdUsuario'];
} else {

    header('Location: ../index.html');
    exit();
}
include ("../back/conexion.php");

if ($con === false) {
    die(print_r(sqlsrv_errors(), true));
}
  ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/Style_formularios.css">
    <title>Subir Archivo</title>
  
</head>

<body>
<header>
            <nav>
            <a href="inicio_docente.php">Inicio docente</a>
            <a href="../back/cerrar_sesion.php">Salir</a>
            </nav>

</header>

    <form action="../subir.php" method="post" enctype="multipart/form-data">
        <label for="nombre_archivo">Nombre del Archivo:</label>
        <input type="text" name="nombre_archivo" id="nombre_archivo"><br><br>
        <label for="archivo">Seleccione el Archivo:</label>
        <input type="file" name="archivo" id="archivo"><br><br>
        <input  type="submit" value="Subir Archivo" name="submit">
    </form>

   
</body>
</html>
