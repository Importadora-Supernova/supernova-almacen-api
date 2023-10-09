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


setlocale(LC_MONETARY, 'en_US.UTF-8');
// validamos si hay conexion 
if($con){
    
        $methodApi = $_SERVER['REQUEST_METHOD'];

        if($methodApi == 'GET'){
              // para obtener un registro especifico
                 $orden = $_GET['orden'];
                 // es para obtener todos los registros 
                 $consulta = 'SELECT *FROM registro_usuario WHERE orden="'.$orden.'"';
                 $result = mysqli_query($con,$consulta);
                 $i=0;
                 $flag=0;
                //Instaciamos la clase para genrear el documento pdf
                    $pdf=new FPDF('P','mm','A4');

                    //Agregamos la primera pagina al documento pdf
                    $pdf->AddPage();
                    //Seteamos el inicio del margen superior en 15 pixeles
                   // $y_axis_initial = 0;

                    //incluimos header de reportes    

                    $pdf->SetFont('Arial','B',11);

                    $pdf->Cell(40,6,'',0,0,'C');
                    $pdf->Cell(100,6,'Factura de orden:'.$orden.'',1,0,'C');

                    $pdf->Ln(10);

                    //Creamos las celdas para los titulo de cada columna y le asignamos un fondo gris y el tipo de letra
                    $pdf->SetFillColor(255,113,132);

                    $pdf->SetFont('Arial','B',9);

                    $pdf->Cell(25,6,'CODIGO',1,0,'C',1);

                    $pdf->Cell(100,6,'PRODUCTO',1,0,'C',1);

                    $pdf->Cell(20,6,'PIEZAS',1,0,'C',1);

                    $pdf->Cell(20,6,'PRECIO',1,0,'C',1);
                    $pdf->Cell(20,6,'TOTAL',1,0,'C',1);

                    $pdf->Ln(6);

                    $sub_total = 0;
                    $sub_iva   = 0;
                    $total_c     = 0;
                    while($row = mysqli_fetch_assoc($result))
                    {
                            
                            $codigo = $row['codigo'];
                            $nombre = $row['nombre'];
                            $cantidad = $row['cantidad'];
                            $precio = $row['precio'];
                            $precio_iva = $precio*1.05;
                            $total_compra = money_format('%.2n', $precio_iva);
                            $total = money_format('%.2n', $precio_iva*$cantidad);

                            $sub_total = $sub_total + (($precio_iva/1.16)*$cantidad);
                            $sub_iva   = $sub_iva + (($precio_iva-($precio_iva/1.16))*$cantidad);
                            $total_c     = $total_c + ($precio_iva*$cantidad);

                            //$cadena =str_replace(' ', '%', $img);

                            $pdf->Cell(25,8,$codigo,1,0,'C',0);

                            $pdf->Cell(100,8,utf8_decode($nombre),1,0,'C',0);

                            $pdf->Cell(20,8,utf8_decode($cantidad),1,0,'C',0);

                            $pdf->Cell(20,8,utf8_decode($total_compra),1,0,'C',0);
                            $pdf->Cell(20,8,utf8_decode($total),1,0,'C',0);


                            $pdf->Ln(8);
                            $i++;

                    }

                    $sub_total_precio = money_format('%.2n', $sub_total);
                    $sub_total_iva    = money_format('%.2n', $sub_iva);
                    $total_completo   = money_format('%.2n', $total_c);

                    $pdf->Ln(8);
                    $pdf->SetFont('Arial','',11);
                    $pdf->Cell(140,20);
                    $pdf->Write(3,utf8_decode('SUBTOTAL: '.$sub_total_precio.''));
                    $pdf->Ln(8);
                    $pdf->Cell(140,20);
                    $pdf->Write(3,utf8_decode('IVA 16%: '.$sub_total_iva.''));
                    $pdf->Ln(8);
                    $pdf->Cell(140,20);
                    $pdf->Write(3,utf8_decode('TOTAL: '.$total_completo.''));
                    


                    //Mostramos el documento pdf
                    $pdf->Output();

        }
        
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>

