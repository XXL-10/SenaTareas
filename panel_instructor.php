<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Instructor') {
    header("Location: login.php");
    exit;
}

// Incluir archivo de conexión a la base de datos
require './conexion/conexion.php';

// Obtener las tareas enviadas por los aprendices
$query = "SELECT t.id, t.asunto, t.contenido, t.fecha_envio, t.calificacion, u.pnombre, u.papellido 
          FROM tareas t 
          JOIN usuarios u ON t.usuario_id = u.id";
$result = $conexion->query($query);

// Incluir el encabezado
require './inclu/header.php';
?>
<title>Panel Instructor</title>
</head>
<body>
<?php require './inclu/nav.php'; ?>

<div class="panel-instructor">
    <h2>Panel de Instructor</h2>

    <table class="tareas-table">
        <thead>
            <tr>
                <th>Asunto</th>
                <th>Contenido</th>
                <th>Fecha de Envío</th>
                <th>Aprendiz</th>
                <th>Calificación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['asunto']); ?></td>
                    <td><?php echo htmlspecialchars($row['contenido']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_envio']); ?></td>
                    <td><?php echo htmlspecialchars($row['pnombre'] . ' ' . $row['papellido']); ?></td>
                    <td><?php echo $row['calificacion'] ? htmlspecialchars($row['calificacion']) : 'Pendiente'; ?></td>
                    <td>
                        <?php if (!$row['calificacion']): ?>
                            <a href="calificar_tarea.php?tarea_id=<?php echo $row['id']; ?>" class="calificar-btn">Calificar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
