<?php session_start(); ?>
<?php

$id = $_POST["id"];
$nombre = $_POST["nombre"];
$telefono = $_POST["telefono"];
$correo = $_POST["correo"];
$dirección = $_POST["dirección"];
$numero_cliente = $_POST["numero_cliente"];
$razon_social = $_POST["razon_social"];

echo $_POST["numero_cliente"];

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

$sql = "UPDATE `clientes` SET `nombre`='" . $nombre . "', `telefono`='" . $telefono . "', `razon_social`='" . $razon_social . "', `correo`='" . $correo . "', `dirección`='" . $dirección . "',  `numero_cliente`='" . $numero_cliente . "'  WHERE id =" . $id;

$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}



mysqli_stmt_execute($stmt);

echo '
        <script>
            alert("Cambios guardados");
            window.location = "clientes.php";
        </script>
    ';
?>