<?php include "../sesion.php" ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds.css">
</head>

<body>

    <h1>Posición</h1>
    <div id='div-form'>
        <form action="createpos.php" method="post" enctype="multipart/form-data">
            <label for="descripcion">Descripción</label>
            <input required type="text" id="descripcion" name="descripcion" placeholder="Descripción" />
            <button>Crear</button>
        </form>
    </div>
    <?php include "./menuPosiciones.php" ?>
</body>

</html>