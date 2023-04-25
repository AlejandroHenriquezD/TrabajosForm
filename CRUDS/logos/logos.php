<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logos</title>
    <link rel="shortcut icon" href="../../frontend/favicon.png">
    <link rel="stylesheet" href="../../styles6.css">
</head>

<body>

    <?php

    $logos = json_decode(file_get_contents("http://localhost/trabajosform/logos"), true);
    $clientes = json_decode(file_get_contents("http://localhost/trabajosform/clientes"), true);

    echo "<form action='formcreatelogo.php'>
            <button id='boton-crear'>Crear Logo</button>
          </form>";

    echo "<table>
           <tr>
              <th>Id</th>
              <th>Imagen</th>
              <th>Imagen Vectorizada</th>
              <th>Obsoleto</th>
              <th>Cliente</th>
              <th>Acciones</th>
            </tr>";
    foreach ($logos as $logo) {
        $cliente = json_decode(file_get_contents("http://localhost/trabajosform/clientes/" . $logo["id_cliente"]), true);
        echo
            "<tr class='fila'>
                    <td>" . $logo["id"] . "</td>
                    <td class='td-img'>
                        <div class='logo-descargable'>
                            <img src='../." . $logo["img"] . "' alt='" . $logo["img"] . "' height=150px>
                            <div class='descargable'>
                                <img src='../../descargar.png'>
                                <p>Descargar imagen</p>
                                <a href='../." . $logo["img"] . "' download></a>
                            </div>
                        </div>
                    </td>
                    <td><img src='../." . $logo["img_vectorizada"] . "' alt='" . $logo["img_vectorizada"] . "' height=150px></td>
                    <td>" . $logo["obsoleto"] . "</td>
                    <td>" . $cliente["nombre"] . "</td>
                    <td> 
                        <form action='deletelogo.php'> <input name='id[]' type='hidden' value=" . $logo["id"] . "></input> <button>Borrar<ion-icon name='trash'></button> </form> 
                        
                        <form action='formupdatelogo.php' method='post'> 
                            <input name='id[]' type='hidden' value=" . $logo["id"] . "></input>
                            <input name='obsoleto[]' type='hidden' value=" . urlencode($logo["obsoleto"]) . "></input> 
                            <button>Editar<ion-icon name='create'></button> 
                        </form>

                </td>
            </tr>";

    }
    echo "</table>"
        ?>
    <?php include "./menuLogos.php" ?>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>