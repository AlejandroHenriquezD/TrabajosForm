<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds2.css">
</head>

<body>

    <h1>Logo</h1>
    <div id='div-form'>
        <form action="../../createlogo.php" method="post" enctype="multipart/form-data">
            <?php
            echo "
            <div class='datos'>
                <p class='tituloDatos'>Cliente</p>
                <p>" . $_POST["razon_social"] . "</p>
                <input type='hidden' value= '" . $_POST["id"] . "' id='id_cliente' name='id_cliente' />
            </div>
            ";  
            
            ?>
            <label for="img">Imagen</label>
            <input required type="file" id="img" name="img" accept="image/*"/>
            <button>Crear</button>
        </form>
    </div>
    <?php include "../clientes/menuCliente.php" ?>
</body>

</html>