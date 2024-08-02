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
    <title>Document</title>
    <style>
     /* Estilos generales */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #e6f7f7;
}

/* Encabezado */
header {
    display: flex;
    justify-content: space-between;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: rgb(17, 34, 158);
    color: white;
    padding: 1px 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

/* Navegación */
nav {
    display: flex;
    align-items: center;
}

nav a {
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    margin: 0 10px;
    transition: background-color 0.3s ease;
}

/* Tabla */
table {
    width: 80%;
    border-collapse: collapse;
    margin-top: 120px; /* Ajuste del margen superior para separar la tabla del encabezado */
    margin-left: auto;
    margin-right: auto;
}

th, td {
    border: 1px solid #ADD8E6;
    padding: 8px;
    text-align: left;
}

/* Ajuste de los anchos de las columnas */
th:nth-of-type(1), td:nth-of-type(1) {
    width: 20%;
}

th:nth-of-type(2), td:nth-of-type(2),
th:nth-of-type(3), td:nth-of-type(3),
th:nth-of-type(4), td:nth-of-type(4) {
    width: 20%;
}

th:nth-of-type(5), td:nth-of-type(5) {
    width: 20%;
}

th {
    background-color: #00008B;
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #ddd;
}

/* Dropdown */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropbtn {
    color: white;
    background-color: rgba(245, 244, 244, 0);
    border: none;
    padding: 10px 15px;
    margin-right: 10px;
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

/* Estilos del texto */
.dropdown .dropbtn,
.dropdown-content a {
    font-family: Arial, sans-serif;
    font-weight: bold;
}

/* Estilo específico para la flecha */
.dropbtn svg {
    margin-top: 3px;
}

/* Media queries */
@media (max-width: 768px) {
    header {
        flex-direction: column;
        align-items: center;
    }

    nav {
        margin-top: 10px;
    }

    nav a {
        margin: 5px;
    }
}


    </style>
</head>
<body>
<header>
    <!-- Botón de Inicio -->
    <a href="inicio_alumno.php" class="icono-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" margin-top: "25px" style= "fill: #fff ;">
            <path d="M11.999 1.993C6.486 1.994 2 6.48 1.999 11.994c0 5.514 4.486 10 10.001 10 5.514-.001 10-4.487 10-10 0-5.514-4.486-10-10.001-10.001zM12 19.994c-4.412 0-8.001-3.589-8.001-8 .001-4.411 3.59-8 8-8.001C16.411 3.994 20 7.583 20 11.994c0 4.41-3.589 7.999-8 8z"></path>
            <path d="m12.012 7.989-4.005 4.005 4.005 4.004v-3.004h3.994v-2h-3.994z"></path>
        </svg>
    </a>

    <div class="logo">
        <!-- Estilos para el logo si es necesario -->
    </div>

    <!-- Dropdown para la sección del usuario -->
    <div class="dropdown">
        <button class="dropbtn" onclick="toggleDropdown()">
            <strong>Bienvenido <?php echo $nombre_usuario; ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #fff;">
                    <path d="M16.939 7.939 12 12.879l-4.939-4.94-2.122 2.122L12 17.121l7.061-7.06z"></path>
                </svg>
            </strong>
        </button>
        <div class="dropdown-content" id="myDropdown">
            <a href="../back/cerrar_sesion.php">Cerrar Sesión</a>
        </div>
    </div>
</header>

<table>
    <thead>
        <tr>
  <th>Puntaje Diagnostica</th>
  <th>Puntaje Ep1</th>
  <th>Puntaje Ep2</th>
  <th>Puntaje Ep3</th>
  <th>Rendimiento</th>
        </tr>
    </thead>
    <tbody>
    <?php
 include ('../back/conexion.php');

 if (isset($_SESSION['IdUsuario'])) {
     $idUsuario = $_SESSION['IdUsuario'];

     $consulta_nota = "SELECT PuntajeIntro, PuntajeEp1, PuntajeEp2, PuntajeEp3, RendimientoTotal FROM Rendimientos  
         INNER JOIN Alumnos ON Alumnos.idAlumno = Rendimientos.idAlumno
         WHERE Alumnos.idUsuario=?;";

     $revisar_estadoCurso = "SELECT AvanceEnCurso AS AVANCE FROM Relacional.RelacionCursoAlumno
         INNER JOIN Alumnos ON Alumnos.idAlumno = Relacional.RelacionCursoAlumno.idAlumno
         INNER JOIN Usuarios ON Alumnos.idUsuario = Usuarios.idUsuario
         WHERE Usuarios.idUsuario=?";

     $resultado = sqlsrv_query($con, $revisar_estadoCurso, array($IdUsuario));
     $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
     $Avance = $fila['AVANCE'];

     $resultado = sqlsrv_query($con, $consulta_nota, array($idUsuario));
     if ($resultado === false) {
         die(print_r(sqlsrv_errors(), true));
     }

     while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) { ?>
         <tr>
             <td><?php echo $row['PuntajeIntro']; ?></td>
             <td><?php echo $row['PuntajeEp1']; ?></td>
             <td><?php echo $row['PuntajeEp2']; ?></td>
             <td><?php echo $row['PuntajeEp3']; ?></td>
             <td><?php echo $row['RendimientoTotal']; ?></td>
         </tr>
     <?php }
 }
 ?>
    </tbody>
</table>

<!-- Referencia al archivo JavaScript -->
<script src="../js/menu.js"></script>

</body>
</html>
