<!-- sidebar_tecnico.php -->
<div style="width: 220px; height: 100vh; background-color: #2c3e50; position: fixed; top: 0; left: 0; padding: 20px; color: white;">
    <h4><?php echo $_SESSION['nombre_usuario']; ?> <br> (<?php echo ucfirst($_SESSION['rol']); ?>)</h4>
    <hr style="border-color: gray;">
    <a href="perfil_tecnico.php" style="display: block; margin: 10px 0; color: white;">Servicios asignados</a>
    <a href="historial_tecnico.php" style="display: block; margin: 10px 0; color: white;">Historial</a>
    <a href="ajuste_tecnico.php" style="display: block; margin: 10px 0; color: white;">Ajustes</a>
    <a href="login.php" style="display: block; margin: 10px 0; color: white;">Cerrar sesión</a>
</div>
