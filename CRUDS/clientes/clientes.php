<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="shortcut icon" href="../../frontend/favicon.png">
    <link rel="stylesheet" href="../../styles6.css">
</head>

<body>

    <?php

    $clientes = json_decode(file_get_contents("http://localhost/trabajosform/clientes"), true);

    echo "<form action='formcreatecliente.php'>
            <button id='boton-crear'>Crear Cliente</button>
          </form>";

    echo "<table>
           <tr>
              <th>Nombre</th>
              <th>Telefono</th>
              <th>Correo</th>
              <th>Dirección</th>
              <th>CIF/NIF</th>
              <th>Número de cliente</th>
              <th>Razón social</th>
              <th>Acciones</th>
            </tr>";
    for ($p = 0; $p < count($clientes); $p++) {
        echo
        "<tr class='fila'>
                    <td>" . $clientes[$p]["nombre"] . "</td>
                    <td>" . $clientes[$p]["telefono"] . "</td>
                    <td>" . $clientes[$p]["correo"] . "</td>
                    <td>" . $clientes[$p]["dirección"] . "</td>
                    <td>" . $clientes[$p]["cif_nif"] . "</td>
                    <td>" . $clientes[$p]["numero_cliente"] . "</td>
                    <td>" . $clientes[$p]["razon_social"] . "</td>
                    <td> 
                        <form action='deletecliente.php'> <input name='id[]' type='hidden' value=" . $clientes[$p]["id"] . "></input><button>Borrar <ion-icon name='trash'></ion-icon>
                        </button> </form> 
                        
                        <form action='formupdatecliente.php' method='post'> 
                            <input name='id[]' type='hidden' value=" . $clientes[$p]["id"] . "></input>
                            <input name='nombre[]' type='hidden' value=" . urlencode($clientes[$p]["nombre"]) . "></input> 
                            <input name='telefono[]' type='hidden' value=" . urlencode($clientes[$p]["telefono"]) . "></input> 
                            <button>Editar <ion-icon name='create'></ion-icon></button> 
                        </form>
                    </td>
                </tr>";
    }
    echo "</table>"
    ?>
    <?php include "./menuCliente.php" ?>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>