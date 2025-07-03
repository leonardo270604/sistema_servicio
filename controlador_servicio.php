<?php
session_start();
date_default_timezone_set('America/Mexico_City');
include 'conexion.php';

// ------------------------------------------
// CONFIRMAR SERVICIO ATENDIDO (COMPLETAR)
// ------------------------------------------
if (isset($_POST['completar_servicio']) && isset($_POST['comentario'])) {
    $id_servicio = $_POST['completar_servicio'];
    $comentario = trim($_POST['comentario']);
    $fecha_fin = date('Y-m-d H:i:s');

    // 1. Actualizar en la tabla servicios
    $update_sql = "UPDATE servicios SET estado = 'completado', comentario = ? WHERE id_servicio = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("si", $comentario, $id_servicio);
    $stmt->execute();

    // 2. Insertar en historial_comentarios (opcional pero recomendable)
    $query = "SELECT 
                s.id_servicio, 
                us.usuario AS solicitante, 
                d.DESCRIPCION AS departamento, 
                ts.nombre_servicio AS tipo_servicio, 
                s.fecha_solicitud, 
                ut.usuario AS tecnico
              FROM servicios s
              INNER JOIN usuarios us ON s.id_usuario = us.id_usuario
              INNER JOIN usuarios ut ON s.id_tecnico = ut.id_usuario
              INNER JOIN departamentos d ON us.IDDEPTO = d.IDDEPTO
              INNER JOIN tiposervicio ts ON s.id_tipo_servicio = ts.id_tipo_servicio
              WHERE s.id_servicio = ?";
    $stmt_info = $conn->prepare($query);
    $stmt_info->bind_param("i", $id_servicio);
    $stmt_info->execute();
    $result = $stmt_info->get_result();

    if ($row = $result->fetch_assoc()) {
        $insert_historial = "INSERT INTO historial_comentarios 
            (id_servicio, solicitante, departamento, tipo_servicio, fecha_solicitud, tecnico, comentario, fecha_registro) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt_historial = $conn->prepare($insert_historial);
        $stmt_historial->bind_param(
            "issssss",
            $row['id_servicio'],
            $row['solicitante'],
            $row['departamento'],
            $row['tipo_servicio'],
            $row['fecha_solicitud'],
            $row['tecnico'],
            $comentario
        );
        $stmt_historial->execute();
    }

    header("Location: perfil_tecnico.php");
    exit();
}

// ------------------------------------------
// GUARDAR COMENTARIO EDITADO (TÉCNICO)
// ------------------------------------------
if (isset($_POST['editar_comentario_id']) && isset($_POST['nuevo_comentario'])) {
    $id_servicio = $_POST['editar_comentario_id'];
    $nuevo_comentario = trim($_POST['nuevo_comentario']);

    $update_sql = "UPDATE servicios SET comentario = ? WHERE id_servicio = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("si", $nuevo_comentario, $id_servicio);
    $stmt_update->execute();

    $query = "SELECT 
                s.id_servicio, 
                us.usuario AS solicitante, 
                d.DESCRIPCION AS departamento, 
                ts.nombre_servicio AS tipo_servicio, 
                s.fecha_solicitud, 
                ut.usuario AS tecnico
              FROM servicios s
              INNER JOIN usuarios us ON s.id_usuario = us.id_usuario
              INNER JOIN usuarios ut ON s.id_tecnico = ut.id_usuario
              INNER JOIN departamentos d ON us.IDDEPTO = d.IDDEPTO
              INNER JOIN tiposervicio ts ON s.id_tipo_servicio = ts.id_tipo_servicio
              WHERE s.id_servicio = ?";
    $stmt_info = $conn->prepare($query);
    $stmt_info->bind_param("i", $id_servicio);
    $stmt_info->execute();
    $result = $stmt_info->get_result();

    if ($row = $result->fetch_assoc()) {
        $insert_historial = "INSERT INTO historial_comentarios 
            (id_servicio, solicitante, departamento, tipo_servicio, fecha_solicitud, tecnico, comentario, fecha_registro) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt_historial = $conn->prepare($insert_historial);
        $stmt_historial->bind_param(
            "issssss",
            $row['id_servicio'],
            $row['solicitante'],
            $row['departamento'],
            $row['tipo_servicio'],
            $row['fecha_solicitud'],
            $_SESSION['usuario'],
            $nuevo_comentario
        );
        $stmt_historial->execute();
    }

    header("Location: perfil_tecnico.php");
    exit();
}

// ------------------------------------------
// EDITAR COMENTARIO (ADMINISTRADOR)
// ------------------------------------------
if (isset($_POST['editar_comentario']) && isset($_POST['comentario_editado'])) {
    $id_servicio = $_POST['editar_comentario'];
    $comentario_nuevo = $_POST['comentario_editado'];

    $stmt = $conn->prepare("SELECT s.*, u.usuario AS solicitante, d.DESCRIPCION AS departamento, ts.nombre_servicio, t.nombre AS tecnico
                            FROM servicios s
                            JOIN usuarios u ON s.id_usuario = u.id_usuario
                            JOIN departamentos d ON u.IDDEPTO = d.IDDEPTO
                            JOIN tiposervicio ts ON s.id_tipo_servicio = ts.id_tipo_servicio
                            JOIN usuarios t ON s.id_tecnico = t.id_usuario
                            WHERE s.id_servicio = ?");
    $stmt->bind_param("i", $id_servicio);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    $update = $conn->prepare("UPDATE servicios SET comentario = ? WHERE id_servicio = ?");
    $update->bind_param("si", $comentario_nuevo, $id_servicio);
    $update->execute();

    $insert = $conn->prepare("INSERT INTO historial_comentarios 
        (id_servicio, solicitante, departamento, tipo_servicio, fecha_solicitud, tecnico, comentario, fecha_registro) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $insert->bind_param("issssss",
        $id_servicio,
        $row['usuario'],
        $row['DESCRIPCION'],
        $row['nombre_servicio'],
        $row['fecha_solicitud'],
        $row['nombre'],
        $comentario_nuevo
    );
    $insert->execute();

    header("Location: servicios_pendientes.php");
    exit();
}
?>

