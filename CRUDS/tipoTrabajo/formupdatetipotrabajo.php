<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css" />
</head>

<body>

    <h1>Tipo Trabajo</h1>
    <?php
    
    $nombre = str_replace('+',' ',$_POST["nombre"][0]);
    echo "
    <form action='updatetipotrabajo.php' method='post' enctype='multipart/form-data'>

        <label for='nombre'>Nombre</label>
        <input name='id[]' type='hidden' value=". $_POST["id"][0] ."></input>
        <input required value='" . $nombre ."' type='text' id='nombre' name='nombre' placeholder='Nombre' />
        </br> 

        <button>Editar</button>
    </form>";
    ?>

<!-- "    <form action="updatepos.php" method="post" enctype="multipart/form-data">
        <label for="descripcion">Descripción</label>
        <input required type="text" id="descripcion" name="descripcion" placeholder="Descripción" />

        </br> 

        <button>Editar</button>
    </form>" -->
</body>

</html>