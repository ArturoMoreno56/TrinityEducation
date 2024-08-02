
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
    <title>Página Maestro</title>
    <link rel="stylesheet" href="../css/tablas.css">
</head>
<body>
    <header>
        <nav>
            <a href="inicio_admin.php">Inicio</a>
            <a href="../back/cerrar_sesion.php">Salir</a>
        </nav>
    </header>
    
    <div class="tabla-container">
        <table class="tabla">
            <thead>
                <tr>
                    <th>idDocente</th>
                    <th>idUsuario</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Genero</th>
                    <th>idEstado</th>
                    <th>FechaIngreso</th>
                    <th>UltimaModificacion</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                include('../back/conexion.php');
                $query = "SELECT * FROM Docentes";
                $resultado = sqlsrv_query($con, $query);
                while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo $row['idDocente']; ?></td>
                        <td><?php echo $row['idUsuario']; ?></td>
                        <td><?php echo $row['Nombres']; ?></td>
                        <td><?php echo $row['Apellidos']; ?></td>
                        <td><?php echo $row['Genero']; ?></td>
                        <td><?php echo $row['idEstado']; ?></td>
                        <td><?php echo $row['FechaIngreso']->format('Y-m-d H:i:s'); ?></td>
                        <td><?php echo $row['UltimaModificacion']->format('Y-m-d H:i:s'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="button-container">
        <button class="eliminar" onclick="location.href='back/borrar_docente.php'">Borrar Docente</button>
        <button class="agregar" onclick="location.href='back/guardar_docente.php'">Agregar</button>
    </div>
</body>
</html>
