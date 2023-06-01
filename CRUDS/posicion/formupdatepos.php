<?php 
include "../sesion.php";
$_SESSION["Volver"] = "./posiciones.php";
?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds7.css">
</head>

<body>

    <h1>Posición</h1>
    <?php
    if(isset($_POST["id"])) {
        $_SESSION["id_pos"] = $_POST["id"][0];
        $_SESSION["descripcion"] = $_POST["descripcion"][0];
    }
    $descripcion = str_replace('+',' ',$_SESSION["descripcion"]);
    echo "
    <div id='div-form'>
        <form action='updatepos.php' method='post' enctype='multipart/form-data'>

            <label for='descripcion'>Descripción</label>
            <input name='id[]' type='hidden' value=". $_SESSION["id_pos"] ."></input>
            <input required value='" . $descripcion ."' type='text' id='descripcion' name='descripcion' placeholder='Descripción' />
            <button>Editar</button>
        </form>
    </div>";
    if(isset($_SESSION['confirmarAccion'])) {
        include "../confirmarAccion.php";
    }
    ?>
    <?php include "./menuPosiciones.php" ?>
</body>

</html>