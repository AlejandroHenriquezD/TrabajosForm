<?php 
include "../sesion.php";
$_SESSION["Volver"] = "./tiposarticulo.php";
?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds2.css">
</head>

<body>

    <h1>Tipo Articulo</h1>
    <?php
    if(isset($_POST["id"])) {
        $_SESSION["id_tipoArticulo"] = $_POST["id"][0];
        $_SESSION["nombre"] = $_POST["nombre"][0];
    }
    $nombre = str_replace('+',' ',$_SESSION["nombre"]);
    echo "
    <div id='div-form'>
        <form action='updatetipoarticulo.php' method='post' enctype='multipart/form-data'>
            <label for='nombre'>Nombre</label>
            <input name='id[]' type='hidden' value=". $_SESSION["id_tipoArticulo"] ."></input>
            <input required value='" . $nombre ."' type='text' id='nombre' name='nombre' placeholder='Nombre' />
            <button>Editar</button>
        </form>
    </div>";
    if(isset($_SESSION['confirmarAccion'])) {
        include "../confirmarAccion.php";
    }
    ?>
    <?php include "./menuTipoArticulo.php" ?>
</body>

</html>