<?php
include 'conexion.php';

$sql = "SELECT id_tipo_servicio, nombre_servicio FROM tiposervicio";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tipos de Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'menu_lateral.php'; ?>

<div style="margin-left: 250px; padding: 20px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Tipos de servicios</h2>
        <a href="agregar_tipo.php" class="btn btn-success">Agregar servicio</a>
    </div>

    <table class="table table-bordered table-hover table-striped align-middle">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Nombre del servio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_tipo_servicio'] ?></td>
                    <td><?= $row['nombre_servicio'] ?></td>
                    <td>
                        <a href="editar_tipo.php?id=<?= $row['id_tipo_servicio'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar_tipo.php?id=<?= $row['id_tipo_servicio'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este tipo de servicio?')">Eliminar</a>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>


