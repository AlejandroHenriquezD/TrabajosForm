<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../../cruds.css">
</head>

<body>

    <h1>Boceto</h1>

    <div id='div-form'>
        <form action="../../createboceto.php" method="post" enctype="multipart/form-data">
            <label for="nombre">Nombre</label>
            <input required type="text" id="nombre" name="nombre" placeholder="Nombre" />

            <label for="pdf">PDF file</label>
            <input type="file" id="pdf" name="pdf">

            <?php
            $clientes = json_decode(file_get_contents("http://localhost/trabajosform/clientes"), true);
            echo "
            <label for='id_cliente'>Clientes</label>
            <select name='id_cliente'>";
            foreach ($clientes as $cliente) {
                echo "<option value='" . $cliente["id"] . "' id='id_cliente' name='id_cliente'>" . $cliente["nombre"] . "</option>";
            }

            echo "  </select>"
            ?>

            <button>Crear</button>
        </form>
    </div>
    <?php include "./menuBoceto.php" ?>
    <?php include "../background.php" ?>
</body>

</html>