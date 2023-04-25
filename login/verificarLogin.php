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
// $contraseña = password_hash($contraseña, PASSWORD_BCRYPT);
echo $usuario;

$sql = mysqli_query($conn, "SELECT * FROM `usuarios` WHERE usuario ='$usuario' AND contraseña ='$contraseña'");

if(mysqli_num_rows($sql) > 0) {
    $_SESSION['usuario'] = $usuario;
    header("location:../frontend/index.php");
    exit;
} else {
    echo '
        <script>
            alert("Usuario incorrecto");
            window.location = "login.php";
        </script>
    ';
    exit;
}
