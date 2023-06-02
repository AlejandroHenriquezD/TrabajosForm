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

    $sql = "SELECT    
                EjercicioPedido,
                SeriePedido,
                NumeroPedido,
                FechaPedido,
                IdDelegacion,
                CodigoCliente,
                CifDni,
                RazonSocial,
                Nombre,
                Domicilio,
                CodigoPostal,
                Municipio,
                Email1,
                Telefono,
                StatusPedido,
                EX_Serigrafiado
            FROM PedidoVentaCabecera
            WHERE StatusPedido = 'P' AND EX_Serigrafiado = -1 ";

    $getResults = sqlsrv_query($conn, $sql);

    echo "<table>
            <thead>
                <th>Ejercicio Pedido</th>
                <th>Serie Pedido</th>
                <th>Numero Pedido</th>
                <th>Fecha Pedido</th>
                <th>IdDelegacion</th>
                <th>Codigo Cliente</th>
                <th>Cif / DNI</th>
                <th>Razon Social</th>
                <th>Nombre</th>
                <th>Domicilio</th>
                <th>Codigo Postal</th>
                <th>Municipio</th>
                <th>Email1</th>
                <th>Telefono</th>
                <th>StatusPedido</th>
                <th>EX_Serigrafiado</th>
            </thead>
            <tbody>";
            while($top = sqlsrv_fetch_array($getResults)){
                $Date = $top['FechaPedido']->format('d/m/Y');
                echo "<tr>
                        <td>". $top["EjercicioPedido"] ."</td>
                        <td>". $top["SeriePedido"] ."</td>
                        <td>". $top["NumeroPedido"] ."</td>
                        <td>". $Date ."</td>
                        <td>". $top["IdDelegacion"] ."</td>
                        <td>". $top["CodigoCliente"] ."</td>
                        <td>". $top["CifDni"] ."</td>
                        <td>". $top["RazonSocial"] ."</td>
                        <td>". $top["Nombre"] ."</td>
                        <td>". $top["Domicilio"] ."</td>
                        <td>". $top["CodigoPostal"] ."</td>
                        <td>". $top["Municipio"] ."</td>
                        <td>". $top["Email1"] ."</td>
                        <td>". $top["Telefono"] ."</td>
                        <td>". $top["StatusPedido"] ."</td>
                        <td>". $top["EX_Serigrafiado"] ."</td>

                      </tr>";
            }
      echo "</tbody>
          </table>";

?>