
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Pedidos de Venta</title>
    <link rel="shortcut icon" href="../../frontend/img/favicon.png">
    <link rel="stylesheet" href="../cruds3.css">
</head>

<body>

    
    <form action="../../updatepedido.php" method="post" enctype="multipart/form-data">


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
                <label for='id_boceto'>Añadir Boceto al pedido</label>
                <input name='ejercicio_pedido[]' type='hidden' value=" . $_POST["ejercicio_pedido"][0] ."></input> 
                <input name='serie_pedido[]' type='hidden' value=" . $_POST["serie_pedido"][0] ."></input> 
                <input name='numero_pedido[]' type='hidden' value=" . $_POST["numero_pedido"][0] . "></input> 
                <select name='id_boceto'>";
                
                
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . [$row["id"]][0] . "' id='id_boceto' name='id_boceto'>" . [$row["nombre"]][0] . "</option>";
                    }
                }

        echo "  </select>
                    <button>Añadir</button>"
        ?>
    </form>
    <?php include "./menuPedidos.php" ?>

</body>

</html>