<?php 
session_start(); 
$_SESSION["Volver"] = "../clientes/datoscliente.php";
?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds2.css">
</head>

<body>

    <h1>Boceto</h1>
    <?php
    if(isset($_POST["nombre"])) {
        $_SESSION["nombre_boceto"] = $_POST["nombre"][0];
    }
    $nombre = str_replace('+',' ',$_SESSION["nombre_boceto"]);
    echo "
    <div id='div-form'>
        <form action='updateboceto.php' method='post' enctype='multipart/form-data'>

            <label for='nombre'>Nombre</label>
            <input name='id[]' type='hidden' value=". $_SESSION["id"] ."></input>
            <select name='firmado'>
                <option value='1' id='firmado' name='firmado'>FIRMADO</option>
                <option value='0' id='firmado' name='firmado'>NO FIRMADO</option>
            </select>

            <button>Editar</button>
        </form>
    </div>";
    if(isset($_SESSION['confirmarAccion'])) {
        include "../confirmarAccion.php";
    }
    ?>
    <?php include "../clientes/menuCliente.php" ?>
</body>

</html>