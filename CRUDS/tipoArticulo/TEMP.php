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

echo "<h2>Posiciones Actuales</h2>
<table>
    <tr>
      <th>Posicion</th>
      <th>Acción</th>
    </tr>
      ";

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      echo "<tr>
              <td>" . $posiciones[$row["id_posicion"]-1]["descripcion"] . "</td>
              <td>

                <form action='deleterelacion.php'>
                  <input type='hidden' value='".$row["id"] ."' name='id_relacion' id='id_relacion'/>
                  <button>Borrar</button>
                </form>

              </td>
            </tr>";
    }
}
echo "</table>";

echo "<h2>Añadir Posiciones</h2>
      <form action='createrelacion.php' method='post'>
      <label for='id'>Posición</label>
      <select name='id_posicion'>";
foreach ($posiciones as $posicion) {
  echo "
        <option value='".$posicion["id"]."' id='id_posicion' name='id_posicion'>".$posicion["descripcion"]."</option>";
}

echo "<input type='hidden' value='".$id."' name='id_tipoarticulo' id='id_tipoarticulo'/>
      </select>
      <button>Añadir</button>
      </form>";
?>
<?php include "./menuTipoArticulo.php" ?>

