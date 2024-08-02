<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre_usuario'])) {
    header('Location: ../index.html');
    exit();
}
$nombre_usuario = $_SESSION['nombre_usuario'];
$idUsuario = $_SESSION['IdUsuario'];
include('../back/conexion.php');
$Conseguir_IdDocente = "SELECT idDocente FROM Docentes WHERE idUsuario = ?";
    $resultado = sqlsrv_query($con, $Conseguir_IdDocente, array($idUsuario));
    if ($resultado === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
    $idDocente = $fila['idDocente'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/tablas.css">
</head>
<style>
      body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #e6f7f7;
        }

        header {
            display: flex;
            justify-content: space-between; /* Alinear elementos a los extremos */
            position: fixed; /* Fijar la barra de navegación en la parte superior */
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgb(17, 34, 158); /* Color azulado */
            color: white;
            padding: 10px 20px; /* Ajustar el padding para mayor espacio */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000; /* Asegurar que esté por encima del contenido */
        }

        .logo {
            /* Estilos para el logo si es necesario */
        }

        nav {
            display: flex;
            align-items: center; /* Centrar verticalmente los elementos del nav */
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }

        table {
            width: 80%; /* Ancho de la tabla */
            border-collapse: collapse;
            margin-top: 120px; /* Asegurar espacio debajo del encabezado fijo */
            margin-left: auto;
            margin-right: auto;
            background-color: white; /* Fondo blanco */
            border: 1px solid #ddd; /* Borde ligero */
        }

        th, td {
            border: 1px solid #ddd; /* Borde ligero */
            padding: 12px; /* Padding ajustado */
            text-align: center; /* Alineación centrada del texto */
        }

        th {
            background-color: #00008B; /* Color de fondo azul oscuro */
            color: white; /* Texto blanco */
        }

        tr:nth-child(even) {
            background-color: #f2f2f2; /* Fondo gris claro para filas pares */
        }

        tr:hover {
            background-color: #ddd; /* Fondo gris más claro cuando se pasa el mouse */
        }

        h3 {
            text-align: center;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            color: white;
            background-color: rgba(245, 244, 244, 0); /* Fondo completamente transparente */
            border: none; /* Sin borde */
            padding: 10px 15px; /* Ajustar padding para espacio alrededor del texto */
            margin-right: 10px; /* Añadir margen a la derecha para separarlo del borde */
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
            margin-top: 3px; /* Ajuste el margen superior para bajar la flecha */
        }

        /* Media queries */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: center;
            }

            nav {
                margin-top: 10px; /* Ajuste de margen para dispositivos móviles */
            }

            nav a {
                margin: 5px; /* Espaciado entre enlaces */
            }
        }
</style>
<body>
<header class="header">
    <div class="logo"></div>
    <nav>
        <ul class="nav-links">
            <li><a href="inicio_docente.php">Inicio</a></li>
            <li><a href="../back/cerrar_sesion.php">Salir</a></li>
        </ul>            
    </nav>
</header>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nombres</th>
        </tr>
    </thead>
    <tbody>
    <?php
    include('../back/conexion.php');

    $consulta_promedio = "
        SELECT Alumnos.idAlumno AS '#',CONCAT(Alumnos.Nombres,' ',Alumnos.Apellidos) AS Nombres  FROM Relacional.RelacionCursoAlumno
INNER JOIN Alumnos ON Alumnos.idAlumno = Relacional.RelacionCursoAlumno.idAlumno
INNER JOIN Relacional.RelacionCursoDocente ON RelacionCursoDocente.idCurso = RelacionCursoAlumno.idCurso
WHERE  idDocente=?";

    $resultado = sqlsrv_query($con, $consulta_promedio,array($idDocente));
    if ($resultado === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo $row['#']; ?></td>
            <td><?php echo $row['Nombres']; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

</body>
</html>
