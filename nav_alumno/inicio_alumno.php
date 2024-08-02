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
        <title>Página Alumno</title>
        <style>
            body {
                margin: 150px;
                font-family: Arial, sans-serif;
                font-weight: bold;
                background-color: #e6f7f7;
            }
            nav {
                margin-top: 120px;
                display: flex;
                justify-content: space-around;
                align-items: center;
                background-color: transparent;
                padding: 10px;
                flex-wrap: wrap;
            
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
                color: rgb(57, 40, 167); /* Color verde al pasar el ratón, similar al de referencia */
            }
            .nav-button img {
                width: 200px;
                height: 200px;
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
            @media (max-width: 600px) {
                nav {
                    flex-direction: column;
                    text-align: center;
                    width: 100%;
                    min-height: 428px;
                }
            }
        </style>
    </head>
    <body>
            <nav>
                <a href="Examen.php" class="nav-button">
                    <img src="../img/examen.jpg" alt="Examen">
                    <p class="titulo">Examen</p>
                </a>
                <a href="../materiales.php" class="nav-button">
                    <img src="../img/materiales.jpg" alt="Materiales">
                    <p class="titulo">Materiales</p>
                </a>
                <a href="mis_notas.php" class="nav-button">
                    <img src="../img/misnotas.png" alt="Mis notas">
                    <p class="titulo">Mis notas</p>
                </a>
                <header>  
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
