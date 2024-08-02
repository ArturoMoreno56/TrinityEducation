<?php
session_start();


if (isset($_SESSION['nombre_usuario'])) {
    $nombre_usuario = $_SESSION['nombre_usuario'];
} else {

    header('Location: index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/nav.css">
    <title>Docentes</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #e6f7f7;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: transparent;
            padding: 10px;
            flex-wrap: wrap;
            margin: 180px auto; /* Ajuste de margen vertical y centrado horizontal */
            max-width: 960px; /* Ancho máximo para evitar que los botones se extiendan demasiado */
        }

        .nav-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: black;
            transition: transform 0.2s;
            margin: 10px; /* Espacio entre botones */
            background-color: rgb(255, 255, 255);
            border: 1px solid black;
            border-radius: 5px; /* Esquinas redondeadas */
            overflow: hidden;
            width: 230px; /* Ancho fijo para cada botón */
            height: 250px; /* Altura fija para cada botón */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .nav-button:hover {
            transform: scale(1.1);
            color: rgb(57, 40, 167); /* Cambio de color al pasar el ratón */
        }

        .nav-button img {
            width: 200px;
            height: 200px;
        }

        .dropdown {
            display: flex;
            justify-content: flex-end;
            margin-left: auto;
        }

        .dropbtn {
            color: white;
            background-color: rgba(245, 244, 244, 0); /* Fondo transparente */
            border: none; /* Sin borde */
        }

        .dropdown-content {
            top: 100%; /* Posición debajo del botón */
            z-index: 15; /* Capa z superior para que se muestre sobre otros elementos */
            background-color: rgba(0, 0, 0, 0);
            display: none;
            position: absolute;
            background-color: transparent;
            min-width: 100px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
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

        /* Estilos para el texto */
        .dropdown .dropbtn,
        .dropdown-content a {
            font-family: Arial, sans-serif;
            font-weight: bold;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            nav {
                flex-direction: column; /* Cambia a columna en pantallas más pequeñas */
                text-align: center; /* Centra el texto */
                width: 90%; /* Ancho del 90% para asegurar que se ajuste */
                margin: 80px auto; /* Ajuste de margen */
            }

            .nav-button {
                width: 100%; /* Ancho del 100% para ocupar todo el contenedor */
                max-width: 300px; /* Ancho máximo para asegurar que no se estire demasiado */
                margin: 10px 0; /* Espacio entre botones */
            }
        }
    </style>
</head>
<body>

<nav>
    <a href="habilitar_examen.php" class="nav-button">
        <img src="../img/habilitarexamen.png" alt="Crear evaluaciones">
        <p class="titulo">Crear Evaluaciones</p>
    </a>
    <a href="subir_materiales.php" class="nav-button">
        <img src="../img/subirmateriales.jpg" alt="Subir materiales">
        <p class="titulo">Subir Materiales</p>
    </a>
    <a href="notas_alumnos.php" class="nav-button">
        <img src="../img/misalumnos.png" alt="Ver mis alumnos">
        <p class="titulo">Ver mis Alumnos</p>
    </a>
    <header>
       
        <!-- Dropdown para la sección del usuario -->
        <div class="dropdown">
                <button class="dropbtn" onclick="toggleDropdown()">
                    Bienvenido <?php echo $nombre_usuario; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #fff;transform: ;msFilter:;">
                        <path d="M16.939 7.939 12 12.879l-4.939-4.94-2.122 2.122L12 17.121l7.061-7.06z"></path>
                    </svg>
                </button>
                <div class="dropdown-content" id="myDropdown">
                    <a href="../configuracion.php">Configuración</a>
                    <a href="../back/cerrar_sesion.php">Cerrar Sesión</a>
                </div>
            </div>
        </header>
    </nav>

    <!-- Referencia al archivo JavaScript -->
    <script src="../js/menu.js"></script>
</body>
</html>
