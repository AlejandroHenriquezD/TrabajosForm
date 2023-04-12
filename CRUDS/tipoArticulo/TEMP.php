<?php

$id = $_GET["id"][0];

$tipo_articulo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos/". $id), true);

$posiciones = json_decode(file_get_contents("http://localhost/trabajosform/posiciones"), true);;

$host = "localhost";
$dbname = "centraluniformes";
$username = "root";
$password = "";

$conn = mysqli_connect(hostname: $host,
               username: $username,
               password: $password,
               database: $dbname);

$sql = "SELECT * FROM `posicionestipoarticulos` WHERE id_tipo_articulo =" .$id;

$result = mysqli_query($conn, $sql);



echo "<h1>".$tipo_articulo["nombre"] ."</h1>";

echo "<h2>Posiciones Actuales</h2>";

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      echo $posiciones[$row["id_posicion"]-1]["descripcion"] . "<br>";
    }
}