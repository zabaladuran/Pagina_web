<?php
// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = ""; // tu contraseña
$dbname = "empresa_inventario";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se enviaron datos mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $tipo_mantenimiento_id = $_POST['tipo_mantenimiento_id'] ?? null;
    $equipo_id = $_POST['equipo_id'] ?? null; // Cambiado a equipo_id
    $fecha_mantenimiento = $_POST['fecha_mantenimiento'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $tecnico = $_POST['tecnico'] ?? null;

    // Validar campos
    if (empty($tipo_mantenimiento_id) || empty($equipo_id) || empty($fecha_mantenimiento) || empty($descripcion) || empty($tecnico)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Preparar la consulta
    $sql = "INSERT INTO mantenimientos (fecha_producto_id, tipo_mantenimiento_id, fecha_mantenimiento, descripcion, tecnico)
            VALUES (?, ?, ?, ?, ?)";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $equipo_id, $tipo_mantenimiento_id, $fecha_mantenimiento, $descripcion, $tecnico); // Actualizado a equipo_id

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Mantenimiento creado con éxito.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la sentencia
    $stmt->close();
}

// Obtener tipos de mantenimiento y equipos para los selects
$tipos_mantenimiento_result = $conn->query("SELECT id, nombre FROM tipos_mantenimiento");
$equipos_result = $conn->query("SELECT id, nombre FROM equipos");

// Cerrar conexión
$conn->close();
?>

<!-- Formulario HTML para crear mantenimiento -->
<form method="POST" action="">
    <label for="tipo_mantenimiento_id">Tipo de Mantenimiento:</label>
    <select name="tipo_mantenimiento_id" id="tipo_mantenimiento_id" required>
        <option value="">Seleccione un tipo de mantenimiento</option>
        <?php while ($tipo = $tipos_mantenimiento_result->fetch_assoc()): ?>
            <option value="<?= $tipo['id'] ?>"><?= $tipo['nombre'] ?></option>
        <?php endwhile; ?>
    </select><br>

    <label for="equipo_id">Seleccionar Equipo:</label>
    <select name="equipo_id" id="equipo_id" required>
        <option value="">Seleccione un equipo</option>
        <?php while ($equipo = $equipos_result->fetch_assoc()): ?>
            <option value="<?= $equipo['id'] ?>"><?= $equipo['nombre'] ?></option>
        <?php endwhile; ?>
    </select><br>

    <label for="fecha_mantenimiento">Fecha de Mantenimiento:</label>
    <input type="date" name="fecha_mantenimiento" id="fecha_mantenimiento" required><br>

    <label for="descripcion">Descripción:</label>
    <textarea name="descripcion" id="descripcion" required></textarea><br>

    <label for="tecnico">Técnico:</label>
    <input type="text" name="tecnico" id="tecnico" required><br>

    <input type="submit" value="Crear Mantenimiento">
</form>
