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


$email = $_POST['email'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];  
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$rol_input = $_POST['rol'];


if ($password !== $confirm_password) {
    echo "Las contraseñas no coinciden.";
    exit;
}


$check_username_query = "SELECT * FROM login WHERE username = ?";
$stmt_check_username = $conn->prepare($check_username_query);
$stmt_check_username->bind_param("s", $username);
$stmt_check_username->execute();
$result = $stmt_check_username->get_result();

if ($result->num_rows > 0) {
    header("Location: register.html");
    exit;
}


$rol = 5; 
if (!empty($rol_input)) {
    switch ($rol_input) {
        case 109:
            $rol = 1;
            break;
        case 619:
            $rol = 2;
            break;
        case 226:
            $rol = 3;
            break;
        case 610:
            $rol = 4;
            break;
    }
}


$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    
    $conn->begin_transaction();

    
    $sql_usuarios = "INSERT INTO usuarios (email, nombre, apellido, telefono, direccion, fecha_nacimiento, rol_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_usuarios = $conn->prepare($sql_usuarios);
    if (!$stmt_usuarios) {
        throw new Exception("Error en la preparación de la consulta para usuarios: " . $conn->error);
    }
    $stmt_usuarios->bind_param("ssssssi", $email, $nombre, $apellido, $telefono, $direccion, $fecha_nacimiento, $rol);
    
    if ($stmt_usuarios->execute()) {
        $usuario_id = $stmt_usuarios->insert_id;
        
        
        $sql_login = "INSERT INTO login (usuario_id, username, password) VALUES (?, ?, ?)";
        $stmt_login = $conn->prepare($sql_login);
        if (!$stmt_login) {
            throw new Exception("Error en la preparación de la consulta para login: " . $conn->error);
        }
        $stmt_login->bind_param("iss", $usuario_id, $username, $hashed_password);
        
        if ($stmt_login->execute()) {
            if ($rol === 3) { 
                $sql_tecnicos = "INSERT INTO tecnicos (nombre, apellido, telefono, correo, rol_id) VALUES (?, ?, ?, ?, ?)";
                $stmt_tecnicos = $conn->prepare($sql_tecnicos);
                $stmt_tecnicos->bind_param("ssssi", $nombre, $apellido, $telefono, $email, $rol);
                $stmt_tecnicos->execute();
                $stmt_tecnicos->close();
            }
            $conn->commit();
            header("Location: login.html");
            exit();
        } else {
            throw new Exception("Error al insertar en la tabla login: " . $conn->error);
        }
    } else {
        throw new Exception("Error al insertar en la tabla usuarios: " . $conn->error);
    }
} catch (Exception $e) {
    $conn->rollback();
    echo $e->getMessage();
}

$stmt_usuarios->close();
$stmt_login->close();
$stmt_check_username->close();
$conn->close();
?>
