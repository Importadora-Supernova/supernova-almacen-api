<?php
//require_once '../conexion/conn.php';
class Querys{

    public $response = array(
        "estatus" => '',
        "message" => ''
    );

    public function  __construct(){

    }

    public function  getQuery($con,$sentencia){
        $stmt =  $con->prepare($sentencia);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data; 
    }

    public function getQueryIdArray($con,$sentencia,$id){
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data; 
    }

    public function getQueryIdArrayAtrr($con,$sentencia,$id_1,$id_2){
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("ii", $id_1,$id_2);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data; 
    }

    public function getQueryId($con,$sentencia,$id){
        $stmt = $con->prepare($sentencia); 
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result(); 
        $data = $result->fetch_assoc(); 
        return $data;
    }

    public function insertData($con,$sentencia,$id,$datos,$fecha){ 
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        if($stmt){
            if($stmt->bind_param('iiis',$id,$datos['id'],$datos['id_caracteristica'],$fecha)){
                $reta = $stmt->execute(); 
            }
        }
        
        return $reta;
    }

    public function deleteRegister($con,$sentencia,$id){
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('i',$id);
        $result = $stmt->execute();
        return $result;
    }
    
    public function insertAtributosProducto($con,$sentencia,$producto,$atributo,$valor,$fecha){
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        if($stmt){
            if($stmt->bind_param('iiss',$producto,$atributo,$valor,$fecha)){
                $reta = $stmt->execute();
            }
        }
        return $reta;
    }

    public function updateAtributos($con,$sentencia,$valor,$id){
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        if($stmt){
            if($stmt->bind_param('si',$valor,$id)){
                $reta = $stmt->execute();
            }
        }
        return $reta;
    }

    //funcion para insertar proveedor
    public function insertProveedor($con,$sentencia,$data,$fecha){
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('sis',$data['nombre_proveedor'],$data['estatus'],$fecha);
        $reta = $stmt->execute();
        return $reta;
    }

    //funcion para actualizar registro proveedor
    public function updateProveedor($con,$sentencia,$data,$id){
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('sii',$data['nombre_proveedor'],$data['estatus'],$id);
        $reta = $stmt->execute();
        return $reta;
    }

    //insertar tasa de cambio
    public function insertarTasaCambio($con,$sentencia,$tasa,$fecha){
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('ds',$tasa,$fecha);
        $reta = $stmt->execute();
        return $reta;
    }

    //obtener ultimo registro de tabla de actualizacion de tasa
    public function getTasaCambio($con,$sentencia){
        $stmt = $con->prepare($sentencia); 
        $stmt->execute();
        $result = $stmt->get_result(); 
        $data = $result->fetch_assoc(); 
        return $data;
    }

    //obtener productos segun codigo
    public function getItemsCodigo($con,$sentencia,$codigo){
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('s',$codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    //actualizar precios costo productos
    public function updatePrecioCosto($con,$sentencia,$precio_costo,$precio_yuan,$id){
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('sdi',$precio_costo,$precio_yuan,$id);
        $reta = $stmt->execute();
        return $reta;
    }

    //insertar proveedores a producto
    public function insertarProveedoresProducto($con,$sentencia,$codigo,$data,$id,$fecha){
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('siiddds', $codigo,$data['id_proveedor'],$id,$data['precio_costo'],$data['precio_yuan'],$data['porcentaje_flete'],$fecha);
        $reta = $stmt->execute();   
        return $reta;
    }

    //insertar historial proveedor
    public function insertarHistorialProveedor($con,$sentencia,$data,$tasa,$codigo,$fecha){
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('isdddds',$data['id_proveedor'],$codigo,$data['precio_costo'],$data['precio_yuan'],$tasa,$data['porcentaje_flete'],$fecha);
        $reta = $stmt->execute();
        return $reta;
    }

    //buscar registro por codigo y por proveedor
    public function searchProductoProveedor($con,$sentencia,$codigo,$proveedor){
        $stmt = $con->prepare($sentencia); 
        $stmt->bind_param("si", $codigo,$proveedor);
        $stmt->execute();
        $result = $stmt->get_result(); 
        $data = $result->fetch_assoc(); 
        return $data;
    }

    //actualizar regiustros de proveedores
    public function updateProductoProveedor($con,$sentencia,$data,$codigo){
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("dddsi",$data['precio_costo'],$data['precio_yuan'],$data['porcentaje_flete'],$codigo,$data['id_proveedor']);
        $reta = $stmt->execute();
        return $reta;
    }

    public function updateOrdenUsuario($con,$sentencia,$orden,$id){
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param("si",$orden,$id);
        $reta = $stmt->execute();
        $stmt->store_result();
        return $reta;
    }

    public function insertRegistroUsuario($con,$sentencia,$data,$id,$orden,$fecha){
        $reta = 0;
        $stmt = $con->prepare($sentencia);
        $stmt->bind_param('iississ',$id,$data['id'],$data['codigo'],$data['precio'],$data['cantidad'],$fecha,$orden);
        $reta = $stmt->execute();
        $stmt->store_result();
        return $reta;
    }
}


?>