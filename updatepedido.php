<?php
session_start();
$id_boceto = $_POST["id_boceto"];
$ejercicio_pedido = $_POST["ejercicio_pedido"];
$serie_pedido = $_POST["serie_pedido"];
$numero_pedido = $_POST["numero_pedido"];

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
$sql = "UPDATE `trabajos` SET `id_boceto`=". $id_boceto ."   WHERE ejercicio_pedido ='" . $ejercicio_pedido[0] . "'AND serie_pedido ='" . $serie_pedido[0] . "'AND numero_pedido ='". $numero_pedido[0]. "'" ;

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

mysqli_stmt_execute($stmt);

$_SESSION['confirmarAccion'] = "./pedidos/pedidos.php";
$_SESSION['mensajeAccion'] = "Boceto añadido";
header("location:./CRUDS/pedidos/formupdatepedido.php");
?>