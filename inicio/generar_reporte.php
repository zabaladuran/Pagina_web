<?php
// Iniciar sesión
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

// Conexión a la base de datos
$conn = new mysqli("127.0.0.1", "root", "", "empresa_inventario");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT e.nombre, e.descripcion, e.cantidad, e.precio, p.nombre AS proveedor, c.nombre AS categoria, m.nombre AS marca
        FROM equipos e
        JOIN proveedores p ON e.proveedor_id = p.id
        JOIN categorias c ON e.categoria_id = c.id
        JOIN marcas m ON e.marca_id = m.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Reporte</title>
    <link rel="stylesheet" href="../css/btton_reportes.css">
</head>
<body>
    <!-- Capa semi-transparente -->
    <div class="overlay"></div>

    <!-- Contenido principal -->
    <div class="container">
        <h2>Reporte de Dispositivos</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Proveedor</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row['nombre'] . "</td><td>" . $row['descripcion'] . "</td><td>" . $row['cantidad'] . "</td><td>" . $row['precio'] . "</td><td>" . $row['proveedor'] . "</td><td>" . $row['categoria'] . "</td><td>" . $row['marca'] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay dispositivos registrados</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Botón de regreso -->
        <button type="button" class="btn" onclick="window.location.href='home.php';">Regresar al Home</button>
    </div>
</body>
</html>

<?php
$conn->close();
?>
