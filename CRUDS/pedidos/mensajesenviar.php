<?php
session_start();

$mensaje = trim($_POST["mensaje"]);
if (strlen($mensaje) > 0) {
    $serie = $_POST["serie_pedido"];
    $ejercicio = $_POST["ejercicio_pedido"];
    $numero = $_POST["numero_pedido"];
    $fecha = date('Y-m-d H:i:s');

    if (isset($_SESSION['usuario'])) {
        $emisor = "serigrafia";
    } else {
        $emisor = "tienda";
    }

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

    $sql = "INSERT INTO chats (mensaje, ejercicio_pedido, serie_pedido, numero_pedido, emisor, fecha) VALUES (?,?,?,?,?,?)";

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die(mysqli_errno($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sisiss",
        $mensaje,
        $ejercicio,
        $serie,
        $numero,
        $emisor,
        $fecha
    );

    mysqli_stmt_execute($stmt);
}

header("location:./datospedido.php");
