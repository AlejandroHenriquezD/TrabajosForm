<?php
// echo $_GET["id_relacion"];
$id = $_GET["id_relacion"];

// echo $id;

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

$sql = "DELETE FROM `posicionestipoarticulos` WHERE id =" . $id ;

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

// mysqli_stmt_bind_param($stmt, "i",
//                        $id);

mysqli_stmt_execute($stmt);

echo "Registro Borrado."; 
echo "<form action='tiposarticulo.php'>
        <button >Volver</button>
      </form>";

?>
<?php include "./menuTipoArticulo.php" ?>