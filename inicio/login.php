<?php
// Iniciar la sesión
session_start();

// Conectar a la base de datos
$servername = "127.0.0.1";  // localhost
$username_db = "root";       // usuario de la base de datos
$password_db = "";           // contraseña de la base de datos
$dbname = "empresa_inventario";  // nombre de la base de datos

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$username = $_POST['username'];
$password = $_POST['password'];

// Consultar la base de datos para verificar si el usuario existe
$sql = "SELECT * FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Si el usuario existe, verificamos la contraseña
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        // Contraseña correcta, iniciar sesión
        $_SESSION['username'] = $username;
        header("Location: home.php");
        exit;
    } else {
        echo "Nombre de usuario o contraseña incorrectos.";
    }
} else {
    echo "Nombre de usuario o contraseña incorrectos.";
}

$stmt->close();
$conn->close();
?>
