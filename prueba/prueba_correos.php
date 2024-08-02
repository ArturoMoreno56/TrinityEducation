<?php
include('conexion.php');


$consulta='
INSERT INTO USUARIOS_DATOS(NOMBRE,CI,CORREO)
VALUES(?,?,?);';


/* 
$nombre='Arturo';
$ci=5527607;
$correo='Arturo@gmail.com';
*/
$nombre='Ever';
$ci=5527605;
$correo='Arturo@gmail.com';

//Primero validar ci o correo

$consulta_revisar='SELECT COUNT(*) AS CANT FROM USUARIOS_DATOS WHERE CI = ?;';
//$valores=array($nombre,$ci,$nombre);
/* $CANTIDAD=sqlsrv_query($con,$query,$params);
 $row_Cantidad=sqlsrv_fetch_array($CANTIDAD,SQLSRV_FETCH_ASSOC); 
  $existe=$row_Cantidad['CANTIDAD'];
 */
$dato=array($ci);
$resultado_consulta=sqlsrv_query($con,$consulta_revisar,$dato);
$row=sqlsrv_fetch_array($resultado_consulta,SQLSRV_FETCH_ASSOC);
$existe=$row['CANT'];

if ($existe==1) {
    echo 'ya existe una persona registrada con esa ci ';
}else{
    echo 'Ci valido';
}


//validar correo uni
$consultar_correo='SELECT COUNT(*) as CANT FROM USUARIOS_DATOS WHERE CORREO = ?;';

$dato=array($correo);

$resultado_consulta=sqlsrv_query($con,$consultar_correo,$dato);
$row=sqlsrv_fetch_array($resultado_consulta,SQLSRV_FETCH_ASSOC);
$existe=$row['CANT'];
if ($existe==1) {
    echo 'Ese correo ya existe en la base de datos';
}

?>