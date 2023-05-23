<?php session_start(); ?>
<?php


$pathinfo = pathinfo($_FILES["pdf"]["name"]);

$base = $pathinfo["filename"];

$base = preg_replace("/[^\w-]/", "_", $base);

$filename = $base . "." . $pathinfo["extension"];

$destination = __DIR__ . "/uploads/" . $filename;

$ejercicio_pedido = $_POST["ejercicio_pedido"][0];
$serie_pedido = $_POST["serie_pedido"][0];
$numero_pedido = $_POST["numero_pedido"][0];
$pdf = "./uploads/" . $filename;


// if ( ! $terms){
//     die("Terms must be accepted");
// }

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

$sql = "UPDATE `trabajos` SET `pdf`='". $pdf ."' , `pdf_firmado`='". 1 ."' WHERE ejercicio_pedido ='" . $ejercicio_pedido . "' AND serie_pedido ='" . $serie_pedido . "' AND numero_pedido ='" . $numero_pedido . "'" ;

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

$i = 1;

while (file_exists($destination)) {

    $filename = $base . "($i)." . $pathinfo["extension"];
    $destination = __DIR__ . "/uploads/" . $filename;
    echo $destination;
    $i++;
}

if (!move_uploaded_file($_FILES["pdf"]["tmp_name"], $destination)) {

    exit("Can't move uploaded file");
}

mysqli_stmt_execute($stmt);

$_SESSION['confirmarAccion'] = "./pedidos/pedidos.php";
$_SESSION['mensajeAccion'] = "Orden Firmada Añadida";
header("location:./CRUDS/pedidos/formpdffirmado.php");
?>