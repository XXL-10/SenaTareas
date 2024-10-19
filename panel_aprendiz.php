<?php
session_start();

// Verificar si el usuario está autenticado y es un aprendiz
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Aprendiz') {
    header("Location: login.php");
    exit;
}

// Incluir archivo de conexión a la base de datos
require './conexion/conexion.php';

// Obtener el nombre del aprendiz de manera segura
$usuario_id = $_SESSION['usuario_id'];
$query = "SELECT pnombre, papellido FROM usuarios WHERE id = ? LIMIT 1";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $usuario_id); // Asegúrate de que el ID sea un entero
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre_aprendiz = $row['pnombre'] . ' ' . $row['papellido'];
} else {
    $nombre_aprendiz = 'Desconocido'; // Manejar caso donde no se encuentre el aprendiz
}

// Incluir el encabezado
require './inclu/header.php';
?>
<title>Panel Aprendiz</title>
</head>
<body>
<?php require './inclu/nav-cerrar1.php'; ?>

<div class="panel-aprendiz-container">
    <h2>Bienvenido, <?php echo htmlspecialchars($nombre_aprendiz); ?></h2> <!-- Sanitizar la salida -->

    <ul class="panel-aprendiz-menu">
        <li><a href="subir_tareas.php">Enviar Tarea</a></li>
        <li><a href="ver_calificaciones.php">Ver Calificaciones</a></li>
    </ul>
</div>

</body>
</html>
