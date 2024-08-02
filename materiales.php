<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Maestro</title>
        <link rel="stylesheet" href="css/descargar.css">
        <STYle>
            header {
    display: flex;
    justify-content: flex-end;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: rgb(17, 34, 158);
    color: white;
    padding: 10px 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}
        </STYle>
</head>
<body>
    <header>
        <nav>
            <a href="nav_alumno/inicio_alumno.php">Inicio</a>
            <a href="back/cerrar_sesion.php">Salir</a>
        </nav>
    </header>
    
    <div class="tabla-container">
        <table class="tabla">
            <thead>
                <tr>
                    <th>Nombre del Archivo</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include ('back/conexion.php');

                session_start();
                if (isset($_SESSION['nombre_usuario'])) {
                    $nombre_usuario = $_SESSION['nombre_usuario'];
                } else {
                    header('Location: ../index.html');
                    exit();
                }

                $idUsuario = $_SESSION['IdUsuario'];

                $obtener_idcurso = "SELECT idCurso FROM Relacional.RelacionCursoAlumno INNER JOIN Alumnos 
                                    ON Alumnos.idAlumno = Relacional.RelacionCursoAlumno.idAlumno
                                    INNER JOIN Usuarios ON Usuarios.idUsuario = Alumnos.idUsuario
                                    WHERE Usuarios.idUsuario=?;";

                $resultado = sqlsrv_query($con, $obtener_idcurso, array($idUsuario));
                $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
                $IdCurso = $fila['idCurso'];

                $query = "SELECT Archivos.NombreArchivo, Archivos.FechaCarga, DireccionArchivo FROM Archivos INNER JOIN Relacional.RelacionCursoAlumno
                          ON Relacional.RelacionCursoAlumno.idCurso = Archivos.idCurso
                          INNER JOIN Alumnos ON Relacional.RelacionCursoAlumno.idAlumno = Alumnos.idAlumno
                          INNER JOIN Usuarios ON Usuarios.idUsuario = Alumnos.idUsuario
                          WHERE Archivos.idCurso=? and Usuarios.idUsuario = ?";

                $resultado = sqlsrv_query($con, $query, array($IdCurso, $idUsuario));
                if ($resultado === false) {
                    echo "<tr><td colspan='2'>Error: " . htmlspecialchars(print_r(sqlsrv_errors(), true)) . "</td></tr>";
                } else {
                    while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
                        $nombre_archivo = htmlspecialchars($row['NombreArchivo']);
                        $ubicacion_archivo = htmlspecialchars($row['DireccionArchivo']);
                        $descargable = $ubicacion_archivo ? "descargable" : "";
                        echo "<tr class='$descargable'>";
                        echo "<td>$nombre_archivo</td>";
                        echo "<td>";
                        if ($ubicacion_archivo) {
                            echo "<a href='$ubicacion_archivo' download>Descargar</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
