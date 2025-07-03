
<?php 
if (session_status() === PHP_SESSION_NONE) session_start();

include 'conexion.php';

function toggle_dir($actual, $campo, $dir) {
    return ($actual === $campo && $dir === 'ASC') ? 'DESC' : 'ASC';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['asignar_servicio'])) {
        $id_servicio = $_POST['id_servicio'];
        $id_tecnico = $_POST['tecnico'];
        $fecha_solicitud = date('Y-m-d H:i:s');

        $stmt = $conn->prepare("UPDATE servicios SET id_tecnico = ?, fecha_solicitud = ? WHERE id_servicio = ?");
        if ($stmt) {
            $stmt->bind_param("isi", $id_tecnico, $fecha_solicitud, $id_servicio);
            $stmt->execute();
            header("Location: servicios_pendientes.php?" . http_build_query($_GET));
            exit();
        }
    }

    if (isset($_POST['completar_servicio'])) {
        $id_servicio = $_POST['completar_servicio'];
        $comentario = trim($_POST['comentario']);
        $stmt = $conn->prepare("UPDATE servicios SET estado = 'completado', comentario = ? WHERE id_servicio = ?");
        $stmt->bind_param("si", $comentario, $id_servicio);
        $stmt->execute();
        header("Location: servicios_pendientes.php?" . http_build_query($_GET));
        exit();
    }

    if (isset($_POST['editar_comentario'])) {
        $id_servicio = $_POST['editar_comentario'];
        $comentario = trim($_POST['comentario_editado']);

        $datos = $conn->prepare("SELECT s.id_servicio, us.usuario AS solicitante, d.DESCRIPCION AS departamento, 
                                ts.nombre_servicio, s.fecha_solicitud, ut.usuario AS tecnico
                                FROM servicios s
                                INNER JOIN usuarios us ON s.id_usuario = us.id_usuario
                                INNER JOIN usuarios ut ON s.id_tecnico = ut.id_usuario
                                INNER JOIN departamentos d ON us.IDDEPTO = d.IDDEPTO
                                INNER JOIN tiposervicio ts ON s.id_tipo_servicio = ts.id_tipo_servicio
                                WHERE s.id_servicio = ?");
        $datos->bind_param("i", $id_servicio);
        $datos->execute();
        $info = $datos->get_result()->fetch_assoc();

        $insert = $conn->prepare("INSERT INTO historial_comentarios 
            (id_servicio, solicitante, departamento, tipo_servicio, fecha_hora, tecnico, comentario)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param(
            "issssss",
            $info['id_servicio'],
            $info['solicitante'],
            $info['departamento'],
            $info['nombre_servicio'],
            $info['fecha_solicitud'],
            $info['tecnico'],
            $comentario
        );
        $insert->execute();

        $stmt = $conn->prepare("UPDATE servicios SET comentario = ? WHERE id_servicio = ?");
        $stmt->bind_param("si", $comentario, $id_servicio);
        $stmt->execute();

        header("Location: servicios_pendientes.php?" . http_build_query($_GET));
        exit();
    }
}

$tecnico_filtro = $_GET['tecnico_filtro'] ?? '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 10;
$inicio = ($pagina - 1) * $por_pagina;

$orden_campo = $_GET['orden'] ?? 's.fecha_solicitud';
$direccion = $_GET['dir'] ?? 'ASC';
$direccion_sql = ($direccion === 'DESC') ? 'DESC' : 'ASC';
$campos_validos = ['u.usuario', 'd.DESCRIPCION', 'ts.nombre_servicio', 's.fecha_solicitud'];
if (!in_array($orden_campo, $campos_validos)) $orden_campo = 's.fecha_solicitud';

$where_tecnico = ($tecnico_filtro !== '') ? "s.id_tecnico = $tecnico_filtro" : "s.id_tecnico IS NULL";

$count_sql = "SELECT COUNT(*) AS total FROM servicios s
              INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
              INNER JOIN departamentos d ON u.IDDEPTO = d.IDDEPTO
              INNER JOIN tiposervicio ts ON s.id_tipo_servicio = ts.id_tipo_servicio
              WHERE $where_tecnico";
$total_result = $conn->query($count_sql)->fetch_assoc();
$total_paginas = ceil($total_result['total'] / $por_pagina);

$sql = "SELECT s.*, u.usuario, d.DESCRIPCION AS departamento, ts.nombre_servicio
        FROM servicios s
        INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
        INNER JOIN departamentos d ON u.IDDEPTO = d.IDDEPTO
        INNER JOIN tiposervicio ts ON s.id_tipo_servicio = ts.id_tipo_servicio
        WHERE $where_tecnico
        ORDER BY $orden_campo $direccion_sql
        LIMIT $inicio, $por_pagina";
$result = $conn->query($sql);

$tecnicos = $conn->query("SELECT id_usuario AS id_tecnico, usuario AS nombre FROM usuarios WHERE rol = 'técnico'");
$tecnicos_array = [];
while ($t = $tecnicos->fetch_assoc()) $tecnicos_array[] = $t;
?>
