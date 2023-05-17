<?php include "../sesion.php" ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds2.css">
</head>

<body>

    <h1>Posición</h1>
    <?php
    
    $descripcion = str_replace('+',' ',$_POST["descripcion"][0]);
    echo "
    <div id='div-form'>
        <form action='updatepos.php' method='post' enctype='multipart/form-data'>

            <label for='descripcion'>Descripción</label>
            <input name='id[]' type='hidden' value=". $_POST["id"][0] ."></input>
            <input required value='" . $descripcion ."' type='text' id='descripcion' name='descripcion' placeholder='Descripción' />
            <button>Editar</button>
        </form>
    </div>";
    ?>
    <?php include "./menuPosiciones.php" ?>
</body>

</html>