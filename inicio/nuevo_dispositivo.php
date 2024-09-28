<?php
session_start(); // Iniciar sesión para almacenar mensajes

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'empresa_inventario');

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inserción de categorías (solo si no existen)
$categorias = [
    'Computadoras',
    'Impresoras',
    'Equipos de red',
    'Televisores',
    'Sistemas de audio',
    'Proyectores',
    'Otros dispositivos electrónicos'
];

foreach ($categorias as $categoria) {
    $sql_categoria = "INSERT IGNORE INTO categorias (nombre) VALUES ('$categoria')";
    $conn->query($sql_categoria);
}

// Inserción de marcas (solo si no existen)
$marcas = [
    'Samsung',
    'LG',
    'HP',
    'Lenovo',
    'Dell',
    'Epson',
    'Canon',
    'Xerox',
    'Asus',
    'Motorola'
];

foreach ($marcas as $marca) {
    $sql_marca = "INSERT IGNORE INTO marcas (nombre) VALUES ('$marca')";
    $conn->query($sql_marca);
}

// Inserción de proveedores (solo si no existen)
$proveedores = [
    ['Tech Solutions S.A.S.', 'Juan Pérez', '3123456789', 'Carrera 10 #20-30', 'info@techsolutions.com'],
    ['Electro World Ltda.', 'María Gómez', '3219876543', 'Avenida 5 #10-20', 'contacto@electroworld.com'],
    ['Innovación Digital S.A.', 'Carlos Ruiz', '3101234567', 'Calle 25 #12-34', 'info@innovaciondigital.com'],
    ['Sistemas Avanzados S.A.S.', 'Ana Torres', '3174567890', 'Carrera 50 #30-40', 'info@sistemasavanzados.com'],
    ['Red de Soluciones Electrónicas', 'Luis Martínez', '3136547890', 'Avenida 15 #22-25', 'info@redsoluciones.com'],
    ['Dispositivos y Tecnología S.A.S.', 'Julián Morales', '3145678901', 'Calle 10 #15-20', 'info@dispositivosyt.com'],
    ['Soluciones Electrónicas de Colombia', 'Sofía León', '3112345678', 'Calle 30 #14-18', 'contacto@solucioneselectronicas.com'],
    ['Grupo Electrónico S.A.S.', 'Diego López', '3159876543', 'Carrera 12 #5-10', 'info@grupoelectronico.com'],
    ['Almacenamiento y Redes S.A.', 'Claudia Romero', '3128765432', 'Avenida 40 #30-45', 'info@almacenamiento.com'],
    ['Electrocomp S.A.S.', 'Esteban Rojas', '3107890123', 'Calle 22 #10-15', 'info@electrocomp.com']
];

foreach ($proveedores as $proveedor) {
    $sql_proveedor = "INSERT IGNORE INTO proveedores (nombre, contacto, telefono, direccion, email) VALUES ('$proveedor[0]', '$proveedor[1]', '$proveedor[2]', '$proveedor[3]', '$proveedor[4]')";
    $conn->query($sql_proveedor);
}

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $proveedor = $_POST['proveedor'];
    $categoria = $_POST['categoria'];
    $marca = $_POST['marca'];
    $precio = $_POST['precio'];
    $caracteristicas = $_POST['caracteristicas'];

    // Validar que los campos obligatorios no estén vacíos
    if (empty($nombre) || empty($descripcion) || empty($cantidad) || empty($proveedor) || empty($categoria) || empty($marca) || empty($precio)) {
        $_SESSION['message'] = "Por favor, completa todos los campos obligatorios.";
        header('Location: mensaje.php');
        exit();
    }

    // Preparar la consulta de inserción
    $sql = $conn->prepare("INSERT INTO equipos (nombre, descripcion, cantidad, proveedor_id, categoria_id, marca_id, precio, caracteristicas) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Verificar si la preparación fue exitosa
    if (!$sql) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $sql->bind_param('ssiiidis', $nombre, $descripcion, $cantidad, $proveedor, $categoria, $marca, $precio, $caracteristicas);

    // Ejecutar la consulta
    if ($sql->execute()) {
        $_SESSION['message'] = "Dispositivo añadido exitosamente.";
    } else {
        $_SESSION['message'] = "Error al añadir dispositivo: " . $conn->error;
    }

    // Cerrar la consulta
    $sql->close();
    // Redirigir a la página de mensaje
    header('Location: mensaje.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Dispositivo</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <h2>Añadir Nuevo Dispositivo</h2>
        <form action="nuevo_dispositivo.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required><br>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" required></textarea><br>

            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" required><br>

            <label for="proveedor">Proveedor:</label>
            <select name="proveedor" required>
                <?php
                $sql_proveedores = "SELECT * FROM proveedores";
                $result_proveedores = $conn->query($sql_proveedores);

                if ($result_proveedores->num_rows > 0) {
                    while($row = $result_proveedores->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                    }
                }
                ?>
            </select><br>

            <label for="categoria">Categoría:</label>
            <select name="categoria" required>
                <?php
                $sql_categorias = "SELECT * FROM categorias";
                $result_categorias = $conn->query($sql_categorias);

                if ($result_categorias->num_rows > 0) {
                    while($row = $result_categorias->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                    }
                }
                ?>
            </select><br>

            <label for="marca">Marca:</label>
            <select name="marca" required>
                <?php
                $sql_marcas = "SELECT * FROM marcas";
                $result_marcas = $conn->query($sql_marcas);

                if ($result_marcas->num_rows > 0) {
                    while($row = $result_marcas->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                    }
                }
                ?>
            </select><br>

            <label for="precio">Precio:</label>
            <input type="number" step="0.01" name="precio" required><br>

            <label for="caracteristicas">Características:</label>
            <textarea name="caracteristicas" required></textarea><br>

            <button type="submit">Añadir Dispositivo</button>
            <button type="button" class="btn" onclick="window.location.href='home.php';">Regresar al Home</button>
        </form>
    </div>
</body>
</html>
