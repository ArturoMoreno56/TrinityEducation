<?php

$host = 'NOTEBOOKARTUROM\MSSQLSERVER2';
$dbname = 'Trinidad3';
$user = 'admin';
$pas = 'M4tsum0t017';
$puerto = '1433';

$con = sqlsrv_connect($host, array(
    'Database' => $dbname,
    'UID' => $user,
    'PWD' => $pas,
    'ConnectionPooling' => 0,
    'CharacterSet' => 'UTF-8'
));



?>