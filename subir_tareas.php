<?php
session_start();

// Verificar si el usuario es un aprendiz y está logueado
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'Aprendiz') {
    header("Location: login.php");
    exit;
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "1006408587", "senatareas");

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Obtener el nombre del aprendiz desde la base de datos
$usuario_id = $_SESSION['usuario_id'];
$query = "SELECT pnombre, papellido FROM usuarios WHERE id = '$usuario_id' LIMIT 1";
$result = $conexion->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre_aprendiz = $row['pnombre'] . ' ' . $row['papellido'];
} else {
    $nombre_aprendiz = 'Desconocido';
}

require './inclu/header.php'; 
?>
<title>Panel Aprendiz</title>
</head>
<body>
<?php require './inclu/nav.php'; ?>

<form class="formulario-tareas" method="POST" enctype="multipart/form-data">
    <h2>Enviar Tarea</h2>

    <fieldset>
        <legend>Detalles de la Tarea</legend>

        <label for="asunto">Asunto</label>
        <input type="text" name="asunto" id="asunto" required>

        <label for="contenido">Contenido</label>
        <textarea name="contenido" id="contenido" rows="6" required></textarea>
    </fieldset>

    <fieldset>
        <legend>Subir Archivos</legend>

        <label for="archivos">Seleccionar archivos (PDF, Excel, Word)</label>
        <input type="file" name="archivos[]" id="archivos" accept=".pdf,.doc,.docx,.xls,.xlsx" multiple required>
    </fieldset>

    <input type="submit" value="Enviar Tarea">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $asunto = $_POST['asunto'];
    $contenido = $_POST['contenido'];
    $fecha_envio = date("Y-m-d H:i:s");

    // Insertar los datos de la tarea en la tabla 'tareas'
    $sql = "INSERT INTO tareas (usuario_id, nombre_aprendiz, asunto, contenido, fecha_envio) 
            VALUES ('$usuario_id', '$nombre_aprendiz', '$asunto', '$contenido', '$fecha_envio')";

    if ($conexion->query($sql) === TRUE) {
        $tarea_id = $conexion->insert_id; // Obtener el ID de la tarea recién creada

        $error = false; // Para detectar errores en la subida
        foreach ($_FILES['archivos']['tmp_name'] as $index => $tmpFilePath) {
            // Muestra la información de los archivos subidos
            print_r($_FILES['archivos']);
            
            if ($_FILES['archivos']['error'][$index] == UPLOAD_ERR_OK) {
                // Verifica si el archivo temporal existe
                if (file_exists($tmpFilePath)) {
                    $nombreArchivo = $_FILES['archivos']['name'][$index];
                    $destino = 'uploads/' . basename($nombreArchivo);

                    // Verificar si la carpeta 'uploads' existe, si no, crearla
                    if (!is_dir('uploads')) {
                        mkdir('uploads', 0777, true);
                    }

                    // Mover el archivo a la carpeta 'uploads'
                    if (move_uploaded_file($tmpFilePath, $destino)) {
                        echo "Archivo subido exitosamente: " . $destino . "<br>"; // Mensaje de éxito
                        // Actualizar la tabla 'tareas' con la información del archivo
                        $sqlArchivo = "UPDATE tareas 
                                       SET nombre_archivo = '$nombreArchivo', archivo = '$destino' 
                                       WHERE id = '$tarea_id'";
                        if (!$conexion->query($sqlArchivo)) {
                            $error = true;
                            echo "Error al guardar el archivo en la base de datos: " . $conexion->error;
                        }
                    } else {
                        $error = true;
                        echo "Error al mover el archivo al directorio de destino.";
                    }
                } else {
                    echo "El archivo temporal no existe.";
                }
            } else {
                // Manejar errores específicos de la subida
                switch ($_FILES['archivos']['error'][$index]) {
                    case UPLOAD_ERR_INI_SIZE:
                        echo "El archivo es demasiado grande (tamaño máximo: 80 MB).";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        echo "El archivo excede el tamaño permitido por el formulario.";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        echo "El archivo fue subido parcialmente.";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        echo "No se subió ningún archivo.";
                        break;
                    default:
                        echo "Error desconocido al subir el archivo.";
                        break;
                }
            }
        }

        if (!$error) {
            echo "<script>
                    alert('Has enviado correctamente el trabajo');
                    window.location.href = 'panel_aprendiz.php';
                  </script>";
        }
    } else {
        echo "Error al guardar la tarea: " . $conexion->error;
    }

    $conexion->close();
}
?>