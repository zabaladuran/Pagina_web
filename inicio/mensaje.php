<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensaje</title>
    <link rel="stylesheet" href="../css/mensaje.css">
</head>
<body>
    <div class="container">
        <h2>Resultado de la Operación</h2>
        <?php
        if (isset($_SESSION['message'])) {
            echo "<p>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']); 
        } else {
            echo "<p>No hay mensaje para mostrar.</p>";
        }
        ?>
        <button type="button" class="btn" onclick="window.location.href='home.php';">Regresar al Home</button>
    </div>
</body>
</html>
