<?php
require 'vendor/autoload.php'; // Asegúrate de que esta ruta es correcta
use Dompdf\Dompdf;

include 'conexion.php';

if (!isset($_GET['id_servicio'])) {
    die('ID de servicio no proporcionado');
}

$id_servicio = intval($_GET['id_servicio']);

// Registrar fecha de finalización
$fecha_fin = date('Y-m-d H:i:s');
$update = $conn->prepare("UPDATE servicios SET fecha_fin = ?, estado = 'terminado' WHERE id_servicio = ?");
$update->bind_param("si", $fecha_fin, $id_servicio);
$update->execute();

// Obtener los datos del servicio con técnico
$sql = "SELECT 
            s.id_servicio,
            u.usuario AS solicitante,
            d.DESCRIPCION AS departamento,
            ts.nombre_servicio,
            s.fecha_hora,
            s.fecha_inicio,
            s.fecha_fin,
            t.nombre AS tecnico
        FROM servicios s
        INNER JOIN usuarios u ON s.id_usuario = u.idusuario
        INNER JOIN departamentos d ON u.IDDEPTO = d.IDDEPTO
        INNER JOIN tiposervicio ts ON s.id_tipo_servicio = ts.id_tipo_servicio
        INNER JOIN tecnicos t ON s.id_tecnico = t.id_tecnico
        WHERE s.id_servicio = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_servicio);
$stmt->execute();
$result = $stmt->get_result();
$servicio = $result->fetch_assoc();

if (!$servicio) {
    die("Servicio no encontrado.");
}

// Generar contenido HTML para el PDF
$html = '
    <h1 style="text-align: center;">Reporte de Servicio</h1>
    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <tr><td><strong>ID Servicio:</strong></td><td>' . $servicio['id_servicio'] . '</td></tr>
        <tr><td><strong>Solicitante:</strong></td><td>' . $servicio['solicitante'] . '</td></tr>
        <tr><td><strong>Departamento:</strong></td><td>' . $servicio['departamento'] . '</td></tr>
        <tr><td><strong>Tipo de Servicio:</strong></td><td>' . $servicio['nombre_servicio'] . '</td></tr>
        <tr><td><strong>Fecha de Solicitud:</strong></td><td>' . $servicio['fecha_hora'] . '</td></tr>
        <tr><td><strong>Fecha de Asignación:</strong></td><td>' . $servicio['fecha_inicio'] . '</td></tr>
        <tr><td><strong>Fecha de Finalización:</strong></td><td>' . $servicio['fecha_fin'] . '</td></tr>
        <tr><td><strong>Técnico Responsable:</strong></td><td>' . $servicio['tecnico'] . '</td></tr>
    </table>
    <br><p style="text-align: center;"></p>
';

// Generar y mostrar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("reporte_servicio_{$servicio['id_servicio']}.pdf", ["Attachment" => false]);
exit;
