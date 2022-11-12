<?php
date_default_timezone_set('America/Mexico_City');
// conexion con base de datos 
include '../conexion/conn.php';

            $validate='';

            $fecha_actual = date('Y-m-d H:i:s');
            $headers = apache_request_headers();
            $tok = '';

            foreach ($headers as $header => $value) {
                if($header == 'Authorization'){
                    $tok = $value;
                }
            }
            if($tok == ''){
                $validate = 'no existe';
            }else{
                $sqlConsulta = 'SELECT token,expire_token FROM usuarios_almacen WHERE token="'.$tok.'"';
                $resultado = mysqli_query($con,$sqlConsulta);
                $fill = mysqli_fetch_assoc($resultado);
                if($fill){
                    if($fill['token'] === $tok){
                        if($fill['expire_token'] > $fecha_actual){
                            $validate = 'validado';
                        }else{
                            $validate = 'ha expirado';
                        }
                    }else{
                        $validate = 'es incorrecto';
                    }
                }else{
                    $validate = 'error';
                }  
            }
            
            
            return $validate;

        
?>