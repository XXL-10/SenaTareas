<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="header">
    <div class="header-container">
        <a href="index.php">
            <img src="img/logo_sena.svg" alt="Logo SENA" class="logo">
        </a>
        <h1 class="title">Sena Tareas</h1>
        <nav class="nav">
            <ul>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li>
                        <a href="<?php echo $_SESSION['rol'] === 'Aprendiz' ? 'panel_aprendiz.php' : 'panel_instructor.php'; ?>">
                            Panel Principal
                        </a>
                    </li>
                    <li><a href="./inclu/cerrar_sesion.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                    <li><a href="crearcuenta.php">Crear Cuenta</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
