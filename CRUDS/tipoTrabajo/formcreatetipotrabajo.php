<?php include "../sesion.php" ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds3.css">
</head>

<body>

    <h1>Tipo Trabajo</h1>
    <div id='div-form'>
        <form action="createtipotrabajo.php" method="post" enctype="multipart/form-data">
            <label for="nombre">Nombre</label>
            <input required type="text" id="nombre" name="nombre" placeholder="Nombre" />
            <button>Crear</button>
        </form>
    </div>
    <?php include "./menuTipoTrabajo.php" ?>
</body>

</html>