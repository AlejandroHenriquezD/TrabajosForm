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

$sql = "UPDATE `tipos_articulos` SET `habilitado` = 0 WHERE id =" . $id ;

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

// mysqli_stmt_bind_param($stmt, "i",
//                        $id);

mysqli_stmt_execute($stmt);

$_SESSION['confirmarAccion'] = "./tipoArticulo/tiposarticulo.php";
$_SESSION['mensajeAccion'] = "Tipo de artículo desabilitado";
header("location:../tipoArticulo/tiposarticulo.php");
?>
<?php include "./menuTipoArticulo.php" ?>