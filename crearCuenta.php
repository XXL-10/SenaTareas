<?php
require('./inclu/header.php');
?>

<title>Crear Cuenta</title>
</head>

<body>
    <?php require './inclu/nav.php'; ?>

    <form class="formulario-crear-cuenta" method="POST">
        <h2>Crear Cuenta</h2>
        <fieldset>
            <legend>Información Personal</legend>

            <label for="cedula">Número Cédula</label>
            <input type="number" name="cedula" id="cedula" required>

            <label for="pnombre">Primer Nombre</label>
            <input type="text" name="pnombre" id="pnombre" required>

            <label for="snombre">Segundo Nombre (Opcional)</label>
            <input type="text" name="snombre" id="snombre">

            <label for="papellido">Primer Apellido</label>
            <input type="text" name="papellido" id="papellido" required>

            <label for="sapellido">Segundo Apellido (Opcional)</label>
            <input type="text" name="sapellido" id="sapellido">
        </fieldset>

        <fieldset>
            <legend>Contacto</legend>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="telefono">Teléfono</label>
            <input type="tel" name="telefono" id="telefono" required pattern="[0-9]{10}">
        </fieldset>

        <fieldset>
            <legend>Rol</legend>

            <label for="rol">Roles:</label>
            <select name="rol" id="rol" required>
                <option value="">Selecciona el rol</option>
                <option value="Aprendiz">Aprendiz</option>
                <option value="Instructor">Instructor</option>
            </select>
        </fieldset>

        <fieldset>
            <legend>Seguridad</legend>

            <label for="contraseña">Contraseña</label>
            <input type="password" name="contraseña" id="contraseña" required minlength="8">

            <label for="confirmar_contraseña">Confirmar Contraseña</label>
            <input type="password" name="confirmar_contraseña" id="confirmar_contraseña" required>
        </fieldset>

        <input type="submit" value="Crear Cuenta">
    </form>


    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $cedula = $_POST['cedula'];
        $pnombre = $_POST['pnombre'];
        $snombre = isset($_POST['snombre']) ? $_POST['snombre'] : null;
        $papellido = $_POST['papellido'];
        $sapellido = isset($_POST['sapellido']) ? $_POST['sapellido'] : null;
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $rol = $_POST['rol'];
        $contraseña = $_POST['contraseña'];
        $confirmar_contraseña = $_POST['confirmar_contraseña'];

        if ($contraseña !== $confirmar_contraseña) {
            echo("<script>
                alert('Las contraseñas no coinciden.');
                window.history.back();
              </script>");
        }

        $sql_check = "SELECT * FROM usuarios WHERE cedula = '$cedula' OR email = '$email' OR telefono = '$telefono'";
        $result_check = mysqli_query($conexion, $sql_check);

        if (mysqli_num_rows($result_check) > 0) {
            echo("<script>
                alert('El teléfono, el email o la cédula ya están registrados.');
                window.history.back();
              </script>");
        }

        $contraseña_encriptada = password_hash($contraseña, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (cedula, pnombre, snombre, papellido, sapellido, email, telefono, rol, contraseña) 
            VALUES ('$cedula', '$pnombre', '$snombre', '$papellido', '$sapellido', '$email', '$telefono', '$rol', '$contraseña_encriptada')";

        if (mysqli_query($conexion, $sql)) {
            echo "<script>
                alert('Usuario registrado exitosamente.');
                window.location.href = 'login.php';
              </script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
        }

        mysqli_close($conexion);
    }
    ?>

    <?php
    require './inclu/footer.php';
    ?>