

<?php

include('conexion.php');

if($con){
    echo "Conexion exitosa";

    $clave='Trinity123';
    $hash=password_hash($clave,PASSWORD_DEFAULT);
       
        
    
        $insert='INSERT INTO CONTRASENHA(Contrasenha) values(?)';
        
        $resultado=sqlsrv_query($con,$insert,array($hash));


        if($resultado){
            echo "Se inserto correctamente";
        }

}
?>