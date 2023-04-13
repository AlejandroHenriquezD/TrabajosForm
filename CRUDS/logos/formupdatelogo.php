<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css" />
</head>

<body>

    <h1>Logo</h1>
    <?php
    
    $obsoleto = str_replace('+',' ',$_POST["obsoleto"][0]);
    echo "
    <form action='updatelogo.php' method='post' enctype='multipart/form-data'>

        <label for='obsoleto'>Obsoleto</label>
        <p>Escriba 1 para indicar que el logo <b>ESTÁ</b> obsoleto o escriba 0 para indicar que el logo <b>NO ESTÁ</b> obsoleto</p>
        <input name='id[]' type='hidden' value=". $_POST["id"][0] ."></input>
        <input required value='" . $obsoleto ."' type='text' id='obsoleto' name='obsoleto' placeholder='Obsoleto' />
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