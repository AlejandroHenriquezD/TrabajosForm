<?php include "../sesion.php" ?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../../cruds.css">
</head>

<body>

    
    <form action="../../updatepedido.php" method="post" enctype="multipart/form-data">


        <?php
        echo "<h1>Pedido ". $_POST["id"][0]."</h1>";
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

        $sql = "SELECT * FROM `bocetos` WHERE id_cliente =" . $_POST["id_cliente"][0];

        $result = mysqli_query($conn, $sql);


        // echo "Cliente".$_POST["id_cliente"][0];
        // echo "id";
        
        $bocetos = json_decode(file_get_contents("http://localhost/trabajosform/bocetos"), true);
        // echo $bocetos[4]["id"];
        echo "
                <label for='id_boceto'>Añadir Boceto al pedido</label>
                <input name='id_pedido' type='hidden' value='".$_POST["id"][0] ."'/>
                <select name='id_boceto'>";
                
                
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . [$row["id"]][0] . "' id='id_boceto' name='id_boceto'>" . [$row["nombre"]][0] . "</option>";
                    }
                }

        echo "  </select>
                    <button>Crear</button>"
        ?>
    </form>
    <?php include "./menuPedidos.php" ?>

</body>

</html>