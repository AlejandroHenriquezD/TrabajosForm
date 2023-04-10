<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabajos</title>
    <link rel="shortcut icon" href="./frontend/favicon.png">
    <link rel="stylesheet" href="trabajos.css">
</head>

<body>
    <?php

    $datos = [];
    function trabajos()
    {
        $trabajos = json_decode(file_get_contents("http://localhost/trabajosform/trabajos"), true);
        echo "<table>
           <tr>
              <th>Posición</th>
              <th>Artículo</th>
              <th>Tipo de trabajo</th>
              <th>Fecha de pedido</th>
              <th>Logo</th>
           </tr>";
        for ($p = 0; $p < count($trabajos); $p++) {
            $posicion = json_decode(file_get_contents("http://localhost/trabajosform/posiciones/" . $trabajos[$p]['id_posicion']), true);
            $articulo = json_decode(file_get_contents("http://localhost/trabajosform/articulos/" . $trabajos[$p]['id_articulo']), true);
            $tipo_trabajo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos/" . $trabajos[$p]['id_tipo_trabajo']), true);
            $pedido = json_decode(file_get_contents("http://localhost/trabajosform/pedidos/" . $trabajos[$p]['id_pedido']), true);
            $logo = json_decode(file_get_contents("http://localhost/trabajosform/logos/" . $trabajos[$p]['id_logo']), true);

            $articulo = json_decode(file_get_contents("http://localhost/API/articulos/" . $trabajos[$p]['id_articulo']), true);
            $tipo_articulo = json_decode(file_get_contents("http://localhost/API/tipo_articulos/" . $articulo['id_tipo_articulo']), true);
            $tipo_trabajo = json_decode(file_get_contents("http://localhost/API/tipo_trabajos/" . $trabajos[$p]['id_tipo_trabajo']), true);
            $pedido = json_decode(file_get_contents("http://localhost/API/pedidos/" . $trabajos[$p]['id_pedido']), true);
            $logo = json_decode(file_get_contents("http://localhost/API/logos/" . $trabajos[$p]['id_logo']), true);

            echo
            "<tr class='fila'>
           <td>" . $trabajos[$p]['id'] . "</td> 
           <td>" . $posicion['descripcion'] . "</td> 
           <td>" . $articulo['descripcion'] . "</td>
           <td>" . $tipo_trabajo['nombre'] . "</td>
           <td>" . $pedido['fecha_pedido'] . "</td>
           <td><img src='" . $logo['img'] . "' alt='hola' height=150px></td>
        </tr>";
        }
        echo "</table>";
    }

    trabajos();
    ?>

</body>

</html>