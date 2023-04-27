<?php
include_once "numTienda.php";

$serverName = "192.168.0.23\SQLEXIT,1433";
$connectionOptions = array(
    "Database" => "ExitERP0415",
    "Uid" => "programacion",
    "PWD" => "CU_2023",
    "CharacterSet" => "UTF-8",
    "TrustServerCertificate" => true
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

$sql = "SELECT
            CodigoAlmacen,
            CodigoArticulo,
            CodigoColor_,
            CodigoTalla,
            DescripcionArticulo,
            Unidades

        FROM PedidoVentaLineas
        WHERE    EjercicioPedido = 2023
        AND (CodigoArticulo LIKE ('6000%') OR CodigoArticulo LIKE ('6001%') OR CodigoArticulo LIKE ('6002%') OR CodigoArticulo LIKE ('6003%'))
        AND LEN(CodigoArticulo) = 7
        AND SeriePedido = 'I'
        ";

$getResults = sqlsrv_query($conn, $sql);

echo "<table>
            <thead>
                <th>Codigo Almacen</th>
                <th>Codigo Articulo</th>
                <th>Codigo Color</th>
                <th>Codigo Talla</th>
                <th>DescripcionArticulo</th>
                <th>Unidades</th>
            </thead>
            <tbody>";
            while($top = sqlsrv_fetch_array($getResults)){
                echo "<tr>
                        <td>". $top["CodigoAlmacen"] ."</td>
                        <td>". $top["CodigoArticulo"] ."</td>
                        <td>". $top["CodigoColor_"] ."</td>
                        <td>". $top["CodigoTalla"] ."</td>
                        <td>". $top["DescripcionArticulo"] ."</td>
                        <td>". $top["Unidades"] ."</td>
                      </tr>";
            }
      echo "</tbody>
          </table>";

?>
