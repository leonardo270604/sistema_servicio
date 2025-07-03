<?php
session_start();
include 'conexion.php';

// Paginación
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 15;
$inicio = ($pagina - 1) * $por_pagina;

// Consulta principal con paginación
$sql = "SELECT * FROM historial_comentarios ORDER BY fecha_registro DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $inicio, $por_pagina);
$stmt->execute();
$resultado = $stmt->get_result();

// Total de registros para paginación
$total_sql = "SELECT COUNT(*) AS total FROM historial_comentarios";
$total_result = $conn->query($total_sql)->fetch_assoc();
$total_paginas = ceil($total_result['total'] / $por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Servicios Completados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <body class="bg-light">

<?php include 'menu_lateral.php'; ?>
<div style="margin-left: 250px; padding: 20px;"> 

    <h3>Historial de Servicios Completados</h3>
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID Servicio</th>
                <th>Solicitante</th>
                <th>Departamento</th>
                <th>Tipo de Servicio</th>
                <th>Fecha/Hora</th>
                <th>Técnico</th>
                <th>Comentario</th>
                <th>Fecha de Registro</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_servicio'] ?></td>
                    <td><?= htmlspecialchars($row['solicitante']) ?></td>
                    <td><?= htmlspecialchars($row['departamento']) ?></td>
                    <td><?= htmlspecialchars($row['tipo_servicio']) ?></td>
                    <td><?= $row['fecha_solicitud'] ?></td>
                    <td><?= htmlspecialchars($row['tecnico']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['comentario'])) ?></td>
                    <td><?= $row['fecha_registro'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Navegación -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
</body>
</html>
