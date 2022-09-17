<?php
    $server = 'localhost';
    $user = 'root';
    $pass = '';
    $database = 'almacen';
    $con = mysqli_connect($server,$user,$pass,$database);
    mysqli_set_charset($con,"utf8");
?>