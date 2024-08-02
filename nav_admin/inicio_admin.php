<?php
session_start();


if (isset($_SESSION['nombre_usuario'])) {
    $nombre_usuario = $_SESSION['nombre_usuario'];
} else {

    header('Location: index.html');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/nav.css">
    <title>Página Admin</title>
</head>
<body>
    <header>
        <nav>

            <a href="mis_alumnos.php">Mis alumnos</a>
             <a href="lista_alumnos.php">Lista alumnos</a>
            <a href="lista_docentes.php">Lista Docentes</a>
            <a href="../configuracion.php">Configuración</a>
            <a href="../back/cerrar_sesion.php">Salir</a>
            <h1>Bienvenido <?php echo $nombre_usuario; ?></h1>
           
        </nav>
    </header>
</body>
</html>
