<?php
session_start();
include 'conexion.php';
include 'sidebar_tecnico.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_tecnico = $_SESSION['id_usuario'];

// Obtener servicios completados por el técnico
$sql = "SELECT s.*, u.usuario AS solicitante, d.DESCRIPCION AS departamento, ts.nombre_servicio
        FROM servicios s
        INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
        INNER JOIN departamentos d ON u.IDDEPTO = d.IDDEPTO
        INNER JOIN tiposervicio ts ON s.id_tipo_servicio = ts.id_tipo_servicio
        WHERE s.id_tecnico = ? AND s.estado = 'completado'
        ORDER BY s.fecha_solicitud DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tecnico);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial del Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        .content {
            margin-left: 220px;
            padding: 30px;
            width: calc(100% - 220px);
        }

        .sidebar h4 {
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            color: white;
            background-color: #495057;
            text-decoration: none;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background-color: #6c757d;
        }
    </style>
</head>
<body>

<div class="content">
    <h4>Historial de servicios completados</h4>

    <table class="table table-bordered">
        <thead class="table-success">
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Solicitante</th>
                <th>Departamento</th>
                <th>Comentario</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_servicio'] ?></td>
                        <td><?= htmlspecialchars($row['nombre_servicio']) ?></td>
                        <td><?= $row['fecha_solicitud'] ?></td>
                        <td><?= htmlspecialchars($row['solicitante']) ?></td>
                        <td><?= htmlspecialchars($row['departamento']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['comentario'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No hay servicios completados aún.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
