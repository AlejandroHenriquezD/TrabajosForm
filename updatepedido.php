<?php
$id_boceto = $_POST["id_boceto"];
$id_pedido = $_POST["id_pedido"];

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
$sql = "UPDATE `trabajos` SET `id_boceto`=". $id_boceto ."   WHERE id_pedido =" . $id_pedido ;

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

mysqli_stmt_execute($stmt);

echo "Cambios Guardados."; 

echo "<form action='CRUDS/pedidos/pedidos.php'>
        <button >Volver</button>
      </form>";
?>