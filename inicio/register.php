<?php
// Conectar a la base de datos
$servername = "127.0.0.1";
$username_db = "root";
$password_db = "";
$dbname = "empresa_inventario";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$role_id = $_POST['role_id'];
$terminos_aceptados = isset($_POST['terminos_aceptados']) ? true : false; // Captura si se aceptaron los términos

// Validar que las contraseñas coincidan
if ($password !== $confirm_password) {
    echo "Las contraseñas no coinciden.";
    exit;
}

// Encriptar la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insertar datos en la base de datos
$sql = "INSERT INTO usuarios (username, password, email, nombre, apellido, telefono, direccion, fecha_nacimiento, role_id, terminos_aceptados) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssi", $username, $hashed_password, $email, $nombre, $apellido, $telefono, $direccion, $fecha_nacimiento, $role_id, $terminos_aceptados);

if ($stmt->execute()) {
    // Registro exitoso, redirigir a la página de login
    header("Location: login.html");
    exit(); // Asegúrate de que no se ejecute más código después de la redirección
} else {
    echo "Error al registrarse: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
