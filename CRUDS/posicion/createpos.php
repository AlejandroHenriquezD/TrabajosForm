<?php include "../sesion.php" ?>
<?php

$descripcion = $_POST["descripcion"];

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

$sql = "INSERT INTO posiciones (descripcion) VALUES (?)";

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

mysqli_stmt_bind_param($stmt, "s",
                       $descripcion);

mysqli_stmt_execute($stmt);

$_SESSION['confirmarAccion'] = "./posicion/posiciones.php";
$_SESSION['mensajeAccion'] = "Posición creada";
header("location:./formcreatepos.php");
?>
<?php include "./menuPosiciones.php" ?>