<?php
// conexion con base de datos 
include '../conexion/conn.php';
function enviarWhatsApp($telefono, $mensaje) {
    $instance_id = "instance120995"; // por ejemplo: instance123
    $token = "5ysnxhvj3w28xrae";             // por ejemplo: abcdef123456

    $url = "https://api.ultramsg.com/$instance_id/messages/chat";

    $data = [
        'token' => $token,
        'to' => $telefono, // formato internacional, ejemplo: +5215551234567
        'body' => $mensaje
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
$query = "SELECT p.id, p.nombre, p.almacen
    FROM productos p
    WHERE p.estatus = 1
      AND p.almacen > 10
      AND p.id NOT IN (
          SELECT DISTINCT ru.id_producto
          FROM (
              SELECT id_producto
              FROM registro_usuario
              ORDER BY fecha_procesado DESC
          ) AS ru
      )
    ORDER BY p.nombre ASC;
";

$stmt = $con->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$productos = $result->fetch_all(MYSQLI_ASSOC);

if (count($productos) > 0) {
    $mensaje = "📦 Productos activos sin ventas recientes:\n\n";

    foreach ($productos as $p) {
        $mensaje .= "🔸 {$p['nombre']} (Stock: {$p['almacen']})\n";
    }

    // Enviar por WhatsApp
    $telefono = "+525548807199"; // Tu número
    enviarWhatsApp($telefono, $mensaje);

    echo "✅ Reporte enviado con éxito.";
} else {
    echo "✔️ Todos los productos han tenido movimiento reciente.";
}


