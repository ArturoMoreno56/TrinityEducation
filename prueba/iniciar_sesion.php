<?php

include('conexion.php');
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $usuario=$_POST['nombre'];
    $clave_ingresada=$_POST['clave'];
    $tipo_usuario=$_POST['usuario'];
    switch ($tipo_usuario) {
        case 'Maestro':
            #Consultar si existe ese  usuario maestro
                $consulta_existe_maestro='SELECT  COUNT(*) AS CANT FROM USUARIO_MAESTRO WHERE USER_NAME=?';
                        $dato_consulta=array($usuario);

                    $resultado_cons ulta=sqlsrv_query($con,$consulta_existe_maestro,$dato_consulta);
                        $candena=sqlsrv_fetch_array($resultado_consulta,SQLSRV_FETCH_ASSOC);
                        $existe_maestro=$candena['CANT'];

                        if($existe_maestro==1){

                             $consultar_clave='SELECT HASH AS CLAVE FROM USUARIO_MAESTRO WHERE USER_NAME = ?';
                                     $dato_consulta=array($usuario);
                                        $resultado_consulta=sqlsrv_query($con,$consultar_clave,$dato_consulta);
                                        $row=sqlsrv_fetch_array($resultado_consulta,SQLSRV_FETCH_ASSOC);
                                        $clave_verificar=$row['CLAVE'];

                            if (password_verify($clave_ingresada,$clave_verificar)) {
                             
                                
                                $consulta_actualizar_login='UPDATE USUARIO_MAESTRO SET ULIMO_LOGIN=GETDATE() WHERE USER_NAME=?';
                                    $dato_consulta=array($usuario);
                                    $resultado_update=sqlsrv_query($con,$consulta_actualizar_login,$dato_consulta);

                                    Header('Location:../inicio_maestro.html');
                              }
                              else{
                                header('Location:../index.html?error=clave_erronea');
                            }
                        }
                        else{
                             header('Location:../index.html?error=usuario_inexistente');
                        }
                        
            break;
        case 'Alumno':
            #consultar si  existe ese usuario alumno
                $consulta_existe_alumno='SELECT COUNT(*) AS CANT FROM USUARIO_ALUMNO WHERE USER_NAME=?';
                        $dato_consulta=array($usuario);

                    $resultado_consulta=sqlsrv_query($con,$consulta_existe_alumno,$dato_consulta);
                        $candena=sqlsrv_fetch_array($resultado_consulta,SQLSRV_FETCH_ASSOC);
                        $existe_alumno=$candena['CANT'];

                        if($existe_alumno==1){

                            $consultar_clave='SELECT HASH AS CLAVE FROM USUARIO_ALUMNO WHERE USER_NAME = ?';
                                     $dato_consulta=array($usuario);
                                        $resultado_consulta=sqlsrv_query($con,$consultar_clave,$dato_consulta);
                                        $row=sqlsrv_fetch_array($resultado_consulta,SQLSRV_FETCH_ASSOC);
                                        $clave_verificar=$row['CLAVE'];

                            if (password_verify($clave_ingresada,$clave_verificar)) {

                                $consulta_actualizar_login='UPDATE USUARIO_ALUMNO SET ULIMO_LOGIN=GETDATE() WHERE USER_NAME=?';
                                    $dato_consulta=array($usuario);
                                    $resultado_update=sqlsrv_query($con,$consulta_actualizar_login,$dato_consulta);
                                Header('Location:../inicio_alumno.html');

                              }
                              else{
                                header('Location:../index.html?error=clave_erronea');
                            }
                        }
                        else{
                             header('Location:../index.html?error=usuario_inexistente');
                        }
            break;
        default:
        #caso de ser administrador
            #consultar
                
        

            break;
    }
}
?>
