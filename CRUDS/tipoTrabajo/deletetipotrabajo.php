<?php include "../sesion.php" ?>
<?php
// echo json_encode($_GET["id"][0]);
$id = $_GET["id"];
$habilitado = $_GET["habilitado"];

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

if($habilitado == 0) {
    $sql = "UPDATE `tipos_trabajos` SET `habilitado` = 1 WHERE id =" . $id;
    $_SESSION['mensajeAccion'] = "Tipo de trabajo habilitado";
} else {
    $sql = "UPDATE `tipos_trabajos` SET `habilitado` = 0 WHERE id =" . $id;
    $_SESSION['mensajeAccion'] = "Tipo de trabajo deshabilitado";
}

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

// mysqli_stmt_bind_param($stmt, "i",
//                        $id);

mysqli_stmt_execute($stmt);

$_SESSION['confirmarAccion'] = "./tipoTrabajo/tipostrabajo.php";
header("location:../tipoTrabajo/tipostrabajo.php");
?>
<?php include "./menuTipoTrabajo.php" ?>