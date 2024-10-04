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
    $tipo_mantenimiento_id = $_POST['tipo_mantenimiento']; // ID del tipo de mantenimiento
    $descripcion = $_POST['descripcion'];
    $tecnico_id = $_POST['tecnico_id'];
    $estado_id = $_POST['estado_mantenimiento']; // Estado del mantenimiento (ID)
    $equipo_id = $_POST['equipo_id']; // ID del equipo seleccionado
    $ultimo_mantenimiento = $_POST['ultimo_mantenimiento']; // Fecha del último mantenimiento
    $proximo_mantenimiento = $_POST['proximo_mantenimiento']; // Fecha del próximo mantenimiento
    $fecha_mantenimiento = date('Y-m-d'); // Asignamos la fecha actual

    // Consulta para insertar el mantenimiento
    $sql = "INSERT INTO mantenimientos (tipo_mantenimiento_id, descripcion, tecnico_id, estado_id, equipo_id, fecha_mantenimiento, ultimo_mantenimiento, proximo_mantenimiento)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Verificamos si la preparación fue exitosa
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // La llamada a bind_param ahora tiene 8 parámetros
    $stmt->bind_param("ississss", $tipo_mantenimiento_id, $descripcion, $tecnico_id, $estado_id, $equipo_id, $fecha_mantenimiento, $ultimo_mantenimiento, $proximo_mantenimiento);

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

// Consulta para obtener técnicos
$tecnicos_result = $conn->query("SELECT id, nombre FROM tecnicos");
if (!$tecnicos_result) {
    die("Error en la consulta de técnicos: " . $conn->error);
}
$tecnicos = [];
while ($row = $tecnicos_result->fetch_assoc()) {
    $tecnicos[] = $row;
}

// Consulta para obtener estados de mantenimiento
$estados_result = $conn->query("SELECT id, nombre FROM estados_equipos");
if (!$estados_result) {
    die("Error en la consulta de estados: " . $conn->error);
}
$estados = [];
while ($row = $estados_result->fetch_assoc()) {
    $estados[] = $row;
}

// Consulta para obtener equipos
$equipos_result = $conn->query("SELECT id, nombre FROM equipos");
if (!$equipos_result) {
    die("Error en la consulta de equipos: " . $conn->error);
}
$equipos = [];
while ($row = $equipos_result->fetch_assoc()) {
    $equipos[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Mantenimiento</title>
    <link rel="stylesheet" href="../css/crear_mantenimiento.css">
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

        <label for="tecnico_id">Seleccionar Técnico:</label>
        <select id="tecnico_id" name="tecnico_id" required>
            <?php foreach ($tecnicos as $tecnico): ?>
                <option value="<?= $tecnico['id']; ?>"><?= $tecnico['nombre']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="estado_mantenimiento">Estado del Mantenimiento:</label>
        <select id="estado_mantenimiento" name="estado_mantenimiento" required>
            <?php foreach ($estados as $estado): ?>
                <option value="<?= $estado['id']; ?>"><?= ucfirst($estado['nombre']); ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="equipo_id">Seleccionar Equipo:</label>
        <select id="equipo_id" name="equipo_id" required>
            <?php foreach ($equipos as $equipo): ?>
                <option value="<?= $equipo['id']; ?>"><?= $equipo['nombre']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="ultimo_mantenimiento">Último Mantenimiento:</label>
        <input type="date" id="ultimo_mantenimiento" name="ultimo_mantenimiento" required><br>

        <label for="proximo_mantenimiento">Próximo Mantenimiento:</label>
        <input type="date" id="proximo_mantenimiento" name="proximo_mantenimiento" required><br>

        <button type="submit">Registrar Mantenimiento</button>
    </form>
        <!-- Botón para volver -->
        <form action="mantenimiento.php" method="get">
            <button type="submit" class="btn-volver">Volver a Mantenimiento</button>
        </form>
</body>
</html>
