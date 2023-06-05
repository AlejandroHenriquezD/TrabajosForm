<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds2.css">
</head>

<body>

    <h1>Cliente</h1>

    <form action="createcliente.php" method="post" enctype="multipart/form-data">
        <label for="nombre">Nombre</label>
        <input required type="text" id="nombre" name="nombre" placeholder="Nombre" />

        <label for="telefono">Telefono</label>
        <input required type="text" id="telefono" name="telefono" placeholder="Telefono" />

        </br> 

        <button>Crear</button>
    </form>
    <?php include "./menuCliente.php" ?>
</body>

</html>