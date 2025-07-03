<?php
session_start();
include 'conexion.php';
date_default_timezone_set('America/Mexico_City');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario']; // ✅ Ahora es correcto
$tipo_servicio = $_POST['tipo_servicio'];
$fecha = date('Y-m-d H:i:s');

$sql = "INSERT INTO servicios (id_usuario, id_tipo_servicio, fecha_solicitud, estado) VALUES (?, ?, ?, 'pendiente')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $id_usuario, $tipo_servicio, $fecha);

if ($stmt->execute()) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Solicitud Enviada</title>
        <meta http-equiv="refresh" content="3;url=servicio.php">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="container mt-5">
        <div class="alert alert-success text-center">
            <h4 class="mb-3">✅ Solicitud enviada correctamente</h4>
            <p>Serás redirigido al inicio de sesión en unos segundos...</p>
        </div>
    </div>
    </body>
    </html>
    <?php
} else {
    echo "<script>alert('❌ Error al registrar servicio'); window.location='servicio.php';</script>";
}
?>

