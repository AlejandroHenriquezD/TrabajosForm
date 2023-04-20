<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bocetos</title>
    <link rel="shortcut icon" href="../../frontend/favicon.png">
    <link rel="stylesheet" href="../../styles.css">
</head>

<body>

    <?php

    $bocetos = json_decode(file_get_contents("http://localhost/trabajosform/bocetos"), true);
    $clientes = json_decode(file_get_contents("http://localhost/trabajosform/clientes"), true);

    echo "<form action='formcreateboceto.php'>
            <button id='boton-crear'>Crear Boceto</button>
          </form>";

    echo "<table>
           <tr>
              <th>Id</th>
              <th>Nombre</th>
              <th>PDF</th>
              <th>Cliente</th>
              <th>Acciones</th>
            </tr>";
    foreach ($bocetos as $boceto) {
        $cliente = json_decode(file_get_contents("http://localhost/trabajosform/clientes/" . $boceto["id_cliente"]), true);
        echo
            "<tr class='fila'>
                    <td>" . $boceto["id"] . "</td>
                    <td>" . $boceto["nombre"] . "</td>
                    <td>" . $boceto["pdf"] . "</td>
                    <td>" . $cliente["nombre"] . "</td>
                    <td> 
                        <form action='deleteboceto.php'> <input name='id[]' type='hidden' value=" . $boceto["id"] . "></input> <button>Borrar<ion-icon name='trash'></button> </form> 
                        
                        <form action='formupdateboceto.php' method='post'> 
                            <input name='id[]' type='hidden' value=" . $boceto["id"] . "></input>
                            <input name='nombre[]' type='hidden' value=" . urlencode($boceto["nombre"]) . "></input> 
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