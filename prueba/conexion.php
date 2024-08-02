<?php

$host = 'SANDRA';
$dbname = 'trinity11-05-24';
$user = 'sa';
$pas = 'unida1010';
$puerto = '1433';

$con = sqlsrv_connect($host, array(
    'Database' => $dbname,
    'UID' => $user,
    'PWD' => $pas,
    'ConnectionPooling' => 0,
    'CharacterSet' => 'UTF-8'
));


?>