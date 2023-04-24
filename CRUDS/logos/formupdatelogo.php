<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../../styles.css">
</head>

<body>

    <h1>Logo</h1>
    <?php
    
    $obsoleto = str_replace('+',' ',$_POST["obsoleto"][0]);
    echo "
    <div id='div-form'>
        <form action='../../updatelogo.php' method='post' enctype='multipart/form-data'>

            <label for='obsoleto'>Obsoleto</label>
            
            <input name='id[]' type='hidden' value=". $_POST["id"][0] ."></input>
            <select name='obsoleto'>
                <option value='1' id='obsoleto' name='obsoleto'>Esta Obsoleto</option>
                <option value='0' id='obsoleto' name='obsoleto'>No Obsoleto</option>
            </select>

            <label for='img_vectorizada'>Imagen Vectorizada</label>
            <input type='file' id='img_vectorizada' name='img_vectorizada'/>

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