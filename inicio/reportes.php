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
    <title>Aulapp - Reportes</title>
    <link rel="stylesheet" href="../css/reportess.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <header class="topbar">
                <h1>Generación de Reportes</h1>
            </header>
            <div class="content">
                <p>Gráficos y estadísticas aquí</p>
            </div>
        </div>
    </div>
</body>
</html>
