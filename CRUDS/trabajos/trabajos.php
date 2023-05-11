<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabajos</title>
  <link rel="shortcut icon" href="../../frontend/favicon.png">
  <link rel="stylesheet" href="../../cruds.css">
</head>

<body>
  <?php

  $trabajos = json_decode(file_get_contents("http://localhost/trabajosform/trabajos"), true);

  if (!isset($_SESSION['usuario'])) {
    include "../../BDReal/numTienda.php";
    $trabajosTemp = array();
    foreach ($trabajos as $trabajo) {
      if ($trabajo['num_tienda'] == $tienda) {
        array_push($trabajosTemp, $trabajo);
      }
    }
    $trabajos = $trabajosTemp;
  }

  echo "<h1>TRABAJOS</h1>";
  echo "<table>
          <tr>
            <th>Tienda</th>
            <th>Num Pedido Venta</th>
            <th>Posición</th>
            <th>Cod Artículo</th>
            <th>Tipo de trabajo</th>
            <th>Fecha de pedido</th>
            <th>Logo</th>
            <th>Boceto</th>
            <th>Orden de Trabajo</th>
          </tr>";
  for ($p = 0; $p < count($trabajos); $p++) {
    $posicion = json_decode(file_get_contents("http://localhost/trabajosform/posiciones/" . $trabajos[$p]['id_posicion']), true);
    $tipo_trabajo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos/" . $trabajos[$p]['id_tipo_trabajo']), true);
    $logo = json_decode(file_get_contents("http://localhost/trabajosform/logos/" . $trabajos[$p]['id_logo']), true);
    $boceto = json_decode(file_get_contents("http://localhost/trabajosform/bocetos/" . $trabajos[$p]['id_boceto']), true);

    if ($trabajos[$p]['id_logo'] == null) {
      $colLogo = "No hay logo";
    } else {
      $colLogo = "<img src='../." . $logo['img'] . "' alt= '" . $logo['img'] . " height=150px>";
    }

    echo
    "<tr class='fila'>
           <td>" . $trabajos[$p]['num_tienda'] . "</td> 
           <td>" . $trabajos[$p]['ejercicio_pedido'] . '/' . $trabajos[$p]['serie_pedido'] . '/' . $trabajos[$p]['numero_pedido']  . "</td> 
           <td>" . $posicion['descripcion'] . "</td> 
           <td>" . $trabajos[$p]['codigo_articulo'] . "</td>
           <td>" . $tipo_trabajo['nombre'] . "</td>
           <td>" . $trabajos[$p]['FechaPedido'] . "</td>
           <td>" . $colLogo . "</td>
           <td>";

    if ($trabajos[$p]['id_boceto'] != null) {
      echo "<form action='../." . $boceto['pdf'] . "'>
                        <button>Ver Boceto </button>
                      </form>";
    } else {
      echo "No Existe Boceto";
    }
    echo "
            </td>
            
           <td>";

    if ($trabajos[$p]['pdf'] != null) {
      echo "<form action='../." . $trabajos[$p]['pdf'] . "'>
                    <button>Ver Orden Trabajo</button>
                  </form>";
    } else {
      echo "Falta Orden Trabajo";
    }
    echo "
            </td>

            </tr>";
  }
  echo "</table>";

  ?>
  <?php include "./menuTrabajos.php" ?>

</body>

</html>