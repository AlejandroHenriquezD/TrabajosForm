<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posiciones</title>
    <link rel="shortcut icon" href="../../frontend/favicon.png">
    <link rel="stylesheet" href="../../trabajos.css">
</head>

<body>

    <?php

    $posiciones = json_decode(file_get_contents("http://localhost/trabajosform/posiciones"), true);

    echo "<form action='formcreatepos.php'>
            <button >Crear Posición</button>
          </form>";

    echo "<table>
           <tr>
              <th>Id</th>
              <th>Descripción</th>
              <th>Acciones</th>
            </tr>";
            for ($p = 0; $p < count($posiciones); $p++) {
                echo 
                "<tr class='fila'>
                    <td>" . $posiciones[$p]["id"] . "</td>
                    <td>" . $posiciones[$p]["descripcion"] . "</td>
                    <td> 
                        <form action='deletepos.php'> <input name='id[]' type='hidden' value=". $posiciones[$p]["id"] ."></input> <button>Borrar</button> </form> 
                        
                        <form action='formupdatepos.php' method='post'> 
                            <input name='id[]' type='hidden' value=". $posiciones[$p]["id"] ."></input>
                            <input name='descripcion[]' type='hidden' value=". urlencode($posiciones[$p]["descripcion"]) ."></input> 
                            <button>Editar</button> 
                        </form>

                    </td>
                </tr>"; 
   
            }
            ?>
</body>

</html>