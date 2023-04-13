<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css" />
</head>

<body>

    <h1>Tipo Trabajo</h1>

    <form action="createtipotrabajo.php" method="post" enctype="multipart/form-data">
        <label for="nombre">Nombre</label>
        <input required type="text" id="nombre" name="nombre" placeholder="Nombre" />

        </br>

        <button>Crear</button>
    </form>
</body>

</html>