<?php
session_start();
include 'conexion.php';
date_default_timezone_set('America/Mexico_City');

// Verificar sesión activa
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Variables de sesión
$nombre_usuario = $_SESSION['nombre_usuario'];
$nombre_departamento = $_SESSION['nombre_departamento'] ?? 'Desconocido';
$fecha_actual = date('Y-m-d H:i:s');

// Obtener tipos de servicio desde BD
$tipos_servicio = [];
$sql = "SELECT id_tipo_servicio, nombre_servicio FROM tiposervicio ORDER BY nombre_servicio ASC";
$resultado = $conn->query($sql);
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $tipos_servicio[] = $fila;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Servicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 30px;
        }
    </style>
</head>
<body>
    <!-- Alerta de confirmación -->
    <?php if (isset($_GET['solicitud']) && $_GET['solicitud'] === 'enviada'): ?>
    <script>
        alert('¡La solicitud de servicio ha sido enviada correctamente!');
    </script>
    <?php endif; ?>

    <!-- Botón Cerrar sesión -->
    <div class="logout-btn">
        <form action="login.php" method="post">
            <button type="submit" class="btn btn-danger btn-sm">Cerrar Sesión</button>
        </form>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Bienvenido <?php echo $nombre_usuario . " - " . $nombre_departamento; ?></h4>
                    </div>
                    <div class="card-body">
                        <h5 class="text-center mb-4">Seleccione el tipo de servicio que necesita</h5>

                        <!-- Información del solicitante -->
                        <div class="mb-4">
                            <p><strong>Nombre del solicitante:</strong> <?php echo $nombre_usuario; ?></p>
                            <p><strong>Departamento:</strong> <?php echo $nombre_departamento; ?></p>
                            <p><strong>Fecha y hora de solicitud:</strong> <?php echo $fecha_actual; ?></p>
                        </div>

                        <!-- Formulario -->
                        <form action="registrar_servicio.php" method="post">
                            <div class="mb-3">
                                <label for="tipo_servicio" class="form-label">Tipo de servicio</label>
                                <select class="form-select" id="tipo_servicio" name="tipo_servicio" required>
                                    <option value="">Seleccione una opción</option>
                                    <?php foreach ($tipos_servicio as $tipo): ?>
                                        <option value="<?php echo $tipo['id_tipo_servicio']; ?>">
                                            <?php echo htmlspecialchars($tipo['nombre_servicio']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Solicitar servicio</button>
                                <a href="login.php" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>

                        <?php if (empty($tipos_servicio)): ?>
                            <div class="alert alert-warning mt-3">No hay tipos de servicio registrados.</div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
