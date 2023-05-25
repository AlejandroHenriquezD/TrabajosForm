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


$_SESSION["id_cliente"] = $id;
$_SESSION["nombre"] = $nombre;
$_SESSION["telefono"] = $telefono;
$_SESSION["correo"] = $correo;
$_SESSION["dirección"] = $dirección;
$_SESSION["numero_cliente"] = $numero_cliente;
$_SESSION["razon_social"] = $razon_social;

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

echo $nombre . "<br>";
echo $telefono . "<br>";
echo $razon_social . "<br>";
echo $correo . "<br>";
echo $dirección . "<br>";
echo $numero_cliente . "<br>";
echo $id . "<br>";
$sql = "UPDATE `clientes` SET `nombre`='" . $nombre . "', `telefono`='" . $telefono . "', `razon_social`='" . $razon_social . "', `correo`='" . $correo . "', `dirección`='" . $dirección . "',  `numero_cliente`='" . $numero_cliente . "'  WHERE id =" . $id;

$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

mysqli_stmt_execute($stmt);

$_SESSION['confirmarAccion'] = "./clientes/datoscliente.php";
$_SESSION['mensajeAccion'] = "Datos del cliente modificados";
header("location:./formupdatecliente.php");
?>