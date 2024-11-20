<?php

$host = "localhost"; 
$user = "root"; 
$password = ""; 
$database = "empresa_inventario";


$conn = new mysqli($host, $user, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$equipos = [];
$tipos_mantenimiento = [];
$tecnicos = [];
$estados = [];


$result = $conn->query("SELECT id, nombre FROM equipos");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $equipos[] = $row;
    }
}


$result = $conn->query("SELECT id, nombre FROM tipos_mantenimiento");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tipos_mantenimiento[] = $row;
    }
}


$result = $conn->query("SELECT id, nombre FROM tecnicos");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tecnicos[] = $row;
    }
}


$result = $conn->query("SELECT id, nombre FROM estados_equipos");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $estados[] = $row;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $equipo_id = $_POST['equipo_id']; 
    $tipo_mantenimiento_id = $_POST['tipo_mantenimiento_id']; 
    $descripcion = $_POST['descripcion']; 
    $tecnico_id = $_POST['tecnico_id']; 
    $estado_id = $_POST['estado_id']; 
    $ultimo_mantenimiento = $_POST['fecha_mantenimiento']; 
    $proximo_mantenimiento = $_POST['proximo_mantenimiento']; 

    $stmt = $conn->prepare("INSERT INTO mantenimientos (equipo_id, tipo_mantenimiento_id, descripcion, tecnico_id, estado_id, ultimo_mantenimiento, proximo_mantenimiento) VALUES (?, ?, ?, ?, ?, ?, ?)");

    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    
    $stmt->bind_param("iisssss", 
        $equipo_id, 
        $tipo_mantenimiento_id, 
        $descripcion, 
        $tecnico_id, 
        $estado_id, 
        $ultimo_mantenimiento, 
        $proximo_mantenimiento
    );

    
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    
    $stmt->close();

    
    header("Location: mantenimiento.php");
    exit(); 
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crear Mantenimiento</title>
        <link rel="stylesheet" href="../css/crearmantenimiento.css"> 
    </head>

<form method="POST" action="">
        <h1>Crear Mantenimiento</h1>
        
        
        <label for="equipo_id">Equipo:</label>
        <select name="equipo_id" id="equipo_id" required>
            <option value="">Seleccione un equipo</option>
            <?php foreach ($equipos as $equipo): ?>
                <option value="<?php echo htmlspecialchars($equipo['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($equipo['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>

        
        <label for="tipo_mantenimiento_id">Tipo de Mantenimiento:</label>
        <select name="tipo_mantenimiento_id" id="tipo_mantenimiento_id" required>
            <option value="">Seleccione un tipo de mantenimiento</option>
            <?php foreach ($tipos_mantenimiento as $tipo): ?>
                <option value="<?php echo htmlspecialchars($tipo['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($tipo['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>

        
        <label for="fecha_mantenimiento">Fecha de Mantenimiento:</label>
        <input type="date" name="fecha_mantenimiento" id="fecha_mantenimiento" required>

        
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required></textarea>

        
        <label for="tecnico_id">Técnico:</label>
        <select name="tecnico_id" id="tecnico_id" required>
            <option value="">Seleccione un técnico</option>
            <?php foreach ($tecnicos as $tecnico): ?>
                <option value="<?php echo htmlspecialchars($tecnico['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($tecnico['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>

        
        <label for="estado_id">Estado:</label>
        <select name="estado_id" id="estado_id" required>
            <option value="">Seleccione un estado</option>
            <?php foreach ($estados as $estado): ?>
                <option value="<?php echo htmlspecialchars($estado['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($estado['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>

        
        <label for="proximo_mantenimiento">Próximo Mantenimiento:</label>
        <input type="date" name="proximo_mantenimiento" id="proximo_mantenimiento" required>

        
        <div class="button-container">
            <button type="submit" onclick="window.location.href='volver_url.php';">Crear Mantenimiento</button>
            <button type="button" onclick="location.href='mantenimiento.php'">Volver</button>
        </div>
    </form>
</body>

</html>
