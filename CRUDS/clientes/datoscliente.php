<?php 
  session_start(); 
  $_SESSION["Volver"] = "./clientes.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Datos del cliente</title>
  <link rel="shortcut icon" href="../../frontend/img/favicon.png">
  <link rel="stylesheet" href="../cruds.css">
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
  <h1>DATOS CLIENTE</h1>
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
    <form action='../logos/formcreatelogo.php' method='post'> 
      <input name='id' type='hidden' value=" . $_SESSION['id'] . "></input> 
      <input name='razon_social' type='hidden' value='" . $_SESSION['razon_social'] . "'></input> 
      <button id='boton-crear'>Subir Logo</button>
    </form>
  </div>
  <table>
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
          <form action='../logos/formupdatelogo.php' method='post'> 
            <input name='id[]' type='hidden' value=" . $logo["id"] . "></input> 
            <button>Editar Estado<ion-icon name='create'></button> 
          </form>
          <form action='../logos/formañadirimagen.php' method='post'> 
            <input name='id[]' type='hidden' value=" . $logo["id"] . "></input> 
            <button>Añadir Imagen Vectorizada<ion-icon name='create'></button> 
          </form>
        </td>
      </tr>";
    }
  }
  echo "</table>";

  $bocetos = json_decode(file_get_contents("http://localhost/trabajosform/bocetos"), true);
  echo "
  <div class='titulo-mas-boton'>
    <h1>BOCETOS</h1>
    <form action='../bocetos/formcreateboceto.php' method='post'> 
      <input name='id' type='hidden' value=" . $_SESSION['id'] . "></input> 
      <input name='numero_cliente' type='hidden' value=" . $_SESSION['numero_cliente'] . "></input> 
      <input name='razon_social' type='hidden' value='" . $_SESSION['razon_social'] . "'></input> 
      <button id='boton-crear'>Subir Boceto</button>
    </form>
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
        <td>
          <form action='../." . $boceto['pdf'] . "'  target='_blank'>
            <button>Ver Boceto</button>
          </form>
        </td>
        <td>" .
        $firmado
        . "
        </td>
        <td> 
          <form action='../bocetos/deleteboceto.php'> <input name='id[]' type='hidden' value=" . $boceto["id"] . "></input> <button>Borrar<ion-icon name='trash'></button> </form> 
          <form action='../bocetos/formupdateboceto.php' method='post'> 
            <input name='id_boceto[]' type='hidden' value=" . $boceto["id"] . "></input>
            <input name='nombre[]' type='hidden' value=" . urlencode($boceto["nombre"]) . "></input> 
            <button>Añadir Boceto Firmado<ion-icon name='create'></button> 
          </form>
        </td>
      </tr>";
    }
  }
  echo "</table>";

  $pedidos = array();
  $trabajos = json_decode(file_get_contents("http://localhost/trabajosform/trabajos"), true);

  if (!isset($_SESSION['usuario'])) {
    $pedidos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos.php"), true);
  } else {
    $pedidos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos_todos.php"), true);
  }

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
  echo "</table>";
  if (isset($_SESSION['confirmarAccion'])) {
    include "../confirmarAccion.php";
  }
  ?>
  <?php include "./menuCliente.php" ?>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>