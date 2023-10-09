<?php

class FilePedido{
    
    public function __construct()
    {

    }

    public function getFilePedido($con,$sentencia){
        
    }

    public function registerPago($con,$sentencia,$orden,$monto,$nota_pago,$factura,$razon_social,$direccion,$colonia,$ciudad,$estado,$codigo_postal,$telefono,$rfc,$correo,$concepto,$cfdi,$forma_pago,$fecha_register)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('sdsisssssssssssss',$orden,$monto,$nota_pago,$factura,$razon_social,$direccion,$colonia,$ciudad,$estado,$codigo_postal,$telefono,$rfc,$correo,$concepto,$cfdi,$forma_pago,$fecha_register);
        $reta = $stmt->execute();
        return $reta;
    }

    public function uploadFile($con,$sentencia,$archivo,$orden,$tipo,$fecha)
    {
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('ssss',$archivo,$orden,$tipo,$fecha);
        $reta = $stmt->execute();
        return $reta;
    }
} 
?>