<?php

session_start();


if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}


$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "empresa_inventario";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


$user = $_SESSION['username'];


$sqlUserId = "SELECT usuario_id FROM login WHERE username = ?";
$stmt = $conn->prepare($sqlUserId);
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($usuario_id);
$stmt->fetch();
$stmt->close();

if ($usuario_id) {
    
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();
} else {
    $userData = null; 
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    
    
    $password_actual = $_POST['password_actual'] ?? '';
    $nueva_contrasena = $_POST['nueva_contrasena'] ?? '';

    
    if (!empty($nueva_contrasena)) {
        
        $sqlPasswordCheck = "SELECT password FROM login WHERE usuario_id = ?";
        $stmt = $conn->prepare($sqlPasswordCheck);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $stmt->bind_result($password_almacenada);
        $stmt->fetch();
        $stmt->close();

        
        if (password_verify($password_actual, $password_almacenada)) {
            
            $hashed_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            
            
            $updateSql = "UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, telefono = ?, direccion = ?, fecha_nacimiento = ?, password = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("sssssssi", $nombre, $apellido, $email, $telefono, $direccion, $fecha_nacimiento, $hashed_password, $usuario_id);
        } else {
            $error = "La contraseña actual no es correcta.";
        }
    } else {
        
        $updateSql = "UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, telefono = ?, direccion = ?, fecha_nacimiento = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssssssi", $nombre, $apellido, $email, $telefono, $direccion, $fecha_nacimiento, $usuario_id);
    }

    
    if (isset($updateStmt) && $updateStmt->execute()) {
        header("Location: perfil.php?mensaje=Perfil actualizado con éxito");
        exit;
    } else {
        $error = "Error al actualizar el perfil: " . $conn->error;
    }
}


if (isset($conn)) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Aulapp</title>
    <link rel="stylesheet" href="../css/editarPerfil.css">
</head>
<body>
    <div class="container">
        <h1>Editar Perfil</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($userData['nombre']); ?>" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($userData['apellido']); ?>" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($userData['telefono']); ?>">

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($userData['direccion']); ?>">

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($userData['fecha_nacimiento']); ?>" required>

            <label for="password_actual">Contraseña Actual:</label>
            <input type="password" id="password_actual" name="password_actual" placeholder="Ingresa tu contraseña actual (si deseas cambiarla)">

            <label for="nueva_contrasena">Nueva Contraseña:</label>
            <input type="password" id="nueva_contrasena" name="nueva_contrasena" placeholder="Ingresa tu nueva contraseña (opcional)">

            <button type="submit">Guardar Cambios</button>

            
        </form>
        <div class="boton">
            <a href="perfil.php">Volver al Perfil</a>
        </div>

        
    </div>
</body>
</html>
