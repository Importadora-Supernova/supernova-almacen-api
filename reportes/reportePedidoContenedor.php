<?php
// conexion con base de datos 
include '../conexion/conn.php';
require('../fpdf/fpdf.php');

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
        $nombre_almacen ='';
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
              // para obtener un registro especifico

                $sqlNombre = 'SELECT c.id_pedido_contenedor as id,c.num_contenedor,c.num_pedido,c.proveedor as id_proveedor,p.nombre_proveedor,c.almacen as id_almacen,a.nombre_almacen,c.estatus,c.fecha_register,c.fecha_recibido FROM admin_pedido_contenedor c INNER JOIN admin_proveedores p ON c.proveedor = p.id_proveedor INNER JOIN almacenes a ON c.almacen = a.id_almacen WHERE c.id_pedido_contenedor='.$_GET['id'].'';

                $result = mysqli_query($con,$sqlNombre);

                while($row = mysqli_fetch_assoc($result)){
                    $contenedor       = $row['num_contenedor'];
                    $estatus          = $row['estatus'];
                    $nombre_proveedor = $row['nombre_proveedor'];
                    $almacen          = $row['nombre_almacen'];
                    $pedido           = $row['num_pedido'];
                }

                $sql = 'SELECT d.id_detalle_pedido_contenedor,d.pedido,d.codigo_producto,d.nombre_producto,d.pz_caja,d.num_cajas,d.nuevo,d.id_producto,d.id_proveedor,p.nombre_proveedor FROM detalle_pedido_contenedor d INNER JOIN admin_proveedores p ON d.id_proveedor = p.id_proveedor WHERE d.pedido='.$_GET['id'].'';
                $result = mysqli_query($con,$sql);
                

                $pdf=new FPDF();

                //Agregamos la primera pagina al documento pdf
                $pdf->AddPage();
                //Seteamos el inicio del margen superior en 15 pixeles
               // $y_axis_initial = 0;

                //incluimos header de reportes    

                $pdf->SetFont('Arial','',11);
                $pdf->Write(3,utf8_decode('N° Contenedor:  '.$contenedor.''),1,0,'C');
                $pdf->Ln(5);
                $pdf->Write(3,utf8_decode('Proveedor:  '.$nombre_proveedor.''),1,0,'C');
                $pdf->Ln(5);
                $pdf->Write(3,utf8_decode('Almacen:  '.$almacen.''),1,0,'C');
                $pdf->Ln(5);
                $pdf->Write(3,utf8_decode('N° Pedido:  '.$pedido.''),1,0,'C');
                $pdf->Ln(5);
                $pdf->Write(3,utf8_decode('Estatus:  '.$estatus.''),1,0,'C');
                $pdf->Ln(10);

                //Creamos las celdas para los titulo de cada columna y le asignamos un fondo gris y el tipo de letra
                $pdf->SetFillColor(255,113,132);

                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(25,6,'Codigo',1,0,'C',1);

                $pdf->Cell(90,6,'Nombre',1,0,'C',1);

                $pdf->Cell(25,6,'Cajas',1,0,'C',1);

                $pdf->Cell(25,6,'Piezas',1,0,'C',1);

                $pdf->Cell(25,6,'Total',1,0,'C',1);

                $pdf->Ln(6);
                $total_cajas=0;
                while($row = mysqli_fetch_assoc($result))
                {
                    $codigo   = $row['codigo_producto'];
                    $nombre   = $row['nombre_producto'];
                    $cajas    = $row['num_cajas'];
                    $piezas   = $row['pz_caja'];
                    $total    = $cajas*$piezas;

                    $total_cajas += $cajas;

                    $pdf->Cell(25,8,$codigo,1,0,'C',0);

                    $pdf->Cell(90,8,utf8_decode($nombre),1,0,'C',0);

                    $pdf->Cell(25,8,utf8_decode($cajas),1,0,'C',0);

                    $pdf->Cell(25,8,utf8_decode($piezas),1,0,'C',0);

                    $pdf->Cell(25,8,utf8_decode($total),1,0,'C',0);


                    //Muestro la iamgen dentro de la celda GetX y GetY dan las coordenadas actuales de la fila

                    //$pdf->Cell( 30, 15, $pdf->Image($imagen, $pdf->GetX()+5, $pdf->GetY()+3, 20), 1, 0, 'C', false );

                    $pdf->Ln(8);
                }
                $pdf->Ln(8);
                $pdf->SetFont('Arial','',11);
                $pdf->Write(3,utf8_decode('Total cajas:  '.$total_cajas.' cajas'),1,0,'C');


                //Mostramos el documento pdf
                $pdf->Output();
        }
        
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>

