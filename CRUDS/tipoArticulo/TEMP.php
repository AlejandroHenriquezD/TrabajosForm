<?php include "../sesion.php" ?>
<?php

if(isset($_POST["id"][0])) {
  $_SESSION["id_tipoArticulo"] = $_POST["id"][0];
}

$tipo_articulo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos/" . $_SESSION["id_tipoArticulo"]), true);

$posiciones = json_decode(file_get_contents("http://localhost/trabajosform/posiciones"), true);;

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

$sql = "SELECT * FROM `posicionestipoarticulos` WHERE id_tipo_articulo =" . $_SESSION["id_tipoArticulo"];

$result = mysqli_query($conn, $sql);

echo "<link rel='stylesheet' href='../cruds.css'>";
echo "<h1>".$tipo_articulo["nombre"] ."</h1>";

echo "<h2>Posiciones Actuales</h2>
<table id='seleccionados'>
    <tr>
      <th>Posicion</th>
      <th>Acción</th>
    </tr>
      ";
$array = array();
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
              <td>" . $posiciones[$row["id_posicion"] - 1]["descripcion"] . "</td>
              <td>

                <form action='deleterelacion.php'>
                  <input type='hidden' value='" . $row["id"] . "' name='id_relacion' id='id_relacion'/>
                  <button>Borrar<ion-icon name='trash'></button>
                </form>

              </td>
            </tr>";
    $array[] = $posiciones[$row["id_posicion"] - 1]["descripcion"];
  }
}
echo "</table>";
echo "<h2>Añadir Posiciones</h2>
      <div id='añadir-posiciones'>
        <form action='createrelacion.php' method='post'>
          <label for='id'>Posición</label>
          <div id='select-button'>
          <select name='id_posicion'>";
          foreach ($posiciones as $posicion) {
            if (!in_array($posicion['descripcion'], $array)) {
              echo "<option value='" . $posicion["id"] . "' id='id_posicion' name='id_posicion'>" . $posicion["descripcion"] . "</option>";
            }
          }
echo "</select>
          <button>Añadir</button>
          </div>
        </form>
      </div>";
if(isset($_SESSION['confirmarAccion'])) {
  include "../confirmarAccion.php";
}
?>



<?php include "./menuTipoArticulo.php" ?>