<?php include "../sesion.php" ?>
<?php

$nombre = $_POST["nombre"];

// if ( ! $terms){
//     die("Terms must be accepted");
// }

$host = "localhost";
$dbname = "centraluniformes";
$username = "root";
$password = "";

$conn = mysqli_connect(hostname: $host,
               username: $username,
               password: $password,
               database: $dbname);

if (mysqli_connect_errno()) {
    die("Connection error: " . mysqli_connect_errno());
}

$sql = "INSERT INTO tipos_trabajos (nombre) VALUES (?)";

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

mysqli_stmt_bind_param($stmt, "s",
                       $nombre);

mysqli_stmt_execute($stmt);

$_SESSION['confirmarAccion'] = "./tipoTrabajo/tipostrabajo.php";
$_SESSION['mensajeAccion'] = "Tipo de trabajo creado";
header("location:./formcreatetipotrabajo.php");
?>
<?php include "./menuTipoTrabajo.php" ?>