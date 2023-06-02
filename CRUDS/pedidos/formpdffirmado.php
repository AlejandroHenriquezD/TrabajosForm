<?php
session_start();
$_SESSION["Volver"] = "./pedidos.php";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Pedidos de Venta</title>
    <link rel="shortcut icon" href="../../frontend/img/favicon.png">
    <link rel="stylesheet" href="../cruds7.css">
</head>

<body>


    <form action="../../updatepdffirmado.php" method="post" enctype="multipart/form-data">


        <?php
        if (isset($_POST["CodigoCliente"][0])) {
            $_SESSION["ejercicio_pedido"] = $_POST["ejercicio_pedido"][0];
            $_SESSION["serie_pedido"] = $_POST["serie_pedido"][0];
            $_SESSION["numero_pedido"] = $_POST["numero_pedido"][0];
            $_SESSION["CodigoCliente"] = $_POST["CodigoCliente"][0];
        }
        echo "<h1>Pedido " . $_SESSION["ejercicio_pedido"] . '/' . $_SESSION["serie_pedido"] .  '/' . $_SESSION["numero_pedido"] . "</h1>";
        echo "<h2>Cliente " . $_SESSION["CodigoCliente"] . "</h2>";
       



        echo "

                <input name='ejercicio_pedido[]' type='hidden' value=" . $_SESSION["ejercicio_pedido"] . "></input> 
                <input name='serie_pedido[]' type='hidden' value=" . $_SESSION["serie_pedido"] . "></input> 
                <input name='numero_pedido[]' type='hidden' value=" . $_SESSION["numero_pedido"] . "></input> 

                <label for='pdf'>Añadir PDF</label>
                <input required accept='application/pdf' type='file' id='pdf' name='pdf'/>
                    <button>Añadir</button>"
        ?>
    </form>
    <?php
    if (isset($_SESSION['confirmarAccion'])) {
        include "../confirmarAccion.php";
    }
    ?>
    <?php include "./menuPedidos.php" ?>

</body>

</html>