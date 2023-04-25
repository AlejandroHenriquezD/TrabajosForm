<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link rel="shortcut icon" href="../../frontend/favicon.png">
    <link rel="stylesheet" href="../../styles6.css">
</head>

<body>

    <?php

    $pedidos = json_decode(file_get_contents("http://localhost/trabajosform/pedidos"), true);
    echo "<h1>PEDIDOS</h1>";
    echo "<table>
           <tr>
              <th>Id</th>
              <th>Fecha Pedido</th>
              <th>Cliente</th>
              <th>Acciones</th>
            </tr>";
    for ($p = 0; $p < count($pedidos); $p++) {
        $cliente = json_decode(file_get_contents("http://localhost/trabajosform/clientes/" . $pedidos[$p]['id_cliente']), true);
 
        echo
            "<tr class='fila'>
                    <td>" . $pedidos[$p]["id"] . "</td>
                    <td>" . $pedidos[$p]["fecha_pedido"] . "</td>
                    <td>" . $cliente["nombre"] . "</td>
                    <td> 
    
                        <form action='formupdatepedido.php' method='post'> 
                            <input name='id[]' type='hidden' value=" . $pedidos[$p]["id"] . "></input> 
                            <input name='id_cliente[]' type='hidden' value=" . $pedidos[$p]["id_cliente"] . "></input> 
                            <button>Editar<ion-icon name='create'></button> 
                        </form>
                    </td>
                </tr>";

    }
    echo "</table>"
        ?>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>