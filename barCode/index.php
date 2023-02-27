<?php
	require('../fpdf/fpdf.php');
	require('barcode.php');
	$pdf=new FPDF();

    //Agregamos la primera pagina al documento pdf
    $pdf->AddPage();
	$pdf->Ln(6);
	$pdf->Image('barcode.php?filepath="barCode"text=124582596&size=100&orientation=horizontal&codetype=Code39&print=true&sizefactor=1', 162 ,6, 42 , 30,'PNG');
	 //Mostramos el documento pdf
	 $pdf->Output();
?>
