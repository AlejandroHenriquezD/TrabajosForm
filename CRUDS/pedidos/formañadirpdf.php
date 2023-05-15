
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Pedidos de Venta</title>
    <link rel="shortcut icon" href="../../frontend/img/favicon.png">
    <link rel="stylesheet" href="../cruds2.css">
</head>

<body>

    
    <form action="../../createpdf.php" method="post" enctype="multipart/form-data">


        <?php
        echo "<h1>Pedido ". $_POST["ejercicio_pedido"][0]. '/' .$_POST["serie_pedido"][0].  '/' .$_POST["numero_pedido"][0]."</h1>";
        echo "<h2>Cliente ". $_POST["CodigoCliente"][0]."</h2>";
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

        $sql = "SELECT * FROM `bocetos` WHERE CodigoCliente =" . $_POST["CodigoCliente"][0];

        $result = mysqli_query($conn, $sql);



        echo "

                <input name='ejercicio_pedido[]' type='hidden' value=" . $_POST["ejercicio_pedido"][0] ."></input> 
                <input name='serie_pedido[]' type='hidden' value=" . $_POST["serie_pedido"][0] ."></input> 
                <input name='numero_pedido[]' type='hidden' value=" . $_POST["numero_pedido"][0] . "></input> 

                <label for='pdf'>Añadir PDF</label>
                <input type='file' id='pdf' name='pdf'/>
                    <button>Añadir</button>"
        ?>
    </form>
    <?php include "./menuPedidos.php" ?>

</body>

</html>