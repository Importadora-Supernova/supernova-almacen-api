<?php
    include '../conexion/conexion.php';

    // insertamos cabeceras para permisos 
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");
    header("Content-Type: JSON");
    header('Content-Type: application/json;charset=utf-8');

    $pdo = new Conexion();
    $method = $_SERVER['REQUEST_METHOD'];

    if($method == 'GET'){

        if(isset($_GET['listas'])){
            $sql = $pdo->prepare('SELECT  id,nombres,orden,paqueteria,fecha_guias FROM folios   WHERE estatus LIKE "Guia enviada"');
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        }else if(isset($_GET['bodega'])){
            $sql = $pdo->prepare('SELECT f.id,f.nombres,f.orden,f.paqueteria,f.fecha_almacen,e.cajas FROM folios f INNER JOIN empaquetado e ON f.orden = e.orden WHERE f.estatus="Listo para salida" && f.envio="Si"');
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        }else if(isset($_GET['entregas'])){
            $sql = $pdo->prepare('SELECT f.id,f.nombres,f.orden,f.fecha_almacen,e.cajas,e.bolsas FROM folios f INNER JOIN empaquetado e ON f.orden = e.orden WHERE f.estatus="Listo para salida" && f.envio="No"');
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit;
        } 
    }
?>