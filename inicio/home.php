<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    // Si no hay sesión iniciada, redirigir al inicio de sesión
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulapp - Gestión de Inventarios y Mantenimiento</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Iconos -->
</head>
<body>
    <div class="dashboard-container">
        <!-- Barra lateral -->
        <nav class="sidebar">
            <div class="logo">
                <img src="../img/Captura_de_pantalla_2024-09-18_224818-removebg-preview.png" alt="inventory Logo">
            </div>
            <ul>
                <li><a href="perfil.php"><i class="fas fa-user"></i> Perfil</a></li> <!-- Enlace al módulo de perfil -->
                <li><a href="inventario.php"><i class="fas fa-boxes"></i> Inventario</a></li>
                <li><a href="mantenimiento.php"><i class="fas fa-tools"></i> Mantenimiento</a></li>
                <li><a href="reportes.php"><i class="fas fa-chart-line"></i> Reportes</a></li>
                <li><a href="configuracion.php"><i class="fas fa-cog"></i> Configuración</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <div class="main-content">
            <!-- Barra superior -->
            <header class="topbar">
                <h1>Panel de Gestión</h1>
                <div class="actions">
                    <a href="nuevo_dispositivo.php" class="btn">Nuevo Dispositivo</a>
                    <a href="generar_reporte.php" class="btn">Generar Reporte</a>
                    <a href="logout.php" class="btn">Salir</a>
                </div>
            </header>

            <!-- Panel principal -->
            <div class="content">
                <div class="widgets">
                    <div class="widget">
                        <h3>Dispositivos en Inventario</h3>
                        <p>150 dispositivos</p>
                    </div>
                    <div class="widget">
                        <h3>Dispositivos en Mantenimiento</h3>
                        <p>20 dispositivos</p>
                    </div>
                    <div class="widget">
                        <h3>Inventario Crítico</h3>
                        <p>5 dispositivos con stock bajo</p>
                    </div>
                </div>

                <div class="alert">
                    <h2>Alertas de Inventario y Mantenimiento</h2>
                    <p>Dispositivos que requieren atención inmediata.</p>
                </div>

                <h3>Detalles del Inventario</h3>
                <table class="doc-table">
                    <thead>
                        <tr>
                            <th>Dispositivo</th>
                            <th>Estado</th>
                            <th>Último Mantenimiento</th>
                            <th>Próximo Mantenimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>PC - Oficina 101</td>
                            <td>En uso</td>
                            <td>2024-01-15</td>
                            <td>2024-07-15</td>
                        </tr>
                        <tr>
                            <td>Impresora - Piso 3</td>
                            <td>Mantenimiento pendiente</td>
                            <td>2024-03-10</td>
                            <td>2024-09-10</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>