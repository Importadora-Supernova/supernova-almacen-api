<?php
require('fpdf.php');
include('../conexion.php');
$id_usuario = $_GET["id"];
$norden = $_GET["orden"];
$mussa = "Le'MUSSA";
class PDF extends FPDF
{

    //Pie de página
    function Footer()
    {


        global $norden;
        global $mussa;
        $this->SetY(-15);

        $this->SetFont('Arial', 'I', 8);

        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb} Orden: ' . $norden . '.', 0, 0, 'L');
        //$this->Cell(0,10,'Almacenista:____________ Hora inicio:___:___:___ Hora Final___:___:___ Peso_______ Firma___________',0,0,'R');
    }
}
$sqlxe = "SELECT * FROM registro_usuario where orden='$norden' LIMIT 0,1  ";
$resultxe = $mysqli->query($sqlxe);
while ($columnaxe = mysqli_fetch_array($resultxe)) {
    //$envio=$columnaxe['envio'];
    //$paqueteria=$columnaxe['paqueteria'];
    $rfc = $columnaxe['rfc'];
    $nombre_usuario = $columnaxe['nombred'];
    $apellido_usuario = $columnaxe['apellidod'];
    $direccion_usuario = $columnaxe['direcciond'];
    $colonia_usuario = $columnaxe['coloniad'];
    $ciudad_usuario = $columnaxe['ciudadd'];
    $estado_usuario = $columnaxe['estadod'];
    $codigop_usuario = $columnaxe['codigopd'];
    $telefono_usuario = $columnaxe['telefonod'];
    $fecha_hora = $columnaxe['fecha'];
}
$sqlxex = "SELECT * FROM folios where orden='$norden' LIMIT 0,1  ";
$resultxex = $mysqli->query($sqlxex);
while ($columnaxex = mysqli_fetch_array($resultxex)) {
    $envio = $columnaxex['envio'];
    $paqueteria = $columnaxex['paqueteria'];
}
$fecha_exacta = date('d-m-Y', strtotime($fecha_hora));
$hora_exacta = date('H:i:s', strtotime($fecha_hora));

$sm = '0';
$precio_gel = '0';
$smp1 = '0';
$precio_gelp1 = '0';
$smp2 = '0';
$precio_gelp2 = '0';
$smp3 = '0';
$precio_gelp3 = '0';
$smp4 = '0';
$precio_gelp4 = '0';
$smp5 = '0';
$precio_gelp5 = '0';
$smp6 = '0';
$precio_gelp6 = '0';
$smp7 = '0';
$precio_gelp7 = '0';
$smp8 = '0';
$precio_gelp8 = '0';
$smp9 = '0';
$precio_gelp9 = '0';

$sm10 = '0';
$precio_gel10 = '0';
$smp11 = '0';
$precio_gelp11 = '0';
$smp12 = '0';
$precio_gelp12 = '0';
$smp13 = '0';
$precio_gelp13 = '0';
$smp14 = '0';
$precio_gelp14 = '0';
$smp15 = '0';
$precio_gelp15 = '0';
$smp16 = '0';
$precio_gelp16 = '0';
$smp17 = '0';
$precio_gelp17 = '0';
$smp18 = '0';
$precio_gelp18 = '0';
$smp19 = '0';
$precio_gelp19 = '0';
$smp20 = '0';
$precio_gelp20 = '0';
$smp21 = '0';
$precio_gelp21 = '0';

$sqlxegp1 = "SELECT * FROM registro_usuario where orden='$norden' ";
$resultxegp1 = $mysqli->query($sqlxegp1);
while ($columnaxegp1 = mysqli_fetch_array($resultxegp1)) {
    $codigo = $columnaxegp1['codigo'];

    if ($codigo == 'UN-40') {
        $cantidad_gelp1 = $columnaxegp1['cantidad'];
        $precio_gelp1 = $columnaxegp1['precio'];
        $smp1 = $smp1 + $cantidad_gelp1;
    } else
                                    if ($codigo == 'UN-05') {
        $cantidad_gelp2 = $columnaxegp1['cantidad'];
        $precio_gelp2 = $columnaxegp1['precio'];
        $smp2 = $smp2 + $cantidad_gelp2;
    } else
                                    if ($codigo == 'UN-43') {
        $cantidad_gelp3 = $columnaxegp1['cantidad'];
        $precio_gelp3 = $columnaxegp1['precio'];
        $smp3 = $smp3 + $cantidad_gelp3;
    } else
                                    if ($codigo == 'PU-02') {
        $cantidad_gelp4 = $columnaxegp1['cantidad'];
        $precio_gelp4 = $columnaxegp1['precio'];
        $smp4 = $smp4 + $cantidad_gelp4;
    } else
                                    if ($codigo == 'PU-04') {
        $cantidad_gelp5 = $columnaxegp1['cantidad'];
        $precio_gelp5 = $columnaxegp1['precio'];
        $smp5 = $smp5 + $cantidad_gelp5;
    } else
                                    if ($codigo == 'PU-01') {
        $cantidad_gelp6 = $columnaxegp1['cantidad'];
        $precio_gelp6 = $columnaxegp1['precio'];
        $smp6 = $smp6 + $cantidad_gelp6;
    } else
                                    if ($codigo == 'PU-06') {
        $cantidad_gelp7 = $columnaxegp1['cantidad'];
        $precio_gelp7 = $columnaxegp1['precio'];
        $smp7 = $smp7 + $cantidad_gelp7;
    } else
                                    if ($codigo == 'PU-05') {
        $cantidad_gelp8 = $columnaxegp1['cantidad'];
        $precio_gelp8 = $columnaxegp1['precio'];
        $smp8 = $smp8 + $cantidad_gelp8;
    } else
                                    if ($codigo == 'PI-16') {
        $cantidad_gelp9 = $columnaxegp1['cantidad'];
        $precio_gelp9 = $columnaxegp1['precio'];
        $smp9 = $smp9 + $cantidad_gelp9;
    } else
                                    if ($codigo == 'UN-08') {
        $cantidad_gel = $columnaxegp1['cantidad'];
        $precio_gel = $columnaxegp1['precio'];
        $sm = $sm + $cantidad_gel;
    } else
                                    if ($codigo == 'GEL-01') {
        $cantidad_gel10 = $columnaxegp1['cantidad'];
        $precio_gel10 = $columnaxegp1['precio'];
        $sm10 = $sm10 + $cantidad_gel10;
    } else
                                    if ($codigo == 'GEL-12') {
        $cantidad_gel11 = $columnaxegp1['cantidad'];
        $precio_gel11 = $columnaxegp1['precio'];
        $sm11 = $sm11 + $cantidad_gel11;
    } else
                                    if ($codigo == 'GEL-13') {
        $cantidad_gel12 = $columnaxegp1['cantidad'];
        $precio_gel12 = $columnaxegp1['precio'];
        $sm12 = $sm12 + $cantidad_gel12;
    } else
                                    if ($codigo == 'GEL-11') {
        $cantidad_gel13 = $columnaxegp1['cantidad'];
        $precio_gel13 = $columnaxegp1['precio'];
        $sm13 = $sm13 + $cantidad_gel13;
    } else
                                    if ($codigo == 'GEL-14') {
        $cantidad_gel14 = $columnaxegp1['cantidad'];
        $precio_gel14 = $columnaxegp1['precio'];
        $sm14 = $sm14 + $cantidad_gel14;
    } else
                                    if ($codigo == 'GEL-15') {
        $cantidad_gel15 = $columnaxegp1['cantidad'];
        $precio_gel15 = $columnaxegp1['precio'];
        $sm15 = $sm15 + $cantidad_gel15;
    } else
                                    if ($codigo == 'GEL-18') {
        $cantidad_gel16 = $columnaxegp1['cantidad'];
        $precio_gel16 = $columnaxegp1['precio'];
        $sm16 = $sm16 + $cantidad_gel16;
    } else
                                    if ($codigo == 'GEL-17') {
        $cantidad_gel17 = $columnaxegp1['cantidad'];
        $precio_gel17 = $columnaxegp1['precio'];
        $sm17 = $sm17 + $cantidad_gel17;
    } else
                                    if ($codigo == 'GEL-22') {
        $cantidad_gel18 = $columnaxegp1['cantidad'];
        $precio_gel18 = $columnaxegp1['precio'];
        $sm18 = $sm18 + $cantidad_gel18;
    } else
                                    if ($codigo == 'GEL-23') {
        $cantidad_gel19 = $columnaxegp1['cantidad'];
        $precio_gel19 = $columnaxegp1['precio'];
        $sm19 = $sm19 + $cantidad_gel19;
    } else
                                    if ($codigo == 'GEL-25') {
        $cantidad_gel20 = $columnaxegp1['cantidad'];
        $precio_gel20 = $columnaxegp1['precio'];
        $sm20 = $sm20 + $cantidad_gel20;
    }
    if ($codigo == 'GEL-28') {
        $cantidad_gel21 = $columnaxegp1['cantidad'];
        $precio_gel21 = $columnaxegp1['precio'];
        $sm21 = $sm21 + $cantidad_gel21;
    }
}
$sumgel = $sm * $precio_gel;
$sumgelp1 = $smp1 * $precio_gelp1;
$sumgelp2 = $smp2 * $precio_gelp2;
$sumgelp3 = $smp3 * $precio_gelp3;
$sumgelp4 = $smp4 * $precio_gelp4;
$sumgelp5 = $smp5 * $precio_gelp5;
$sumgelp6 = $smp6 * $precio_gelp6;
$sumgelp7 = $smp7 * $precio_gelp7;
$sumgelp8 = $smp8 * $precio_gelp8;
$sumgelp9 = $smp9 * $precio_gelp9;

$sumgel10 = $sm10 * $precio_gel10;
$sumgel11 = $sm11 * $precio_gel11;
$sumgel12 = $sm12 * $precio_gel12;
$sumgel13 = $sm13 * $precio_gel13;
$sumgel14 = $sm14 * $precio_gel14;
$sumgel15 = $sm15 * $precio_gel15;
$sumgel16 = $sm16 * $precio_gel16;
$sumgel17 = $sm17 * $precio_gel17;
$sumgel18 = $sm18 * $precio_gel18;
$sumgel19 = $sm19 * $precio_gel19;
$sumgel20 = $sm20 * $precio_gel20;
$sumgel21 = $sm21 * $precio_gel21;




$pdf = new PDF;
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->Image('5.png', 10, 8, 27);
$pdf->SetFont('Arial', '', 8);
$pdf->SetY(8);
$pdf->Setx(115);
$pdf->Cell(35, 5, 'Importadora Supernova', 0, 0, 'R');
$pdf->ln(3.5);
$pdf->Setx(115);
$pdf->Cell(35, 5, 'RFC: ISU180223L27', 0, 0, 'R');
$pdf->ln(3.5);
$pdf->SetFont('Arial', '', 8);
$pdf->Setx(115);
$pdf->Cell(35, 5, utf8_decode('Centro, Calle artículo 123 #41'), 0, 0, 'R');
$pdf->ln(6);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Sety(8);
$pdf->Setx(150);
$pdf->Cell(40, 5, utf8_decode('Fecha: ' . $fecha_exacta . ''), 0, 0, 'L');
$pdf->ln(3);
$pdf->Setx(150);
$pdf->Cell(40, 5, utf8_decode('Hora: ' . $hora_exacta . ''), 0, 0, 'L');
$pdf->ln(3);
$pdf->Setx(150);
$pdf->Cell(40, 5, utf8_decode('Orden Nº: ' . $norden . ''), 0, 0, 'L');
$pdf->ln(4.5);
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetY(8);
$pdf->Setx(30);
$pdf->MultiCell(84, 4, utf8_decode('Nombre: ' . $nombre_usuario . " " . $apellido_usuario . ''), '0', 'C', 0);
$pdf->Setx(30);
$pdf->MultiCell(84, 4, utf8_decode('Dirección: ' . $direccion_usuario . ''), '0', 'C', 0);
$pdf->Setx(30);
$pdf->MultiCell(84, 4, utf8_decode('Colonia: ' . $colonia_usuario . ''), '0', 'C', 0);
$pdf->Setx(30);
$pdf->MultiCell(84, 4, utf8_decode('Ciudad: ' . $ciudad_usuario . ''), '0', 'C', 0);
$pdf->Setx(30);
$pdf->MultiCell(84, 4, utf8_decode('Estado: ' . $estado_usuario . ''), '0', 'C', 0);
$pdf->Setx(30);
$pdf->Cell(82, 5, utf8_decode('CP: ' . $codigop_usuario . ''), 0, 0, 'C');
$pdf->ln(3);
$pdf->Setx(30);
$pdf->Cell(82, 5, utf8_decode('Tlf: ' . $telefono_usuario . ''), 0, 0, 'C');
$pdf->ln(3.5);
$pdf->SetY(11);
$pdf->Setx(150);
$pdf->SetFont('Arial', 'B', 8);
$pdf->ln(6);
$pdf->Setx(150);
$pdf->Cell(40, 5, utf8_decode('Enviar? ' . $envio . ''), 0, 0, 'L');
if ($envio == 'Si') {
    $pdf->ln(3);
    $pdf->Setx(150);
    $pdf->Cell(40, 5, utf8_decode('Paqueteria: ' . $paqueteria . ''), 0, 0, 'L');
    if ($paqueteria == 'EVISA' or $paqueteria == 'CASTORES' or $paqueteria == 'TRAVISA' or $paqueteria == 'LINEAS DEL SUR') {
        $pdf->ln(3);
        $pdf->Setx(150);
        $pdf->Cell(40, 5, utf8_decode('Rfc: ' . $rfc . ''), 0, 0, 'L');
    }
}





$pdf->SetY(30);
$pdf->Setx(150);
$pdf->SetFillColor(223, 223, 223);
//define la tabla
$pdf->Cell(47, 3, 'Puntas', 1, 1, 'C', 1);
$puntas = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    $producto = $rowg['nombre'];

    if ($codigo == 'UN-08' or $codigo == 'UN-40' or $codigo == 'UN-05' or $codigo == 'UN-43' or $codigo == 'PU-02' or $codigo == 'PU-04' or $codigo == 'PU-01' or $codigo == 'PU-06' or $codigo == 'PU-05' or $codigo == 'PI-16') {

        $puntas = $puntas + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(255, 255, 255);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $puntas . ''), '1', 'C', 1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(223, 223, 223);
$pdf->Setx(150);
$pdf->Cell(47, 3, '' . $mussa . ' (GEL-01)', 1, 1, 'C', 1);
$pdf->Setx(150);
//$pdf->Cell(47,3,'Gama',1,1,'C',1);
$gelish = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    //$producto=$rowg['nombre'];
    $producto = substr($rowg['nombre'], 15);
    if ($codigo == 'GEL-01') {

        $gelish = $gelish + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $gelish . ''), '1', 'C', 1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(223, 223, 223);
$pdf->Setx(150);
$pdf->Cell(47, 3, 'Gel ojo de gato 9D (GEL-12)', 1, 1, 'C', 1);
$pdf->Setx(150);
//$pdf->Cell(47,3,'Gama',1,1,'C',1);
$gelish = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    $producto = substr($rowg['nombre'], 21);

    if ($codigo == 'GEL-12') {

        $gelish = $gelish + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $gelish . ''), '1', 'C', 1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(223, 223, 223);
$pdf->Setx(150);
$pdf->Cell(47, 3, 'Gel ojo de gato GALAXY (GEL-13)', 1, 1, 'C', 1);
$pdf->Setx(150);
// $pdf->Cell(47,3,'Gama',1,1,'C',1);
$gelish = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    $producto = $rowg['nombre'];
    $producto = substr($rowg['nombre'], 25);
    if ($codigo == 'GEL-13') {

        $gelish = $gelish + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $gelish . ''), '1', 'C', 1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(223, 223, 223);
$pdf->Setx(150);
$pdf->Cell(47, 3, 'Gel Termico (GEL-11)', 1, 1, 'C', 1);
$pdf->Setx(150);
//$pdf->Cell(47,3,'Gama',1,1,'C',1);
$gelish = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    $producto = substr($rowg['nombre'], 14);

    if ($codigo == 'GEL-11') {

        $gelish = $gelish + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $gelish . ''), '1', 'C', 1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(223, 223, 223);
$pdf->Setx(150);
$pdf->Cell(47, 3, 'Gel Painting (GEL-14)', 1, 1, 'C', 1);
$pdf->Setx(150);
//$pdf->Cell(47,3,'Gama',1,1,'C',1);
$gelish = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    $producto = substr($rowg['nombre'], 15);

    if ($codigo == 'GEL-14') {

        $gelish = $gelish + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $gelish . ''), '1', 'C', 1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(223, 223, 223);
$pdf->Setx(150);
$pdf->Cell(47, 3, 'Gel Painting (GEL-15)', 1, 1, 'C', 1);
$pdf->Setx(150);
//$pdf->Cell(47,3,'Gama',1,1,'C',1);
$gelish = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    $producto = substr($rowg['nombre'], 15);
    if ($codigo == 'GEL-15') {

        $gelish = $gelish + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $gelish . ''), '1', 'C', 1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(223, 223, 223);
$pdf->Setx(150);
$pdf->Cell(47, 3, 'Gel de Construcci' . utf8_decode('ó') . 'n (GEL-18)', 1, 1, 'C', 1);
$pdf->Setx(150);
//$pdf->Cell(47,3,'Gama',1,1,'C',1);
$gelish = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    $producto = $rowg['nombre'];

    if ($codigo == 'GEL-18') {

        $gelish = $gelish + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $gelish . ''), '1', 'C', 1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(223, 223, 223);
$pdf->Setx(150);
$pdf->Cell(47, 3, 'PolyGel ' . $mussa . ' (GEL-17)', 1, 1, 'C', 1);
$pdf->Setx(150);
//$pdf->Cell(47,3,'Gama',1,1,'C',1);
$gelish = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    $producto = substr($rowg['nombre'], 17);

    if ($codigo == 'GEL-17') {

        $gelish = $gelish + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $gelish . ''), '1', 'C', 1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(223, 223, 223);
$pdf->Setx(150);
$pdf->Cell(47, 3, 'Gel Granizo (GEL-22)', 1, 1, 'C', 1);
$pdf->Setx(150);
//$pdf->Cell(47,3,'Gama',1,1,'C',1);
$gelish = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    $producto = substr($rowg['nombre'], 12);

    if ($codigo == 'GEL-22') {

        $gelish = $gelish + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $gelish . ''), '1', 'C', 1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(223, 223, 223);
$pdf->Setx(150);
$pdf->Cell(47, 3, 'Gel Neon (GEL-23)', 1, 1, 'C', 1);
$pdf->Setx(150);
//$pdf->Cell(47,3,'Gama',1,1,'C',1);
$gelish = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    $producto = substr($rowg['nombre'], 9);

    if ($codigo == 'GEL-23') {

        $gelish = $gelish + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $gelish . ''), '1', 'C', 1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(223, 223, 223);
$pdf->Setx(150);
$pdf->Cell(47, 3, 'Gel Peluche (GEL-25)', 1, 1, 'C', 1);
$pdf->Setx(150);
//$pdf->Cell(47,3,'Gama',1,1,'C',1);
$gelish = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    $producto = $rowg['nombre'];

    if ($codigo == 'GEL-25') {

        $gelish = $gelish + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $gelish . ''), '1', 'C', 1);

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(223, 223, 223);
$pdf->Setx(150);
$pdf->Cell(47, 3, 'Gel Gelatina (GEL-28)', 1, 1, 'C', 1);
$pdf->Setx(150);
//$pdf->Cell(47,3,'Gama',1,1,'C',1);
$gelish = '0';
$consultag = "SELECT * FROM registro_usuario where orden='$norden'order by nombre asc";
$resultadog = $mysqli->query($consultag);
while ($rowg = $resultadog->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 5);
    $codigo = $rowg['codigo'];
    $cantidad = $rowg['cantidad'];
    $producto = substr($rowg['nombre'], 15);

    if ($codigo == 'GEL-28') {

        $gelish = $gelish + $cantidad;
        $pdf->Setx(150);
        $pdf->Cell(40, 3, utf8_decode($producto), 1, 0, 'C', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(7, 3, utf8_decode($cantidad), 1, 1, 'C', 0);
    }
}
$pdf->Setx(150);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(47, 3, utf8_decode('total: ' . $gelish . ''), '1', 'C', 1);


















$pdf->SetFont('Arial', 'B', 5);
$pdf->SetY(34);
$pdf->Setx(10);
$pdf->Cell(25, 10, 'Codigo', 0, 0, 'C', 0);
$pdf->Cell(70, 10, 'Producto', 0, 0, 'C', 0);



$pdf->Cell(10, 10, 'Cantidad', 0, 0, 'C', 0);
$pdf->Cell(15, 10, 'Precio U', 0, 0, 'C', 0);
$pdf->Cell(18, 10, 'Precio T', 0, 1, 'C', 0);

$pdf->SetDrawColor(252, 118, 106);
$pdf->SetLineWidth(1);
$pdf->Line(10, 42, 147.5, 42);












$sub = '0';
$total = '0';
$consulta = "SELECT * FROM registro_usuario where id_usuario='$id_usuario' and orden='$norden' and codigo!='UN-08' and codigo!='UN-40' and codigo!='UN-05' and codigo!='UN-43' and codigo!='PU-02' and codigo!='PU-04' and codigo!='PU-01' and codigo!='PU-06' and codigo!='PU-05' and codigo!='PI-16' and codigo!='GEL-01' and codigo!='GEL-12' and codigo!='GEL-13' and codigo!='GEL-11' and codigo!='GEL-14' and codigo!='GEL-15' and codigo!='GEL-18' and codigo!='GEL-17' and codigo!='GEL-22' and codigo!='GEL-23' and codigo!='GEL-25' and codigo!='GEL-28' ";
$resultado = $mysqli->query($consulta);

$sqlxa = "SELECT * FROM registro_usuario where id_usuario='$id_usuario' and orden='$norden'";
$resultxa = $mysqli->query($sqlxa);
while ($columnaxa = mysqli_fetch_array($resultxa)) {
    $precio = $columnaxa['precio'];
    $cantidad = $columnaxa['cantidad'];

    $sub = $precio * $cantidad;

    $total = $total + $sub;
}



$pdf->SetFont('Arial', '', 7);


//$pdf->Image('http://placehold.it/15/Fc766a/Fc766a',0,0,0,0,'png','hola');
$pdf->Sety(45);
$co = '0';

$cont_cantidad = '0';
$j = '0';
while ($row = $resultado->fetch_assoc()) {

    if ($co % 2 == 0) {
        $pdf->SetFillColor(223, 223, 223);
        $pdf->SetFillColor(223, 223, 223);
    } else {
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetFillColor(255, 255, 255);
    }

    $co = $co + 1;


    $pdf->Setx(10);
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(25, 5, utf8_decode($row['codigo']), 0, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode($row['nombre']), 0, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $row['cantidad'], 0, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $row['precio'], 0, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $row['cantidad'] * $row['precio'] . '.00', 0, 1, 'C', 1);
    $cantidad_total_productos = $row['cantidad'];
    $tot = $tot + $cantidad_total_productos;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($smp1 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('UN-40'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Barrilitos o Puntas Madrill'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $smp1, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gelp1, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgelp1 . '.00', 1, 1, 'C', 1);
    $pdf->Setx(10);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($smp2 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('UN-05'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Puntas de metal'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $smp2, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gelp2, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgelp2 . '.00', 1, 1, 'C', 1);
    $pdf->Setx(10);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($smp3 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('UN-43'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Base para puntas'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $smp3, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gelp3, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgelp3 . '.00', 1, 1, 'C', 1);
    $pdf->Setx(10);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($smp4 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('PU-02'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Punta Tungsteno'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $smp4, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gelp4, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgelp4 . '.00', 1, 1, 'C', 1);
    $pdf->Setx(10);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($smp5 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('PU-04'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Punta Tungsteno'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $smp5, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gelp5, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgelp5 . '.00', 1, 1, 'C', 1);
    $pdf->Setx(10);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
//Nuevos
if ($smp6 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('PU-01'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Punta Tungsteno'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $smp6, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gelp6, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgelp6 . '.00', 1, 1, 'C', 1);
    $pdf->Setx(10);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}

if ($smp7 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('PU-06'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Punta de Metal'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $smp7, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gelp7, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgelp7 . '.00', 1, 1, 'C', 1);
    $pdf->Setx(10);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($smp8 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('PU-05'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Punta Tungsteno'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $smp8, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gelp8, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgelp8 . '.00', 1, 1, 'C', 1);
    $pdf->Setx(10);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($smp9 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('PI-16'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Punta Tungsteno'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $smp9, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gelp9, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgelp9 . '.00', 1, 1, 'C', 1);
    $pdf->Setx(10);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('UN-08'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Punta de ceramica'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}

if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm10 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('GEL-01'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Gel ' . $mussa . ''), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm10, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel10, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel10 . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm11 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('GEL-12'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Gel ojo de gato 9D'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm11, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel11, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel11 . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm12 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('GEL-13'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Gel ojo de gato GALAXY'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm12, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel12, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel12 . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm13 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('GEL-11'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Gel Termico'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm13, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel13, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel13 . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm14 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('GEL-14'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Gel Painting'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm14, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel14, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel14 . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm15 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('GEL-15'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Gel Painting'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm15, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel15, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel15 . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm16 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('GEL-18'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Gel de Construccion'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm16, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel16, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel16 . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm17 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('GEL-17'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('PolyGel ' . $mussa . ''), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm17, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel17, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel17 . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm18 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('GEL-22'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Gel Granizo'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm18, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel18, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel18 . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm19 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('GEL-23'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Gel Neon'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm19, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel19, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel19 . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm20 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('GEL-25'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Gel Peluche'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm20, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel20, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel20 . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
if ($sm21 >= '1') {
    $pdf->Setx(10);
    $pdf->Cell(25, 5, utf8_decode('GEL-28'), 1, 0, 'C', 1);
    $pdf->Cell(70, 5, utf8_decode('Gel Gelatina'), 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(10, 5, $sm21, 1, 0, 'C', 1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(15, 5, '$' . $precio_gel21, 1, 0, 'C', 1);
    $pdf->Cell(18, 5, '$' . $sumgel21 . '.00', 1, 1, 'C', 1);
    $co = $co + 1;
}
if ($co % 2 == 0) {
    $pdf->SetFillColor(223, 223, 223);
    $pdf->SetFillColor(223, 223, 223);
} else {
    $pdf->SetDrawColor(255, 255, 255);
    $pdf->SetFillColor(255, 255, 255);
}
$pdf->Setx(10);
$totalp = $tot + $sm + $smp1 + $smp2 + $smp3 + $smp4 + $smp5 + $smp6 + $smp7 + $smp8 + $smp9 + $sm10 + $sm11 + $sm12 + $sm13 + $sm14 + $sm15 + $sm16 + $sm17 + $sm18 + $sm19 + $sm20 + $sm21;
$totall = $total;
$pdf->Cell(70, 5, 'Cantidad total de Productos: ' . $totalp, 0, 0, 'L', 1);
$pdf->Cell(69, 5, 'Precio Total: $' . $totall . '.00', 0, 0, 'R', 1);

$pdf->output();
