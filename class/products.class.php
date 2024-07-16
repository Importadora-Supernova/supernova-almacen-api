<?php

    class Producto
    {

        public function __construct()
        {
            
        }

        public function getAllProductsCategory($con,$category)
        {
            $sql = 'SELECT p.*, ip.ruta_image FROM view_products_categoria p LEFT JOIN ( SELECT id_producto, MIN(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto WHERE p.id_categoria=?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$category);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $con->close();
            return $data;
        }

        public function getProductId($con,$id,$id_user)
        {
            $sql = "SELECT p.*, CASE WHEN pfu.id_user IS NOT NULL THEN 'Si'  ELSE 'No' END AS en_favoritos FROM productos p LEFT JOIN (SELECT DISTINCT id_product,id_user  FROM products_favorite_user WHERE id_user=?) AS pfu ON p.id = pfu.id_product WHERE p.id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ii',$id_user,$id);
            $stmt->execute();
            $result = $stmt->get_result(); 
            $data = $result->fetch_assoc(); 
            return $data;
        }

        public function getImagesProduct($con,$id)
        {
            $sql = 'SELECT *FROM img WHERE id_producto=?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        }

        public function getProductsSubcategory($con,$subcategory)
        {
            $sql = 'SELECT p.*, ip.ruta_image FROM view_products_categoria p LEFT JOIN ( SELECT id_producto, MAX(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto WHERE p.sub_categoria=? ORDER BY RAND() LIMIT 10';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$subcategory);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        }

        public function getAtributesProductId($con,$id)
        {
            $sql ='SELECT a.id_admin_atributos_caracteristica as id_atributo, p.id_admin_atributos_productos as id_register, a.nombre_atributo,a.preffix,a.id_caracteristica,c.nombre_caracteristica,p.id_producto,p.valor_atributo FROM admin_atributos_productos p INNER JOIN admin_atributos_caracteristica a ON p.id_atributo = a.id_admin_atributos_caracteristica INNER JOIN admin_caracteristicas c ON a.id_caracteristica = c.id_admin_caracteristica WHERE p.id_producto=? ORDER BY a.id_caracteristica';

            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $con->close();
            return $data;
        }

        public function getAllProductsSubcategory($con,$subcategory)
        {
            $sql = 'SELECT p.*, ip.ruta_image FROM productos p LEFT JOIN ( SELECT id_producto, MAX(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto WHERE p.sub_categoria=?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$subcategory);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $con->close();
            return $data;
        }

        //obtener productos con sus medidas
        public function getMedidasProducts($con,$codigos)
        {
            $sql = "SELECT codigo_product,alto_pz * largo_pz * ancho_pz AS volumen_caja FROM admin_medidas_productos WHERE codigo_product IN ($codigos)";
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        }

        //obtener productos en oferta
        public function getProductsOferta($con)
        {
            $sql = 'SELECT p.*, ip.ruta_image FROM view_products_categoria p LEFT JOIN ( SELECT id_producto, MAX(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto WHERE p.descuento="si" ORDER BY RAND() LIMIT 20';
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        }

        //obtener productos en oferta
        public function getProductsOfertaTodos($con)
        {
            $sql = 'SELECT p.*, ip.ruta_image FROM view_products_categoria p LEFT JOIN ( SELECT id_producto, MAX(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto WHERE p.descuento="si"  LIMIT 60';
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        }

         //obtener productos en oferta
        public function getProductsNuevos($con)
        {
            $sql = 'SELECT p.*, ip.ruta_image FROM view_products_categoria p LEFT JOIN ( SELECT id_producto, MAX(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto WHERE p.nuevo="si" ORDER BY  p.id DESC LIMIT 20 ';
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        }

        
         //obtener productos en oferta
        public function getProductsNuevosTodos($con)
        {
            $sql = 'SELECT p.*, ip.ruta_image FROM view_products_categoria p LEFT JOIN ( SELECT id_producto, MAX(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto WHERE p.nuevo="si" ORDER BY  p.id DESC LIMIT 8 0 ';
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        }

           //obtener productos en oferta
            public function getProductsMasVistos($con)
            {
                $sql = 'SELECT p.*, ip.ruta_image FROM view_products_categoria p LEFT JOIN ( SELECT id_producto, MAX(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto  ORDER BY  p.visitas DESC LIMIT 20';
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);
                return $data;
            }
            //obtener productos en oferta
            public function getProductsMasVistosTodos($con)
            {
                $sql = 'SELECT p.*, ip.ruta_image FROM view_products_categoria p LEFT JOIN ( SELECT id_producto, MAX(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto  ORDER BY  p.visitas DESC LIMIT 50';
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);
                return $data;
            }

            //obtener productos para busqueda
            public function getProductsSearch($con)
            {
                $sql = 'SELECT id,codigo,nombre,descripcion FROM productos';
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);
                return $data;
            }

             //obtener productos para busqueda
            public function getCodigosAll($con)
            {
                $sql = 'SELECT SELECT codigo FROM productos GROUP BY codigo ORDER BY id';
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);
                return $data;
            }
    }
?>