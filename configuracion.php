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
    <title>Configuración</title>
    <style>
        /* Estilos comunes a ambos archivos */
        body {
            background-color: #e6f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgb(17, 34, 158);
            color: white;
            padding: 8px 0; /* Reducimos el padding vertical */
        }

        nav {
            display: flex;
            justify-content: space-around;
            width: 30%;
            margin: 0 auto;
            background-color: rgb(17, 34, 158);
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 16px; /* Ajustamos el tamaño del texto */
        }

        nav a:hover {
            color: #28a745;
        }

        main {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 30%;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 60px;
            border: 2px solid black; /* Borde negro */
        }

        h1, h4, h5 {
            color: black;
        }

        input[type="password"] {
            display: block;
            margin-bottom: 10px;
        }

        button {
            background-color: #11999e;
            color: white;
            border: 2px solid black;
            padding: 10px 20px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: rgb(17, 34, 158);
        }

        /* Estilos específicos del archivo 2 */
        body {
            margin: 150px;
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
            color: rgb(57, 40, 167);
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
            background-color: rgba(245, 244, 244, 0);
            border: none;
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

    <header>
        <nav>
            <a href="javascript:void(0);" onclick="redirigirAtras()">Volver</a>
        </nav>
    </header>

    <main>
        <h1>Configuración</h1>

        <?php
          include ('back/conexion.php');
          if (isset($_SESSION['IdUsuario'])) {
              $idUsuario = $_SESSION['IdUsuario'];
      
              // Asume que $con es tu conexión a la base de datos. Asegúrate de que está definida.
              $ultimo_login = "SELECT UltimoLogin AS LOGIN FROM Usuarios WHERE idUsuario = ?";
              $resultado = sqlsrv_query($con, $ultimo_login, array($idUsuario));
      
              if ($resultado === false) {
                  die(print_r(sqlsrv_errors(), true));
              }
      
              $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
              if ($fila !== null) {
                  $login = $fila['LOGIN'];
      
                  // Verifica si $login es un objeto DateTime
                  if ($login instanceof DateTime) {
                      // Formatea el objeto DateTime a una cadena
                      $loginString = $login->format('Y-m-d H:i:s');
                  } else {
                      // Si no es un DateTime, conviértelo a cadena
                      $loginString = strval($login);
                  }
      
                  echo "<h4>Último inicio de sesión: $loginString</h4>";
              } else {
                  echo "<h4>No se pudo obtener el último inicio de sesión.</h4>";
              }
          }
        ?>

        <section id="cambiar-contrasena">
            <h4>Cambiar contraseña</h4>
            <h5>Repita la contraseña 2 veces</h5>
            <form action="back/cambiar_clave.php" method="post">
                <input type="password" id="contrasena1" name="clave" placeholder="Contraseña">
                <input type="password" id="contrasena2" placeholder="Repetir Contraseña">
                <button onclick="return validarContrasena()">Guardar</button>
                <p id="mensaje-error" style="color: red;"></p>
            </form>
        </section>
    </main>

    <script>
        function redirigirAtras() {
            window.history.back();
        }

        function validarContrasena() {
            var contrasena1 = document.getElementById('contrasena1').value;
            var contrasena2 = document.getElementById('contrasena2').value;

            if (contrasena1 !== contrasena2) {
                document.getElementById('mensaje-error').innerHTML = 'Las contraseñas no coinciden';
                return false;
            } else {
                document.getElementById('mensaje-error').innerHTML = '';
            }
        }
    </script>
</body>
</html>
