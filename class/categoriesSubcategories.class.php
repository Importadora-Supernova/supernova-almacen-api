<?php

    class categoriesSubcategoies
    {
        public function __construct()
        {

        }

        public function getAllCategories($con)
        {   
            $sql = 'SELECT  c.id_categoria,c.nombre_categoria,c.estatus_categoria,c.id_depa,c.fecha_created,d.nombre_departamento FROM admin_categorias c INNER JOIN admin_departamentos d ON c.id_depa = d.id_departamento';
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data; 
        }

        public function getSubcategoriesCategoryId($con,$idCategory)
        {
            $sql = 'SELECT *FROM admin_subcategorias WHERE id_categoria=?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i',$idCategory);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data; 
        }

    }

    
?>