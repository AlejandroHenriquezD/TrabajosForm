<?php
session_start();
$_SESSION["Volver"] = $_SESSION["VolverDatosCliente"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Datos del cliente</title>
  <link rel="shortcut icon" href="../../frontend/img/favicon.png">
  <link rel="stylesheet" href="../cruds2.css">
</head>

<body>
  <?php
  $logos = json_decode(file_get_contents("http://localhost/trabajosform/logos"), true);
  if (isset($_POST['id'])) {
    $_SESSION['id'] = $_POST['id'];
    $_SESSION['nombre'] = $_POST['nombre'];
    $_SESSION['correo'] = $_POST['correo'];
    $_SESSION['cif_nif'] = $_POST['cif_nif'];
    $_SESSION['dirección'] = $_POST['dirección'];
    $_SESSION['telefono'] = $_POST['telefono'];
    $_SESSION['razon_social'] = $_POST['razon_social'];
    $_SESSION['numero_cliente'] = $_POST['numero_cliente'];
  }
  echo "
  <div class='titulo-mas-boton'>
    <h1>DATOS CLIENTE</h1>
    <form action='formupdatecliente.php' method='post'> 
      <input name='id' type='hidden' value=" . urlencode($_SESSION['id']) . "></input>
      <input name='nombre' type='hidden' value=" . urlencode($_SESSION['nombre']) . "></input>
      <input name='telefono' type='hidden' value=" . urlencode($_SESSION['telefono']) . "></input>
      <input name='correo' type='hidden' value=" . urlencode($_SESSION['correo']) . "></input>
      <input name='dirección' type='hidden' value=" . urlencode($_SESSION['dirección']) . "></input>
      <input name='cif_nif' type='hidden' value=" . urlencode($_SESSION['cif_nif']) . "></input>
      <input name='numero_cliente' type='hidden' value=" . urlencode($_SESSION['numero_cliente']) . "></input>
      <input name='razon_social' type='hidden' value=" . urlencode($_SESSION['razon_social']) . "></input> 
      <button>Editar<ion-icon name='create'></button> 
    </form>
  </div>
  <div id='divDatosCliente'>
    <div>
      <p class='tituloDatos'>Nombre</p>
      <p>" . $_SESSION['nombre'] . "</p>
    </div>  
    <div>
      <p class='tituloDatos'>Número cliente</p>
      <p>" . $_SESSION['numero_cliente'] . "</p>
    </div>  
    <div>
      <p class='tituloDatos'>Correo</p>
      <p>" . $_SESSION['correo'] . "</p>
    </div> 
    <div>
      <p class='tituloDatos'>Cif/Nif</p>
      <p>" . $_SESSION['cif_nif'] . "</p>
    </div>  
    <div>
      <p class='tituloDatos'>Dirección</p>
      <p>" . $_SESSION['dirección'] . "</p>
    </div>  
    <div>
      <p class='tituloDatos'>Teléfono</p>
      <p>" . $_SESSION['telefono'] . "</p>
    </div>  
    <div>
      <p class='tituloDatos'>Razón social</p>
      <p>" . $_SESSION['razon_social'] . "</p>
    </div> 
  </div>
  <div class='titulo-mas-boton'>
    <h1>LOGOS</h1>
    <form action='../../createlogo.php' method='post' id='subirlogo' enctype='multipart/form-data'> 
      <input name='id' type='hidden' value=" . $_SESSION['id'] . "></input> 
      <label for='img'>
        <div class='boton-de-pega'>Subir logo<ion-icon name='arrow-up-circle'></ion-icon></div>
        <input required type='file' id='img' name='img' accept='image/*' onchange='document.getElementById(\"subirlogo\").submit()'/>
      </label>
      </form>
  </div>
  <table id='tablaClientes'>
    <tr>
      <th>Imagen</th>
      <th>Imagen Vectorizada/Texto</th>
      <th>Estado</th>
      <th>Cliente</th>
      <th>Acciones</th>
    </tr>
  ";
  foreach ($logos as $logo) {
    if ($logo['id_cliente'] == $_SESSION['id']) {
      $obsoleto = "";
      $vectorizada = "";

      if ($logo['obsoleto'] == 1) {
        $obsoleto = "Obsoleto";
      } else {
        $obsoleto = "Activo";
      }

      if ($logo['img_vectorizada'] == "FALTA") {
        $vectorizada = "Falta por añadir";
      } else if (substr($logo['img_vectorizada'], 0, 2) != "./") {
        $vectorizada = $logo['img_vectorizada'];
      } else {
        $vectorizada = "
          <div class='logo-descargable'>
              <img src='../." . $logo["img_vectorizada"] . "' alt='" . $logo["img_vectorizada"] . "' height=150px>
              <div class='descargable'>
                  <img src='../../descargar.png'>
                  <p>Descargar imagen</p>
                  <a href='../." . $logo["img"] . "' download></a>
              </div>
          </div>";
      }

      if (substr($logo['img'], 0, 2) != "./") {
        $logotipo = $logo['img'];
      } else {
        $logotipo = "<div class='logo-descargable'>
          <img src='../." . $logo["img"] . "' alt='" . $logo["img"] . "' height=150px>
          <div class='descargable'>
              <img src='../../descargar.png'>
              <p>Descargar imagen</p>
              <a href='../." . $logo["img"] . "' download></a>
          </div>
      </div>";
      }
      echo "
      <tr class='fila'>
        <td>" . $logotipo . "</td>
        <td>" . $vectorizada . "</td>
        <td>" . $obsoleto . "</td>
        <td>" . $_SESSION['razon_social'] . "</td>
        <td> 
          <form action='../logos/deletelogo.php'> <input name='id[]' type='hidden' value=" . $logo["id"] . "></input> <button>Borrar<ion-icon name='trash'></button> </form> 
          <form action='../logos/updatelogo.php' method='post'> 
            <input name='id[]' type='hidden' value=" . $logo["id"] . "></input> 
            <input name='obsoleto[]' type='hidden' value=" . $logo["obsoleto"] . "></input> 
            <button>Editar Estado<ion-icon name='create'></button> 
          </form>
        ";
      if (isset($_SESSION['usuario'])) {
        echo "
          <form action='../../updatelogo.php' method='post' id='subirvectorizada-" . $logo["id"] . "' enctype='multipart/form-data'>
            <input name='id' type='hidden' value=" . $_SESSION["id"] . "></input>
            <input name='id_logo' type='hidden' value=" . $logo["id"] . "></input>
            <label for='img_vectorizada'>
              <div class='boton-de-pega'>Añadir imagen vectorizada<ion-icon name='arrow-up-circle'></ion-icon></div>
              <input required type='file' id='img_vectorizada' name='img_vectorizada' accept='image/*' onchange='document.getElementById(\"subirvectorizada-" . $logo["id"] . "\").submit()' />
            </label>
          </form>
          ";
      }
      echo "
        </td>
      </tr>";
    }
  }
  echo "</table>";

  $bocetos = json_decode(file_get_contents("http://localhost/trabajosform/bocetos"), true);
  echo "
  <div class='titulo-mas-boton'>
    <h1>BOCETOS</h1>
  ";
  if (isset($_SESSION['usuario'])) {
    echo "
    <form action='../../createboceto.php' method='post' id='subirboceto' enctype='multipart/form-data'> 
      <input name='id' type='hidden' value=" . $_SESSION['id'] . "></input> 
      <input name='numero_cliente' type='hidden' value=" . $_SESSION['numero_cliente'] . "></input> 
      <input name='razon_social' type='hidden' value='" . $_SESSION['razon_social'] . "'></input> 
      <label for='pdf'>
        <div class='boton-de-pega'>Subir boceto<ion-icon name='arrow-up-circle'></ion-icon></div>
        <input required type='file' id='pdf' name='pdf' accept='application/pdf' onchange='document.getElementById(\"subirboceto\").submit()'/>
      </label>
    </form>
    ";
  }
  echo "
  </div>
  <table>
    <tr>
      <th>Nombre</th>
      <th>PDF</th>
      <th>Firmado</th>
      <th>Acciones</th>
    </tr>
  ";

  foreach ($bocetos as $boceto) {

    if ($boceto['firmado'] == 1) {
      $firmado = 'Sí';
    } else {
      $firmado = 'No';
    }


    if ($boceto['id_cliente'] == $_SESSION['id']) {
      echo "
      <tr class='fila'>
        <td>" . $boceto["nombre"] . "</td>
        <td>";
      echo ($boceto['pdf']);
      echo "          <form action='../." . $boceto['pdf'] . "'  target='_blank'>
            <button>Ver Boceto</button>
          </form>
        </td>
        <td>" .
        $firmado
        . "
        </td>
        <td> 
          <form action='../bocetos/deleteboceto.php'> <input name='id[]' type='hidden' value=" . $boceto["id"] . "></input> <button>Borrar<ion-icon name='trash'></button> </form> 
        ";
      // if(isset($_SESSION['usuario'])) {
      echo "
          <form action='../../updateboceto.php' method='post' enctype='multipart/form-data' id='bocetofirmado-" . $boceto["id"] . "'>
            <input name='id' type='hidden' value=" . $_SESSION["id"] . "></input>
            <input name='id_boceto' type='hidden' value=" . $boceto["id"] . "></input>
            <label for='boceto'>
              <div class='boton-de-pega'>Añadir boceto firmado<ion-icon name='arrow-up-circle'></ion-icon></div>
              <input required type='file' id='boceto' name='boceto' accept='application/pdf' onchange='document.getElementById(\"bocetofirmado-" . $boceto["id"] . "\").submit()'/>
            </label>
          </form>
          ";
      // }
      echo "
        </td>
      </tr>";
    }
  }
  echo "</table>";

  $trabajos = json_decode(file_get_contents("http://localhost/trabajosform/trabajos"), true);

  include_once "../../BDReal/numTienda.php";
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

  $pedidos = [];

  while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    $pedidos[] = $row;
  }
  sqlsrv_free_stmt($getResults);

  $pedidosCliente = array();
  foreach ($pedidos as $pedido) {
    if (
      $pedido['Nombre'] === $_SESSION['nombre'] &&
      $pedido['Telefono'] === $_SESSION['telefono'] &&
      $pedido['Email1'] === $_SESSION['correo'] &&
      $pedido['CifDni'] === $_SESSION['cif_nif'] &&
      $pedido['CodigoCliente'] === $_SESSION['numero_cliente'] &&
      $pedido['RazonSocial'] === $_SESSION['razon_social']
    ) {
      array_push($pedidosCliente, $pedido);
    }
  }

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
    foreach ($pedidosCliente as $pedidoCliente) {
      if (
        $trabajos[$p]['ejercicio_pedido'] === $pedidoCliente['EjercicioPedido'] &&
        $trabajos[$p]['serie_pedido'] === $pedidoCliente['SeriePedido'] &&
        $trabajos[$p]['numero_pedido'] === $pedidoCliente['NumeroPedido']
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
        if ($countTrabajos == 0) {
          foreach ($trabajos as $trabajo) {
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

        if ($mismoTrabajo == true) {
          echo "<td rowspan='" . $countTrabajos . "'>";
          if ($trabajos[$p]['id_boceto'] != null) {
            echo "
            <form action='../." . $boceto['pdf'] . "'>
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
            <form action='../." . $trabajos[$p]['pdf'] . "'>
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
  }
  echo "
  </table>
  <div id='margen-inferior'></div>
  ";
  if (isset($_SESSION['confirmarAccion'])) {
    include "../confirmarAccion.php";
  }
  ?>
  <?php include "./menuCliente.php" ?>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>