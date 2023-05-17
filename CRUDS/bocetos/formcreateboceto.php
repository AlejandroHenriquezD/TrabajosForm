<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds2.css">
</head>

<body>

    <h1>Boceto</h1>

    <div id='div-form'>
        <form action="../../createboceto.php" method="post" enctype="multipart/form-data">
            <?php
            echo "
            <div class='datos'>
                <p class='tituloDatos'>Cliente</p>
                <p>" . $_POST["razon_social"] . "</p>
                <input type='hidden' value= '" . $_POST["id"] . "' id='id_cliente' name='id_cliente' />
                <input type='hidden' value= '" . $_POST["numero_cliente"] . "' id='numero_cliente' name='numero_cliente' />
            </div>
            ";  
            ?>
            <label for="nombre">Nombre</label>
            <input required type="text" id="nombre" name="nombre" placeholder="Nombre" />

            <label for="pdf">PDF file</label>
            <input require type="file" id="pdf" name="pdf">

            <button>Crear</button>
        </form>
    </div>
    <?php include "../clientes/menuCliente.php" ?>
    <?php include "../background.php" ?>
</body>

</html>