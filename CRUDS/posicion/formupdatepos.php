<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css" />
</head>

<body>

    <h1>Posición</h1>
    <?php
    
    $descripcion = str_replace('+',' ',$_POST["descripcion"][0]);
    echo "
    <form action='updatepos.php' method='post' enctype='multipart/form-data'>

        <label for='descripcion'>Descripción</label>
        <input name='id[]' type='hidden' value=". $_POST["id"][0] ."></input>
        <input required value='" . $descripcion ."' type='text' id='descripcion' name='descripcion' placeholder='Descripción' />
        </br> 

        <button>Editar</button>
    </form>";
    ?>
    <?php include "./menuPosiciones.php" ?>
<!-- "    <form action="updatepos.php" method="post" enctype="multipart/form-data">
        <label for="descripcion">Descripción</label>
        <input required type="text" id="descripcion" name="descripcion" placeholder="Descripción" />

        </br> 

        <button>Editar</button>
    </form>" -->
</body>

</html>