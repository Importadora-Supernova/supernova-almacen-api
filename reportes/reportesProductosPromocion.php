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

                $cliente = '';
                $cantidades = 0;
                $total = 0;
                $sql = 'SELECT *FROM productos_promocion ';
                $result = mysqli_query($con,$sql);
                $i=0;

             
                //Instaciamos la clase para genrear el documento pdf
                    $pdf=new FPDF();

                    //Agregamos la primera pagina al documento pdf
                    $pdf->AddPage();
                    //Seteamos el inicio del margen superior en 15 pixeles
                   // $y_axis_initial = 0;

                    $pdf->Ln(6);
                    $i=1;
                    while($row = mysqli_fetch_assoc($result))
                    {
                        //$id = $row['id'];
                        $codigo = $row['codigo'];
                        $imagen = $row['ruta'].$row['a'];
                        $nombre = $row['nombre'];
                        $precio = $row['descuento_precio_docena'];

                        $pdf->Cell(10,8,$i,1,0,'C',0);

                        $pdf->Cell(20,8,$id,1,0,'C',0);

                        $pdf->Cell(90,8,utf8_decode($codigo),1,0,'C',0);

                        $pdf->Image('https://www.importadorasupernova.com/'.$imagen.'', 162 ,6, 42 , 30,'PNG');

                        $pdf->Cell(20,8,utf8_decode($nombre),1,0,'C',0);

                        $pdf->Cell(20,8,utf8_decode($precio),1,0,'C',0);


                        //Muestro la iamgen dentro de la celda GetX y GetY dan las coordenadas actuales de la fila

                        //$pdf->Cell( 30, 15, $pdf->Image($imagen, $pdf->GetX()+5, $pdf->GetY()+3, 20), 1, 0, 'C', false );

                        $pdf->Ln(8);

                        $i++;
                    }
                    


                    //Mostramos el documento pdf
                      $pdf->Output();
            }
        
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>

