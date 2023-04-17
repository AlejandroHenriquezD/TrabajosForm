<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css" />
</head>

<body>

    <h1>Logo</h1>

    <form action="../../createlogo.php" method="post" enctype="multipart/form-data">
        <label for="img">Imagen</label>
        <input required type="file" id="img" name="img" />
        
        <label for="img_vectorizada">Imagen Vectorizada</label>
        <input type="file" id="img_vectorizada" name="img_vectorizada" />

        <?php
        $clientes = json_decode(file_get_contents("http://localhost/trabajosform/clientes"), true);
        echo"
            <label for='id_cliente'>Clientes</label>
            <select name='id_cliente'>";
        foreach ($clientes as $cliente) {
            echo "<option value='".$cliente["id"]."' id='id_cliente' name='id_cliente'>".$cliente["nombre"]."</option>";
        }
        
        echo "  </select>
                <button>Crear</button>"  
        ?>
        


        
    </form>
</body>

</html>