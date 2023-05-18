<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds.css">
</head>

<body>

    <h1>Boceto</h1>
    <?php
    
    $nombre = str_replace('+',' ',$_POST["nombre"][0]);
    echo "
    <div id='div-form'>
        <form action='updateboceto.php' method='post' enctype='multipart/form-data'>

            <label for='nombre'>Nombre</label>
            <input name='id[]' type='hidden' value=". $_POST["id"][0] ."></input>
            <select name='firmado'>
                <option value='1' id='firmado' name='firmado'>FIRMADO</option>
                <option value='0' id='firmado' name='firmado'>NO FIRMADO</option>
            </select>

            <button>Editar</button>
        </form>
    </div>";
    ?>
    <?php include "../clientes/menuCliente.php" ?>
</body>

</html>