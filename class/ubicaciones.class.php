<?php 

class Ubicaciones{

    public function __construct()
    {
        
        
    }

    public function getMunicipioEstado($con,$sentencia,$codigo_postal)
    {
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("i",$codigo_postal);
        $stmt->execute();
        $result = $stmt->get_result(); 
        $data = $result->fetch_assoc(); 
        return $data;
    }

    public function getColoniasCodigoPostal($con,$sentencia,$codigo_postal)
    {
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("i",$codigo_postal);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }
}

?>