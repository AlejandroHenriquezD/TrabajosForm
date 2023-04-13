<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logos</title>
    <link rel="shortcut icon" href="../../frontend/favicon.png">
    <link rel="stylesheet" href="../../trabajos.css">
</head>

<body>

    <?php

    $logos = json_decode(file_get_contents("http://localhost/trabajosform/logos"), true);
    $clientes = json_decode(file_get_contents("http://localhost/trabajosform/clientes"), true);

    echo "<form action='formcreatelogo.php'>
            <button >Crear Logo</button>
          </form>";

    echo "<table>
           <tr>
              <th>Id</th>
              <th>Imagen</th>
              <th>Imagen Vectorizada</th>
              <th>Obsoleto</th>
              <th>Cliente</th>
              <th>Acciones</th>
            </tr>";
            for ($p = 0; $p < count($logos); $p++) {
                echo 
                "<tr class='fila'>
                    <td>" . $logos[$p]["id"] . "</td>
                    <td><img src='../." . $logos[$p]["img"] . "' alt='".$logos[$p]["img"]."' height=150px></td>
                    <td><img src='../." . $logos[$p]["img_vectorizada"] . "' alt='".$logos[$p]["img_vectorizada"]."' height=150px></td>
                    <td>" . $logos[$p]["obsoleto"] . "</td>
                    <td>" . $clientes[$logos[$p]["id_cliente"]]["nombre"] . "</td>
                    <td> 
                        <form action='deletelogo.php'> <input name='id[]' type='hidden' value=". $logos[$p]["id"] ."></input> <button>Borrar</button> </form> 
                        
                        <form action='formupdatelogo.php' method='post'> 
                            <input name='id[]' type='hidden' value=". $logos[$p]["id"] ."></input>
                            <input name='obsoleto[]' type='hidden' value=". urlencode($logos[$p]["obsoleto"]) ."></input> 
                            <button>Editar Estado</button> 
                        </form>

                    </td>
                </tr>"; 
   
            }
            ?>
</body>

</html>