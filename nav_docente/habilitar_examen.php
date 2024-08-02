<?php
session_start();
require ('../back/conexion.php');

if (!isset($_SESSION['nombre_usuario'])) {
    header('Location: ../index.html');
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'];
$idUsuario = $_SESSION['IdUsuario'];

function getUnusedStages($con, $idDocente)
{
    // Obtener el curso del docente
    $conseguir_idcurso = "SELECT idCurso FROM Relacional.RelacionCursoDocente WHERE idDocente = ?";
    $resultado = sqlsrv_query($con, $conseguir_idcurso, array($idDocente));
    if ($resultado === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
    $IdCurso = $fila['idCurso'];

    // Obtener etapas que ya tienen exámenes para el curso del docente
    $query_used_stages = "SELECT DISTINCT Etapa FROM Examenes WHERE idCurso = ?";
    $resultado = sqlsrv_query($con, $query_used_stages, array($IdCurso));
    $used_stages = array();
    while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $used_stages[] = $row['Etapa'];
    }

    // Obtener todas las etapas disponibles
    $query = "SELECT idAvanceCurso, Nombre FROM AvancesCursos WHERE idAvanceCurso BETWEEN 2 AND 5";
    $resultado = sqlsrv_query($con, $query);
    $options = "";
    while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        if (!in_array($row['idAvanceCurso'], $used_stages)) {
            $options .= '<option value="' . $row['idAvanceCurso'] . '">' . $row['Nombre'] . '</option>';
        }
    }
    return $options;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_examen = $_POST['nombre_examen'];
    $cantidad_preguntas = $_POST['cantidad_preguntas'];
    $etapa = $_POST['Etapa'];

    // Obtener idDocente
    $Conseguir_IdDocente = "SELECT idDocente FROM Docentes WHERE idUsuario = ?";
    $resultado = sqlsrv_query($con, $Conseguir_IdDocente, array($idUsuario));
    if ($resultado === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
    $idDocente = $fila['idDocente'];

    // Obtener idCurso
    $conseguir_idcurso = "SELECT idCurso FROM Relacional.RelacionCursoDocente WHERE idDocente = ?";
    $resultado = sqlsrv_query($con, $conseguir_idcurso, array($idDocente));
    if ($resultado === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
    $IdCurso = $fila['idCurso'];

    // Verificar si ya existe un examen con el mismo idCurso y Etapa
    $query_verificar_examen = "SELECT COUNT(*) AS count FROM Examenes WHERE idCurso = ? AND Etapa = ?";
    $params_verificar = array($IdCurso, $etapa);
    $resultado_verificar = sqlsrv_query($con, $query_verificar_examen, $params_verificar);
    if ($resultado_verificar === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $fila_verificar = sqlsrv_fetch_array($resultado_verificar, SQLSRV_FETCH_ASSOC);
    if ($fila_verificar['count'] > 0) {
        die("Ya existe un examen con el mismo idCurso y Etapa.");
    }

    // Insertar examen y obtener idExamen insertado
    $query_insert_examen = "INSERT INTO Examenes (idCurso, Nombre, Etapa, FechaCarga) VALUES (?, ?, ?, GETDATE())";
    $params_examen = array($IdCurso, $nombre_examen, $etapa);
    $stmt = sqlsrv_query($con, $query_insert_examen, $params_examen);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $ultimo_id = "SELECT TOP 1 idExamen FROM Examenes ORDER BY idExamen DESC;";
    $resultado = sqlsrv_query($con, $ultimo_id);
    $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
    $id_examen = $fila['idExamen'];

    // Insertar preguntas y opciones
    for ($i = 1; $i <= $cantidad_preguntas; $i++) {
        if (isset($_POST['pregunta_' . $i])) {
            $texto_pregunta = $_POST['pregunta_' . $i];

            $query_insert_pregunta = "INSERT INTO Preguntas (idExamen, txtPregunta) VALUES (?, ?)";
            $params_pregunta = array($id_examen, $texto_pregunta);
            $stmt = sqlsrv_query($con, $query_insert_pregunta, $params_pregunta);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            $ultimo_id = "SELECT TOP 1 idPregunta FROM Preguntas ORDER BY idPregunta DESC;";
            $resultado = sqlsrv_query($con, $ultimo_id);
            $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
            $id_pregunta = $fila['idPregunta'];

            for ($j = 1; $j <= 4; $j++) {
                if (isset($_POST['opcion_' . $i . '_' . $j])) {
                    $texto_opcion = $_POST['opcion_' . $i . '_' . $j];
                    $es_correcta = isset($_POST['correcta_' . $i]) && $_POST['correcta_' . $i] == $j ? 1 : 0;

                    $query_insert_opcion = "INSERT INTO Opciones (idPregunta, EsCorrecto, txtOpcion) VALUES (?, ?, ?)";
                    $params_opcion = array($id_pregunta, $es_correcta, $texto_opcion);
                    $result_insert_opcion = sqlsrv_query($con, $query_insert_opcion, $params_opcion);
                    if ($result_insert_opcion === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }
                } else {
                    die("Missing option: opcion_" . $i . "_" . $j);
                }
            }
        } else {
            die("Missing question: pregunta_" . $i);
        }
    }

    header("Location: inicio_docente.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Examen</title>
   
    <link rel="stylesheet" href="../css/Style_formularios.css">
</head>
<body>

<header>
    <nav>
        <a href="inicio_docente.php">Inicio docente</a>
        <a href="../back/cerrar_sesion.php">Salir</a>
    </nav>
</header>

<h1 class="titulo">Agregar Examen</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="formulario">
    <label for="nombre_examen" class="etiqueta">Nombre del examen:</label>
    <input type="text" id="nombre_examen" name="nombre_examen" required class="campo-texto"><br>

    <label for="Etapa" class="etiqueta">Etapa:</label>
    <select id="Etapa" name="Etapa" required class="campo-select">
        <?php
        // Obtener idDocente
        $Conseguir_IdDocente = "SELECT idDocente FROM Docentes WHERE idUsuario = ?";
        $resultado = sqlsrv_query($con, $Conseguir_IdDocente, array($idUsuario));
        if ($resultado === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
        $idDocente = $fila['idDocente'];

        echo getUnusedStages($con, $idDocente);
        ?>
    </select><br>

    <label for="cantidad_preguntas" class="etiqueta">Cantidad de preguntas:</label>
    <input type="number" id="cantidad_preguntas" name="cantidad_preguntas" min="2" max="10" required class="campo-numero"><br>
 
    <div id="preguntas" class="contenedor-preguntas"></div>
    
    <button type="submit" class="boton">Agregar Examen</button>
</form>

<script>
    document.getElementById('cantidad_preguntas').addEventListener('change', function() {
        var cantidadPreguntas = this.value;
        var preguntasDiv = document.getElementById('preguntas');
        preguntasDiv.innerHTML = ''; 

        for (var i = 1; i <= cantidadPreguntas; i++) {
            var preguntaLabel = document.createElement('label');
            preguntaLabel.textContent = 'Pregunta ' + i + ':';
            preguntaLabel.classList.add('etiqueta');
            preguntasDiv.appendChild(preguntaLabel);
            preguntasDiv.appendChild(document.createElement('br'));

            var preguntaInput = document.createElement('input');
            preguntaInput.type = 'text';
            preguntaInput.name = 'pregunta_' + i;
            preguntaInput.required = true;
            preguntaInput.classList.add('campo-texto');
            preguntasDiv.appendChild(preguntaInput);
            preguntasDiv.appendChild(document.createElement('br'));

            for (var j = 1; j <= 4; j++) {
                var respuestaLabel = document.createElement('label');
                respuestaLabel.textContent = 'Respuesta ' + j + ':';
                respuestaLabel.classList.add('etiqueta');
                preguntasDiv.appendChild(respuestaLabel);

                var respuestaInput = document.createElement('input');
                respuestaInput.type = 'text';
                respuestaInput.name = 'opcion_' + i + '_' + j;
                respuestaInput.required = true;
                respuestaInput.classList.add('campo-texto');
                preguntasDiv.appendChild(respuestaInput);

                var correctaLabel = document.createElement('label');
                correctaLabel.textContent = ' Correcta';
                correctaLabel.classList.add('etiqueta');
                preguntasDiv.appendChild(correctaLabel);

                var correctaInput = document.createElement('input');
                correctaInput.type = 'radio';
                correctaInput.name = 'correcta_' + i;
                correctaInput.value = j;
                correctaInput.required = true;
                correctaInput.classList.add('campo-radio');
                preguntasDiv.appendChild(correctaInput);
            }

            preguntasDiv.appendChild(document.createElement('br'));
        }
    });
</script>

</body>
</html>
