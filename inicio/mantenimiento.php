<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulapp - Mantenimiento</title>
    <link rel="stylesheet" href="../css/mantenimiento.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <header class="topbar">
                <h1>Gestión de Mantenimiento</h1>
            </header>
            <div class="content">
                <table class="doc-table">
                    <thead>
                        <tr>
                            <th>Dispositivo</th>
                            <th>Último Mantenimiento</th>
                            <th>Próximo Mantenimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Impresora - Piso 3</td>
                            <td>2024-03-10</td>
                            <td>2024-09-10</td>
                            <td><a href="#">Editar</a> | <a href="#">Eliminar</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
