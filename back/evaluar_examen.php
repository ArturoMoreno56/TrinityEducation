<?php
require('conexion.php');

$id_alumno = '';
$id_examen = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    session_start();

    if(isset($_SESSION['IdUsuario'])){
        $idUsuario = $_SESSION['IdUsuario'];
    }

    $combertir_usuario_A_alumno='SELECT idAlumno FROM Usuarios INNER JOIN Alumnos
    ON Usuarios.idUsuario=Alumnos.idUsuario
    WHERE Usuarios.idUsuario='.$idUsuario;
    $respuesta=sqlsrv_query($con,$combertir_usuario_A_alumno);
    $row=sqlsrv_fetch_array($respuesta,SQLSRV_FETCH_ASSOC);
    $id_alumno=$row['idAlumno'];

    $id_examen = $_POST['id_examen'];

    $query_preguntas = "SELECT ID_Pregunta, Texto_Pregunta FROM Preguntas WHERE ID_Examen = ?";
        $params_preguntas = array($id_examen);
        $result_preguntas = sqlsrv_query($con, $query_preguntas, $params_preguntas);

    while ($pregunta = sqlsrv_fetch_array($result_preguntas, SQLSRV_FETCH_ASSOC)) {
        $id_pregunta = $pregunta['ID_Pregunta'];
        $respuesta_alumno = isset($_POST['respuesta_' . $id_pregunta]) ? $_POST['respuesta_' . $id_pregunta] : null;

        if ($respuesta_alumno !== null) {
            $query_insert_respuesta = "INSERT INTO Respuestas (ID_Alumno, ID_Examen, ID_Pregunta, ID_Opcion) VALUES (?, ?, ?, ?)";
                $params_respuesta = array($id_alumno, $id_examen, $id_pregunta, $respuesta_alumno);
                $result_insert_respuesta = sqlsrv_query($con, $query_insert_respuesta, $params_respuesta);

            if ($result_insert_respuesta === false) {
                die(print_r(sqlsrv_errors(), true));
            }
        }
    }

    $actualizar_Examen='EXEC CORREGIR ?,?;';
    $resultado_actualizar=sqlsrv_query($con,$actualizar_Examen,array($id_alumno,$id_examen));

    $query_calcular_nota = "{call CalcularNota(?, ?)}";
    $params_calcular_nota = array($id_alumno, $id_examen);
    $result_calcular_nota = sqlsrv_query($con, $query_calcular_nota, $params_calcular_nota);

    if ($result_calcular_nota === false) {
        die(print_r(sqlsrv_errors(), true));
    }
} 

if (isset($_POST['id_examen'])) {
    $id_examen = $_POST['id_examen'];
} else if (isset($_GET['id_examen'])) {
    $id_examen = $_GET['id_examen'];
}

$query_preguntas = "SELECT ID_Pregunta, Texto_Pregunta FROM Preguntas WHERE ID_Examen = ?";
$params_preguntas = array($id_examen);
$result_preguntas = sqlsrv_query($con, $query_preguntas, $params_preguntas);

$query_opciones = "SELECT ID_Opcion, Texto_Opcion FROM Opciones WHERE ID_Pregunta = ?";

?>
