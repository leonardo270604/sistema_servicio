<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$tecnico_filtro = $_GET['tecnico_filtro'] ?? '';

// Obtener nombre del técnico seleccionado
$tecnico_nombre = '';
if (!empty($tecnico_filtro)) {
    $stmt = $conn->prepare("SELECT nombre FROM tecnicos WHERE id_tecnico = ?");
    $stmt->bind_param("i", $tecnico_filtro);
    $stmt->execute();
    $stmt->bind_result($tecnico_nombre);
    $stmt->fetch();
    $stmt->close();
}

$sql = "SELECT s.id_servicio, u.usuario, d.DESCRIPCION AS departamento, ts.nombre_servicio, s.fecha_hora, s.fecha_inicio
        FROM servicios s
        INNER JOIN usuarios u ON s.id_usuario = u.idusuario
        INNER JOIN departamentos d ON u.IDDEPTO = d.IDDEPTO
        INNER JOIN tiposervicio ts ON s.id_tipo_servicio = ts.id_tipo_servicio
        WHERE s.id_tecnico = ? AND s.id_servicio NOT IN (SELECT id_servicio FROM soluciones)
        ORDER BY s.fecha_inicio ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tecnico_filtro);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Servicios Asignados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center mb-4">Servicios Asignados a <?php echo htmlspecialchars($tecnico_nombre); ?></h3>

    <!-- Botón para regresar a servicios pendientes -->
    <a href="servicio_terminado.php" class="btn btn-secondary mb-3">← Regresar a servicios pendientes</a>
    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>ID Servicio</th>
                <th>Solicitante</th>
                <th>Departamento</th>
                <th>Tipo de Servicio</th>
                <th>Fecha/Hora Solicitud</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id_servicio']; ?></td>
                <td><?php echo $row['usuario']; ?></td>
                <td><?php echo $row['departamento']; ?></td>
                <td><?php echo $row['nombre_servicio']; ?></td>
                <td><?php echo $row['fecha_hora']; ?></td>
                <td>
                    <a href="finalizar_servicio.php?id_servicio=<?php echo $row['id_servicio']; ?>" class="btn btn-success">Generar reporte PDF</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>

