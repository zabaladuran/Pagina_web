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

$result = $conn->query("SELECT * FROM equipos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulapp - Inventario</title> <!-- Cambiado a Inventario -->
    <link rel="stylesheet" href="../css/mantenimiento.css">
    <link rel="stylesheet" href="../css/modal.css">
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
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                                <td><?php echo htmlspecialchars($row['precio']); ?></td>
                                <td>
                                    <a href="#" class="edit-btn" data-id="<?php echo $row['id']; ?>" data-nombre="<?php echo $row['nombre']; ?>" data-descripcion="<?php echo $row['descripcion']; ?>" data-cantidad="<?php echo $row['cantidad']; ?>" data-precio="<?php echo $row['precio']; ?>">Editar</a> |
                                    <a href="inventario.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('¿Está seguro de que desea eliminar este dispositivo?');">Eliminar</a> <!-- Cambiado a inventario.php -->
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No hay dispositivos registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para editar -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Dispositivo</h2>
            <form method="POST" action="inventario.php"> <!-- Cambiado a inventario.php -->
                <input type="hidden" id="edit_id" name="edit_id">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required><br><br>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea><br><br>

                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" required><br><br>

                <label for="precio">Precio:</label>
                <input type="number" step="0.01" id="precio" name="precio" required><br><br>

                <input type="submit" value="Guardar Cambios">
            </form>
        </div>
    </div>

    <script>
        var modal = document.getElementById("editModal");
        var btns = document.querySelectorAll(".edit-btn");
        var span = document.getElementsByClassName("close")[0];
        var edit_id = document.getElementById("edit_id");
        var nombre = document.getElementById("nombre");
        var descripcion = document.getElementById("descripcion");
        var cantidad = document.getElementById("cantidad");
        var precio = document.getElementById("precio");

        // Al hacer clic en un botón de editar, abrir el modal y rellenar los campos
        btns.forEach(function(btn) {
            btn.onclick = function() {
                modal.style.display = "block";
                edit_id.value = btn.dataset.id;
                nombre.value = btn.dataset.nombre;
                descripcion.value = btn.dataset.descripcion;
                cantidad.value = btn.dataset.cantidad;
                precio.value = btn.dataset.precio;
            }
        });

        // Cerrar el modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Cerrar el modal si se hace clic fuera de él
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
