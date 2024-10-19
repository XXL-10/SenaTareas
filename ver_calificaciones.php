<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Aprendiz') {
    header("Location: login.php");
    exit;
}

require './conexion/conexion.php';

// Obtener calificaciones
$usuario_id = $_SESSION['usuario_id'];
$query = "SELECT * FROM tareas WHERE usuario_id = '$usuario_id'";
$result = $conexion->query($query);

require './inclu/header.php';
?>
<title>Ver Calificaciones</title>
<link rel="stylesheet" href="calificaciones.css">
</head>
<body>
<?php require './inclu/nav-cerrar1.php'; ?>

<div class="calificaciones-container">
    <h2 class="calificaciones-title">Calificaciones</h2>

    <table class="calificaciones-table">
        <thead>
            <tr>
                <th>Asunto</th>
                <th>Calificación</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['asunto']; ?></td>
                <td><?php echo $row['calificacion'] ? $row['calificacion'] : 'Pendiente'; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="panel_aprendiz.php" class="volver-btn">Volver</a>
</div>

</body>
</html>
