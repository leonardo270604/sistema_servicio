<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $contrasena = trim($_POST['contrasena']);

    $sql = "SELECT u.*, d.DESCRIPCION AS nombre_departamento 
            FROM usuarios u
            INNER JOIN departamentos d ON u.IDDEPTO = d.IDDEPTO
            WHERE u.usuario = ? AND u.contrasena = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("ss", $usuario, $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $fila = $result->fetch_assoc();

        // ✅ Guardar sesión correctamente
        $_SESSION['loggedin'] = true;
        $_SESSION['id_usuario'] = $fila['id_usuario'];
        $_SESSION['nombre_usuario'] = $fila['usuario']; // ← AQUÍ SE CORRIGE
        $_SESSION['usuario'] = $fila['usuario'];
        $_SESSION['rol'] = $fila['rol'];
        $_SESSION['nombre_departamento'] = $fila['nombre_departamento'];

        // 🔁 Redirección por rol y nombre
        if ($fila['rol'] === 'admin') {
            if ($fila['usuario'] === 'Jaime') {
                header("Location: panel_admin.php");
            } else {
                header("Location: perfil_tecnico.php");
            }
        } elseif ($fila['rol'] === 'técnico') {
            header("Location: perfil_tecnico.php");
        } elseif ($fila['rol'] === 'usuario') {
            header("Location: servicio.php");
        } else {
            echo "Rol desconocido.";
        }
        exit();
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='login.php';</script>";
    }
}
?>
