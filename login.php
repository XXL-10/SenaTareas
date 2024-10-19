<?php require './inclu/header.php'; ?>
<title>Login</title>
</head>

<body>
    <?php require './inclu/nav.php'; ?>

    <form class="formulario-login" method="POST">
        <h2>Iniciar Sesión</h2>

        <label for="email">Correo Electrónico</label>
        <input type="email" name="email" id="email" required>

        <label for="contraseña">Contraseña</label>
        <input type="password" name="contraseña" id="contraseña" required>

        <input type="submit" value="Iniciar Sesión">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $contraseña = $_POST['contraseña'];

        $sql = "SELECT * FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conexion, $sql);

        if (mysqli_num_rows($result) > 0) {
            $usuario = mysqli_fetch_assoc($result);


            if (password_verify($contraseña, $usuario['contraseña'])) {
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['rol'] = $usuario['rol'];

                if ($usuario['rol'] === 'Aprendiz') {
                    echo "<script>
                        alert('Bienvenido Aprendiz, " . $usuario['pnombre'] .' '. $usuario['papellido']. "!');
                        window.location.href = 'panel_aprendiz.php';
                      </script>";
                } elseif ($usuario['rol'] === 'Instructor') {
                    echo "<script>
                        alert('Bienvenido Instructor, " . $usuario['pnombre'] .' '. $usuario['papellido']. "!');
                        window.location.href = 'panel_instructor.php';
                      </script>";
                }
            } else {
                echo "<script>
                    alert('Contraseña incorrecta. Intenta nuevamente.');
                    window.history.back();
                  </script>";
            }
        } else {
            echo "<script>
                alert('No existe un usuario con ese correo electrónico.');
                window.history.back();
              </script>";
        }

        mysqli_close($conexion);
    }
    ?>



    <?php require './inclu/footer.php'; ?>