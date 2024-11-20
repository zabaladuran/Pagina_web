<?php

session_start();

$servername = "127.0.0.1";  
$username_db = "root";       
$password_db = "";           
$dbname = "empresa_inventario"; 

$conn = new mysqli($servername, $username_db, $password_db, $dbname);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];


$sql = "SELECT login.password, usuarios.id, usuarios.nombre 
        FROM login 
        JOIN usuarios ON login.usuario_id = usuarios.id 
        WHERE login.username = ?";

$stmt = $conn->prepare($sql);


if (!$stmt) {
    die("Error en la consulta: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        
        $_SESSION['username'] = $username;  
        $_SESSION['user_id'] = $user['id']; 
        $_SESSION['nombre'] = $user['nombre']; 
        
        header("Location: home.php");
        exit;
    } else {
        header("Location: login.html");
    }
} else {
    header("Location: login.html");
}

$stmt->close();
$conn->close();
?>
