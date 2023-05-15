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
    <?php
    
    echo "
    <div id='div-form'>
        <form action='../../updatelogo.php' method='post' enctype='multipart/form-data'>

            <label for='img_vectorizada'>Imagen Vectorizada</label>
            <input name='id[]' type='hidden' value=". $_POST["id"][0] ."></input>
            <input type='file' id='img_vectorizada' name='img_vectorizada'/>

            <button>Editar</button>

        </form>
    </div>";
    ?>
    <?php include "../clientes/menuCliente.php" ?>
<!-- "    <form action="updatepos.php" method="post" enctype="multipart/form-data">
        <label for="descripcion">Descripción</label>
        <input required type="text" id="descripcion" name="descripcion" placeholder="Descripción" />

        </br> 

        <button>Editar</button>
    </form>" -->
</body>

</html>