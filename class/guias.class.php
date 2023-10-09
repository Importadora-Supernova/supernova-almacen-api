<?php

class Guias{

    public function __construct()
    {
        
    }

    //metodo para obtener pedidos segun busqueda, entregas en bodegas, listos , o envios
    public function  getPedidosEstatus($con,$sentencia){
        $stmt =  $con->prepare($sentencia);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data; 
    }

    public function  getCajasOrden($con,$sentencia,$orden){
        $stmt =  $con->prepare($sentencia);
        $stmt->bind_param("s",$orden);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data; 
    }

    public function getRastreoOrden($con,$sentencia,$orden){
        $stmt = $con->prepare($sentencia); 
        $stmt->bind_param("s", $orden);
        $stmt->execute();
        $result = $stmt->get_result(); 
        $data = $result->fetch_assoc(); 
        return $data;
    }

    //metodo para insertar guia despues de tomadas las medidas
    public function insertGuia($con,$sentencia,$orden,$fecha,$paqueteria)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("sss",$orden,$fecha,$paqueteria);
        $reta = $stmt->execute();
        return $reta;
    }

    //metodo para insertar las medidas segun el numero de cajas
    public function insertMedidas($con,$sentencia,$orden,$fecha,$data)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("ssssss",$data['altura'],$data['largo'],$data['ancho'],$data['peso'],$orden,$fecha);
        $reta = $stmt->execute();
        return $reta;
    }

    //metodo para insertar datos de otra paquetria
    public function insertOtraPaqueteria($con,$sentencia,$orden,$num,$paqueteria,$fecha)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("sssss",$orden,$fecha,$fecha,$paqueteria,$num);
        $reta = $stmt->execute();
        return $reta;
    }

    //metodo para actualizar folio en su marca
    public function updateMarcaGuia($con,$sentencia,$orden)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('s',$orden);
        $reta = $stmt->execute();
        return $reta;
    }

    //metodo para actualizar folio
    public function updateFolioEstatus($con,$sentencia,$orden)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('s',$orden);
        $reta = $stmt->execute();
        return $reta;
    }

    //metodo para actualizar archivo de guia
    public function updateFileGuia($con,$sentencia,$orden,$fecha,$monto,$file)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("ssss",$fecha,$monto,$file,$orden);
        $reta = $stmt->execute();
        return $reta;
    }

    //método para actualizar el numero de rastreo de las cajas
    public function updateNumeroRastreo($con,$sentencia,$num,$id)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("ss",$num,$id);
        $reta = $stmt->execute();
        return $reta;
    }

    
    

}