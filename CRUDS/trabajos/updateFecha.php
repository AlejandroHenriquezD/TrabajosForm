<?php
session_start();

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

if(isset($_POST["fecha_inicio"])) {
  $sql = "UPDATE `trabajos` 
          SET `fecha_inicio`='". $_POST["fecha_inicio"] ."'
          WHERE num_tienda = '" . $_POST["num_tienda"] . "' 
          AND ejercicio_pedido ='" . $_POST["ejercicio_pedido"] . "'
          AND serie_pedido ='" . $_POST["serie_pedido"] . "'
          AND numero_pedido ='". $_POST["numero_pedido"]. "'
          AND FechaPedido ='". $_POST["FechaPedido"]. "'
          ";
  echo $_POST["fecha_inicio"] . "<br>";
  echo $_POST["num_tienda"] . "<br>";
  echo $_POST["ejercicio_pedido"] . "<br>";
  echo $_POST["serie_pedido"] . "<br>";
  echo $_POST["numero_pedido"] . "<br>";
  echo $_POST["id_boceto"] . "<br>";
  echo $_POST["pdf"] . "<br>";
  echo $_POST["FechaPedido"] . "<br>";
} else if (isset($_POST["fecha_terminado"])){
  echo $_POST["fecha_terminado"];
  $sql = "UPDATE `trabajos` 
          SET `fecha_terminado`='". $_POST["fecha_terminado"] ."' 
          WHERE num_tienda='". $_POST["num_tienda"] . "'
          AND ejercicio_pedido='" . $_POST["ejercicio_pedido"] . "'
          AND serie_pedido='" . $_POST["serie_pedido"] . "'
          AND numero_pedido='". $_POST["numero_pedido"]. "'
          AND FechaPedido='". $_POST["FechaPedido"]. "'
          ";
}

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

mysqli_stmt_execute($stmt);

$_SESSION['confirmarAccion'] = "./trabajos/trabajos.php";
$_SESSION['mensajeAccion'] = "Fecha actualizada";
header("location:./trabajos.php");
?>