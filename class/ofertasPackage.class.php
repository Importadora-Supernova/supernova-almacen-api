<?php 

class OfertasPackages{

    public function __construct()
    {
        
        
    }
    //obtener productos por codigo
    public function getProductosCodigo($con,$codigo)
    {
        $query = "SELECT *FROM productos WHERE codigo=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('s',$codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;

    }

    //obtener los paquetes creados
    public function getPackages($con)
    {
        $query = "SELECT *FROM package_offers";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }


    //crear paquete de oferta
    public function createPackageOffers($con,$data)
    {
        $query = "CALL InsertPackageOffers(?,?,?)";
        $products = json_encode($data['productos'], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

        $stmt = $con->prepare($query);
        $stmt->bind_param('sis',$data['name_package'],$data['active'],$products);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    //OBTENER PRODUCTOS Y SUS VENTAS 
    public function getProductsOfferPackage($con,$id_package)
    {
        $query = "SELECT * FROM view_products_package_offers WHERE package_id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id_package);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }
    
    //agregar productos a un paquete ya creado
    function addProductPackage($con,$data)
    {
        $products = json_encode($data['productos'], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        $query = "CALL AddProductsPackageOffers(?,?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param('is',$data['id_package'],$products);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    //borrar un producto de un paquete 
    function deleteProductPackage($con,$codigo)
    {
        $query = "DELETE FROM details_package_offers WHERE codigo=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $codigo);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    //activar paquete de oferta
    function activePackageOffers($con,$id)
    {
        $query = "CALL ActiveOffersPackage(?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

        //desactivar paquete de oferta
        function desactivePackageOffers($con,$id)
        {
            $query = "CALL DesactiveOffersPackage(?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param('i', $id);
    
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }
}

?>