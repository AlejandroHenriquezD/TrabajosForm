<?php
    include_once "numTienda.php";

    $serverName = "192.168.0.23\SQLEXIT,1433";
    $connectionOptions = array(
        "Database" => "ExitERP0415",
        "Uid" => "programacion",
        "PWD" => "CU_2023",
        "CharacterSet" => "UTF-8",
        "TrustServerCertificate" =>true
    );
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    $sql = "SELECT PVC.EjercicioPedido, PVC.SeriePedido, PVC.NumeroPedido FROM PedidoVentaCabecera AS PVC LEFT JOIN PedidoIntercambioCabecera AS PIC ON PIC.CodigoEmpresa = PVC.CodigoEmpresa AND PIC.EjercicioPedido = PVC.EjercicioPedido AND PIC.SeriePedido = PVC.EX_SeriePedidoIntercambio AND PIC.NumeroPedido = PVC.EX_NumeroPedidoIntercambio WHERE PVC.StatusPedido = 'P' AND PIC.StatusPedido = 'S' AND PIC.AlmacenContrapartida = '55' AND PVC.EX_Serigrafiado = -1 AND PIC.UnidadesPendientes = 0 AND PVC.IdDelegacion = '17' AND PVC.EjercicioPedido = '2023' AND PVC.SeriePedido = 'U' AND PVC.NumeroPedido = '340'";

    $getResults = sqlsrv_query($conn, $sql);

    $row_count = sqlsrv_num_rows( $getResults );


    echo "<table>
            <thead>
                <th>Ejercicio Pedido</th>
                <th>Serie Pedido</th>
                <th>Numero Pedido</th>
                
            </thead>
            <tbody>";
            while($top = sqlsrv_fetch_array($getResults)){
                echo "<tr>
                        <td>". $top["EjercicioPedido"] ."</td>
                        <td>". $top["SeriePedido"] ."</td>
                        <td>". $top["NumeroPedido"] ."</td>
                        
                      </tr>";
            }
      echo "</tbody>
          </table>";
