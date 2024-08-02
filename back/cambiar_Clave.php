<?php
session_start(); 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('conexion.php');

    if(isset($_SESSION['IdUsuario'])){
    $idUsuario = $_SESSION['IdUsuario'];
    $clave_nueva=$_POST['clave'];

    
    $hash_nueva_Clave=password_hash($clave_nueva,PASSWORD_DEFAULT);

    $idclave_consulta="SELECT Contrasenha.idContrasenha AS ID FROM Contrasenha
	INNER JOIN Usuarios ON Usuarios.idContrasenha = Contrasenha.idContrasenha
	where Usuarios.idUsuario=?";

    $resultado=sqlsrv_query($con,$idclave_consulta,array($idUsuario));
    $fila=sqlsrv_fetch_array($resultado,SQLSRV_FETCH_ASSOC);
    $idContra=$fila['ID'];

    $consulta_insertar = 'update Contrasenha set Contrasenha=? where idContrasenha=?';

    $resultado=sqlsrv_query($con,$consulta_insertar,array($hash_nueva_Clave,$idContra));


    if($resultado){
        header("location: cerrar_sesion.php");
    }

}
}
?>