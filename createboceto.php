<?php
session_start();
$pathinfo = pathinfo($_FILES["pdf"]["name"]);

$base = $pathinfo["filename"];

$base = preg_replace("/[^\w-]/", "_", $base);

$filename = $base . "." . $pathinfo["extension"];

$destination = __DIR__ . "/uploads/" . $filename;

$nombre = $_POST["nombre"];
$pdf = "./uploads/" . $filename;
$id_cliente = $_POST["id_cliente"];
$numero_cliente = $_POST["numero_cliente"];


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

$sql = "INSERT INTO bocetos (nombre,pdf,id_cliente,CodigoCliente) VALUES (?,?,?,?)";

$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "ssis",
    $nombre,
    $pdf,
    $id_cliente,
    $numero_cliente
);


// Replace any characters not \w- in the original filename
$pathinfo = pathinfo($_FILES["pdf"]["name"]);

$base = $pathinfo["filename"];

$base = preg_replace("/[^\w-]/", "_", $base);

$filename = $base . "." . $pathinfo["extension"];

$destination = __DIR__ . "/uploads/" . $filename;

// Add a numeric suffix if the file already exists
$i = 1;

while (file_exists($destination)) {

    $filename = $base . "($i)." . $pathinfo["extension"];
    $destination = __DIR__ . "/uploads/" . $filename;

    $i++;
}

if (!move_uploaded_file($_FILES["pdf"]["tmp_name"], $destination)) {

    exit("Can't move uploaded file");
}
mysqli_stmt_execute($stmt);

// echo "Registro Guardado."; 
$_SESSION['confirmarAccion'] = "./clientes/datoscliente.php";
$_SESSION['mensajeAccion'] = "Archivo subido";
header("location:./CRUDS/bocetos/formcreateboceto.php");
