<?php
session_start();
include 'conexion.php';
include 'sidebar_tecnico.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nuevo_usuario'])) {
        $nuevo_usuario = trim($_POST['nuevo_usuario']);
        if ($nuevo_usuario !== '') {
            $stmt = $conn->prepare("UPDATE usuarios SET usuario = ? WHERE id_usuario = ?");
            $stmt->bind_param("si", $nuevo_usuario, $id_usuario);
            if ($stmt->execute()) {
                $_SESSION['usuario'] = $nuevo_usuario;
                $mensaje = "Nombre de usuario actualizado correctamente.";
            }
        }
    }

    if (isset($_POST['contrasena_actual'], $_POST['nueva_contrasena'], $_POST['confirmar_contrasena'])) {
        $contrasena_actual = $_POST['contrasena_actual'];
        $nueva_contrasena = $_POST['nueva_contrasena'];
        $confirmar_contrasena = $_POST['confirmar_contrasena'];

        $stmt = $conn->prepare("SELECT contrasena FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->bind_result($hash_actual);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($contrasena_actual, $hash_actual)) {
            if ($nueva_contrasena === $confirmar_contrasena) {
                $nuevo_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios SET contrasena = ? WHERE id_usuario = ?");
                $stmt->bind_param("si", $nuevo_hash, $id_usuario);
                if ($stmt->execute()) {
                    $mensaje = "Contraseña actualizada correctamente.";
                }
            } else {
                $mensaje = "Las contraseñas no coinciden.";
            }
        } else {
            $mensaje = "Contraseña actual incorrecta.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ajustes del perfil técnico</title>
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

<!-- Sidebar incluida automáticamente -->
<div class="content">
    <h2>Ajustes del perfil técnico</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="nuevo_usuario" class="form-label">Nuevo nombre de usuario</label>
            <input type="text" class="form-control" id="nuevo_usuario" name="nuevo_usuario" value="<?= htmlspecialchars($_SESSION['usuario']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Nombre</button>
    </form>

    <hr>

    <form method="POST">
        <div class="mb-3">
            <label for="contrasena_actual" class="form-label">Contraseña actual</label>
            <input type="password" class="form-control" id="contrasena_actual" name="contrasena_actual" required>
        </div>
        <div class="mb-3">
            <label for="nueva_contrasena" class="form-label">Nueva contraseña</label>
            <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required>
        </div>
        <div class="mb-3">
            <label for="confirmar_contrasena" class="form-label">Confirmar nueva contraseña</label>
            <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
        </div>
        <button type="submit" class="btn btn-warning">Cambiar Contraseña</button>
    </form>
</div>

</body>
</html>

