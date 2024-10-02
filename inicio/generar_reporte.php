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

// Consulta SQL para obtener datos de los dispositivos
$sql = "SELECT e.nombre, e.descripcion, e.cantidad, e.precio, 
               (e.cantidad * e.precio) AS precio_total,  -- Calcular precio total
                p.nombre AS proveedor, c.nombre AS categoria, m.nombre AS marca
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
                    <th>Precio Total</th> <!-- Nueva columna para Precio Total -->
                    <th>Proveedor</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['nombre']) . "</td>
                                <td>" . htmlspecialchars($row['descripcion']) . "</td>
                                <td>" . htmlspecialchars($row['cantidad']) . "</td>
                                <td>" . number_format($row['precio'], 2) . "</td>
                                <td>" . number_format($row['precio_total'], 2) . "</td> <!-- Mostrar precio total -->
                                <td>" . htmlspecialchars($row['proveedor']) . "</td>
                                <td>" . htmlspecialchars($row['categoria']) . "</td>
                                <td>" . htmlspecialchars($row['marca']) . "</td>
                                </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No hay dispositivos registrados</td></tr>"; // Cambiar a colspan='8'
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
