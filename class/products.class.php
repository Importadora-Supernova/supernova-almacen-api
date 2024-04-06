<?php

    class Producto
    {

        public function __construct()
        {
            
        }

        public function getAllProductsCategory($con,$category)
        {
            $sql = 'SELECT p.*, ip.ruta_image FROM view_products_categoria p LEFT JOIN ( SELECT id_producto, MAX(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto WHERE p.id_categoria=?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$category);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $con->close();
            return $data;
        }
    }
?>