<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "empresa_inventario";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultas para evitar duplicados
$resultUsuarios = $conn->query("SELECT COUNT(DISTINCT email) AS total FROM usuarios");
$totalUsuarios = $resultUsuarios->fetch_assoc()['total'];

$resultEquipos = $conn->query("SELECT COUNT(DISTINCT nombre) AS total FROM equipos");
$totalEquipos = $resultEquipos->fetch_assoc()['total'];

$resultProveedores = $conn->query("SELECT COUNT(DISTINCT nombre) AS total FROM proveedores");
$totalProveedores = $resultProveedores->fetch_assoc()['total'];

// Datos para la gráfica
$graficaData = [
    'usuarios' => $totalUsuarios,
    'equipos' => $totalEquipos,
    'proveedores' => $totalProveedores
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventarios y Mantenimiento</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Iconos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Inclusión de Chart.js -->
</head>
<body>
    <div class="dashboard-container">
    <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <header class="topbar">
                <h1>Panel de Gestión</h1>
                <div class="actions">
                    <a href="nuevo_dispositivo.php" class="btn">Nuevo Dispositivo</a>
                    <a href="generar_reporte.php" class="btn">Generar Reporte</a>
                    <a href="logout.php" class="btn">Salir</a>
                </div>
            </header>

            <div class="content">
                <div class="alert">
                    <h2>Alertas de Inventario y Mantenimiento</h2>
                    <p>Dispositivos que requieren atención inmediata.</p>

                    <div class="clock" id="clock">00:00:00</div>

                        <script>
                            function updateClock() {
                                const clock = document.getElementById('clock');
                                const now = new Date();

                                const hours = String(now.getHours()).padStart(2, '0');
                                const minutes = String(now.getMinutes()).padStart(2, '0');
                                const seconds = String(now.getSeconds()).padStart(2, '0');

                                clock.textContent = `${hours}:${minutes}:${seconds}`;
                            }

                            // Actualiza el reloj cada segundo
                            setInterval(updateClock, 1000);

                            // Llama a la función una vez para evitar el retraso inicial
                            updateClock();
                        </script>

                </div>

                <!-- Paneles -->
                <div class="dashboard-panels">
                    <div class="panel">
                        <h2>Usuarios</h2>
                        <p>Total: <strong><?php echo $totalUsuarios; ?></strong></p>
                    </div>
                    <div class="panel">
                        <h2>Equipos</h2>
                        <p>Total: <strong><?php echo $totalEquipos; ?></strong></p>
                    </div>
                    <div class="panel">
                        <h2>Proveedores</h2>
                        <p>Total: <strong><?php echo $totalProveedores; ?></strong></p>
                    </div>
                </div>

                <!-- Gráfica -->
                <div class="chart-container">
                    <canvas id="inventoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Datos para la gráfica
        const dataGrafica = <?php echo json_encode($graficaData); ?>;

        const ctx = document.getElementById('inventoryChart').getContext('2d');
        const inventoryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Usuarios', 'Equipos', 'Proveedores'],
                datasets: [{
                    label: 'Cantidad',
                    data: [dataGrafica.usuarios, dataGrafica.equipos, dataGrafica.proveedores],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Distribución de Recursos' }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
