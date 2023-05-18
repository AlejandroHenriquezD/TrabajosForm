<?php
session_start();

$id = $_POST["id"];
$obsoleto = $_POST["obsoleto"];



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

$sql = "UPDATE `logos` SET `obsoleto`='". $obsoleto ."'   WHERE id =" . $id[0] ;

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

mysqli_stmt_execute($stmt);

// echo '
//         <script>
//             alert("Cambios guardados");
//             window.location = "../clientes/clientes.php";
//         </script>
//     ';

$_SESSION['confirmarAccion'] = "./clientes/datoscliente.php";
$_SESSION['mensajeAccion'] = "Estado modificado";
header("location:./formupdatelogo.php");
?>