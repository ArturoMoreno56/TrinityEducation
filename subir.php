<?php
session_start();

if (isset($_SESSION['nombre_usuario'])) {
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $IdUsuario = $_SESSION['IdUsuario'];
} else {
    header('Location: index.html');
    exit();
}

include ("back/conexion.php");

if ($con === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (isset($_POST['submit'])) {
    $nombre_archivo = $_POST['nombre_archivo'];
    $archivo_nombre = $_FILES['archivo']['name'];
    $archivo_tmp = $_FILES['archivo']['tmp_name'];
    $archivo_ubicacion = "archivos/" . $archivo_nombre;
    $IdUsuario = $_SESSION['IdUsuario'];
    echo $IdUsuario;
    $Conseguir_IdDocente = "SELECT idDocente FROM Docentes WHERE idUsuario = ?";
    $resultado = sqlsrv_query($con, $Conseguir_IdDocente, array($IdUsuario));
    if ($resultado === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
    if (!$fila) {
        die('Error: No se encontró el docente.');
    }
    $idDocente = $fila['idDocente'];

    $conseguir_idcurso = "SELECT idCurso FROM Relacional.RelacionCursoDocente WHERE idDocente = ?";
    $resultado = sqlsrv_query($con, $conseguir_idcurso, array($idDocente));
    if ($resultado === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
    if (!$fila) {
        die('Error: No se encontró el curso para el docente.');
    }
    $IdCurso = $fila['idCurso'];

    if (move_uploaded_file($archivo_tmp, $archivo_ubicacion)) {
        $sql = "INSERT INTO Archivos (nombreArchivo, DireccionArchivo, FechaCarga, idCurso) VALUES (?, ?, Getdate(), ?)";
        $params = array($nombre_archivo, $archivo_ubicacion, $IdCurso);
        $stmt = sqlsrv_query($con, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            echo "Archivo subido exitosamente.";
            header("Location: nav_docente/inicio_docente.php");
            exit();
        }
    } else {
        echo "Error al subir el archivo. Temp File: $archivo_tmp | Target Location: $archivo_ubicacion";
        die(print_r(error_get_last(), true));
    }
}

sqlsrv_close($con);
?>
