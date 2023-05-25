<?php session_start(); ?>
<?php


$pathinfo = pathinfo($_FILES["boceto"]["name"]);

$base = $pathinfo["filename"];

$base = preg_replace("/[^\w-]/", "_", $base);

$filename = $base . "." . $pathinfo["extension"];

$destination = __DIR__ . "/uploads/" . $filename;

$id_boceto = $_POST["id_boceto"];
$pdf = "./uploads/" . $filename;

$nombre = $filename;

// if ( ! $terms){
//     die("Terms must be accepted");
// }

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

$sql = "UPDATE `bocetos` SET `firmado`='" . 1 . "', `pdf`='" . $pdf . "', `nombre`='" . $nombre . "' WHERE id =" . $id_boceto;

$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

$i = 1;

while (file_exists($destination)) {

    $filename = $base . "($i)." . $pathinfo["extension"];
    $destination = __DIR__ . "/uploads/" . $filename;
    echo $destination;
    $i++;
}

if (!move_uploaded_file($_FILES["boceto"]["tmp_name"], $destination)) {

    exit("Can't move uploaded file");
}

mysqli_stmt_execute($stmt);

$_SESSION['confirmarAccion'] = "./clientes/datoscliente.php";
$_SESSION['mensajeAccion'] = "Boceto Sustituido";
header("location:./CRUDS/clientes/datoscliente.php");
?>