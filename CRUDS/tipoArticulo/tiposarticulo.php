<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos Articulos</title>
    <link rel="shortcut icon" href="../../frontend/favicon.png">
    <link rel="stylesheet" href="../../styles.css">
</head>

<body>

    <?php

    $tipo_articulos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos"), true);

    echo "<form action='formcreatetipoarticulo.php'>
            <button >Crear Tipo Articulo</button>
          </form>";

    echo "<table>
           <tr>
              <th>Id</th>
              <th>Nombre</th>
              <th>Imagen</th>
              <th>Acciones</th>
            </tr>";
            for ($p = 0; $p < count($tipo_articulos); $p++) {
                echo 
                "<tr class='fila'>
                    <td>" . $tipo_articulos[$p]["id"] . "</td>
                    <td>" . $tipo_articulos[$p]["nombre"] . "</td>
                    <td><img src='../." . $tipo_articulos[$p]["img"] . "' alt='hola' height=150px></td>
                    <td> 
                        <form action='deletetipoarticulo.php'> <input name='id[]' type='hidden' value=". $tipo_articulos[$p]["id"] ."></input> <button>Borrar<ion-icon name='trash'></button> </form> 
                        
                        <form action='formupdatetipoarticulo.php' method='post'> 
                            <input name='id[]' type='hidden' value=". $tipo_articulos[$p]["id"] ."></input>
                            <input name='nombre[]' type='hidden' value=". urlencode($tipo_articulos[$p]["nombre"]) ."></input> 
                            <button>Editar Nombre<ion-icon name='create'></button> 
                        </form>

                        <form action='temp.php'> <input name='id[]' type='hidden' value=". $tipo_articulos[$p]["id"] ."></input> <button>Editar Posiciones<ion-icon name='create'></button> </form>

                    </td>
                </tr>"; 
   
            }
        ?>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>