<?php
session_start();
include 'conexion.php';
include 'sidebar_tecnico.php';

// Paginación
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 10;
$inicio = ($pagina - 1) * $por_pagina;

// Servicios asignados
$id_tecnico = $_SESSION['id_usuario'];
$sql = "SELECT s.*, u.usuario AS solicitante, d.DESCRIPCION AS departamento, ts.nombre_servicio
        FROM servicios s
        INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
        INNER JOIN departamentos d ON u.IDDEPTO = d.IDDEPTO
        INNER JOIN tiposervicio ts ON s.id_tipo_servicio = ts.id_tipo_servicio
        WHERE s.id_tecnico = ?
        ORDER BY s.fecha_solicitud DESC
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $id_tecnico, $inicio, $por_pagina);
$stmt->execute();
$result = $stmt->get_result();

// Total
$total_sql = "SELECT COUNT(*) AS total FROM servicios WHERE id_tecnico = ?";
$total_stmt = $conn->prepare($total_sql);
$total_stmt->bind_param("i", $id_tecnico);
$total_stmt->execute();
$total_result = $total_stmt->get_result()->fetch_assoc();
$total_paginas = ceil($total_result['total'] / $por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil Técnico</title>
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
    <h4>Servicios asignados</h4>

    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Fecha de asignación</th>
                <th>Solicitante</th>
                <th>Departamento</th>
                <th>Estado</th>
                <th>Acción</th>
                <th>Comentario</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="<?= $row['estado'] === 'completado' ? 'table-success' : '' ?>">
                    <td><?= $row['id_servicio'] ?></td>
                    <td><?= htmlspecialchars($row['nombre_servicio']) ?></td>
                    <td><?= $row['fecha_solicitud'] ?></td>
                    <td><?= htmlspecialchars($row['solicitante']) ?></td>
                    <td><?= htmlspecialchars($row['departamento']) ?></td>
                    <td><?= $row['estado'] ?></td>
                    <td>
                        <?php if ($row['estado'] === 'pendiente'): ?>
                            <button class="btn btn-success btn-sm"
                                onclick="mostrarModal(
                                    <?= $row['id_servicio'] ?>,
                                    '<?= htmlspecialchars($row['solicitante'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($row['nombre_servicio'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($row['departamento'], ENT_QUOTES) ?>',
                                    '<?= $row['fecha_solicitud'] ?>',
                                    '<?= htmlspecialchars($_SESSION['usuario'], ENT_QUOTES) ?>'
                                )">
                                Completar
                            </button>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= isset($row['comentario']) && $row['comentario'] !== '' ? nl2br(htmlspecialchars($row['comentario'], ENT_QUOTES)) : '-' ?>
                        <?php
                            $id_servicio = $row['id_servicio'];
                            $comentario = json_encode($row['comentario'] ?? "", JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
                        ?>
                        <button class="btn btn-warning btn-sm ms-2"
                            onclick='abrirModalEditar(<?= $id_servicio ?>, <?= $comentario ?>)'>
                            Editar
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="page-item <?= ($i === $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Modal: Completar Servicio -->
<div class="modal fade" id="modalComentario" tabindex="-1" aria-labelledby="modalComentarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="controlador_servicio.php">
        <div class="modal-header">
          <h5 class="modal-title" id="modalComentarioLabel">Confirmar Servicio Atendido</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="completar_servicio" id="modal_id_servicio">
          <p><strong>ID del Servicio:</strong> <span id="texto_id"></span></p>
          <p><strong>Solicitante:</strong> <span id="texto_solicitante"></span></p>
          <p><strong>Tipo de servicio:</strong> <span id="texto_tipo"></span></p>
          <p><strong>Departamento:</strong> <span id="texto_depto"></span></p>
          <p><strong>Fecha y Hora:</strong> <span id="texto_fecha"></span></p>
          <p><strong>Técnico:</strong> <span id="texto_tecnico"></span></p>
          <div class="mb-3">
            <label for="comentario" class="form-label">¿Qué se realizó?</label>
            <textarea name="comentario" id="comentario" class="form-control" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Confirmar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Editar Comentario -->
<div class="modal fade" id="modalEditarComentario" tabindex="-1" aria-labelledby="modalEditarComentarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="controlador_servicio.php">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarComentarioLabel">Editar Comentario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="editar_comentario_id" id="editar_comentario_id">
          <div class="mb-3">
            <label for="nuevo_comentario" class="form-label">Nuevo comentario</label>
            <textarea name="nuevo_comentario" id="nuevo_comentario" class="form-control" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function mostrarModal(id, solicitante, tipo, depto, fecha, tecnico) {
    document.getElementById('modal_id_servicio').value = id;
    document.getElementById('texto_id').textContent = id;
    document.getElementById('texto_solicitante').textContent = solicitante;
    document.getElementById('texto_tipo').textContent = tipo;
    document.getElementById('texto_depto').textContent = depto;
    document.getElementById('texto_fecha').textContent = fecha;
    document.getElementById('texto_tecnico').textContent = tecnico;
    document.getElementById('comentario').value = '';
    new bootstrap.Modal(document.getElementById('modalComentario')).show();
}

function abrirModalEditar(id, comentario) {
    document.getElementById('editar_comentario_id').value = id;
    document.getElementById('nuevo_comentario').value = comentario || '';
    new bootstrap.Modal(document.getElementById('modalEditarComentario')).show();
}
</script>

</body>
</html>
