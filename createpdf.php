<?php
$pathinfo = pathinfo($_FILES["pdf"]["name"]);

$base = $pathinfo["filename"];

$base = preg_replace("/[^\w-]/", "_", $base);

$filename = $base . "." . $pathinfo["extension"];

$destination = __DIR__ . "/uploads/" . $filename;

$pdf = "./uploads/" . $filename;
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
$sql = "UPDATE `trabajos` SET `pdf`='". $pdf ."'   WHERE ejercicio_pedido ='" . $ejercicio_pedido[0] . "'AND serie_pedido ='" . $serie_pedido[0] . "'AND numero_pedido ='". $numero_pedido[0]. "'" ;

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

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

if ( ! move_uploaded_file($_FILES["pdf"]["tmp_name"], $destination)) {

    exit("Can't move uploaded file");

}

mysqli_stmt_execute($stmt);

echo "Cambios Guardados."; 

echo "<form action='CRUDS/pedidos/pedidos.php'>
        <button >Volver</button>
      </form>";
?>