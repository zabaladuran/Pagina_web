<?php
// Conexión a la base de datos
$host = 'localhost';
$db = 'empresa_inventario';
$user = 'root';
$pass = ''; // Contraseña vacía

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_mantenimiento = $_POST['tipo_mantenimiento'];
    $descripcion = $_POST['descripcion'];
    $tecnico_id = $_POST['tecnico_id'];
    $estado_mantenimiento = $_POST['estado_mantenimiento'];

    // Consulta para insertar el mantenimiento
    $sql = "INSERT INTO mantenimientos (tipo_mantenimiento, descripcion, tecnico_id, estado_mantenimiento)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $tipo_mantenimiento, $descripcion, $tecnico_id, $estado_mantenimiento);

    if ($stmt->execute()) {
        echo "Mantenimiento registrado correctamente.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Consulta para obtener tipos de mantenimiento
$tipos_mantenimiento_result = $conn->query("SELECT id, nombre FROM tipos_mantenimiento");
if (!$tipos_mantenimiento_result) {
    die("Error en la consulta de tipos de mantenimiento: " . $conn->error);
}
$tipos_mantenimiento = [];
while ($row = $tipos_mantenimiento_result->fetch_assoc()) {
    $tipos_mantenimiento[] = $row;
}

// Consulta para obtener técnicos (asumiendo que tienes una tabla de técnicos)
$tecnicos_result = $conn->query("SELECT id, nombre FROM tecnicos");
if (!$tecnicos_result) {
    die("Error en la consulta de técnicos: " . $conn->error);
}
$tecnicos = [];
while ($row = $tecnicos_result->fetch_assoc()) {
    $tecnicos[] = $row;
}

// Consulta para obtener estados de mantenimiento (asumiendo que tienes una tabla de estados)
$estados_result = $conn->query("SELECT DISTINCT estado FROM mantenimientos");
if (!$estados_result) {
    die("Error en la consulta de estados: " . $conn->error);
}
$estados = [];
while ($row = $estados_result->fetch_assoc()) {
    $estados[] = $row['estado'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Mantenimiento</title>
</head>
<body>
    <h1>Registrar Mantenimiento</h1>
    <form method="POST" action="">
        <label for="tipo_mantenimiento">Tipo de Mantenimiento:</label>
        <select id="tipo_mantenimiento" name="tipo_mantenimiento" required>
            <?php foreach ($tipos_mantenimiento as $tipo): ?>
                <option value="<?= $tipo['id']; ?>"><?= $tipo['nombre']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea><br>

        <label for="tecnico_id">ID del Técnico:</label>
        <select id="tecnico_id" name="tecnico_id" required>
            <?php foreach ($tecnicos as $tecnico): ?>
                <option value="<?= $tecnico['id']; ?>"><?= $tecnico['nombre']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="estado_mantenimiento">Estado del Mantenimiento:</label>
        <select id="estado_mantenimiento" name="estado_mantenimiento" required>
            <?php foreach ($estados as $estado): ?>
                <option value="<?= $estado; ?>"><?= ucfirst($estado); ?></option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Registrar Mantenimiento</button>
    </form>
</body>
</html>
