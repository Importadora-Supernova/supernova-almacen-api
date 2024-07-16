<?php

  class PedidosClientes{

    public function __construct()
    {
        
    }

    public function getAllPedidosCliente($con,$id)
    {
        $sql = 'SELECT *FROM folios WHERE id_usuario=?';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $con->close();
        return $data;
    }

    public function createProductFavorite($con,$data,$fecha)
    {
        $reta = 0;
        $sql = 'INSERT INTO products_favorite_user (id_user,id_product,fecha_created) VALUES(?,?,?)';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('iis',$data['id_user'],$data['id_producto'],$fecha);
        $reta = $stmt->execute();
        return $reta;
    }

    
    public function getAllProductsFavorites($con,$id)
    {
        $sql = 'SELECT p.*, ip.ruta_image FROM view_products_categoria p LEFT JOIN (SELECT id_producto, MAX(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto LEFT JOIN products_favorite_user pfu ON p.id = pfu.id_product WHERE pfu.id_user=? ORDER BY p.id LIMIT 20';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $con->close();
        return $data;
    }

      //obtener productos en oferta
      public function getProductsCodigo($con,$codigo)
      {
          $sql = 'SELECT p.*, ip.ruta_image FROM view_products_categoria p LEFT JOIN ( SELECT id_producto, MAX(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto WHERE p.codigo=?';
          $stmt = $con->prepare($sql);
          $stmt->bind_param('s',$codigo);
          $stmt->execute();
          $result = $stmt->get_result();
          $data = $result->fetch_all(MYSQLI_ASSOC);
          return $data;
      }

      //REGISTRAR  producto search
      public function createSearchProduct($con,$data,$fecha)
      {
        $reta = 0;
        $sql = 'INSERT INTO searchs_users (id_user,text_search,codigo,fecha_search) VALUES (?,?,?,?)';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('isss',$data['user_id'],$data['nombre'],$data['codigo'],$fecha);
        $reta = $stmt->execute();
        return $reta;
      }

      //buscar si existe esta busqueda
         //obtener productos en oferta
        public function buscarBusquedaPalabra($con,$data)
        {
            $sql = 'SELECT *FROM searchs_users WHERE id_user=? AND text_search=?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('is',$data['user_id'],$data['nombre']);
            $stmt->execute();
            $result = $stmt->get_result(); 
            $data = $result->fetch_assoc(); 
            return $data;
        }

        public function buscarProductWord($con,$palabra)
        {
          $sql = 'SELECT p.*, ip.ruta_image FROM view_products_categoria p LEFT JOIN ( SELECT id_producto, MAX(a) AS ruta_image FROM img GROUP BY id_producto ) AS ip ON p.id = ip.id_producto WHERE p.codigo LIKE ? OR p.nombre LIKE ? OR p.descripcion LIKE ?';
          $stmt = $con->prepare($sql);
          $stmt->bind_param('sss',$palabra,$palabra,$palabra);
          $stmt->execute();
          $result = $stmt->get_result();
          $data = $result->fetch_all(MYSQLI_ASSOC);
          return $data;
        }

         //REGISTRAR  producto search
      public function createSearchWord($con,$data,$fecha)
      {
        $reta = 0;
        $sql = 'INSERT INTO searchs_users (id_user,text_search,fecha_search) VALUES (?,?,?)';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('iss',$data['user_id'],$data['nombre'],$fecha);
        $reta = $stmt->execute();
        return $reta;
      }

      public function getSearchedWords($con,$id)
      {
        $sql = 'SELECT *FROM searchs_users WHERE id_user=? ORDER BY id_search DESC LIMIT 5';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
      }
    /* sentencia que me ayuda a traer el restock mas reciente de los productos favoritos 
   SELECT nombre_producto, fecha_created FROM ( SELECT p.nombre AS nombre_producto, r.fecha_created, ROW_NUMBER() OVER (PARTITION BY pfu.id_user, p.id ORDER BY r.fecha_created DESC) AS rn FROM products_favorite_user pfu INNER JOIN productos p ON pfu.id_product = p.id INNER JOIN historial_carga_producto r ON p.id = r.id_producto WHERE pfu.id_user = 5849 ) AS ranked_restock WHERE rn = 1;
    */

  }

?>