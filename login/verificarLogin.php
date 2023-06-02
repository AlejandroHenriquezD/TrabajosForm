<?php

session_start();

$host = "localhost";
$dbname = "centraluniformes";
$username = "root";
$password = "";

$conn = mysqli_connect(
	hostname: $host,
	username: $username,
	password: $password,
	database: $dbname
);

$usuario = $_POST['usuario'];
$contraseña = $_POST['contraseña'];

$sql = mysqli_query($conn, "SELECT * FROM `usuarios` WHERE usuario ='$usuario'");

$sqlContraseña = $sql->fetch_array(MYSQLI_BOTH)['contraseña'];
if (mysqli_num_rows($sql) > 0 && password_verify($contraseña, $sqlContraseña)) {
	$_SESSION['usuario'] = $usuario;
	unset($_SESSION['usuario_incorrecto']);
	header("location:../CRUDS/clientes/clientes.php");
	exit;
} else {
	if (strlen($usuario) == 0 && strlen($contraseña) == 0) {
		$_SESSION['usuario_incorrecto'] = 'Introduzca usuario y contraseña';
	} else {
		$_SESSION['usuario_incorrecto'] = 'Usuario y/o contraseña incorrectos';
	}
	header("location:login.php");
	exit;
}
