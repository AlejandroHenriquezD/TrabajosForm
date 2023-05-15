<?php include "../sesion.php" ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds.css">
</head>

<body>

    <h1>Tipo Articulo</h1>
    <div id='div-form'>
        <form action="../../createtipoarticulo.php" method="post" enctype="multipart/form-data">
            <label for="nombre">Nombre</label>
            <input required type="text" id="nombre" name="nombre" placeholder="Nombre" />

            <label for="image">Image file</label>
            <input type="file" id="image" name="image">
            <button>Crear</button>
        </form>
    </div>
    <?php include "./menuTipoArticulo.php" ?>
</body>

</html>