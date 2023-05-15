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
    <?php
    
    $nombre = str_replace('+',' ',$_POST["nombre"][0]);
    $telefono = str_replace('+',' ',$_POST["telefono"][0]);
    echo "
    <form action='updatecliente.php' method='post' enctype='multipart/form-data'>

        <label for='nombre'>Nombre</label>
        <input name='id[]' type='hidden' value=". $_POST["id"][0] ."></input>
        <input required value='" . $nombre ."' type='text' id='nombre' name='nombre' placeholder='Nombre' />
        <label for='telefono'>Telefono</label>
        <input required value='" . $telefono ."' type='text' id='telefono' name='telefono' placeholder='Telefono' />
        </br> 

        <button>Editar</button>
    </form>";
    ?>
    <?php include "./menuCliente.php" ?>

<!-- "    <form action="updatepos.php" method="post" enctype="multipart/form-data">
        <label for="descripcion">Descripción</label>
        <input required type="text" id="descripcion" name="descripcion" placeholder="Descripción" />

        </br> 

        <button>Editar</button>
    </form>" -->
</body>

</html>