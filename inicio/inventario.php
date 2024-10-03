<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "empresa_inventario"); // Conexión con la base de datos

// Verificar si se está eliminando un dispositivo
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM equipos WHERE id = $delete_id");
    header("Location: inventario.php"); // Cambiado a inventario.php
    exit;
}

// Verificar si se está actualizando un dispositivo
if (isset($_POST['edit_id'])) {
    $id = $_POST['edit_id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    
    $conn->query("UPDATE equipos SET nombre='$nombre', descripcion='$descripcion', cantidad='$cantidad', precio='$precio' WHERE id = $id");
    header("Location: inventario.php"); // Cambiado a inventario.php
    exit;
}

// Consulta para obtener los dispositivos junto con su estado
$result = $conn->query("
    SELECT e.*, es.nombre AS estado_nombre
    FROM equipos e
    LEFT JOIN estados_equipos es ON e.estado_id = es.id
");

$totalPrecio = 0; // Variable para almacenar el precio total
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulapp - Inventario</title> <!-- Cambiado a Inventario -->
    <link rel="stylesheet" href="../css/mantenimiento.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Asegúrate de que este enlace esté presente -->
</head>
<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <header class="topbar">
                <h1>Gestión de Inventario</h1> <!-- Cambiado a Inventario -->
            </header>
            <div class="content">
                <table class="doc-table">
                    <thead>
                        <tr>
                            <th>Dispositivo</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Precio (UNI)</th>
                            <th>Precio Total</th>
                            <th>Estado</th> <!-- Nueva columna para Estado -->
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): 
                                $precioTotal = $row['precio'] * $row['cantidad']; // Calcular precio total por dispositivo
                                $totalPrecio += $precioTotal; // Calcular precio total acumulado
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                                <td><?php echo htmlspecialchars($row['precio']); ?></td>
                                <td><?php echo number_format($precioTotal, 2); ?> USD</td> <!-- Mostrar precio total por dispositivo -->
                                <td><?php echo htmlspecialchars($row['estado_nombre']); ?></td> <!-- Mostrar el estado del dispositivo -->
                                <td>
                                    <a href="edit_device.php?id=<?php echo $row['id']; ?>" class="edit-btn">Editar</a> | 
                                    <a href="inventario.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('¿Está seguro de que desea eliminar este dispositivo?');">Eliminar</a> 
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No hay dispositivos registrados.</td> <!-- Cambiado a colspan="7" para la nueva columna -->
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <h2>Precio Total General: <?php echo number_format($totalPrecio, 2); ?> USD</h2> <!-- Mostrar precio total general -->
            </div>
        </div>
    </div>

    <script>
        // Aquí puedes agregar cualquier script adicional si es necesario
    </script>
</body>
</html>
