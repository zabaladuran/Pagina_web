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
    
    $sql = "SELECT u.*, r.nombre AS rol_nombre 
            FROM usuarios u 
            JOIN roles r ON u.rol_id = r.id 
            WHERE u.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();
} else {
    $userData = null;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    
    $foto = $_FILES['foto'];
    $targetDir = "uploads/"; 
    $targetFile = $targetDir . basename($foto['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    
    $check = getimagesize($foto['tmp_name']);
    if ($check === false) {
        echo "El archivo no es una imagen válida.";
        $uploadOk = 0;
    }

    
    if (file_exists($targetFile)) {
        echo "Lo sentimos, el archivo ya existe.";
        $uploadOk = 0;
    }

    
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Solo se permiten formatos JPG, JPEG, PNG y GIF.";
        $uploadOk = 0;
    }

    
    if ($uploadOk == 1) {
        if (move_uploaded_file($foto['tmp_name'], $targetFile)) {
            echo "La imagen " . htmlspecialchars(basename($foto['name'])) . " se ha subido correctamente.";

            
            $sqlUpdate = "UPDATE usuarios SET foto_perfil = ? WHERE id = ?";
            $stmt = $conn->prepare($sqlUpdate);
            $stmt->bind_param("si", $targetFile, $usuario_id);
            if ($stmt->execute()) {
                echo "Foto de perfil actualizada con éxito.";
                header("Refresh:0"); 
            } else {
                echo "Error al guardar la foto en la base de datos.";
            }
            $stmt->close();
        } else {
            echo "Lo sentimos, hubo un error al subir tu archivo.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Aulapp</title>
    <link rel="stylesheet" href="../css/perfil4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Iconos -->
</head>
<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
            <div class="main-content">
                <header class="topbar">
                    <h1>Perfil de Usuario</h1>
                    <div class="actions">
                        <a href="editar_perfil.php" class="btn">Editar Perfil</a> 
                        <a href="logout.php" class="btn">Salir</a>
                    </div>
                </header>
                <div class="contentmadre">
                    <div class="content">
                        <?php if ($userData): ?>
                            <h2>Bienvenido, <?php echo htmlspecialchars($userData['nombre'] . ' ' . $userData['apellido']); ?></h2>
                            <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
                            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($userData['telefono']); ?></p>
                            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($userData['direccion']); ?></p>
                            <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($userData['fecha_nacimiento']); ?></p>
                            <p><strong>Rol:</strong> <?php echo htmlspecialchars($userData['rol_nombre']); ?></p>
                            
                            
                        <?php else: ?>
                            <p>No se encontraron datos del usuario.</p>
                        <?php endif; ?>
                    </div>

                    <div class="content2">
                        
                        <img src="<?php echo htmlspecialchars($userData['foto_perfil']); ?>" alt="Foto de Perfil" style="width:250px; height:auto;">

                        
                        <form action="perfil.php" method="post" enctype="multipart/form-data">
                            <label for="foto" class="custom-file-upload">
                                Seleccionar archivo
                            </label>
                            <input type="file" name="foto" id="foto" accept="image/*" required>
                            <button type="submit">Actualizar Foto</button>
                        </form>
                    </div>
                </div>
                <div class="menu"></div>
            </div>
    </div>
</body>
</html>
