<?php
include 'conexion.php';

// Variables para paginación y búsqueda
$registros_por_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $registros_por_pagina;
$busqueda = $_GET['busqueda'] ?? '';

// Contar total de registros para la paginación
$count_sql = "SELECT COUNT(*) as total FROM usuarios u 
              LEFT JOIN departamentos d ON u.IDDEPTO = d.IDDEPTO
              WHERE u.usuario LIKE '%$busqueda%'";
$total_resultado = $conn->query($count_sql)->fetch_assoc();
$total_usuarios = $total_resultado['total'];
$total_paginas = ceil($total_usuarios / $registros_por_pagina);

// Consulta de usuarios paginada y filtrada
$sql = "SELECT u.id_usuario, u.usuario, u.rol, d.DESCRIPCION AS departamento 
        FROM usuarios u 
        LEFT JOIN departamentos d ON u.IDDEPTO = d.IDDEPTO
        WHERE u.usuario LIKE '%$busqueda%' 
        ORDER BY u.id_usuario DESC
        LIMIT $inicio, $registros_por_pagina";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'menu_lateral.php'; ?>

<div style="margin-left: 250px; padding: 20px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de Usuarios</h2>
        <a href="agregar_usuarios.php" class="btn btn-success">Agregar usuario</a>
    </div>

    <!-- Formulario de búsqueda -->
    <form method="GET" class="mb-3 d-flex" role="search">
        <input type="text" name="busqueda" class="form-control me-2" placeholder="Buscar por nombre" value="<?= htmlspecialchars($busqueda) ?>">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <!-- Tabla de resultados -->
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Departamento</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $fila['id_usuario'] ?></td>
                <td><?= $fila['usuario'] ?></td>
                <td><?= $fila['departamento'] ?? 'Sin asignar' ?></td>
                <td><?= $fila['rol'] ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?= $fila['id_usuario'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="eliminar_usuario.php?id=<?= $fila['id_usuario'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
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
                    <a class="page-link" href="?pagina=<?= $i ?>&busqueda=<?= urlencode($busqueda) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

</body>
</html>
