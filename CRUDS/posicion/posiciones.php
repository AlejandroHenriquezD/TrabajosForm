<?php include "../sesion.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posiciones</title>
    <link rel="shortcut icon" href="../../frontend/img/favicon.png">
    <link rel="stylesheet" href="../cruds3.css">
</head>

<body>
    <h1>Posiciones</h1>
    <?php

    $posiciones = json_decode(file_get_contents("http://localhost/trabajosform/posiciones"), true);

    echo "<form action='formcreatepos.php'>
            <button id='boton-crear'>Crear Posición</button>
          </form>";

    echo "<table>
           <tr>
              <th>Descripción</th>
              <th>Acciones</th>
            </tr>";
    for ($p = 0; $p < count($posiciones); $p++) {
        echo
            "<tr class='fila'>
                    <td>" . $posiciones[$p]["descripcion"] . "</td>
                    <td> 
                        <form action='deletepos.php'> 
                            <input name='id' type='hidden' value=" . $posiciones[$p]["id"] . "></input> 
                            <input name='habilitado' type='hidden' value=" . $posiciones[$p]["habilitado"] . "></input>";
                            if($posiciones[$p]["habilitado"] == 0) {
                                echo "<button>Habilitar<ion-icon name='arrow-redo-circle'></button>";
                            } else {
                                echo "<button>Deshabilitar<ion-icon name='trash'></button>";
                            }
                        echo "
                        </form> 
                        <form action='formupdatepos.php' method='post'> 
                            <input name='id[]' type='hidden' value=" . $posiciones[$p]["id"] . "></input>
                            <input name='descripcion[]' type='hidden' value=" . urlencode($posiciones[$p]["descripcion"]) . "></input> 
                            <button>Editar<ion-icon name='create'></button> 
                        </form>

                    </td>
                </tr>";

    }
    echo "</table>";
    if(isset($_SESSION['confirmarAccion'])) {
        include "../confirmarAccion.php";
    }
        ?>
    <?php include "./menuPosiciones.php" ?>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>