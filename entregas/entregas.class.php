<?php

class Entregas{

    public function __construct()
    {
        
    }
     
    /**
     * Funcion para obtener las entregas
     * @param $con
     * @param $fecha
     * @return array
     */
    public function getEntregas($con, $fecha = null) {
        if($fecha) {
            $sql = "SELECT * FROM view_pedidos_entregas WHERE date(fecha_entrega) = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $fecha);
        } else {
            $sql = "SELECT * FROM view_pedidos_entregas ORDER BY fecha_entrega DESC";
            $stmt = $con->prepare($sql);
        }
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $con->error);
        }
        $stmt->execute();

        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    /**
     * Funcion para entregar una orden
     * @param $con
     * @param $id
     * @param $nota
     * @return bool
     */
    public function entregarOrden($con, $id, $nota) {
        try {
            // Verificar que la conexión sea válida
            if (!$con || $con->connect_error) {
                throw new Exception("Error de conexión a la base de datos");
            }
            
            // Verificar que el ID sea válido
            if (!is_numeric($id)) {
                throw new Exception("ID inválido");
            }
            
            $query = "UPDATE entregas_pedidos SET status = ?, entregado = ?, nota = ?, fecha_entregado = NOW() WHERE id = ?";
            $status = 'Entregado';
            $entregado = 1;
            
            $stmt = $con->prepare($query);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $con->error);
            }
            
            $stmt->bind_param("sisi", $status, $entregado, $nota, $id);
            $result = $stmt->execute();
            
            if (!$result) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            
            return $result;
        } catch (Exception $e) {
            // Registrar el error para depuración
            error_log("Error en entregarOrden: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Función para guardar una imagen y registrar su URL en la base de datos
     * @param object $con Conexión a la base de datos
     * @param int $id ID de la entrega
     * @param array $file Archivo de imagen
     * @param string $orden Número de orden
     * @return bool|int Resultado de la operación
     */
    public function registrarEntregaConImagen($con, $id, $file, $orden) {
        try {
            // Verificar que la conexión sea válida
            if ($con && $con->errno === 0) {
                $con->rollback();
            }
            
            // Crear directorio base si no existe
            $directorio_base = "imagenes_entregas/";
            if (!file_exists($directorio_base)) {
                mkdir($directorio_base, 0777, true);
            }
            
            // Crear directorio específico para la orden
            $directorio_orden = $directorio_base . $orden . "/";
            if (!file_exists($directorio_orden)) {
                mkdir($directorio_orden, 0777, true);
            }
            
            // Procesar la imagen
            $file_name = time() . '_' . $file['name'];
            $file_tmp = $file['tmp_name'];
            $file_path = $directorio_orden . $file_name;
            
            // Mover el archivo al directorio
            if (!move_uploaded_file($file_tmp, $file_path)) {
                throw new Exception("Error al subir la imagen");
            }
            
            // URL completa de la imagen
            $url_imagen = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . 
            dirname($_SERVER['PHP_SELF']) . '/' . $file_path;
            
            // Iniciar transacción
            $con->begin_transaction();

            $query = "INSERT INTO entregas_imagenes( register_id, url_image, created_at ) VALUES (?, ? , NOW())";
            $stmt = $con->prepare($query);

            $stmt->bind_param("is",$id,$url_imagen);
            
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $con->error);
            }
            
            $result = $stmt->execute();
            
            if (!$result) {
                // Rollback en caso de error
                $con->rollback();
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            
            // Confirmar transacción
            $con->commit();
            
            return $result;
            
        } catch (Exception $e) {
            // Rollback en caso de error
            if ($con->connect_error === null) {
                $con->rollback();
            }
            
            // Registrar el error para depuración
            error_log("Error en registrarEntregaConImagen: " . $e->getMessage());
            return false;
        }
    }

    public function getImagenesEntrega($con, $id) {
        try {
            // Verificar que la conexión sea válida
            if (!$con || $con->connect_error) {
                throw new Exception("Error de conexión a la base de datos");
            }
            
            // Verificar que el ID sea válido
            if (!is_numeric($id)) {
                throw new Exception("ID inválido");
            }
            
            $query = "SELECT * FROM entregas_imagenes WHERE register_id = ?";
            $stmt = $con->prepare($query);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $con->error);
            }
            
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();
            
            if (!$result) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            return $data;
        } catch (Exception $e) {
            // Registrar el error para depuración
            error_log("Error en getImagenesEntrega: " . $e->getMessage());
            return false;
        }
    }



}

