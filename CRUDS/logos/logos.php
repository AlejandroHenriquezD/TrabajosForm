<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logos</title>
    <link rel="shortcut icon" href="../../frontend/img/favicon.png">
    <link rel="stylesheet" href="../cruds7.css">
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
              <th>Imagen</th>
              <th>Imagen Vectorizada/Texto</th>
              <th>Estado</th>
              <th>Cliente</th>
              <th>Acciones</th>
            </tr>";
    foreach ($logos as $logo) {
        $cliente = json_decode(file_get_contents("http://localhost/trabajosform/clientes/" . $logo["id_cliente"]), true);
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

        echo
        "<tr class='fila'>
                    <td>"
            . $logotipo .
            "</td>
                    <td>" . $vectorizada . "</td>
                    <td>" . $obsoleto . "</td>
                    <td>" . $cliente["razon_social"] . "</td>
                    <td> 
                        <form action='deletelogo.php'> <input name='id[]' type='hidden' value=" . $logo["id"] . "></input> <button>Borrar<ion-icon name='trash'></button> </form> 
                        
                        <form action='formupdatelogo.php' method='post'> 
                            <input name='id[]' type='hidden' value=" . $logo["id"] . "></input> 
                            <button>Editar Estado<ion-icon name='create'></button> 
                        </form>

                    <form action='formañadirimagen.php' method='post'> 
                        <input name='id[]' type='hidden' value=" . $logo["id"] . "></input> 
                        <button>Añadir Imagen Vectorizada<ion-icon name='create'></button> 
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