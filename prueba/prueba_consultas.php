<?php

include('conexion.php');

$consulta='
SELECT top 3
Person.FirstName,Person.LastName,
Person.EmailPromotion FROM Person.Person';


$resultado=sqlsrv_query($con,$consulta);


echo "<pre>";
var_dump($resultado);
echo "</pre>";


while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
    echo "<pre>";
    var_dump($fila);
    echo "</pre>";
    echo "<br>"; // Salto de línea después de cada fila
}
?>