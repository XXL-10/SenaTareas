<?php

$host = 'localhost';
$dbname = 'senatareas';
$username = 'root'; 
$password = '1006408587';

$conexion = mysqli_connect($host, $username, $password, $dbname);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
