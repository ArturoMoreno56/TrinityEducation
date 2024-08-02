<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Alumno</title>
    <link rel="stylesheet" href="../css/agregar.css">
</head>
<body>
    <header> 
        <nav>
            <ul class="nav-links">
                <li><a href="../main_maestro.php">Inicio</a></li>
                <li><a href="../navegacion/Estado_curso.php">Estado curso</a></li>
                <li><a href="../navegacion/institucion.php">Instituciones</a></li>
                <li><a href="../navegacion/grado.php">Grado</a></li>
                <li><a href="../navegacion/Departamentos.php">Departamentos</a></li>
                <li><a href="../navegacion/Ciudades.php">Ciudades</a></li>
                <li><a href="../navegacion/cerrar.php">Cerrar Sesión</a></li>
            </ul>            
        </nav>
    </header>
    <h1>Agregar Alumno</h1>

    <form action="guardar_alumno.php" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br>
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required>
        <br>
        <label for="genero">Género:</label>
        <select id="genero" name="genero" required>
            <option value="Masculino">Masculino</option>
            <option value="Femenino">Femenino</option>
        </select>
        <br>
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required>
        <br>
        <label for="institucion">Institución:</label>
        <select id="institucion" name="institucion" required>
            <?php 
            include('conexion.php');
            $query = "SELECT * FROM Instituciones";
            $resultado = sqlsrv_query($con, $query);
            while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) { ?>
                <option value="<?php echo $row['idInstitucion']; ?>"><?php echo $row['Nombre']; ?></option>
            <?php } ?>
        </select>
        <br>
        <label for="ciudad">Ciudad:</label>
        <select id="ciudad" name="ciudad" required>
            <?php 
            $query = "SELECT * FROM Ciudades order by nombre";
            $resultado = sqlsrv_query($con, $query);
            while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) { ?>
                <option value="<?php echo $row['idCiudad']; ?>"><?php echo $row['Nombre']; ?></option>
            <?php } ?>
        </select>
        <label for="grado">Grado:</label>
        <select id="grado" name="grado" required>
            <?php 
            $query = "SELECT * FROM Grado";
            $resultado = sqlsrv_query($con, $query);
            while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) { ?>
                <option value="<?php echo $row['idGrado']; ?>"><?php echo $row['Nombre']; ?></option>
            <?php } ?>
        </select>
        <br>
        <button class='Boton_agregar_Alumno' type="submit">Guardar Alumno</button>
    </form>
                
</body>
</html>
