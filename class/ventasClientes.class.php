<?php

class pedidoCliente{

    public function __construct()
    {
        
    }
    
    public function getPedidosCliente($con,$sql,$fecha)
    {
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s",$fecha);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }
    
}

?>