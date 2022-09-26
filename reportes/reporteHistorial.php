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
              if(isset($_GET['id'])){

                $sqlNombre = 'SELECT nombre_almacen FROM almacenes WHERE id_almacen="'.$_GET['id'].'"';
                $result = mysqli_query($con,$sqlNombre);
                while($row = mysqli_fetch_assoc($result)){
                   $nombre_almacen = $row['nombre_almacen'];
                }

                $sql = 'SELECT *FROM  historial_registro_productos WHERE id_almacen="'.$_GET['id'].'" ORDER BY fecha_created DESC';
                $result = mysqli_query($con,$sql);
                

                $pdf=new FPDF();

                //Agregamos la primera pagina al documento pdf
                $pdf->AddPage();
                //Seteamos el inicio del margen superior en 15 pixeles
               // $y_axis_initial = 0;

                //incluimos header de reportes    

                $pdf->SetFont('Arial','B',12);

                $pdf->Cell(30,6,'',0,0,'C');
                $pdf->Cell(130,6, utf8_decode('Historial traspaso de productos de almacen:'.$nombre_almacen.' '),1,0,'C');

                $pdf->Ln(10);

                //Creamos las celdas para los titulo de cada columna y le asignamos un fondo gris y el tipo de letra
                $pdf->SetFillColor(255,113,132);

                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(25,6,'Codigo',1,0,'C',1);

                $pdf->Cell(90,6,'Producto',1,0,'C',1);

                $pdf->Cell(20,6,'Cantidad',1,0,'C',1);

                $pdf->Cell(50,6,'Fecha de registro',1,0,'C',1);

                $pdf->Ln(6);

                while($row = mysqli_fetch_assoc($result))
                {
                    $codigo = $row['codigo'];
                    $nombre = $row['nombre'];
                    $cantidad = $row['cantidad'];
                    $fecha = $row['fecha_created'];

                    $pdf->Cell(25,8,$codigo,1,0,'C',0);

                    $pdf->Cell(90,8,utf8_decode($nombre),1,0,'C',0);

                    $pdf->Cell(20,8,utf8_decode($cantidad),1,0,'C',0);

                    $pdf->Cell(50,8,utf8_decode($fecha),1,0,'C',0);


                    //Muestro la iamgen dentro de la celda GetX y GetY dan las coordenadas actuales de la fila

                    //$pdf->Cell( 30, 15, $pdf->Image($imagen, $pdf->GetX()+5, $pdf->GetY()+3, 20), 1, 0, 'C', false );

                    $pdf->Ln(8);
                }
                


                //Mostramos el documento pdf
                  $pdf->Output();


             }else if(isset($_GET['total'])){

                $sqlNombre = 'SELECT nombre_almacen FROM almacenes WHERE id_almacen="'.$_GET['total'].'"';
                $result = mysqli_query($con,$sqlNombre);
                while($row = mysqli_fetch_assoc($result)){
                   $nombre_almacen = $row['nombre_almacen'];
                }

                $sql = 'SELECT *FROM productos_almacenes WHERE id_almacen='.$_GET['total'].' ORDER BY cantidad';
                $result = mysqli_query($con,$sql);

                $pdf=new FPDF();

                //Agregamos la primera pagina al documento pdf
                $pdf->AddPage();
                //Seteamos el inicio del margen superior en 15 pixeles
               // $y_axis_initial = 0;

                $pdf->SetFont('Arial','B',12);

                $pdf->Cell(30,6,'',0,0,'C');
                $pdf->Cell(130,6, utf8_decode('Productos del  almacen:'.$nombre_almacen.' '),1,0,'C');

                $pdf->Ln(10);

                $pdf->SetFillColor(255,113,132);

                $pdf->SetFont('Arial','B',10);

                $pdf->Cell(25,6,'id',1,0,'C',1);

                $pdf->Cell(30,6,'codigo',1,0,'C',1);

                $pdf->Cell(90,6,'Producto',1,0,'C',1);

                $pdf->Cell(30,6,'Cantidad',1,0,'C',1);

                $pdf->Ln(6);

                while($row = mysqli_fetch_assoc($result))
                {
                    $id = $row['id'];
                    $codigo = $row['codigo'];
                    $nombre = $row['nombre'];
                    $cantidad = $row['cantidad'];

                    $pdf->Cell(25,8,$id,1,0,'C',0);

                    $pdf->Cell(30,8,utf8_decode($codigo),1,0,'C',0);

                    $pdf->Cell(90,8,utf8_decode($nombre),1,0,'C',0);

                    $pdf->Cell(30,8,utf8_decode($cantidad),1,0,'C',0);


                    //Muestro la iamgen dentro de la celda GetX y GetY dan las coordenadas actuales de la fila

                    //$pdf->Cell( 30, 15, $pdf->Image($imagen, $pdf->GetX()+5, $pdf->GetY()+3, 20), 1, 0, 'C', false );

                    $pdf->Ln(8);
                }
                $pdf->Output();

             } else{
                 // es para obtener todos los registros 


             }
        }
        
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>

