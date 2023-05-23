<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds.css">
</head>

<body>

    <h1>Cliente</h1>
    <?php

    $nombre = str_replace('+', ' ', $_POST["nombre"][0]);
    $telefono = str_replace('+', ' ', $_POST["telefono"][0]);
    $correo = str_replace('+', ' ', $_POST["correo"][0]);
    $dirección = str_replace('+', ' ', $_POST["dirección"][0]);
    $cif_nif = str_replace('+', ' ', $_POST["cif_nif"][0]);
    $numero_cliente = str_replace('+', ' ', $_POST["numero_cliente"][0]);
    $razon_social = str_replace('+', ' ', $_POST["razon_social"][0]);

    echo "
    <form action='updatecliente.php' method='post' enctype='multipart/form-data'>

        <label for='nombre'>Nombre</label>
        <input name='id' type='hidden' value=" . $_POST["id"][0] . "></input>
        
        <input value='" . urldecode($nombre) . "' type='text' id='nombre' name='nombre' placeholder='Nombre' />
        <label for='cif_nif'>CIF_NIF</label>"
        . $cif_nif .
        "<label for='telefono'>Telefono</label>
        <input value='" . $telefono . "' type='text' id='telefono' name='telefono' placeholder='Telefono' />
        <label for='correo'>Correo</label>
        <input value='" . urldecode($correo) . "' type='text' id='correo' name='correo' placeholder='Correo' />
        <label for='dirección'>Dirección</label>
        <input value='" . urldecode($dirección) . "' type='text' id='dirección' name='dirección' placeholder='Dirección' />
        <label for='numero_cliente'>Número de cliente</label>";
    if (!isset($_SESSION['usuario'])) {
        echo $numero_cliente . "<input name='numero_cliente' type='hidden' value='" . $_POST['numero_cliente'][0] . "'></input>";
    } else {
        echo "<input required value='" . $numero_cliente . "' type='text' id='numero_cliente' name='numero_cliente' placeholder='Número de cliente' />";
    }
    echo "
        <label for='razon_social'>Razón social</label>
        <input required value='" . urldecode($razon_social) . "' type='text' id='razon_social' name='razon_social' placeholder='Razón social' />
        </br> 

        <button>Editar</button>
    </form>";
    ?>
    <?php include "./menuCliente.php" ?>
</body>

</html>