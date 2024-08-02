<?php
session_start();
include ('conexion.php');



function obtener_clave_usuario($con, $usuario)
{
    $obtener_clave = 'SELECT Contrasenha AS CLAVE FROM Usuarios 
                      INNER JOIN Correos ON Correos.idUsuario= Usuarios.idUsuario
                      INNER JOIN Contrasenha ON Contrasenha.idContrasenha = Usuarios.idContrasenha
                      WHERE Correos.Correo=?;';
    $resultado_consulta = sqlsrv_query($con, $obtener_clave, array($usuario));
    if ($resultado_consulta === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $row = sqlsrv_fetch_array($resultado_consulta, SQLSRV_FETCH_ASSOC);
    return $row['CLAVE'] ?? null;
}

function obtener_id_usuario($con, $usuario)
{
    $obtener_id = 'SELECT Usuarios.idUsuario AS ID FROM Usuarios INNER JOIN Correos ON Usuarios.idUsuario = Correos.idUsuario
                   WHERE Correo=?';
    $resultado_consulta = sqlsrv_query($con, $obtener_id, array($usuario));
    if ($resultado_consulta === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $row = sqlsrv_fetch_array($resultado_consulta, SQLSRV_FETCH_ASSOC);
    return $row['ID'] ?? null;
}

function actualizar_login($con, $id_actualizar)
{
    $update_login = 'EXEC LOGINUPDATE ?';
    sqlsrv_query($con, $update_login, array($id_actualizar));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['nombre'];
    $clave_ingresada = $_POST['clave'];

    $id_actualizar = obtener_id_usuario($con, $usuario);
    if (!$id_actualizar) {
        header('Location:../index.html?error=usuario_no_encontrado');
        exit();
    }

    $consultar_existencia_alumno = 'SELECT count(*) as CANT FROM Correos INNER JOIN Alumnos ON Alumnos.idUsuario = Correos.idUsuario 
                                    WHERE Correo=?';
    $resultado_consulta = sqlsrv_query($con, $consultar_existencia_alumno, array($usuario));
    $row = sqlsrv_fetch_array($resultado_consulta, SQLSRV_FETCH_ASSOC);
    $existe_alumno = $row['CANT'];

    if ($existe_alumno == 1) {
        $clave_original = obtener_clave_usuario($con, $usuario);
        if (password_verify($clave_ingresada, $clave_original)) {
            $_SESSION['IdUsuario'] = $id_actualizar;
            actualizar_login($con, $id_actualizar);
            header('Location:../inicio_alumno.php');
            exit();
        } else {
            header('Location:../index.html?error=clave_erronea');
            exit();
        }
    }

    $consultar_existe_docente = "SELECT COUNT(*) AS CANT FROM Docentes 
                                 INNER JOIN Correos ON Correos.idUsuario = Docentes.idUsuario
                                 WHERE Correos.idUsuario = ?";

    $resultado_consulta = sqlsrv_query($con, $consultar_existe_docente, array($id_actualizar));
    $fila = sqlsrv_fetch_array($resultado_consulta, SQLSRV_FETCH_ASSOC);
    $existe_docente = $fila['CANT'];

    if ($existe_docente == 1) {
        $clave_original = obtener_clave_usuario($con, $usuario);
        if (password_verify($clave_ingresada, $clave_original)) {
            $_SESSION['IdUsuario'] = $id_actualizar;
            actualizar_login($con, $id_actualizar);
            header('Location:../inicio_docente.php');
            exit();
        } else {
            header('Location:../index.html?error=clave_erronea');
            exit();
        }
    }

    $consultar_existencia_admin = 'SELECT COUNT(*) AS CANT FROM Correos INNER JOIN Usuarios ON Usuarios.idUsuario = Correos.idUsuario
                                   WHERE CORREO=?';
    $resultado_consulta = sqlsrv_query($con, $consultar_existencia_admin, array($usuario));
    $row = sqlsrv_fetch_array($resultado_consulta, SQLSRV_FETCH_ASSOC);
    $existe_admin = $row['CANT'];

    if ($existe_admin == 1) {
        $clave_original = obtener_clave_usuario($con, $usuario);
        if (password_verify($clave_ingresada, $clave_original)) {
            $_SESSION['IdUsuario'] = $id_actualizar;
            actualizar_login($con, $id_actualizar);
            header('Location:../inicio_admin.php');
            exit();
        } else {
            header('Location:../index.html?error=clave_erronea');
            exit();
        }
    } else {
        header('Location:../index.html?error=usuario_no_encontrado');
        exit();
    }
}
?>
