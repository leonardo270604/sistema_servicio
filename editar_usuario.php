<?php
include 'conexion.php';

$id = $_GET['id'];

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

// Obtener lista de departamentos
$departamentos = $conn->query("SELECT * FROM departamentos");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];
    $iddepto = $_POST['iddepto'];

    $stmt = $conn->prepare("UPDATE usuarios SET usuario = ?, contrasena = ?, rol = ?, IDDEPTO = ? WHERE id_usuario = ?");
    $stmt->bind_param("sssii", $nuevo_usuario, $contrasena, $rol, $iddepto, $id);
    $stmt->execute();

    header("Location: usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">

    <h2 class="mb-4">Editar Usuario</h2>

    <form method="post" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="usuario" class="form-label">Nombre de usuario</label>
            <input type="text" name="usuario" id="usuario" class="form-control" value="<?= $usuario['usuario'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="text" name="contrasena" id="contrasena" class="form-control" value="<?= $usuario['contrasena'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select name="rol" id="rol" class="form-select" required>
                <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>admin</option>
                <option value="tecnico" <?= $usuario['rol'] === 'tecnico' ? 'selected' : '' ?>>tecnico</option>
                <option value="usuario" <?= $usuario['rol'] === 'usuario' ? 'selected' : '' ?>>usuario</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="iddepto" class="form-label">Departamento</label>
            <select name="iddepto" id="iddepto" class="form-select" required>
                <?php while ($d = $departamentos->fetch_assoc()): ?>
                    <option value="<?= $d['IDDEPTO'] ?>" <?= $usuario['IDDEPTO'] == $d['IDDEPTO'] ? 'selected' : '' ?>>
                        <?= $d['DESCRIPCION'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
        <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
    </form>

</div>
</body>
</html>
