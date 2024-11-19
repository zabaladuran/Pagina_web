<?php
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

// Verificar si se ha enviado el ID del mantenimiento para eliminar
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Asegurarse de que sea un número entero

    // Consulta SQL para eliminar el mantenimiento
    $sql = "DELETE FROM mantenimientos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // "i" indica que el parámetro es un entero

    if ($stmt->execute()) {
        // Puedes agregar un mensaje de éxito aquí
       // echo "<script>alert('Mantenimiento eliminado correctamente.');</script>";
    } else {
        //echo "<script>alert('Error al eliminar el mantenimiento: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Consulta SQL para obtener datos de mantenimiento
$sql = "SELECT m.id, e.nombre AS dispositivo, 
            m.fecha_mantenimiento AS ultimo_mantenimiento,
            DATE_ADD(m.fecha_mantenimiento, INTERVAL t.frecuencia_mantenimiento DAY) AS proximo_mantenimiento,
            t.nombre AS tipo_mantenimiento
        FROM mantenimientos m
        JOIN equipos e ON m.equipo_id = e.id
        JOIN fechas_productos fp ON e.id = fp.producto_equipo_id
        JOIN tipos_mantenimiento t ON m.tipo_mantenimiento_id = t.id";

$result = $conn->query($sql);

// Manejo de errores
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulapp - Mantenimiento</title>
    <link rel="stylesheet" href="../css/mantenimineto.css">
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
                <!-- Botón para crear un nuevo mantenimiento -->
                <div class="button-container">
                    <a href="crear_mantenimiento.php" class="btn btn-primary">Crear Mantenimiento</a>
                </div>

                <table class="doc-table">
                    <thead>
                        <tr>
                            <th>Dispositivo</th>
                            <th>Último Mantenimiento</th>
                            <th>Próximo Mantenimiento</th>
                            <th>Tipo de Mantenimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['dispositivo']) . "</td>
                                        <td>" . htmlspecialchars($row['ultimo_mantenimiento']) . "</td>
                                        <td>" . htmlspecialchars($row['proximo_mantenimiento']) . "</td>
                                        <td>" . htmlspecialchars($row['tipo_mantenimiento']) . "</td>
                                        <td>
                                            <div class='action-buttons'>
                                                <a href='edit_device.php?id=" . htmlspecialchars($row['id']) . "' class='edit-btn' title='Editar'>
                                                    <svg class='icon' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                                                        <path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path>
                                                        <path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path>
                                                    </svg>
                                                </a>
                                                <a href='inventario.php?delete_id=" . htmlspecialchars($row['id']) . "' class='delete-btn' title='Eliminar' onclick='return confirm(\"¿Está seguro de que desea eliminar este dispositivo?\");'>
                                                    <svg class='icon' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                                                        <polyline points='3 6 5 6 21 6'></polyline>
                                                        <path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No hay mantenimientos registrados</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
