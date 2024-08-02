<?php
session_start();

// Verificar si hay sesión activa
if (isset($_SESSION['nombre_usuario'])) {
    $nombre_usuario = $_SESSION['nombre_usuario'];
} else {
    // Redireccionar a la página de inicio de sesión si no hay sesión activa
    header('Location: ../index.html');
    exit();
}

// Incluir el archivo de conexión a la base de datos
require ('../back/conexion.php');

$id_alumno = '';
$id_examen = '';

// Manejar la solicitud POST cuando se envía el formulario de respuestas
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    // Obtener el ID de usuario de la sesión
    if (isset($_SESSION['IdUsuario'])) {
        $idUsuario = $_SESSION['IdUsuario'];
    }

    // Convertir el usuario a alumno y obtener su ID de alumno
    $convertir_usuario_a_alumno = 'SELECT idAlumno FROM Usuarios INNER JOIN Alumnos ON Usuarios.idUsuario = Alumnos.idUsuario WHERE Usuarios.idUsuario = ?';
    $params_usuario = array($idUsuario);
    $respuesta = sqlsrv_query($con, $convertir_usuario_a_alumno, $params_usuario);

    if ($respuesta === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($respuesta, SQLSRV_FETCH_ASSOC);
    if ($row === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $id_alumno = $row['idAlumno'];
    $id_examen = $_POST['id_examen'];

    // Obtener la etapa del examen
    $conseguir_etapa = "SELECT Etapa FROM Examenes WHERE idExamen = ?";
    $resultado_etapa = sqlsrv_query($con, $conseguir_etapa, array($id_examen));
    $fila_etapa = sqlsrv_fetch_array($resultado_etapa, SQLSRV_FETCH_ASSOC);
    $etapa = $fila_etapa['Etapa'];

    // Obtener el curso del examen
    $conseguir_curso = "SELECT Cursos.idCurso AS curso FROM Examenes INNER JOIN Cursos ON Cursos.idCurso = Examenes.idCurso WHERE Examenes.idExamen = ?";
    $resultado_curso = sqlsrv_query($con, $conseguir_curso, array($id_examen));
    $fila_curso = sqlsrv_fetch_array($resultado_curso, SQLSRV_FETCH_ASSOC);
    $idCurso = $fila_curso['curso'];

    // Obtener todas las preguntas del examen
    $query_preguntas = "SELECT idPregunta, txtPregunta FROM Preguntas WHERE idExamen = ?";
    $params_preguntas = array($id_examen);
    $result_preguntas = sqlsrv_query($con, $query_preguntas, $params_preguntas);

    if ($result_preguntas === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Verificar si se enviaron respuestas
    $respuestas_enviadas = false;
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'respuesta_') === 0) {
            $respuestas_enviadas = true;
            break;
        }
    }

    if ($respuestas_enviadas) {
        // Recorrer cada pregunta y registrar las respuestas del alumno
        while ($pregunta = sqlsrv_fetch_array($result_preguntas, SQLSRV_FETCH_ASSOC)) {
            if ($pregunta === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $id_pregunta = $pregunta['idPregunta'];
            $respuesta_alumno = isset($_POST['respuesta_' . $id_pregunta]) ? $_POST['respuesta_' . $id_pregunta] : null;

            if ($respuesta_alumno !== null) {
                // Insertar la respuesta del alumno en la tabla RelacionAlumnoRespuestas
                $query_insert_respuesta = "INSERT INTO Relacional.RelacionAlumnoRespuestas (idAlumno, idExamen, idPregunta, idOpcion) VALUES (?, ?, ?, ?)";
                $params_respuesta = array($id_alumno, $id_examen, $id_pregunta, $respuesta_alumno);
                $result_insert_respuesta = sqlsrv_query($con, $query_insert_respuesta, $params_respuesta);

                if ($result_insert_respuesta === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
            }
        }
        
        
        // Llamar al procedimiento almacenado para calcular la nota del alumno
        $query_calcular_nota = "EXEC CalcularNota ?, ?, ?, ?";
        $params_calcular_nota = array($id_alumno, $id_examen, $etapa, $idCurso);
        $result_calcular_nota = sqlsrv_query($con, $query_calcular_nota, $params_calcular_nota);

        if ($result_calcular_nota === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Llamar al procedimiento almacenado Evaluar
        $query_evaluar = "EXEC Evaluar ?, ?";
        $params_evaluar = array($id_alumno, $id_examen);
        $result_evaluar = sqlsrv_query($con, $query_evaluar, $params_evaluar);

        if ($result_evaluar === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Redirigir al inicio del alumno
        header('Location: inicio_alumno.php');
        exit();
    }
}

// Obtener el ID del examen desde la solicitud POST o GET
if (isset($_POST['id_examen'])) {
    $id_examen = $_POST['id_examen'];
} else if (isset($_GET['id_examen'])) {
    $id_examen = $_GET['id_examen'];
}

// Obtener todas las preguntas del examen específico
$query_preguntas = "SELECT idPregunta, txtPregunta FROM Preguntas WHERE idExamen = ?";
$params_preguntas = array($id_examen);
$result_preguntas = sqlsrv_query($con, $query_preguntas, $params_preguntas);

if ($result_preguntas === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Consulta para obtener las opciones de respuesta de cada pregunta
$query_opciones = "SELECT idOpcion, txtOpcion FROM Opciones WHERE idPregunta = ?";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Activo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #008080; /* Teal background */
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: white;
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .pregunta {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .opciones {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .opcion {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }

        label {
            color: #333;
            margin-left: 5px;
        }

        input[type="radio"] {
            width: auto;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #004d40; /* Dark teal color */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #00332a;
        }
    </style>
</head>
<body>

    <h1>Examen Activo</h1>
    <form id="miFormulario" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="id_alumno" value="<?php echo $id_alumno; ?>">
        <input type="hidden" name="id_examen" value="<?php echo $id_examen; ?>">

        <?php if (isset($result_preguntas)) { ?>
            <?php while ($pregunta = sqlsrv_fetch_array($result_preguntas, SQLSRV_FETCH_ASSOC)) { ?>
                <p class="pregunta"><?php echo $pregunta['txtPregunta']; ?></p>
                <div class="opciones">
                    <?php
                    $params_opciones = array($pregunta['idPregunta']);
                    $result_opciones = sqlsrv_query($con, $query_opciones, $params_opciones);
                    if ($result_opciones === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }
                    while ($opcion = sqlsrv_fetch_array($result_opciones, SQLSRV_FETCH_ASSOC)) { ?>
                        <div class="opcion">
                            <input type="radio" id="respuesta_<?php echo $opcion['idOpcion']; ?>" name="respuesta_<?php echo $pregunta['idPregunta']; ?>" value="<?php echo $opcion['idOpcion']; ?>">
                            <label for="respuesta_<?php echo $opcion['idOpcion']; ?>"><?php echo $opcion['txtOpcion']; ?></label>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>

        <button type="submit">Enviar Respuestas</button>
    </form>
       
    <script>
    // Manejar el envío del formulario mediante AJAX
    document.getElementById('miFormulario').addEventListener('submit', function(event) {
        event.preventDefault();

        var form = event.target;
        var formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            if (response.ok) {
                window.location.href = "inicio_alumno.php"; // redirigir a la página de inicio del alumno después de enviar las respuestas
            } else {
                console.error('Error:', response.statusText);
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
        });
    });
    </script>
</body>
</html>
