<?php include "../sesion.php" ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds.css">
</head>

<body>

    <h1>Tipo Articulo</h1>
    <?php
    
    $nombre = str_replace('+',' ',$_POST["nombre"][0]);
    echo "
    <div id='div-form'>
        <form action='updatetipoarticulo.php' method='post' enctype='multipart/form-data'>
            <label for='nombre'>Nombre</label>
            <input name='id[]' type='hidden' value=". $_POST["id"][0] ."></input>
            <input required value='" . $nombre ."' type='text' id='nombre' name='nombre' placeholder='Nombre' />
            <button>Editar</button>
        </form>
    </div>";
    ?>
    <?php include "./menuTipoArticulo.php" ?>
</body>

</html>