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
            EjercicioPedido,
            SeriePedido,
            NumeroPedido,
            DescripcionArticulo


        FROM PedidoVentaLineas
        WHERE (CodigoArticulo NOT LIKE ('6000%') OR CodigoArticulo NOT LIKE ('6001%') OR CodigoArticulo NOT LIKE ('6002%') OR CodigoArticulo NOT LIKE ('6003%'))
        AND EX_Serigrafiado = -1
        AND TipoArticulo = 'M'
        AND SeriePedido = 'I'
        AND NumeroPedido = '585'
        AND EjercicioPedido = '2023'
        
        AND CodigoAlmacen = '06'
        ";

$getResults = sqlsrv_query($conn, $sql);

echo "<table>
            <thead>
                <th>Codigo Almacen</th>
                <th>Codigo Articulo</th>
                <th>Ejercicio Pedido</th>
                <th>Serie Pedido</th>
                <th>Numero Pedido</th>
                <th>Descripcion Articulo</th>

            </thead>
            <tbody>";
            while($top = sqlsrv_fetch_array($getResults)){
                echo "<tr>
                        <td>". $top["CodigoAlmacen"] ."</td>
                        <td>". $top["CodigoArticulo"] ."</td>
                        <td>". $top["EjercicioPedido"] ."</td>
                        <td>". $top["SeriePedido"] ."</td>
                        <td>". $top["NumeroPedido"] ."</td>
                        <td>". $top["DescripcionArticulo"] ."</td>
   
                      </tr>";
            }
      echo "</tbody>
          </table>";

?>
