<?php include "../sesion.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos Trabajo</title>
    <link rel="shortcut icon" href="../../frontend/img/favicon.png">
    <link rel="stylesheet" href="../cruds.css">
  <meta http-equiv='Expires' content='0'>
  <meta http-equiv='Last-Modified' content='0'>
  <meta http-equiv='Cache-Control' content='no-cache, mustrevalidate'>
  <meta http-equiv='Pragma' content='no-cache'>
</head>

<body>
    <h1>Tipos Trabajos</h1>
    <?php

    $tipo_trabajos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos"), true);

    echo "<form action='formcreatetipotrabajo.php'>
            <button id='boton-crear'>Crear Tipo Trabajo</button>
          </form>";

    echo "<table>
           <tr>
              <th>Nombre</th>
              <th>Acciones</th>
            </tr>";
    for ($p = 0; $p < count($tipo_trabajos); $p++) {
        echo
            "<tr class='fila'>
                    <td>" . $tipo_trabajos[$p]["nombre"] . "</td>
                    <td> 
                        <form action='deletetipotrabajo.php'> 
                            <input name='id' type='hidden' value=" . $tipo_trabajos[$p]["id"] . "></input> 
                            <input name='habilitado' type='hidden' value=" . $tipo_trabajos[$p]["habilitado"] . "></input>";
                            if($tipo_trabajos[$p]["habilitado"] == 0) {
                                echo "<button>Habilitar<ion-icon name='arrow-redo-circle'></button>";
                            } else {
                                echo "<button>Deshabilitar<ion-icon name='trash'></button>";
                            }
                        echo "
                        </form> 
                        <form action='formupdatetipotrabajo.php' method='post'> 
                            <input name='id[]' type='hidden' value=" . $tipo_trabajos[$p]["id"] . "></input>
                            <input name='nombre[]' type='hidden' value=" . urlencode($tipo_trabajos[$p]["nombre"]) . "></input> 
                            <button>Editar Nombre<ion-icon name='create'></button> 
                        </form>
                    </td>
                </tr>";

    }
    echo "</table>";
    if(isset($_SESSION['confirmarAccion'])) {
        include "../confirmarAccion.php";
    }
        ?>
    <?php include "./menuTipoTrabajo.php" ?>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>