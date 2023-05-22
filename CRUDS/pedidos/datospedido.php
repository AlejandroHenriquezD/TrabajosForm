<?php 
session_start(); 
$_SESSION["Volver"] = "./pedidos.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Datos del pedido</title>
  <link rel="shortcut icon" href="../../frontend/img/favicon.png">
  <link rel="stylesheet" href="../cruds.css">
</head>

<body>
  <?php
  $logos = json_decode(file_get_contents("http://localhost/trabajosform/logos"), true);
  echo "
  <h1>DATOS PEDIDO</h1>
  <div id='divDatosPedido'>
    <div>
      <p class='tituloDatos'>Tienda</p>
      <p>" . $_POST['IdDelegacion'] . "</p>
    </div>  
    <div>
      <p class='tituloDatos'>Número pedido venta</p>
      <p>" . $_POST['EjercicioPedido'] . "/" . $_POST['SeriePedido'] . "/" . $_POST['NumeroPedido'] . "</p>
    </div>  
    <div>
      <p class='tituloDatos'>Código Cliente</p>
      <p>" . $_POST['CodigoCliente'] . "</p>
    </div>  
    <div>
      <p class='tituloDatos'>Razón social</p>
      <p>" . $_POST['RazonSocial'] . "</p>
      </div>";

  if ($_POST['Estado'] == 'cancelar') {
    $estado = "No listo";
  } else {
    $estado = "Listo";
  }

  echo "<div>
      <p class='tituloDatos'>Estado</p>
      <p>" . $estado . "</p>
    </div> 
  </div>";

  $trabajos = json_decode(file_get_contents("http://localhost/trabajosform/trabajos"), true);

  echo "
  <h1>Ordenes de trabajo Serigrafía</h1>
  <table>
    <tr>
      <th>Tienda</th>
      <th>Num Pedido Venta</th>
      <th>Fecha de pedido</th>
      <th>Posición</th>
      <th>Cod Artículo</th>
      <th>Tipo de trabajo</th>
      <th>Logo</th>
      <th>Boceto</th>
      <th>Orden de Trabajo</th>
    </tr>
  ";
  $countTrabajos = 0;
  for ($p = 0; $p < count($trabajos); $p++) {
    if (
      trim($trabajos[$p]['ejercicio_pedido']) === trim($_POST['EjercicioPedido']) &&
      trim($trabajos[$p]['serie_pedido']) === trim($_POST['SeriePedido']) &&
      trim($trabajos[$p]['numero_pedido']) === trim($_POST['NumeroPedido'])
    ) {
      $posicion = json_decode(file_get_contents("http://localhost/trabajosform/posiciones/" . $trabajos[$p]['id_posicion']), true);
      $tipo_trabajo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos/" . $trabajos[$p]['id_tipo_trabajo']), true);
      $logo = json_decode(file_get_contents("http://localhost/trabajosform/logos/" . $trabajos[$p]['id_logo']), true);
      $boceto = json_decode(file_get_contents("http://localhost/trabajosform/bocetos/" . $trabajos[$p]['id_boceto']), true);

      if ($trabajos[$p]['id_logo'] == null) {
        $colLogo = "No hay logo";
      } else {
        $colLogo = "<img src='../." . $logo['img'] . "' alt='" . $logo['img'] . "' height=150px>";
      }

      echo "<tr class='fila'>";
      $mismoTrabajo = false;
      if($countTrabajos == 0) {
        foreach($trabajos as $trabajo) {
          if (
            $trabajo['ejercicio_pedido'] === $trabajos[$p]['ejercicio_pedido'] &&
            $trabajo['serie_pedido'] === $trabajos[$p]['serie_pedido'] &&
            $trabajo['numero_pedido'] === $trabajos[$p]['numero_pedido']
          ) {
            $countTrabajos++;
          }
        }
        echo "
        <td rowspan='" . $countTrabajos . "'>" . $trabajos[$p]['num_tienda'] . "</td> 
        <td rowspan='" . $countTrabajos . "'>" . $trabajos[$p]['ejercicio_pedido'] . '/' . $trabajos[$p]['serie_pedido'] . '/' . $trabajos[$p]['numero_pedido']  . "</td> 
        <td rowspan='" . $countTrabajos . "'>" . $trabajos[$p]['FechaPedido'] . "</td>
        ";
        $mismoTrabajo = true;
      }
      echo "
      <td>" . $posicion['descripcion'] . "</td> 
      <td>" . $trabajos[$p]['codigo_articulo'] . "</td>
      <td>" . $tipo_trabajo['nombre'] . "</td>
      <td>" . $colLogo . "</td>
      ";

      if($mismoTrabajo == true) {
        echo "<td rowspan='" . $countTrabajos . "'>";
        if ($trabajos[$p]['id_boceto'] != null) {
          echo "
          <form action='../." . $boceto['pdf'] . "' target='_blank'>
            <button>Ver Boceto </button>
          </form>
          ";
        } else {
          echo "No Existe Boceto";
        }
        echo "
        </td>
        <td rowspan='" . $countTrabajos . "'>
        ";

        if ($trabajos[$p]['pdf'] != null) {
          echo "
          <form action='../." . $trabajos[$p]['pdf'] . "' target='_blank'>
            <button>Ver Orden Trabajo</button>
          </form>
          ";
        } else {
          echo "Falta Orden Trabajo";
        }
        echo "</td>";
    }
      echo "</tr>";
    }
  }
  echo "</table>";
  ?>
  <?php include "./menuPedidos.php" ?>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>