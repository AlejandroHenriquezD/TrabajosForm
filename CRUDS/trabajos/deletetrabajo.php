<?php session_start(); ?>
<?php
// echo json_encode($_GET["id"][0]);
$ejercicio = $_GET["ejercicio_pedido"];
$serie = $_GET["serie_pedido"];
$numero = $_GET["numero_pedido"];

// echo $id;

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

if (mysqli_connect_errno()) {
    die("Connection error: " . mysqli_connect_errno());
}

$sql = "DELETE FROM `trabajos` WHERE ejercicio_pedido = " . $ejercicio . " AND serie_pedido = '" . $serie . "' AND numero_pedido = " . $numero . ";";
    
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

// mysqli_stmt_bind_param($stmt, "i",
//                        $id);

mysqli_stmt_execute($stmt);

$_SESSION['confirmarAccion'] = "./trabajos/trabajos.php";
$_SESSION['mensajeAccion'] = "Orden de trabajo borrada";
header("location:./trabajos.php");
?>