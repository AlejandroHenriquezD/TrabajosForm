<?php

$id = $_POST["id"];
$nombre = $_POST["nombre"];

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

$sql = "UPDATE `bocetos` SET `nombre`='". $nombre ."' WHERE id =" . $id[0] ;

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}



mysqli_stmt_execute($stmt);

echo "Cambios Guardados."; 

echo "<form action='bocetos.php'>
        <button >Volver</button>
      </form>";
?>
<?php include "./menuBoceto.php" ?>