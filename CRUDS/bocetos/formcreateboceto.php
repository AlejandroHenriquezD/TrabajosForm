<?php 
session_start(); 
$_SESSION["Volver"] = "../clientes/datoscliente.php";
?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds.css">
</head>

<body>

    <h1>Boceto</h1>

    <div id='div-form'>
        <form action="../../createboceto.php" method="post" enctype="multipart/form-data">
            <?php
            echo "
            <div class='datos'>
                <p class='tituloDatos'>Cliente</p>
                <p>" . $_SESSION["razon_social"] . "</p>
                <input type='hidden' value= '" . $_SESSION["id"] . "' id='id_cliente' name='id_cliente' />
                <input type='hidden' value= '" . $_SESSION["numero_cliente"] . "' id='numero_cliente' name='numero_cliente' />
            </div>
            ";  
            ?>
            <label for="nombre">Nombre</label>
            <input required type="text" id="nombre" name="nombre" placeholder="Nombre" />

            <label for="pdf">PDF file</label>
            <input required type="file" id="pdf" name="pdf" accept="application/pdf">

            <button>Subir</button>
        </form>
    </div>
    <?php 
    if(isset($_SESSION['confirmarAccion'])) {
        include "../confirmarAccion.php";
    }
    ?>
    <?php include "../clientes/menuCliente.php" ?>
</body>

</html>