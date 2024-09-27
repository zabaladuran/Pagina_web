<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $conn = new mysqli("127.0.0.1", "root", "", "empresa_inventario");

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Recoger datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $proveedor_id = $_POST['proveedor'];
    $categoria_id = $_POST['categoria'];
    $marca_id = $_POST['marca'];
    $precio = $_POST['precio'];
    $caracteristicas = $_POST['caracteristicas'];

    // Insertar el dispositivo en la tabla 'equipos'
    $sql = "INSERT INTO equipos (nombre, descripcion, cantidad, proveedor_id, categoria_id, marca_id, precio, caracteristicas)
            VALUES ('$nombre', '$descripcion', $cantidad, $proveedor_id, $categoria_id, $marca_id, $precio, '$caracteristicas')";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo dispositivo añadido correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cerrar conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Dispositivo</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <!-- Capa semi-transparente -->
    <div class="overlay"></div>

    <!-- Contenido principal -->
    <div class="container">
        <h2>Añadir Nuevo Dispositivo</h2>
        <form action="nuevo_dispositivo.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required><br>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" required></textarea><br>

            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" required><br>

            <label for="proveedor">Proveedor:</label>
            <select name="proveedor" required>
                <!-- Llenar con datos -->
            </select><br>

            <label for="categoria">Categoría:</label>
            <select name="categoria" required>
                <!-- Llenar con datos -->
            </select><br>

            <label for="marca">Marca:</label>
            <select name="marca" required>
                <!-- Llenar con datos -->
            </select><br>

            <label for="precio">Precio:</label>
            <input type="number" step="0.01" name="precio" required><br>

            <label for="caracteristicas">Características:</label>
            <textarea name="caracteristicas" required></textarea><br>

            <button type="submit">Añadir Dispositivo</button>
            <button type="button" class="btn" onclick="window.location.href='home.php';">Regresar al Home</button>
        </form>
    </div>
</body>
</html>
