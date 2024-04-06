<?php

class Productos{

    public function __construct()
    {
        
    }

    //metodo para obtener pedidos segun busqueda, entregas en bodegas, listos , o envios
    public function  getProductoDescription($con,$orden,$codigo,$subStrng){
        $total = 0;
        $sql = 'SELECT DISTINCT(r.id_producto),d.id_almacen,d.cantidad as cantidad_almacen,d.orden,r.nombre,r.codigo,r.id_producto,a.nombre_almacen FROM registro_usuario r INNER JOIN almacen_descuentos d ON r.id_producto = d.id_producto AND r.orden = d.orden INNER JOIN almacenes a ON d.id_almacen = a.id_almacen WHERE d.orden=? AND codigo=?';
        $stmt =  $con->prepare($sql);
        $stmt->bind_param('ss',$orden,$codigo);
        $stmt->execute();
        $result = $stmt->get_result();
        $data   = $result->fetch_all(MYSQLI_ASSOC);
        $res    = count($data);
        $template = '';
        if($res > 0){
            $template .='<tr style="background:#dbdbdb;"><td colspan="2"><b>Le Mussa ('.$codigo.')</b></td><td>Ubicacion</td></tr>';
            foreach($data as $row){
                $total = $total + intval($row['cantidad_almacen']);
                $template .='<tr><td style="width: 300px;">'.substr($row['nombre'],$subStrng).'</td><td style="width: 50px;">'.$row['cantidad_almacen'].'</td><td><div style="width: 65px;position: absolute;text-align: initial;">'.$row['nombre_almacen'].'</div><div style="margin-left: 50px;">N°'.$row['cantidad'].'</div></td></tr>';
            }
            $template .= '<tr style="background:#dbdbdb;"><td colspan="3"><b>Total</b> '.$total.'</td></tr>';
            $template .= '<tr style="height: 5px;"><td colspan="3"></td></tr>';
        }
        return $template; 
    }


}