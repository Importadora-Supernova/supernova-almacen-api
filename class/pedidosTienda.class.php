<?php

class pedidosTienda{

    public function __construct()
    {
        
    }

    //metodo para traer los pedidos creados por tiendas
    public function getPedidosTienda($con,$sentencia)
    {
        $stmt = $con->prepare($sentencia);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data; 
    }
    //metodo traer un pedido especifico creado por tienda
    public function getPedidoTiendaId($con,$sentencia,$tienda)
    {
        $stmt = $con->prepare($sentencia); 
        $stmt->bind_param("s", $tienda);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    //metodo para traer los pedidos que ya tienen medidas cargadas
    public function getPedidosMedidas($con,$sentencia,$status)
    {
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("s",$status);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    //metodo para traer las medidas de un pedido
    public function getMedidas($con,$sentencia,$id)
    {
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    //metodo para crear un nuevo pedido de tienda
    public function insertPedido($con,$sentencia,$data,$estatus,$fecha)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("ssssssssisss",$data['tienda'],$data['cliente'],$data['estado'],$data['ciudad'],$data['direccion'],$data['colonia'],$data['cp'],$data['rfc'],$data['cajas'],$estatus,$data['paqueteria'],$fecha);
        $reta = $stmt->execute();
        return $reta;
    }
    //metodo para registrar las medidas de las cajas 
    public function insertMedidasPedidoTienda($con,$sentencia,$data,$id,$fecha)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("idddds",$id,$data['peso'],$data['alto'],$data['ancho'],$data['largo'],$fecha);
        $reta = $stmt->execute();
        return $reta;
    }
    // metodo para actualizar el estatus del pedido despues de cargadas las medidas
    public function  updatePedidoMedidas($con,$sentencia,$estatus,$id,$fecha)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("ssi",$estatus,$fecha,$id);
        $reta = $stmt->execute();
        return $reta;
    }
    // metodo para actualizar el estatus del pedido despues de cargadas las guias
    public function updatePedidoGuias($con,$sentencia,$estatus,$costo,$id,$fecha,$file)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("sdssi",$estatus,$costo,$fecha,$file,$id);
        $reta = $stmt->execute();
        return $reta;
    }
    //metodo para guardar el numero de rastreo de la caja
    public function updateNumeroRastreo($con,$sentencia,$num,$id)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("si",$num,$id);
        $reta = $stmt->execute();
        return $reta;
    }

    //metodo para actualizar medidas de una caja
    public function updateMedidaCaja($con,$sentencia,$data,$id)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("ddddi",$data['peso'],$data['alto'],$data['ancho'],$data['largo'],$id);
        $reta = $stmt->execute();
        return $reta;
    }

}

?>