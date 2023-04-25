<?php include "../sesion.php" ?>
<?php

$id_tipo_articulo = $_POST["id_tipoarticulo"];
$id_posicion = $_POST["id_posicion"];

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

$sql = "INSERT INTO posicionestipoarticulos (id_tipo_articulo,id_posicion) VALUES (?,?)";

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

mysqli_stmt_bind_param($stmt, "ii",
                       $id_tipo_articulo,
                       $id_posicion);

mysqli_stmt_execute($stmt);

echo "Registro Guardado."; 

echo "<form action='tiposarticulo.php'>
        <button >Volver</button>
      </form>";
?>
<?php include "./menuTipoArticulo.php" ?>