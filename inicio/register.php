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
$email = $_POST['email'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validar que las contraseñas coincidan
if ($password !== $confirm_password) {
    echo "Las contraseñas no coinciden.";
    exit;
}

// Encriptar la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Iniciar transacción
    $conn->begin_transaction();

    // Insertar datos en la tabla `usuarios`
    $sql_usuarios = "INSERT INTO usuarios (email, nombre, apellido, telefono, direccion, fecha_nacimiento)VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_usuarios = $conn->prepare($sql_usuarios);
    $stmt_usuarios->bind_param("ssssss", $email, $nombre, $apellido, $telefono, $direccion, $fecha_nacimiento);
    
    if ($stmt_usuarios->execute()) {
        // Obtener el ID del usuario recién creado
        $usuario_id = $stmt_usuarios->insert_id;
        
        // Insertar datos en la tabla `login`
        $sql_login = "INSERT INTO login (usuario_id, username, password) VALUES (?, ?, ?)";
        $stmt_login = $conn->prepare($sql_login);
        $stmt_login->bind_param("iss", $usuario_id, $username, $hashed_password);
        
        if ($stmt_login->execute()) {
            // Confirmar transacción
            $conn->commit();
            // Registro exitoso, redirigir a la página de login
            header("Location: login.html");
            exit(); // Asegúrate de que no se ejecute más código después de la redirección
        } else {
            // Error al insertar en la tabla `login`, deshacer la transacción
            throw new Exception("Error al insertar en la tabla login: " . $conn->error);
        }
    } else {
        // Error al insertar en la tabla `usuarios`, deshacer la transacción
        throw new Exception("Error al insertar en la tabla usuarios: " . $conn->error);
    }
} catch (Exception $e) {
    // Deshacer transacción si hay algún error
    $conn->rollback();
    echo $e->getMessage();
}

// Cerrar conexiones
$stmt_usuarios->close();
$stmt_login->close();
$conn->close();
?>
