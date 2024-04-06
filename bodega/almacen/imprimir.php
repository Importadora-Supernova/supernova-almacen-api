<?php
include("../../conexion/conn.php");
require_once("../../class/pedidoImprimir.class.php");
//error_reporting(0);
date_default_timezone_set('America/Mexico_City');

$fecha_print=date('Y-m-d H:i:s');
$print = '1';
$orden = $_GET['orden'];

//declaramos array para substring de nombre del producto
$gelesSubString = [
    "GEL-01" => 15,
    "GEL-11" => 4,
    "GEL-12" => 4,
    "GEL-13" => 4,
    "GEL-14" => 4,
    "GEL-15" => 4,
    "GEL-17" => 4,
    "GEL-17N" => 4,
    "GEL-17G" => 7,
    "GEL-17T" => 7,
    "GEL-22" => 4,
    "GEL-24" => 4,
    "GEL-28" => 4,
    "GEL-30" => 13,
    "GEL-31" => 12,
    "GEL-32" => 13,
    "GEL-33" => 13,
    "GEL-34" => 13,
    "GEL-35" => 13,
    "GEL-36" => 13,
    "GEL-37" => 16,
    "GEL-38" => 13,
];

//actualizamos folios en marca de impresion
// $sqlUpdate = 'UPDATE folios  SET marca_tiempo=?,impresion=? WHERE orden=?';
// $stmt = $con->prepare($sqlUpdate);
// $stmt->bind_param('sss',$fecha_print,$print,$orden);
// $stmt->execute();

//llamamos a la clase de pedidoImprimir
$product = new Productos();

//consulta a vista para traer datos del cliente y su obsequio
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
//validamos si es entrega o mostramos la paqueteria
$paque = $envio == 'No' ? 'Entrega en Bodega' : $paqueteria;

//se valida el obsequio
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

//actualizamos el obsequio en la tabla del usuario
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
        <img src="http://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=<?php echo $orden ?>&.png',111,0.1,45,45" style="position: absolute;left: 503px;top: -10px;width: 150px;z-index: -1;">
        <div style="width:190px; float:right;">
            <table style="text-align:center;font-size: 10px;border-collapse: collapse;" border="1">
                <?php
                $sum = '0';
                $viejo = '0';
                $vfp = '0';
                $sqllxg = "SELECT DISTINCT(r.id_producto),d.id_almacen,d.cantidad as cantidad_almacen,d.orden,r.nombre,r.codigo,r.id_producto,a.nombre_almacen FROM registro_usuario r INNER JOIN almacen_descuentos d ON r.id_producto = d.id_producto AND r.orden = d.orden INNER JOIN almacenes a ON d.id_almacen = a.id_almacen WHERE d.orden='".$orden."' AND (codigo='UN-08' or codigo='UN-40' or codigo='UN-05' or codigo='UN-43' or codigo='PU-02' or codigo='PU-04' or codigo='PU-01' or codigo='PU-06' or codigo='PU-05' or codigo='PI-16')";

                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $codigo = $columnaxg['codigo'];
                    $nombre = $columnaxg['nombre'];
                    $cantidad = $columnaxg['cantidad_almacen'];
                    $producto_id = $columnaxg['id_producto'];
                    $nombre_almacen = $columnaxg['nombre_almacen'];
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
                            <div style="width: 65px;position: absolute;text-align: initial;"><?php echo $nombre_almacen ?></div>
                            <div style="margin-left: 50px;">N°<?php echo $cantidad ?></div>
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
                //consultamos si el pedido de este cliente tiene algun gel necesario
                //para mostrar en la parte derecha de la hoja de recoleccion 
                $sqlFilter = "SELECT nombre,codigo,cantidad,id_producto FROM registro_usuario WHERE orden='".$orden."' and (codigo='GEL-01' or codigo='GEL-11' or codigo='GEL-12' or codigo='GEL-13' or codigo='GEL-14' or codigo='GEL-15' or codigo='GEL-17' or codigo='GEL-17N' or codigo='GEL-17G' or codigo='GEL-17T' or codigo='GEL-22' or codigo='GEL-23' or codigo='GEL-28' or codigo='GEL-30' or codigo='GEL-31' or codigo='GEL-32' or codigo='GEL-33' or codigo='GEL-34' or codigo='GEL-35' or codigo='GEL-36' or codigo='GEL-37' or codigo='GEL-38') GROUP BY codigo order by codigo ASC";

                $resultFilter = mysqli_query($con,$sqlFilter);
                $i=0;
                //si obtenemos resultado llamaremos a la clase dinamicamente
                while($row = mysqli_fetch_assoc($resultFilter)){
                    //llamamos a la funcion que genera la celda, pasando dinamicamente orden, codigo, y substring 
                    //formatear texto del nombre
                    $celda = $product->getProductoDescription($con,$orden,$row['codigo'],$gelesSubString[$row['codigo']]);
                    echo $celda;
                }

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
                $sqllxg = "SELECT d.id_almacen,d.cantidad as cantidad_almacen,d.orden,r.nombre,r.codigo,r.id_producto,a.nombre_almacen FROM registro_usuario r LEFT JOIN almacen_descuentos d ON r.id_producto = d.id_producto AND r.orden = d.orden LEFT JOIN almacenes a ON d.id_almacen = a.id_almacen WHERE d.orden='".$orden."' and r.codigo!='GEL-01' and r.codigo!='GEL-11' and r.codigo!='GEL-12' and r.codigo!='GEL-13' and r.codigo!='GEL-14' and r.codigo!='GEL-15' and r.codigo!='GEL-17' and r.codigo!='GEL-17N' and r.codigo!='GEL-17G' and r.codigo!='GEL-17T' and r.codigo!='GEL-22' and r.codigo!='GEL-23' and r.codigo!='GEL-28' and r.codigo!='GEL-30' and r.codigo!='GEL-31' and r.codigo!='GEL-32' and r.codigo!='GEL-33' and r.codigo!='GEL-34' and r.codigo!='GEL-35' and r.codigo!='GEL-36' and r.codigo!='GEL-37' and r.codigo!='GEL-38' order by r.codigo ASC";
                $resultxg = $con->query($sqllxg);
                while ($columnaxg = mysqli_fetch_array($resultxg)) {
                    $producto_id    = $columnaxg['id_producto'];
                    $codigo         = $columnaxg['codigo'];
                    $nombre         = $columnaxg['nombre'];
                    $cantidad       = $columnaxg['cantidad_almacen'];
                    $nombre_almacen = $columnaxg['nombre_almacen'];
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
                            <div style="width: 9%;position: absolute;"><?php echo $nombre_almacen ?></div>
                            <div style="margin-left: 65px;">N°<?php echo $cantidad?></div>
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