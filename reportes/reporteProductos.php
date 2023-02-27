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

                 // es para obtener todos los registros 
                 $consulta = 'SELECT p.id,p.nombre,p.codigo,i.ruta,i.a FROM productos p LEFT JOIN img i ON p.id = i.id_producto WHERE p.almacen > 0';
                 $result = mysqli_query($con,$consulta);
                 $i=0;
                 $flag=0;
                //Instaciamos la clase para genrear el documento pdf
                    $pdf=new FPDF('L','mm','A4');

                    //Agregamos la primera pagina al documento pdf
                    $pdf->AddPage();
                    //Seteamos el inicio del margen superior en 15 pixeles
                   // $y_axis_initial = 0;

                    //incluimos header de reportes    

                    $pdf->SetFont('Arial','B',12);

                    $pdf->Cell(40,6,'',0,0,'C');
                    $pdf->Cell(130,6,'LISTA DE productos',1,0,'C');

                    $pdf->Ln(10);

                    //Creamos las celdas para los titulo de cada columna y le asignamos un fondo gris y el tipo de letra
                    $pdf->SetFillColor(255,113,132);

                    $pdf->SetFont('Arial','B',10);

                    $pdf->Cell(20,6,'id',1,0,'C',1);

                    $pdf->Cell(60,6,'Nombre',1,0,'C',1);

                    $pdf->Cell(70,6,'Fecha created',1,0,'C',1);

                    $pdf->Cell(75,6,'Status',1,0,'C',1);

                    $pdf->Ln(6);

                    while($row = mysqli_fetch_assoc($result))
                    {
                        if($flag != $row['id']){
                            
                            $id = $row['id'];
                            $nombre = $row['nombre'];
                            $codigo = $row['codigo'];
                            $img = $row['a'];
                            //$cadena =str_replace(' ', '%', $img);

                            $pdf->Cell(20,8,$id,1,0,'C',0);

                            $pdf->Cell(50,8,utf8_decode($nombre),1,0,'C',0);

                            $pdf->Cell(60,8,utf8_decode($codigo),1,0,'C',0);

                            if(strpos($img, " ")){
                                $pdf->Cell(11,11, $pdf->Image('https://www.importadorasupernova.com/images/logo%20spring.png', $pdf->GetX(), $pdf->GetY(),11),1);
                            }else{
                                $pdf->Cell(11,11, $pdf->Image('https://www.importadorasupernova.com/imagenes/'.$id.'/'.$img.'', $pdf->GetX(), $pdf->GetY(),11),1);
                            }


                            // $pdf->Cell(65,8,$pdf->Link(10,8,10,10,"http://www.recetasparatodos.com.es"),1,0,'C',0);



                            $pdf->Cell(70,8,utf8_decode($img),1,0,'C',0);


                            //Muestro la iamgen dentro de la celda GetX y GetY dan las coordenadas actuales de la fila

                            //$pdf->Cell( 30, 15, $pdf->Image($imagen, $pdf->GetX()+5, $pdf->GetY()+3, 20), 1, 0, 'C', false );

                            $pdf->Ln(8);
                            $i++;
                        }
                        $flag = $row['id']; 
                    }
                    


                    //Mostramos el documento pdf
                      $pdf->Output();

        }
        
    //echo "Informacion".file_get_contents('php://input');

}else{
    echo "DB FOUND CONNECTED";
}
?>

