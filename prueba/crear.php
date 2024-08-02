<?php

include('conexion.php');

$usuario = 'Administrador';
$clave = 'contra123';

// Hashing the password
$hash = password_hash($clave, PASSWORD_DEFAULT);

// SQL statement with placeholders for prepared statement
$consulta_insertar = 'INSERT INTO Contrasenha(Contrasenha) VALUES (?);';

// Establishing the database connection (assuming $con is defined in conexion.php)
if (!$con) {
    die('Error de conexión: ' . sqlsrv_errors());
}

// Preparing the statement
$stmt = sqlsrv_prepare($con, $consulta_insertar, array($hash));

// Executing the statement
if ($stmt && sqlsrv_execute($stmt)) {
    echo 'Se insertó correctamente.';
} else {
    echo 'Error al insertar: ' . print_r(sqlsrv_errors(), true);
}

#verificar el id de la ultima clave ingresada

$obtener_id='SELECT TOP 1 * FROM Contrasenha ORDER BY idContrasenha DESC;';

$resultado=sqlsrv_query($con,$obtener_id);
    $row=sqlsrv_fetch_array($resultado,SQLSRV_FETCH_ASSOC);
    $ultima_id=$row['idContrasenha'];


$consulta_usuario='INSERT INTO Administracion(Nick,idContrasenha) VALUES(?,?);';
$stmt=sqlsrv_prepare($con,$consulta_usuario,array($usuario,$ultima_id));

if ($stmt && sqlsrv_execute($stmt)) {
    echo 'Se insertó correctamente.';
} else {
    echo 'Error al insertar: ' . print_r(sqlsrv_errors(), true);
}


// Closing the connection
sqlsrv_close($con);
/*
 CREATE TABLE USUARIO_MAESTRO(
    ID INTEGER PRIMARY KEY IDENTITY(1,1),
    USER_NAME VARCHAR(30),
    HASH CHAR(60),
    ID_MAESTRO INTEGER NOT NULL,
    ULIMO_LOGIN DATE
    FOREIGN KEY (ID_MAESTRO) REFERENCES DATOS_MAESTRO(ID)
)



*/
?>
