<?php
// conexion con base de datos 
include '../conexion/conn.php';
require('../fpdf/fpdf.php');

// declarar array para respuestas 
$response = array();

// insertamos cabeceras para permisos 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type,Authorization, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: JSON");
header('Content-Type: application/json;charset=utf-8'); 



// validamos si hay conexion 
if($con){
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
              // para obtener un registro especifico
            if(isset($_GET['id'])){
                $sql = 'SELECT a.id_almacen,a.nombre_almacen,a.tipo,a.fecha_create,s.nombre_status FROM almacenes a INNER JOIN estados s ON a.status = s.id_status  where a.id_almacen="'.$_GET['id'].'"';
                $result = mysqli_query($con,$sql);
                $i=0;
                while($row = mysqli_fetch_assoc($result)){
                    $response['id_almacen'] = $row['id_almacen'];
                    $response['nombre_almacen'] = $row['nombre_almacen'];
                    $response['tipo'] = $row['tipo'];
                    $response['fecha_create'] = $row['fecha_create'];
                    $response['status'] = $row['nombre_status'];
                    $i++;
                }
            } else{
                 // es para obtener todos los registros 
                $sql = 'SELECT  *FROM caja_chica WHERE  fecha LIKE "'.$_GET['fecha_salida'].'%"';
                $result = mysqli_query($con,$sql);
                //Instaciamos la clase para genrear el documento pdf
                    $pdf=new FPDF();

                    //Agregamos la primera pagina al documento pdf
                    $pdf->AddPage();
                    //Seteamos el inicio del margen superior en 15 pixeles
                   // $y_axis_initial = 0;

                    //incluimos header de reportes    

                    $pdf->SetFont('Arial','B',12);

                    $pdf->Cell(40,6,'',0,0,'C');
                    $pdf->Cell(90,6,'Reporte Caja chica  '.$_GET['fecha_salida'].'',1,0,'C');

                    $pdf->Ln(10);

                    //Creamos las celdas para los titulo de cada columna y le asignamos un fondo gris y el tipo de letra
                    $pdf->SetFillColor(255,113,132);

                    $pdf->SetFont('Arial','B',10);
                    $pdf->Cell(20,6,'id',1,0,'C',1);

                    $pdf->Cell(80,6,'Motivo',1,0,'C',1);

                    $pdf->Cell(40,6,'Cantidad',1,0,'C',1);

                    $pdf->Cell(40,6,'Fecha',1,0,'C',1);

                    $pdf->Ln(6);

                    $total=0;

                    while($row = mysqli_fetch_assoc($result))
                    {
                        $id = $row['id'];
                        $motivo = $row['motivo'];
                        $cantidad = $row['monto'];
                        $fecha = $row['fecha'];
                        $total = $total + $cantidad;

                        $pdf->Cell(20,8,$id,1,0,'C',0);

                        $pdf->Cell(80,8,utf8_decode($motivo),1,0,'C',0);

                        $pdf->Cell(40,8,utf8_decode($cantidad),1,0,'C',0);

                        $pdf->Cell(40,8,utf8_decode($fecha),1,0,'C',0);


                        //Muestro la iamgen dentro de la celda GetX y GetY dan las coordenadas actuales de la fila

                        //$pdf->Cell( 30, 15, $pdf->Image($imagen, $pdf->GetX()+5, $pdf->GetY()+3, 20), 1, 0, 'C', false );

                        $pdf->Ln(8);
                    }
                    $pdf->SetFont('Arial','B',12);
                    $pdf->Cell(90,6,'',0,0,'C');
                    $pdf->Cell(90,6,'Total salidas:$'.$total.'',1,0,'C');


                    //Mostramos el documento pdf
                    $pdf->Output();

             }
        }
        
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>

