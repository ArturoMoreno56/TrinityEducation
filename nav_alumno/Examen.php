<?php
session_start();

if (isset($_SESSION['nombre_usuario'])) {
    $nombre_usuario = $_SESSION['nombre_usuario'];
} else {
    header('Location: ../index.html');
    exit();
}

require('../back/conexion.php');

if(isset($_SESSION['IdUsuario'])){
    $idUsuario = $_SESSION['IdUsuario'];
}

$combertir_usuario_A_alumno = 'SELECT idAlumno FROM Usuarios INNER JOIN Alumnos
                                ON Usuarios.idUsuario=Alumnos.idUsuario WHERE Usuarios.idUsuario='.$idUsuario;

$respuesta = sqlsrv_query($con, $combertir_usuario_A_alumno);
$row = sqlsrv_fetch_array($respuesta, SQLSRV_FETCH_ASSOC);
$id_alumno = $row['idAlumno'];

$query_examenes = "SELECT Examenes.Nombre, Examenes.idExamen 
                   FROM Examenes
                   INNER JOIN Relacional.RelacionAlumnoExamen
                   ON Relacional.RelacionAlumnoExamen.idExamen=Examenes.idExamen
                   WHERE Corregido=3 AND idAlumno=?";
$params = array($id_alumno);
$result_examenes = sqlsrv_query($con, $query_examenes, $params);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6f7f7;
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

        label {
            color: #333;
            display: block;
            margin-bottom: 10px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
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

        /* Estilos para el menú de navegación */
        header {
            display: flex;
            justify-content: flex-end;
            position: fixed; /* Fija la barra de navegación en la parte superior */
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgb(17, 34, 158); /* Color azulado */
            color: white;
            padding: 10px 0; /* Reducido padding para ser consistente con el de referencia */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            color: white;
            background-color: rgba(245, 244, 244, 0); /* Hace el fondo completamente transparente */
            border: none; /* Elimina el borde */
        }

        .dropdown-content {
            background-color: rgba(0, 0, 0, 0);
            display: none;
            position: absolute;
            background-color: transparent;
            min-width: 100px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            background-color: rgba(0, 0, 0, 0);
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: transparent;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Aplicar estilos del body a los elementos de texto */
        .dropdown .dropbtn,
        .dropdown-content a {
            font-family: Arial, sans-serif;
            font-weight: bold;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Added margin for spacing */
        }

        th, td {
            border: 1px solid #d3d3d3; /* Light grey border */
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #b0e0e6; /* Powder blue header */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f0f8ff; /* Light blue for even rows */
        }

        tr:hover {
            background-color: #add8e6; /* Lighter blue on row hover */
        }
    </style>
</head>
<body>
    <nav>
        <header> 
            <!-- Dropdown para la sección del usuario -->
            <div class="dropdown">
                <button class="dropbtn" onclick="toggleDropdown()"><strong>Bienvenido <?php echo $nombre_usuario;?></strong>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #fff;transform: ;msFilter:;">
                        <path d="M16.939 7.939 12 12.879l-4.939-4.94-2.122 2.122L12 17.121l7.061-7.06z"></path>
                    </svg>
                </button>
                <div class="dropdown-content" id="myDropdown">
                    <a href="../back/cerrar_sesion.php">Cerrar Sesión</a>
                </div>
            </div>
        </header>

        <h1>Examen</h1>

        <form action="Examen_activo.php" method="post">
            <label for="id_examen"><strong>Selecciona el examen:</strong></label>
            <select id="id_examen" name="id_examen">
                <?php while ($examen = sqlsrv_fetch_array($result_examenes, SQLSRV_FETCH_ASSOC)) { ?>
                    <option value="<?php echo $examen['idExamen']; ?>"><?php echo $examen['Nombre']; ?></option>
                <?php } ?>
            </select><br>
            <button type="submit">Comenzar Examen</button>
        </form>
    </nav>
    <script src="../js/menu.js"></script>
</body>
</html>
