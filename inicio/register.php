<?php
// Iniciar la sesión
session_start();

// Conectar a la base de datos
$servername = "127.0.0.1";
$username_db = "root";
$password_db = "";
$dbname = "empresa_inventario";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Verificar la conexión
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
$rol_input = $_POST['rol'];

// Validar que las contraseñas coincidan
if ($password !== $confirm_password) {
    echo "Las contraseñas no coinciden.";
    exit;
}

// Verificar si el nombre de usuario ya existe
$check_username_query = "SELECT * FROM login WHERE username = ?";
$stmt_check_username = $conn->prepare($check_username_query);
$stmt_check_username->bind_param("s", $username);
$stmt_check_username->execute();
$result = $stmt_check_username->get_result();

if ($result->num_rows > 0) {
    // Redirigir a register.html sin mensaje
    header("Location: register.html");
    exit; // Asegúrate de salir después de la redirección
}

// Determinar el rol según el código ingresado
$rol = 5; // Por defecto será 'usuarios'
if (!empty($rol_input)) {
    switch ($rol_input) {
        case 109:
            $rol = 1; // ID del rol 'admin'
            break;
        case 619:
            $rol = 2; // ID del rol 'inventarista'
            break;
        case 226:
            $rol = 3; // ID del rol 'mantenimiento'
            break;
        case 610:
            $rol = 4; // ID del rol 'reportes'
            break;
    }
}

// Encriptar la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Iniciar transacción
    $conn->begin_transaction();

    // Insertar datos en la tabla `usuarios`
    $sql_usuarios = "INSERT INTO usuarios (email, nombre, apellido, telefono, direccion, fecha_nacimiento, rol) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_usuarios = $conn->prepare($sql_usuarios);
    $stmt_usuarios->bind_param("ssssssi", $email, $nombre, $apellido, $telefono, $direccion, $fecha_nacimiento, $rol);
    
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
            exit();
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
$stmt_check_username->close();
$conn->close();
?>
