<?php include "../sesion.php" ?>
<?php
// echo json_encode($_GET["id"][0]);
$id = $_GET["id"][0];

// echo $id;

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

$sql = "UPDATE `posiciones` SET `habilitado` = 0 WHERE id =" . $id ;

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

// mysqli_stmt_bind_param($stmt, "i",
//                        $id);

mysqli_stmt_execute($stmt);

$_SESSION['confirmarAccion'] = "./posicion/posiciones.php";
$_SESSION['mensajeAccion'] = "Posición desabilitada";
header("location:../posicion/posiciones.php");
?>
<?php include "./menuPosiciones.php" ?>