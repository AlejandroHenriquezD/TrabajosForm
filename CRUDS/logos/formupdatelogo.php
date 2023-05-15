<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds.css">
</head>

<body>

    <h1>Logo</h1>
    <?php
    
    echo "
    <div id='div-form'>
        <form action='updatelogo.php' method='post' enctype='multipart/form-data'>

            <label for='obsoleto'>Obsoleto</label>
            
            <input name='id[]' type='hidden' value=". $_POST["id"][0] ."></input>
            <select name='obsoleto'>
                <option value='1' id='obsoleto' name='obsoleto'>Esta Obsoleto</option>
                <option value='0' id='obsoleto' name='obsoleto'>No Obsoleto</option>
            </select>



            <button>Editar</button>

        </form>
    </div>";
    ?>
    <?php include "./menuLogos.php" ?> 
<!-- "    <form action="updatepos.php" method="post" enctype="multipart/form-data">
        <label for="descripcion">Descripción</label>
        <input required type="text" id="descripcion" name="descripcion" placeholder="Descripción" />

        </br> 

        <button>Editar</button>
    </form>" -->
</body>

</html>