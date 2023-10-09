<?php
include '../vendor/autoload.php';
date_default_timezone_set('America/Mexico_City');
// conexion con base de datos 
include '../conexion/conn.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

            $token_access = array(
                "validate" => "",
                "token" => false
            );


            $fechaActual = date('Y-m-d');

            $headers = getallheaders();
            $headersToken = $headers['Authorization'];
            
            $tokenParts = explode(' ', $headersToken);

            if (count($tokenParts) !== 2 || $tokenParts[0] !== 'Bearer') {
                $token_access['validate'] = "Token invalidado";
            }else{

                try {

                    $fechaActual = date('Y-m-d');
                    $key = hash('sha256', $fechaActual);
    
                    if (isset($headers['Authorization'])) {
                        $authorizationHeader = $headers['Authorization'];
                    }
                    $token = $tokenParts[1];
                    $decoded = JWT::decode($token, new Key($key, 'HS256'));
                    
                    $usuario = $decoded->usuario;
                    $rol = $decoded->rol;
                    $id = $decoded->id;
                    
                    $sql = 'SELECT u.id_user_bodega,u.usuario_bodega,t.nombre_tipo_usuario FROM app_usuarios_bodega u INNER JOIN app_tipo_usuario_bodega t ON u.tipo_usuario = t.id_tipo_usuario  WHERE u.usuario_bodega="'.$usuario.'" AND u.token="'.$token.'"';
                    $result = mysqli_query($con,$sql);
                    $row = mysqli_fetch_assoc($result);

                    if($row){
                        $token_access['validate'] = 'Token validado';
                        $token_access['token'] = true;
                    }else{
                        $token_access['validate'] = 'Token no encontrado';
                    }
                    
                    // Continúa con el procesamiento de la solicitud
                } catch (Exception $e) {
                    // El token no es válido
                    $token_access['validate']  = 'Error al decodificar el token: ' . $e->getMessage();
                }
            }

            return $token_access;

        
?>