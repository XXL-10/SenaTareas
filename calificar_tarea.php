<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Instructor') {
    header("Location: login.php");
    exit;
}

// Incluir archivo de conexión a la base de datos
require './conexion/conexion.php';

// Verificar si se ha pasado un ID de tarea
if (isset($_GET['tarea_id'])) {
    $tarea_id = $_GET['tarea_id'];

    // Obtener información de la tarea
    $query = "SELECT t.id, t.asunto, t.contenido, u.pnombre, u.papellido, t.archivo 
              FROM tareas t 
              JOIN usuarios u ON t.usuario_id = u.id 
              WHERE t.id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $tarea_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $tarea = $result->fetch_assoc();
    } else {
        header("Location: panel_instructor.php");
        exit;
    }
} else {
    header("Location: panel_instructor.php");
    exit;
}

// Procesar el formulario de calificación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $calificacion = $_POST['calificacion'];

    // Actualizar la calificación en la base de datos
    $updateQuery = "UPDATE tareas SET calificacion = ? WHERE id = ?";
    $updateStmt = $conexion->prepare($updateQuery);
    $updateStmt->bind_param("di", $calificacion, $tarea_id);
    
    if ($updateStmt->execute()) {
        header("Location: panel_instructor.php?success=1");
        exit;
    } else {
        $error = "Error al calificar la tarea. Inténtalo de nuevo.";
    }
}

// Incluir el encabezado
require './inclu/header.php';
?>
<title>Calificar Tarea</title>
</head>
<body>
<?php require './inclu/nav.php'; ?>

<div class="calificar-tarea-unique">
    <h2>Calificar Tarea</h2>
    <h3>Asunto: <?php echo htmlspecialchars($tarea['asunto']); ?></h3>
    <p><strong>Contenido:</strong> <?php echo htmlspecialchars($tarea['contenido']); ?></p>
    <p><strong>Aprendiz:</strong> <?php echo htmlspecialchars($tarea['pnombre'] . ' ' . $tarea['papellido']); ?></p>
    
    <?php if (!empty($tarea['archivo'])): ?>
        <p><strong>Archivo Enviado:</strong> <a class="descargar-archivo-unique" href="<?php echo htmlspecialchars($tarea['archivo']); ?>" target="_blank">Descargar Archivo</a></p>
    <?php else: ?>
        <p>No se ha enviado ningún archivo.</p>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <p class="error-message-unique"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" class="formulario-calificacion-unique">
        <label for="calificacion">Calificación:</label>
        <input type="number" name="calificacion" id="calificacion" min="0" max="5" step="0.1" required>
        <input type="submit" value="Guardar Calificación">
    </form>
</div>

</body>
</html>
