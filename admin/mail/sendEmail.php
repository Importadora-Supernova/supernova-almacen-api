<?php
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
// conexion con base de datos 
include '../../conexion/conn.php';
include '../../headers.php';


// declarar array para respuestas 
$response = array();

date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d H:i:s');

// insertamos cabeceras para permisos 

// validamos si hay conexion 
if($con){
    //if($token_access['token']){
        $methodApi = $_SERVER['REQUEST_METHOD'];


        if($methodApi == 'POST'){

            $_POST = json_decode(file_get_contents('php://input'),true);

            $email =  $_POST['email'];
            $codigo = $_POST['codigo'];
            $sql = 'INSERT INTO app_codigos_descuento (email,codigo,fecha_registrado) VALUES(?,?,?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param($email,$codigo,$fecha);
            $result = $stmt->execute();



            $from = "administrador@importadorasupernova.com";
            $to = $email;
            $subject = "Tu Codigo de descuento";

            // Datos del usuario (asegúrate de definir $nombrere, $apellidore, $ran, $ran2, $idre antes de usarlos)


            // URL de la imagen (asegúrate de que la imagen esté en un servidor accesible públicamente y los espacios estén codificados como %20)
            $image_url = "https://www.importadorasupernova.com/img/logo_expandido.png";

            // Plantilla del mensaje con estilos en línea y una imagen
            $message = "
            <html>
            <head>
            <title>Recuperación de contraseña</title>
            </head>
            <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;'>
            <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                <tr>
                <td style='padding: 20px;'>
                    <table width='600' border='0' cellspacing='0' cellpadding='0' style='margin: 0 auto; background-color: #ffffff; padding: 20px;'>
                    <tr>
                        <td style='text-align: center;'>
                        <img src='{$image_url}' alt='Importadora Supernova' style='max-width: 100%; height: auto;'>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 20px 0; text-align: center;'>
                        <h1 style='color: #333333;'>CODIGO DE DESCUENTO</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 20px;'>
                        <p style='color: #333333;'>Estimado(a) {$email} ,</p>
                        <p style='color: #333333;'>Has solicitado un codigo de descuento.</p>
                        <p style='color: #333333;'>Tu codigo es: <b style='color:#EF3CA3;font-size:18px'> {$codigo}  </b> </p>
                        <p style='color: #333333;'>Este codigo solo podra ser usado en nuestras tiendas físicas.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 20px; text-align: center; border-top: 1px solid #dddddd;'>
                        <p style='color: #666666;'>Atentamente,<br>El equipo de Importadora Supernova</p>
                        </td>
                    </tr>
                    </table>
                </td>
                </tr>
            </table>
            </body>
            </html>
            ";

            // Para enviar correo HTML, debes establecer la cabecera Content-type
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // Cabeceras adicionales
            $headers .= 'From: ' . $from . "\r\n";

            mail($to, $subject, $message, $headers);
        }

        if($methodApi == 'PUT'){
            $_PUT = json_decode(file_get_contents('php://input'),true);

            $codigo = $_PUT['codigo'];
            $tienda = $_PUT['tienda'];

            $sql = 'UPDATE app_codigos_descuento SET validado=?,fecha_validado=?,tienda=?';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('iss',1,$fecha,$tienda);
            $result = $stmt->execute();

            echo $result;
        }


}else{
    echo "DB FOUND CONNECTED";
}