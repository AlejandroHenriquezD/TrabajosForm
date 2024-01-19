<?php
session_start();

include_once "../BDReal/numTienda.php";
$_SESSION['numTienda'] = $tienda;

$serverName = "192.168.0.23\SQLEXIT,1433";
$connectionOptions = array(
  "Database" => "ExitERP0415",
  "Uid" => "programacion",
  "PWD" => "CU_2023",
  "CharacterSet" => "UTF-8",
  "TrustServerCertificate" => true
);
$connSQLSERVER = sqlsrv_connect($serverName, $connectionOptions);
if (isset($_SESSION['usuario'])) {
  $sql = "SELECT    
                EjercicioPedido,
                SeriePedido,
                NumeroPedido,
                FechaPedido,
                IdDelegacion,
                CodigoCliente,
                CifDni,
                RazonSocial,
                Nombre,
                Domicilio,
                CodigoPostal,
                Municipio,
                Email1,
                Telefono,
                StatusPedido,
                EX_Serigrafiado
            FROM PedidoVentaCabecera
            WHERE StatusPedido = 'P' AND EX_Serigrafiado = -1 
            ORDER BY EjercicioPedido DESC, SeriePedido ASC, NumeroPedido ASC
        ";
} else {
  $sql = "SELECT    
                EjercicioPedido,
                SeriePedido,
                NumeroPedido,
                FechaPedido,
                IdDelegacion,
                CodigoCliente,
                CifDni,
                RazonSocial,
                Nombre,
                Domicilio,
                CodigoPostal,
                Municipio,
                Email1,
                Telefono,
                StatusPedido,
                EX_Serigrafiado
            FROM PedidoVentaCabecera
            WHERE StatusPedido = 'P' AND EX_Serigrafiado = -1 AND IdDelegacion = '" . $tienda . "' 
            ORDER BY EjercicioPedido DESC, SeriePedido ASC, NumeroPedido ASC
        ";
}

$getResults = sqlsrv_query($connSQLSERVER, $sql);
if (mysqli_connect_errno()) {
  die("Connection error: " . mysqli_connect_errno());
}

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

$pedidos = [];

while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
  $pedidos[] = $row;
  $numero_cliente = $row["CodigoCliente"];
  $cif_nif = $row["CifDni"];

  // Compruebo si los datos están en la otra base de datos
  $condicion = mysqli_query($conn, "
                SELECT 
                    1
                FROM clientes WHERE 
                    razon_social = '" . $row["RazonSocial"] . "' AND
                    numero_cliente = '" . $row["CodigoCliente"] . "' AND
                    cif_nif = '" . $row["CifDni"] . "'
            ");

  // Si no están los introduzco
  if (mysqli_num_rows($condicion) == 0) {
    $sql2 = "
                    INSERT INTO clientes (
                        numero_cliente,
                        cif_nif,
                        razon_social,
                        nombre,
                        dirección,
                        correo,
                        telefono) 
                    VALUES (?,?,?,?,?,?,?)
                ";

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql2)) {
      die(mysqli_errno($conn));
    }

    mysqli_stmt_bind_param(
      $stmt,
      "sssssss",
      $row["CodigoCliente"],
      $row["CifDni"],
      $row["RazonSocial"],
      $row["Nombre"],
      $row["Domicilio"],
      $row["Email1"],
      $row["Telefono"]
    );

    mysqli_stmt_execute($stmt);
  }
}
sqlsrv_free_stmt($getResults);


$clientes = json_decode(file_get_contents("http://localhost/trabajosform/clientes"), true);

$articulos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_articulos.php"), true);
$articulosColor = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_articulos_color.php"), true);
// $tiposTrabajos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos"), true);

$sql = "SELECT * FROM `tipos_trabajos` WHERE habilitado = 1";
$result = mysqli_query($conn, $sql);
$tiposTrabajos = [];
if (mysqli_num_rows($result) >= 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $tiposTrabajos[] = $row;
  }
}

// $tiposArticulos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos"), true);

$sql = "SELECT * FROM `tipos_articulos` WHERE habilitado = 1";
$result = mysqli_query($conn, $sql);
$tiposArticulos = [];
if (mysqli_num_rows($result) >= 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $tiposArticulos[] = $row;
  }
}

// $tiposPosiciones = json_decode(file_get_contents("http://localhost/trabajosform/posiciones"), true);

$sql = "SELECT * FROM `posiciones` WHERE habilitado = 1";
$result = mysqli_query($conn, $sql);
$tiposPosiciones = [];
if (mysqli_num_rows($result) >= 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $tiposPosiciones[] = $row;
  }
}

$logos = array();
$posicionesArticulos = json_decode(file_get_contents("http://localhost/trabajosform/posiciones_tipo_articulos/"), true);
$numeroPedidos = count($pedidos);

$numeroArticulos = count($articulos);
$numeroTrabajos = count($tiposTrabajos);
$numeroTipoArticulos = count($tiposArticulos);
$numeroPosicionesArticulos = count($posicionesArticulos);
$numeroPosiciones = count($tiposPosiciones);

$arrayBocetos = array();
$bocetosUrl = array();
$arrayArticulos = array();
$trabajos = array();
$arrayTipoArticulos = array();
$arrayLogos = array();
$posiciones = array();
$arrayLogos = array();
$relacion = array();
$divPedidos = "<div id='pedidos'><div id='divPedidos'><h1>Pedido</h1><select name='selectPedido[]' id='selectPedido' onchange='mostrarArticulos()'>";
$divPedidos .= "<option id='pedidoDefault' value='pedidoDefault'>--</option>";

for ($o = 0; $o < $numeroPedidos; $o++) {
  $sql = "SELECT * FROM `trabajos` 
          WHERE ejercicio_pedido =" . $pedidos[$o]['EjercicioPedido'] .  " 
          AND serie_pedido ='" . $pedidos[$o]['SeriePedido'] .  "'
          AND numero_pedido =" . $pedidos[$o]['NumeroPedido'];
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) == 0) {
    $divPedidos .= "<option id='{$pedidos[$o]['SeriePedido']}-{$pedidos[$o]['NumeroPedido']}' value='{$pedidos[$o]['SeriePedido']}-{$pedidos[$o]['NumeroPedido']}'>{$pedidos[$o]['EjercicioPedido']}" . "/" . "{$pedidos[$o]['SeriePedido']}" . "/" . "{$pedidos[$o]['NumeroPedido']}</option>";
  }
}
$divPedidos .= "</select></div>";


for ($o = 0; $o < $numeroPedidos; $o++) {
  $arrayBocetos[$o] = "<div class='div-boceto' id='boceto-{$pedidos[$o]['SeriePedido']}-{$pedidos[$o]['NumeroPedido']}'><div id='boceto'><h1 class='titulo'>Boceto</h1><select name='selectBoceto[]' id='selectBoceto' onchange='updatePdf()'>";
  // Hay que buscar el boceto por el id en la tabla cliente, por tanto debemos obtenerlo antes comparando valores con la tabla pedidos
  foreach ($clientes as $cliente) {
    // if($cliente['numero_cliente'] == $pedidos[$o]['CodigoCliente'] &&
    // $cliente['cif_nif'] == $pedidos[$o]['CifDni'] ){
    //   if($cliente['telefono'] == $pedidos[$o]['Telefono']) {
    //   echo $cliente['telefono'] . " // " . $pedidos[$o]['Telefono'] . " // true <br><br>";
    //   } else {
    //     echo $cliente['telefono'] . " // " . $pedidos[$o]['Telefono'] . " // false <br><br>";
    //   }
    // }
    if (
      $cliente['numero_cliente'] == $pedidos[$o]['CodigoCliente'] &&
      $cliente['cif_nif'] == $pedidos[$o]['CifDni'] &&
      $cliente['razon_social'] == $pedidos[$o]['RazonSocial']
    ) {
      $sql = "SELECT * FROM `bocetos` WHERE id_cliente =" . $cliente['id'] .  "";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) >= 0) {
        $arrayBocetos[$o] .= "<option id='bocetoDefault' value='bocetoDefault'>Boceto no seleccionado</option>";
        while ($row = mysqli_fetch_assoc($result)) {
          $arrayBocetos[$o] .= "<option id=" . trim(json_encode([$row["id"]][0]), '"')  . " value=" . trim(json_encode([$row["id"]][0]), '"') . ">" . trim(json_encode([$row['nombre']][0]), '"') . "</option>";
          $bocetosUrl[$o][$row["id"]][0] = trim(json_encode([$row["pdf"]][0]), '"');
        }
      }
    }
  }
  $arrayBocetos[$o] .= "</select></div></div>";

  $arrayArticulos[$o] = "<div class='articulo' id='articulos-{$pedidos[$o]['SeriePedido']}-{$pedidos[$o]['NumeroPedido']}'><h1>Articulos </h1><div id='cb-articulos'>";
  for ($i = 0; $i < $numeroArticulos; $i++) {

    if (
      $articulos[$i]['EjercicioPedido'] == $pedidos[$o]['EjercicioPedido'] &&
      $articulos[$i]['SeriePedido'] == $pedidos[$o]['SeriePedido'] &&
      $articulos[$i]['NumeroPedido'] == $pedidos[$o]['NumeroPedido']
    ) {
      $arrayArticulos[$o] .= "<div id=\"form-control-{$articulos[$i]['CodigoArticulo']}-" . str_replace('-', '[guion]', $articulos[$i]['DescripcionArticulo']) . "\">";
      $arrayArticulos[$o] .= "<label class='label-articulo' for=\"articulo-{$articulos[$i]['CodigoArticulo']}-{$articulos[$i]['DescripcionArticulo']}\">";
      $arrayArticulos[$o] .= "<div><input type='checkbox' id=\"articulo-{$articulos[$i]['CodigoArticulo']}-{$articulos[$i]['DescripcionArticulo']}\" name='articulo[]' value=\"{$articulos[$i]['DescripcionArticulo']}\" onclick='mostrarTiposArticulos(\"form-control-{$articulos[$i]['CodigoArticulo']}-" . str_replace('-', '[guion]', $articulos[$i]['DescripcionArticulo']) . "\")'>" . $articulos[$i]['DescripcionArticulo'] . "</div>";
      $arrayArticulos[$o] .= "<p class='referencia-articulo'><b>Referencia:</b> " . $articulos[$i]['CodigoArticulo'] . "</p><p class='codigo-color'><b>Colores:</b>";
      foreach ($articulosColor as $articuloColor) {
        if (
          $articulos[$i]['CodigoAlmacen'] == $articuloColor['CodigoAlmacen'] &&
          $articulos[$i]['CodigoArticulo'] == $articuloColor['CodigoArticulo'] &&
          $articulos[$i]['EjercicioPedido'] == $articuloColor['EjercicioPedido'] &&
          $articulos[$i]['SeriePedido'] == $articuloColor['SeriePedido'] &&
          $articulos[$i]['NumeroPedido'] == $articuloColor['NumeroPedido'] &&
          $articulos[$i]['DescripcionArticulo'] == $articuloColor['DescripcionArticulo']
        ) {
          $arrayArticulos[$o] .= " " . $articuloColor['CodigoColor_'];
        }
      }
      $arrayArticulos[$o] .= "</p></label></div>";
    }
  }
  $arrayArticulos[$o] .= "</div></div>";
}
$divPedidos .= "</div>";

// Tipos de Articulos
$arrayTipoArticulos = "<div class='tipoArticulo' id=\"tipoArticulos-codigoArticulo\">";
$arrayTipoArticulos .= "<h1>Tipo de articulo</h1><div class='slider'><div class='coleccion'>";
for ($a = 0; $a < $numeroTipoArticulos; $a++) {
  $arrayTipoArticulos .= "<div class='ta' id=\"form-control-codigoArticulo-{$tiposArticulos[$a]['id']}\">";
  $arrayTipoArticulos .= "<input type='radio' class=\"articuloRadio-codigoArticulo\" id=\"tipoArticulo-codigoArticulo-{$tiposArticulos[$a]['id']}\" name=\"articuloRadio-codigoArticulo\" value=\"{$tiposArticulos[$a]['nombre']}\" onclick='mostrarPosiciones(\"form-control-codigoArticulo-{$tiposArticulos[$a]['id']}\")'>";
  $arrayTipoArticulos .= "<img src=\".{$tiposArticulos[$a]['img']}\" alt=\".{$tiposArticulos[$a]['img']}\"/>";
  $arrayTipoArticulos .= "<br></div>";
}
$arrayTipoArticulos .= "</div></div></div>";

// Posiciones
for ($a = 0; $a < $numeroTipoArticulos; $a++) {
  $posiciones[$a] = "<div class='posiciones' id=\"posiciones-codigoArticulo-{$tiposArticulos[$a]['id']}\"><h1>Posiciones</h1><div class='coleccionHorizontal'>";
  for ($p = 0; $p < $numeroPosicionesArticulos; $p++) {
    $posIndex = array_search($posicionesArticulos[$p]['id_posicion'], array_column($tiposPosiciones, 'id'));
    if ($posicionesArticulos[$p]['id_tipo_articulo'] == $tiposArticulos[$a]['id']) {
      // Obtiene la posición del array donde se encuentra el id de la posición
      $posiciones[$a] .= "<div id=\"form-control-codigoArticulo-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\">";
      $posiciones[$a] .= "<label for=\"posicion-codigoArticulo-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\">";
      $posiciones[$a] .= "<input type='checkbox' id=\"posicion-codigoArticulo-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" class='posicion-checkbox' name=\"{$tiposPosiciones[$posIndex]['descripcion']}\" value=\"form-control-codigoArticulo-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" onclick='mostrarTrabajos(\"form-control-codigoArticulo-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\")'>";
      $posiciones[$a] .= $tiposPosiciones[$posIndex]['descripcion'] . "</label>";
      $posiciones[$a] .= "</div>";
    }
  }
  $posiciones[$a] .= "</div></div>";
}

// Trabajos
$trabajos = "<div class='trabajos' id=\"trabajos-codigoArticulo-tiposArticulos-posiciones\"><div class='seleccionado'><h1>nombrePosicion</h1></div><h1>Trabajos</h1><div class='coleccionHorizontal'>";
for ($t = 0; $t < $numeroTrabajos; $t++) {
  $trabajos .= "<div id=\"form-control-codigoArticulo-tiposArticulos-posiciones-{$tiposTrabajos[$t]['id']}\">";
  $trabajos .= "<label for=\"trabajo-codigoArticulo-tiposArticulos-posiciones-{$tiposTrabajos[$t]['id']}\">";
  $trabajos .= "<input type='radio' class=\"trabajo-codigoArticulo-tiposArticulos-posiciones\" id=\"trabajo-codigoArticulo-tiposArticulos-posiciones-{$tiposTrabajos[$t]['id']}\" name='trabajo-codigoArticulo-tiposArticulos-posiciones' value={{$tiposTrabajos[$t]['nombre']}} onclick='mostrarLogos(\"form-control-codigoArticulo-tiposArticulos-posiciones-{$tiposTrabajos[$t]['id']}\")'>";
  $trabajos .= $tiposTrabajos[$t]['nombre'] . "</label>";
  $trabajos .= "</div>";
}
$trabajos .= "</div>";

// Logos
for ($o = 0; $o < $numeroPedidos; $o++) {
  $arrayLogos[$o] = "<div class='logos' id='logos-{$pedidos[$o]['SeriePedido']}-{$pedidos[$o]['NumeroPedido']}-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos'>";
  // Tenemos que encontrar el id cliente en la tabla de cliente con la informacion del pedido
  // 1: Buscar en json pedidos los datos de cliente
  foreach ($clientes as $cliente) {
    if (
      $cliente['numero_cliente'] == $pedidos[$o]['CodigoCliente'] &&
      $cliente['cif_nif'] == $pedidos[$o]['CifDni'] &&
      $cliente['razon_social'] == $pedidos[$o]['RazonSocial']
    ) {
      // 2: Querie con el id del cliente para obtener sus logos
      $sql = "SELECT * FROM `logos` WHERE id_cliente =" . $cliente['id'] . " AND obsoleto=0";
      $result = mysqli_query($conn, $sql);
      $logos[$o] = array();
      if (mysqli_num_rows($result) > 0) {
        $l = 0;
        while ($row = mysqli_fetch_assoc($result)) {
          $logos[$o][$l] = $row;
          $l++;
        }
      }
      $arrayLogos[$o] .= "<div class='seleccionado'><h1>nombrePosicion</h1></div><h1>Logotipo</h1><div class='slider'><div class='coleccion'>";

      $arrayLogos[$o] .= "<div class='ta' id=\"form-control-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos-0\">";
      $arrayLogos[$o] .= "<input type='radio' class=\"logoRadio-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos\" id=\"logo-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos-0\" name=\"img-input[grupo-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos]\" value=\"logo-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos-0\" onclick='logoSeleccionado(\"logoRadio-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos\")'>";
      $arrayLogos[$o] .= "<img src=\"../frontend/img/eliminar.png\" alt=\"Sin logo\"/>";
      $arrayLogos[$o] .= "</div>";

      if (count($logos[$o]) > 0) {
        for ($l = 0; $l < count($logos[$o]); $l++) {
          if (substr($logos[$o][$l]['img'], 0, 2) != "./") {
            $logotipo = $logos[$o][$l]['img'];
          } else {
            $logotipo = "<img src=\".{$logos[$o][$l]['img']}\" alt=\".{$logos[$o][$l]['img']}\"/>";
          }

          $arrayLogos[$o] .= "<div class='ta' id=\"form-control-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos-{$logos[$o][$l]['id']}\">";
          $arrayLogos[$o] .= "<input type='radio' class=\"logoRadio-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos\" id=\"logo-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos-{$logos[$o][$l]['id']}\" name=\"img-input[grupo-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos]\" value=\"logo-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos-{$logos[$o][$l]['id']}\" onclick='logoSeleccionado(\"logoRadio-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos\")'>";
          $arrayLogos[$o] .= $logotipo;
          $arrayLogos[$o] .= "</div>";
        }
      }
      $arrayLogos[$o] .= "</div>";
    }
  }
}

$desplegables = "<div class='desplegable' id='desplegable-codigos' onclick='desplegable(\"tipo-codigos\")'><p>textoPadre</p><div class='flecha' id='flecha-codigos'></div></div>";

$pedidos = json_encode($pedidos);
$clientes = json_encode($clientes);
$bocetosUrl = json_encode($bocetosUrl);
$arrayBocetos = json_encode($arrayBocetos);
$arrayArticulos = json_encode($arrayArticulos);
$arrayTipoArticulos = json_encode($arrayTipoArticulos);
$desplegables = json_encode($desplegables);
$arrayTrabajos = json_encode($trabajos);
$arrayPosiciones = json_encode($posiciones);
$arrayLogos = json_encode($arrayLogos);
$art = json_encode($articulos);
$tra = json_encode($tiposTrabajos);
$tart = json_encode($tiposArticulos);
$pos = json_encode($tiposPosiciones);
$relacion = json_encode($relacion);
