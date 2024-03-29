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
    <link rel="stylesheet" href="../cruds.css">
  <meta http-equiv='Expires' content='0'>
  <meta http-equiv='Last-Modified' content='0'>
  <meta http-equiv='Cache-Control' content='no-cache, mustrevalidate'>
  <meta http-equiv='Pragma' content='no-cache'>
</head>

<body>


    <form action="../../updatepedido.php" method="post" enctype="multipart/form-data">
        
        <?php
        if(isset($_POST["CodigoCliente"][0])) {
            $_SESSION["ejercicio_pedido"] = $_POST["ejercicio_pedido"][0];
            $_SESSION["serie_pedido"] = $_POST["serie_pedido"][0];
            $_SESSION["numero_pedido"] = $_POST["numero_pedido"][0];
            $_SESSION["CodigoCliente"] = $_POST["CodigoCliente"][0];
        }
        echo "<h1>Pedido " . $_SESSION["ejercicio_pedido"] . '/' . $_SESSION["serie_pedido"] .  '/' . $_SESSION["numero_pedido"] . "</h1>";
        echo "<h2>Cliente " . $_SESSION["CodigoCliente"] . "</h2>";
        $host = "localhost";
        $dbname = "centraluniformes";
        $username = "root";
        $password = "";

        $conn = mysqli_connect(
            hostname: $host,
            username: $username,
            password: $password,
            database: $dbname
        );

        $sql = "SELECT * FROM `bocetos` WHERE CodigoCliente =" . $_SESSION["CodigoCliente"] .  " ";;

        $result = mysqli_query($conn, $sql);



        echo "
                <label for='id_boceto'>Añadir Boceto al pedido</label>
                <input name='ejercicio_pedido[]' type='hidden' value=" . $_SESSION["ejercicio_pedido"] . "></input> 
                <input name='serie_pedido[]' type='hidden' value=" . $_SESSION["serie_pedido"] . "></input> 
                <input name='numero_pedido[]' type='hidden' value=" . $_SESSION["numero_pedido"] . "></input> 

                <label for='id_boceto'><div class='boton-de-pega'>Añadir Boceto</div></label>
                <input required accept='application/pdf' type='file' id='id_boceto' name='id_boceto'/>
                
                
                <script>
                function clickado(){
                    var firmadoInput = document.getElementById('firmado');
                    var firmado = firmadoInput.checked ? 1 : 0;
                    firmadoInput.value = firmado;
                }
                </script>
                ";
        echo "
                <button>Añadir</button>"
        ?>
    </form>
    <?php
    if(isset($_SESSION['confirmarAccion'])) {
        include "../confirmarAccion.php";
    }
    ?>
    <?php include "./menuPedidos.php" ?>

</body>

</html>