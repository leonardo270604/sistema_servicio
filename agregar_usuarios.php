<?php
include 'conexion.php';

// Obtener lista de departamentos
$departamentos = $conn->query("SELECT * FROM departamentos");

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];
    $iddepto = $_POST['iddepto'];

    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, contrasena, rol, IDDEPTO) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $usuario, $contrasena, $rol, $iddepto);
    $stmt->execute();

    header("Location: usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">

    <h2 class="mb-4">Agregar Usuario</h2>

    <form method="post" class="card p-4 shadow-sm">

        <div class="mb-3">
            <label for="usuario" class="form-label">Nombre de usuario</label>
            <input type="text" name="usuario" id="usuario" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="text" name="contrasena" id="contrasena" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select name="rol" id="rol" class="form-select" required>
                <option value="admin">admin</option>
                <option value="tecnico">tecnico</option>
                <option value="usuario">usuario</option>

            </select>
        </div>

        <div class="mb-3">
            <label for="iddepto" class="form-label">Departamento</label>
            <select name="iddepto" id="iddepto" class="form-select" required>
                <option value="">Seleccione un departamento</option>
                <?php while ($d = $departamentos->fetch_assoc()): ?>
                    <option value="<?= $d['IDDEPTO'] ?>"><?= $d['DESCRIPCION'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar Usuario</button>
        <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
    </form>

</div>
</body>
</html>


