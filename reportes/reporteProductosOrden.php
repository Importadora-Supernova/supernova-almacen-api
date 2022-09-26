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
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
              // para obtener un registro especifico
              if(isset($_GET['orden'])){

                $cliente = '';
                $cantidades = 0;
                $total = 0;

                $sqlNombre = 'SELECT nombres,cantidad,total FROM folios WHERE orden="'.$_GET['orden'].'"';
                $result = mysqli_query($con,$sqlNombre);

                while($row = mysqli_fetch_assoc($result)){
                   $cliente = $row['nombres'];
                   $cantidades = $row['cantidad'];
                   $total = $row['total'];
                }

                $sql = 'SELECT p.id,p.nombre,p.codigo,r.precio,r.cantidad,r.fecha,f.orden FROM productos p INNER JOIN registro_usuario r ON p.id = r.id_producto INNER JOIN folios f ON r.orden = f.orden where r.orden="'.$_GET['orden'].'"';
                $result = mysqli_query($con,$sql);
                $i=0;

             
                //Instaciamos la clase para genrear el documento pdf
                    $pdf=new FPDF();

                    //Agregamos la primera pagina al documento pdf
                    $pdf->AddPage();
                    //Seteamos el inicio del margen superior en 15 pixeles
                   // $y_axis_initial = 0;

                    //incluimos header de reportes    
                    $pdf->SetFont('Arial','B',11);
                    $pdf->Cell(5,15);
                    $pdf->Ln(2);
                    $pdf->Write(3,utf8_decode('Numero de Orden:'.$_GET['orden'].''));
                    $pdf->Ln(5);
                    $pdf->Write(3,utf8_decode('Cliente:'.$cliente.''));
                    $pdf->Ln(5);
                    $pdf->Write(3,utf8_decode('Cantidades:'.$cantidades.' Productos'));
                    $pdf->Ln(5);
                    $pdf->Write(3,utf8_decode('Total:'.$total.'$'));
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial','B',12);
                    $pdf->Cell(30,6,'',0,0,'C');
                    $pdf->Cell(140,6,'LISTA DE PRODUCTOS',1,0,'C');

                    $pdf->Ln(10);

                    //Creamos las celdas para los titulo de cada columna y le asignamos un fondo gris y el tipo de letra
                    $pdf->SetFillColor(255,113,132);

                    $pdf->SetFont('Arial','B',10);

                    $pdf->Cell(10,6,'#',1,0,'C',1);

                    $pdf->Cell(20,6,'id',1,0,'C',1);

                    $pdf->Cell(90,6,'Producto',1,0,'C',1);

                    $pdf->Cell(30,6,'codigo',1,0,'C',1);

                    $pdf->Cell(20,6,'Precio',1,0,'C',1);

                    $pdf->Cell(20,6,'Cantidad',1,0,'C',1);

                    $pdf->Ln(6);
                    $i=1;
                    while($row = mysqli_fetch_assoc($result))
                    {
                        $id = $row['id'];
                        $nombre = $row['nombre'];
                        $codigo = $row['codigo'];
                        $precio = $row['precio'];
                        $cantidad = $row['cantidad'];

                        $pdf->Cell(10,8,$i,1,0,'C',0);

                        $pdf->Cell(20,8,$id,1,0,'C',0);

                        $pdf->Cell(90,8,utf8_decode($nombre),1,0,'C',0);

                        $pdf->Cell(30,8,utf8_decode($codigo),1,0,'C',0);

                        $pdf->Cell(20,8,utf8_decode($precio),1,0,'C',0);

                        $pdf->Cell(20,8,utf8_decode($cantidad),1,0,'C',0);


                        //Muestro la iamgen dentro de la celda GetX y GetY dan las coordenadas actuales de la fila

                        //$pdf->Cell( 30, 15, $pdf->Image($imagen, $pdf->GetX()+5, $pdf->GetY()+3, 20), 1, 0, 'C', false );

                        $pdf->Ln(8);

                        $i++;
                    }
                    


                    //Mostramos el documento pdf
                      $pdf->Output();
            }
        }
        
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>

