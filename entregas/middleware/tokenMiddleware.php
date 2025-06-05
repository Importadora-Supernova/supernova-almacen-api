<?php
require_once __DIR__ . '/../../vendor/autoload.php';
date_default_timezone_set('America/Mexico_City');
// conexion con base de datos 

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Función para verificar el token JWT
 * @param string $token Token JWT a verificar
 * @return array Resultado de la verificación
 */

class Token {

    public function __construct()
    {
        
    }

    function verificarToken($token) {
    global $con;
    $token_access = array(
        "validate" => "",
        "token" => false,
        "user" => null
    );
    
    try {
        $key = "APP_ENTREGAS_168**";
        
        $tokenParts = explode(' ', $token);
        
        if (count($tokenParts) !== 2 || $tokenParts[0] !== 'Bearer') {
            $token_access['validate'] = "Token invalidado";
            return $token_access;
        }
        
        $jwt = $tokenParts[1];
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        
        // Verificar si el token existe en la base de datos
        $query = "SELECT * FROM entregas_users WHERE id = ? AND token = ? LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param('is', $decoded->id, $jwt);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if ($user) {
            $token_access['validate'] = 'Token validado';
            $token_access['token'] = true;
            $token_access['user'] = $user;
        } else {
            $token_access['validate'] = 'Token no encontrado';
        }
        
    } catch (Exception $e) {
        $token_access['validate'] = 'Error al decodificar el token: ' . $e->getMessage();
    }
    
    return $token_access;
}

}

?>