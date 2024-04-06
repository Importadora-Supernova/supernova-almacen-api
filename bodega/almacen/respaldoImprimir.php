<?php
include("../../conexion/conn.php");
//error_reporting(0);
date_default_timezone_set('America/Mexico_City');

$fecha_print=date('Y-m-d H:i:s');
$print = '1';
$orden = $_GET['orden'];

$geles = array("GEL-01","GEL-11","GEL-12","GEL-13","GEL-14","GEL-15","GEL-17","GEL-17N","GEL-17G","GEL-17T","GEL-22","GEL-23","GEL-28","GEL-30","GEL-31","GEL-32","GEL-33","GEL-34","GEL-35","GEL-36","GEL-37","GEL-38");

foreach($geles as $datos){
    echo $datos;
}

//actualizamos folios en marca de impresion
// $sqlUpdate = 'UPDATE folios marca_tiempo=?,impresion=? WHERE orden=?';
// $stmt = $con->prepare($sqlUpdate);
// $stmt->bind_param('sss',$fecha_print,$print,$orden);
// $stmt->execute();

$sqllxg = 'SELECT f.orden,f.nombres,f.envio,f.paqueteria,f.nota,f.impresion,f.cajas,f.id_usuario,f.pendiente,f.id_operador,u.obsequio FROM folios f INNER JOIN usuario u ON f.id_usuario = u.id WHERE f.orden="'.$orden.'"';

$resultxg = $con->query($sqllxg);
while ($columnaxg = mysqli_fetch_array($resultxg)) {
    $nombre_cliente = $columnaxg['nombres'];
    $envio          = $columnaxg['envio'];
    $paqueteria     = $columnaxg['paqueteria'];
    $nota           = $columnaxg['nota'];
    $impresion      = $columnaxg['impresion'];
    $cajas          = $columnaxg['cajas'];
    $id_usuario     = $columnaxg['id_usuario'];
    $pendiente      = $columnaxg['pendiente'];
    $operador       = $columnaxg['id_operador'];
    $obesquio       = $columnaxg['obsequio'];
}
$paque = $envio == 'No' ? 'Entrega en Bodega' : $paqueteria;


// $sqllxg = "SELECT obsequio FROM usuario where id='$id_usuario' ";
// $resultxg = $con->query($sqllxg);
// while ($columnaxg = mysqli_fetch_array($resultxg)) {
//     $obesquio = $columnaxg['obsequio'];
// }

if ($obesquio == '1') {
    $ob = "Botella Premium";
} else
if ($obesquio == '2') {
    $ob = "Chivas 18 o Green Label o buchanans 18 o buchanans Dorada";
} else
if ($obesquio == '3') {
    $ob = "Chivas 13 o buchanans two souls";
} else
if ($obesquio == '4') {
    $ob = "Red Label o Absolut";
} else
if ($obesquio == '5') {
    $ob = "Tasa o Chocolate";
} else {
    $ob = "";
}

$sqli1f = "UPDATE usuario SET obsequio='$ob' where id='$id_usuario' ";
$quef = mysqli_query($con, $sqli1f);


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
</head>

<body onload="javascript:window.print()">
    <div style="width:800px;">
        <div>
            Nombre: <b><?php echo $nombre_cliente ?></b><br>
            Tipo de entrega:<b> <?php echo $paque ?></b><br>
            Orden: <b><?php echo $orden ?></b><br>
            Cajas: <b><?php echo $cajas ?></b><br>
            Obsequio navideño: <b><?php echo $ob ?></b><br>
            Recolector: <b><?php echo $operador ?></b>
            <?php
            if ($pendiente == 'Pendiente') {
                echo "<b>Pago pendiente favor no dar salida</b>";
            }
            ?>
            <br><br>
            <?php
            if ($nota != NULL) {
            ?>
                <div style="width:500px;background: #ff9800;border-radius: 10px;padding: 10px;text-align: center;">
                    NOTA: <b><?php echo $nota ?></b>
                </div>
            <?php
            }
            ?>


        </div><br><br>
        <img src="http://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=<?php echo $orden ?>&.png',111,0.1,45,45" style="position: absolute;left: 503px;top: -10px;width: 108px;z-index: -1;">
        <div style="width:190px; float:right;">
            <table style="text-align:center;font-size: 10px;border-collapse: collapse;" border="1">
                <?php
                $sum = '0';
                $viejo = '0';
                $vfp = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto FROM registro_usuario where orden='$orden' and (codigo='UN-08' or codigo='UN-40' or codigo='UN-05' or codigo='UN-43' or codigo='PU-02' or codigo='PU-04' or codigo='PU-01' or codigo='PU-06' or codigo='PU-05' or codigo='PI-16')  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    //$nombre= substr($columnaxg['nombre'], 15);
                    $nombre = $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sum = $sum + $cantidad;

                    if ($codigo != NULL and $vfp == '0') {
                ?>
                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Puntas</b></td>
                            <td>Ubicacion</td>
                        </tr>
                    <?php
                        $vfp = '1';
                    }
                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>
                        </td>
                    </tr>
                <?php
                }
                if ($vfp == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sum ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>

                <?php
                }

                ?>
                <?php

                $sumg1 = '0';
                $viejo = '0';
                $vfg1 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto FROM registro_usuario where orden='$orden' and codigo='GEL-01'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 15);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg1 = $sumg1 + $cantidad;

                    if ($codigo != NULL and $vfg1 == '0') {
                ?>
                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Le'Mussa (GEL-01)</b></td>
                            <td>Ubicacion</td>
                        </tr>
                    <?php
                        $vfg1 = '1';
                    }
                    ?>

                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>

                    </tr>
                <?php
                }
                if ($vfg1 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg1 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }

                ?>
                <?php
                // consulta refactorizada 
                /*SELECT DISTINCT(r.id_producto),d.id_almacen,d.cantidad as cantidad_almacen,d.orden,r.nombre,r.codigo,r.id_producto,a.nombre_almacen FROM registro_usuario r INNER JOIN almacen_descuentos d ON r.id_producto = d.id_producto AND r.orden = d.orden INNER JOIN almacenes a ON d.id_almacen = a.id_almacen WHERE d.orden='5279-48';*/

                $sumg11 = '0';
                $viejo = '0';
                $vfg11 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-11'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 4);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg11 = $sumg11 + $cantidad;

                    if ($codigo != NULL and $vfg11 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel termico (GEL-11)</b></td>
                        </tr>
                    <?php
                        $vfg11 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg11 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg11 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>
                <?php

                $sumg12 = '0';
                $viejo = '0';
                $vfg12 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-12'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 4);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg12 = $sumg12 + $cantidad;

                    if ($codigo != NULL and $vfg12 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel ojo de gato 9D (GEL-12)</b></td>
                        </tr>
                    <?php
                        $vfg12 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg12 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg12 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>
                <?php

                $sumg13 = '0';
                $viejo = '0';
                $vfg13 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-13'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 4);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg13 = $sumg13 + $cantidad;

                    if ($codigo != NULL and $vfg13 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel ojo de gato galaxy (GEL-13)</b></td>
                        </tr>
                    <?php
                        $vfg13 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg13 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg13 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>


                <?php

                $sumg14 = '0';
                $viejo = '0';
                $vfg14 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-14'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 4);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg14 = $sumg14 + $cantidad;

                    if ($codigo != NULL and $vfg14 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel painting (GEL-14)</b></td>
                        </tr>
                    <?php
                        $vfg14 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg14 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg14 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>


                <?php

                $sumg15 = '0';
                $viejo = '0';
                $vfg15 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-15'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 4);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg15 = $sumg15 + $cantidad;

                    if ($codigo != NULL and $vfg15 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel painting (GEL-15)</b></td>
                        </tr>
                    <?php
                        $vfg15 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg15 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg15 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>

                <?php

                $sumg17 = '0';
                $viejo = '0';
                $vfg17 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-17'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 19);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg17 = $sumg17 + $cantidad;

                    if ($codigo != NULL and $vfg17 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Polygel Le'Mussa (GEL-17)</b></td>
                        </tr>
                    <?php
                        $vfg17 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg17 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg17 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>

                <?php

                $sumg17n = '0';
                $viejo = '0';
                $vfg17n = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-17N'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 24);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg17n = $sumg17n + $cantidad;

                    if ($codigo != NULL and $vfg17n == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Polygel Neon Le'Mussa (GEL-17N)</b></td>
                        </tr>
                    <?php
                        $vfg17n = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg17n == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg17n ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>

                <?php

                $sumg17g = '0';
                $viejo = '0';
                $vfg17g = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-17G'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 7);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg17g = $sumg17g + $cantidad;

                    if ($codigo != NULL and $vfg17g == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Polygel Glitter Le'Mussa (GEL-17G)</b></td>
                        </tr>
                    <?php
                        $vfg17g = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg17g == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg17g ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>
                <?php

                $sumg17t = '0';
                $viejo = '0';
                $vfg17t = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-17T'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 7);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg17t = $sumg17t + $cantidad;

                    if ($codigo != NULL and $vfg17t == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Polygel Termico Le'Mussa (GEL-17T)</b></td>
                        </tr>
                    <?php
                        $vfg17t = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg17t == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg17t ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>

                <?php

                $sumg22 = '0';
                $viejo = '0';
                $vfg22 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-22'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 4);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg22 = $sumg22 + $cantidad;

                    if ($codigo != NULL and $vfg22 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel Granizo (GEL-22)</b></td>
                        </tr>
                    <?php
                        $vfg22 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg22 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg22 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>

                <?php

                $sumg23 = '0';
                $viejo = '0';
                $vfg23 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-23'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 4);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg23 = $sumg23 + $cantidad;

                    if ($codigo != NULL and $vfg23 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel Neon (GEL-23)</b></td>
                        </tr>
                    <?php
                        $vfg23 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg23 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg23 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>

                <?php

                $sumg28 = '0';
                $viejo = '0';
                $vfg28 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-28'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 4);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg28 = $sumg28 + $cantidad;

                    if ($codigo != NULL and $vfg28 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel Gelatina (GEL-28)</b></td>
                        </tr>
                    <?php
                        $vfg28 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg28 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg28 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>
                <?php

                $sumg30 = '0';
                $viejo = '0';
                $vfg30 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-30'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 13);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg30 = $sumg30 + $cantidad;

                    if ($codigo != NULL and $vfg30 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel Diamante (GEL-30)</b></td>
                        </tr>
                    <?php
                        $vfg30 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg30 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg30 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>

                <?php

                $sumg31 = '0';
                $viejo = '0';
                $vfg31 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-31'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 12);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];

                    $sumg31 = $sumg31 + $cantidad;

                    if ($codigo != NULL and $vfg31 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel Cristal Reflectivo (GEL-31)</b></td>
                        </tr>
                    <?php
                        $vfg31 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg31 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg31 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>
                <?php

                $sumg32 = '0';
                $viejo = '0';
                $vfg32 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-32'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 13);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg32 = $sumg32 + $cantidad;

                    if ($codigo != NULL and $vfg32 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel Diamante laser (GEL-32)</b></td>
                        </tr>
                    <?php
                        $vfg32 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg32 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg32 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>
                <?php

                $sumg33 = '0';
                $viejo = '0';
                $vfg33 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-33'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 13);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg33 = $sumg33 + $cantidad;

                    if ($codigo != NULL and $vfg33 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel Diamante glow (GEL-33)</b></td>
                        </tr>
                    <?php
                        $vfg33 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg33 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg33 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>
                <?php

                $sumg34 = '0';
                $viejo = '0';
                $vfg34 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-34'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 13);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg34 = $sumg34 + $cantidad;

                    if ($codigo != NULL and $vfg34 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel Galaxy (GEL-34)</b></td>
                        </tr>
                    <?php
                        $vfg34 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg34 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg34 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>
                <?php

                $sumg35 = '0';
                $viejo = '0';
                $vfg35 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-35'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 13);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg35 = $sumg35 + $cantidad;

                    if ($codigo != NULL and $vfg35 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel Neon (GEL-35)</b></td>
                        </tr>
                    <?php
                        $vfg35 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg35 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg35 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>
                <?php

                $sumg36 = '0';
                $viejo = '0';
                $vfg36 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-36'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 13);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg36 = $sumg36 + $cantidad;

                    if ($codigo != NULL and $vfg36 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel Firework (GEL-36)</b></td>
                        </tr>
                    <?php
                        $vfg36 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg36 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg36 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>
                <?php

                $sumg37 = '0';
                $viejo = '0';
                $vfg37 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-37'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 16);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg37 = $sumg37 + $cantidad;

                    if ($codigo != NULL and $vfg37 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel ojo de gato diamante 12d (GEL-37)</b></td>
                        </tr>
                    <?php
                        $vfg37 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg37 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg37 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>
                <?php

                $sumg38 = '0';
                $viejo = '0';
                $vfg38 = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto  FROM registro_usuario where orden='$orden' and codigo='GEL-38'  order by codigo ASC, id_producto ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = substr($columnaxg['nombre'], 13);
                    //$nombre= $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];
                    $producto_id = $columnaxg['id_producto'];
                    $sumg38 = $sumg38 + $cantidad;

                    if ($codigo != NULL and $vfg38 == '0') {
                ?>

                        <tr style="background:#dbdbdb;">
                            <td colspan="2"><b>Gel Diamante negro (GEL-38)</b></td>
                        </tr>
                    <?php
                        $vfg38 = '1';
                    }




                    ?>
                    <tr>
                        <td style="width: 300px;"><?php echo $nombre ?></td>
                        <td style="width: 50px;"><?php echo $cantidad ?></td>
                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 50px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>
                    </tr>
                <?php
                }
                if ($vfg38 == '1') {
                ?>
                    <tr style="background:#dbdbdb;">
                        <td colspan="3"><b>Total</b> <?php echo $sumg38 ?></td>
                    </tr>
                    <tr style="height: 5px;">
                        <td colspan="3"></td>
                    </tr>
                <?php
                }
                //and codigo='GEL-01' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-11' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-18' or codigo='GEL-17' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-25' or codigo='GEL-28' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T'

                ?>



            </table>
        </div>
        <div style="width:600px; float:left;">
            <table style="text-align: center;width: 100%;font-size: 15px;">
                <tr style="background:#fc766a;">
                    <td>Codigo</td>
                    <td>Nombre</td>
                    <td>Cantidad</td>
                    <td>Ubicacion / cantidad</td>
                </tr>
                <?php
                $sumt = '0';
                $cn = '0';
                $sqllxg = "SELECT nombre,codigo,cantidad,id_producto FROM registro_usuario where orden='$orden' and codigo!='GEL-01' and codigo!='GEL-11' and codigo!='GEL-12' and codigo!='GEL-13' and codigo!='GEL-14' and codigo!='GEL-15' and codigo!='GEL-17'	 and codigo!='GEL-17N' and codigo!='GEL-17G' and codigo!='GEL-17T' and codigo!='GEL-22' and codigo!='GEL-23' and codigo!='GEL-28' and codigo!='GEL-30' and codigo!='GEL-31' and codigo!='GEL-32' and codigo!='GEL-33' and codigo!='GEL-34' and codigo!='GEL-35' and codigo!='GEL-36' and codigo!='GEL-37' and codigo!='GEL-38' order by codigo ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {

                    $producto_id = $columnaxg['id_producto'];
                    $codigo = $columnaxg['codigo'];
                    $nombre = $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad'];

                    $sumt = $sumt + $cantidad;
                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }



                ?>

                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;"><?php echo $codigo ?></td>
                        <td style="padding: 2px;"><?php echo $nombre ?></td>
                        <td style="padding: 2px;"><?php echo $cantidad ?></td>

                        <td>
                            <?php
                            $sqllxg2 = "SELECT cantidad,id_almacen FROM almacen_descuentos where orden='$orden' and id_producto='$producto_id'";
                            $resultxg2 = $con->query($sqllxg2);
                            while ($columnaxg2 = mysqli_fetch_array($resultxg2)) {
                                $id_almacen_producto = $columnaxg2['id_almacen'];
                                $cantidad_almacen_producto = $columnaxg2['cantidad'];

                                $sqllxg22 = "SELECT nombre_almacen FROM almacenes where id_almacen='$id_almacen_producto' limit 1";
                                $resultxg22 = $con->query($sqllxg22);
                                while ($columnaxg22 = mysqli_fetch_array($resultxg22)) {
                                    $nombre_almacen = $columnaxg22['nombre_almacen'];
                            ?>
                                    <div style="width: 9%;position: absolute;"><?php echo $nombre_almacen ?></div>
                                    <div style="margin-left: 65px;">N°<?php echo $cantidad_almacen_producto ?></div>
                            <?php
                                    //echo$nombre_almacen." ".$cantidad_almacen_producto."<br>";
                                }
                            }

                            ?>


                        </td>

                    </tr>
                <?php
                    $cn++;
                }



                if ($vfg1 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-01</td>
                        <td style="padding: 2px;">Gel Le'Mussa</td>
                        <td style="padding: 2px;"><?php echo $sumg1 ?></td>

                    </tr>
                <?php
                    $cn++;
                }

                if ($vfg11 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-11</td>
                        <td style="padding: 2px;">Gel termico</td>
                        <td style="padding: 2px;"><?php echo $sumg11 ?></td>

                    </tr>
                <?php
                    $cn++;
                }

                if ($vfg12 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-12</td>
                        <td style="padding: 2px;">Gel ojo de gato 9d</td>
                        <td style="padding: 2px;"><?php echo $sumg12 ?></td>

                    </tr>
                <?php
                    $cn++;
                }

                if ($vfg13 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-13</td>
                        <td style="padding: 2px;">Gel ojo de gato galaxy</td>
                        <td style="padding: 2px;"><?php echo $sumg13 ?></td>

                    </tr>
                <?php
                    $cn++;
                }

                if ($vfg14 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-14</td>
                        <td style="padding: 2px;">Gel painting</td>
                        <td style="padding: 2px;"><?php echo $sumg14 ?></td>

                    </tr>
                <?php
                    $cn++;
                }

                if ($vfg15 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-15</td>
                        <td style="padding: 2px;">Gel painting</td>
                        <td style="padding: 2px;"><?php echo $sumg15 ?></td>

                    </tr>
                <?php
                    $cn++;
                }

                if ($vfg17 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-17</td>
                        <td style="padding: 2px;">Polygel Le'Mussa</td>
                        <td style="padding: 2px;"><?php echo $sumg17 ?></td>

                    </tr>
                <?php
                    $cn++;
                }
                if ($vfg17n == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-17N</td>
                        <td style="padding: 2px;">Polygel Neon</td>
                        <td style="padding: 2px;"><?php echo $sumg17n ?></td>

                    </tr>
                <?php
                    $cn++;
                }
                if ($vfg17g == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-17G</td>
                        <td style="padding: 2px;">Polygel Glitter</td>
                        <td style="padding: 2px;"><?php echo $sumg17g ?></td>

                    </tr>
                <?php
                    $cn++;
                }
                if ($vfg17t == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-17t</td>
                        <td style="padding: 2px;">Polygel Termico</td>
                        <td style="padding: 2px;"><?php echo $sumg17t ?></td>

                    </tr>
                <?php
                    $cn++;
                }
                if ($vfg22 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-22</td>
                        <td style="padding: 2px;">Gel granizo</td>
                        <td style="padding: 2px;"><?php echo $sumg22 ?></td>

                    </tr>
                <?php
                    $cn++;
                }

                if ($vfg23 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-23</td>
                        <td style="padding: 2px;">Gel Neon</td>
                        <td style="padding: 2px;"><?php echo $sumg23 ?></td>

                    </tr>
                <?php
                    $cn++;
                }
                if ($vfg28 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-28</td>
                        <td style="padding: 2px;">Gel Gelatina</td>
                        <td style="padding: 2px;"><?php echo $sumg28 ?></td>

                    </tr>
                <?php
                    $cn++;
                }
                if ($vfg30 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-30</td>
                        <td style="padding: 2px;">Gel le mussa diamante</td>
                        <td style="padding: 2px;"><?php echo $sumg30 ?></td>

                    </tr>
                <?php
                    $cn++;
                }



                if ($vfg31 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-31</td>
                        <td style="padding: 2px;">Gel cristal reflectivo</td>
                        <td style="padding: 2px;"><?php echo $sumg31 ?></td>

                    </tr>
                <?php
                    $cn++;
                }

                if ($vfg32 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-32</td>
                        <td style="padding: 2px;">Gel diamante laser</td>
                        <td style="padding: 2px;"><?php echo $sumg32 ?></td>

                    </tr>
                <?php
                    $cn++;
                }
                if ($vfg33 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-33</td>
                        <td style="padding: 2px;">Gel diamante glow</td>
                        <td style="padding: 2px;"><?php echo $sumg33 ?></td>

                    </tr>
                <?php
                    $cn++;
                }
                if ($vfg34 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-34</td>
                        <td style="padding: 2px;">Gel galaxy</td>
                        <td style="padding: 2px;"><?php echo $sumg34 ?></td>

                    </tr>
                <?php
                    $cn++;
                }
                if ($vfg35 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-35</td>
                        <td style="padding: 2px;">Gel neon</td>
                        <td style="padding: 2px;"><?php echo $sumg35 ?></td>

                    </tr>
                <?php
                    $cn++;
                }
                if ($vfg36 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-36</td>
                        <td style="padding: 2px;">Gel firework</td>
                        <td style="padding: 2px;"><?php echo $sumg36 ?></td>

                    </tr>
                <?php
                    $cn++;
                }
                if ($vfg37 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-37</td>
                        <td style="padding: 2px;">Gel ojo de gato diamante 12d</td>
                        <td style="padding: 2px;"><?php echo $sumg37 ?></td>

                    </tr>
                <?php
                    $cn++;
                }
                if ($vfg38 == '1') {

                    if ($cn % 2 == 0) {
                        $bg = "antiquewhite;";
                    } else {
                        $bg = "";
                    }
                ?>
                    <tr style="background:<?php echo $bg ?>">
                        <td style="padding: 2px;">GEL-38</td>
                        <td style="padding: 2px;">Gel diamante negro</td>
                        <td style="padding: 2px;"><?php echo $sumg38 ?></td>

                    </tr>
                <?php
                    $cn++;
                }


                $total = $sumt + $sumg1 + $sumg11 + $sumg12 + $sumg13 + $sumg14 + $sumg15 + $sumg17    + $sumg17N + $sumg17G + $sumg17T + $sumg22 + $sumg23 + $sumg28 + $sumg30 + $sumg31 + $sumg32 + $sumg33 + $sumg34 + $sumg35 + $sumg36 + $sumg37 + $sumg38;

                if ($cn % 2 == 0) {
                    $bg = "antiquewhite;";
                } else {
                    $bg = "";
                }
                ?>
                <tr style="background:<?php echo $bg ?>">
                    <td colspan="3" style="text-align: start; padding: 2px;">Cantidad total de Productos: <b><?php echo $total ?></b></td>

                </tr>




            </table>

        </div>
    </div>

</body>

</html>