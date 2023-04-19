<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bocetos</title>
    <link rel="shortcut icon" href="../../frontend/favicon.png">
    <link rel="stylesheet" href="../../styles2.css">
</head>

<body>

    <?php

    $bocetos = json_decode(file_get_contents("http://localhost/trabajosform/bocetos"), true);
    $clientes = json_decode(file_get_contents("http://localhost/trabajosform/clientes"), true);

    echo "<form action='formcreateboceto.php'>
            <button >Crear Boceto</button>
          </form>";

    echo "<table>
           <tr>
              <th>Id</th>
              <th>Nombre</th>
              <th>PDF</th>
              <th>Cliente</th>
              <th>Acciones</th>
            </tr>";
    for ($p = 0; $p < count($bocetos); $p++) {
        echo
            "<tr class='fila'>
                    <td>" . $bocetos[$p]["id"] . "</td>
                    <td>" . $bocetos[$p]["nombre"] . "</td>
                    <td>" . $bocetos[$p]["pdf"] . "</td>
                    <td>" . $clientes[$bocetos[$p]["id_cliente"] - 1]["nombre"] . "</td>
                    <td> 
                        <form action='deleteboceto.php'> <input name='id[]' type='hidden' value=" . $bocetos[$p]["id"] . "></input> <button>Borrar<ion-icon name='trash'></button> </form> 
                        
                        <form action='formupdateboceto.php' method='post'> 
                            <input name='id[]' type='hidden' value=" . $bocetos[$p]["id"] . "></input>
                            <input name='nombre[]' type='hidden' value=" . urlencode($bocetos[$p]["nombre"]) . "></input> 
                            <button>Editar Nombre<ion-icon name='create'></button> 
                        </form>
                    </td>
                </tr>";
    }
    echo "</table>"
        ?>
    <?php include "./menuBoceto.php" ?>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>