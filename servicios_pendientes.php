<?php
session_start();
date_default_timezone_set('America/Mexico_City');
include 'conexion.php';
include 'listar_servicios.php'; // Controlador con lógica y consultas
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Servicios Pendientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'menu_lateral.php'; ?>
<div style="margin-left: 250px; padding: 20px;">
    <h2 class="mb-3">Servicios pendientes</h2>

    <!-- Filtro de técnicos -->
    <form method="GET" class="mb-4">
        <input type="hidden" name="orden" value="<?= htmlspecialchars($orden_campo) ?>">
        <input type="hidden" name="dir" value="<?= htmlspecialchars($direccion_sql) ?>">
        <label class="form-label">Filtrar por técnico:</label>
        <select name="tecnico_filtro" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
            <option value="">Todos los técnicos</option>
            <?php foreach ($tecnicos_array as $tecnico): ?>
                <option value="<?= $tecnico['id_tecnico']; ?>" <?= ($tecnico_filtro == $tecnico['id_tecnico']) ? 'selected' : '' ?>>
                    <?= $tecnico['nombre']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <!-- Tabla de servicios -->
    <table class="table table-bordered">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Solicitante</th>
                <th>Departamento</th>
                <th>Tipo de Servicio</th>
                <th>Fecha/Hora</th>
                <th><?= !empty($tecnico_filtro) ? 'Estado del Servicio' : 'Técnico' ?></th>
                <th>Comentario</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="<?= ($row['estado'] === 'completado') ? 'table-success' : '' ?>">
                <td><?= $row['id_servicio']; ?></td>
                <td><?= $row['usuario']; ?></td>
                <td><?= $row['departamento']; ?></td>
                <td><?= $row['nombre_servicio']; ?></td>
                <td><?= $row['fecha_solicitud']; ?></td>
                <td>
                    <?php if (empty($tecnico_filtro)): ?>
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="id_servicio" value="<?= $row['id_servicio']; ?>">
                            <select name="tecnico" class="form-select me-2" required>
                                <option value="">Seleccione</option>
                                <?php foreach ($tecnicos_array as $tecnico): ?>
                                    <option value="<?= $tecnico['id_tecnico']; ?>"><?= $tecnico['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="asignar_servicio" class="btn btn-sm btn-primary">Asignar</button>
                        </form>
                    <?php elseif ($row['estado'] === 'completado'): ?>
                        <span class="text-success fw-bold">Completado</span>
                    <?php elseif ($row['estado'] === 'pendiente'): ?>
                        <span class="text-warning fw-bold">Pendiente</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?= !empty($row['comentario']) ? htmlspecialchars($row['comentario']) : '-' ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>&tecnico_filtro=<?= $tecnico_filtro ?>&orden=<?= $orden_campo ?>&dir=<?= $direccion_sql ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function mostrarDatosServicio(id, usuario, departamento, fecha, tecnico, servicio) {
    alert("ID: " + id + "\nSolicitante: " + usuario + "\nTipo: " + servicio + "\nDepto: " + departamento + "\nFecha: " + fecha + "\nTécnico: " + tecnico);
}

function editarComentario(id, comentario) {
    let nuevo = prompt("Editar comentario:", comentario);
    if (nuevo !== null) {
        const form = document.createElement('form');
        form.method = 'POST';
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'editar_comentario';
        idInput.value = id;

        const comentarioInput = document.createElement('input');
        comentarioInput.type = 'hidden';
        comentarioInput.name = 'comentario_editado';
        comentarioInput.value = nuevo;

        form.appendChild(idInput);
        form.appendChild(comentarioInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
</body>
</html>
