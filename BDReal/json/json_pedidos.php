<?php
include_once "../numTienda.php";

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
            WHERE StatusPedido = 'P' AND EX_Serigrafiado = -1 AND IdDelegacion = '06'";

$getResults = sqlsrv_query($conn, $sql);

$host = "localhost";
$dbname = "centraluniformes";
$username = "root";
$password = "";

$conn2 = mysqli_connect(
    hostname: $host,
    username: $username,
    password: $password,
    database: $dbname
);

if (mysqli_connect_errno()) {
    die("Connection error: " . mysqli_connect_errno());
}

$data = [];

while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    $data[] = $row;

    $sql2 = "INSERT INTO clientes (
        numero_cliente,
        cif_nif,
        razon_social,
        nombre,
        dirección,
        correo,
        telefono) VALUES (?,?,?,?,?,?,?)";
    
    $stmt = mysqli_stmt_init($conn2);
    
    if (!mysqli_stmt_prepare($stmt, $sql2)) {
        die(mysqli_errno($conn2));
    }
    
    mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $row["CodigoCliente"],
        $row["CifDni"],
        $row["RazonSocial"],
        $row["Nombre"],
        $row["Domicilio"],
        $row["Email1"],
        $row["Telefono"]
    );
    
    mysqli_stmt_execute($stmt);
}

sqlsrv_free_stmt($getResults);
echo json_encode($data);

    // echo "<table>
    //         <thead>
    //             <th>Ejercicio Pedido</th>
    //             <th>Serie Pedido</th>
    //             <th>Numero Pedido</th>
    //             <th>IdDelegacion</th>
    //             <th>Codigo Cliente</th>
    //             <th>Cif / DNI</th>
    //             <th>Razon Social</th>
    //             <th>Nombre</th>
    //             <th>Domicilio</th>
    //             <th>Codigo Postal</th>
    //             <th>Municipio</th>
    //             <th>Email1</th>
    //             <th>Telefono</th>
    //             <th>StatusPedido</th>
    //             <th>EX_Serigrafiado</th>
    //         </thead>
    //         <tbody>";
    //         while($top = sqlsrv_fetch_array($getResults)){
    //             echo "<tr>
    //                     <td>". $top["EjercicioPedido"] ."</td>
    //                     <td>". $top["SeriePedido"] ."</td>
    //                     <td>". $top["NumeroPedido"] ."</td>
    //                     <td>". $top["IdDelegacion"] ."</td>
    //                     <td>". $top["CodigoCliente"] ."</td>
    //                     <td>". $top["CifDni"] ."</td>
    //                     <td>". $top["RazonSocial"] ."</td>
    //                     <td>". $top["Nombre"] ."</td>
    //                     <td>". $top["Domicilio"] ."</td>
    //                     <td>". $top["CodigoPostal"] ."</td>
    //                     <td>". $top["Municipio"] ."</td>
    //                     <td>". $top["Email1"] ."</td>
    //                     <td>". $top["Telefono"] ."</td>
    //                     <td>". $top["StatusPedido"] ."</td>
    //                     <td>". $top["EX_Serigrafiado"] ."</td>

    //                   </tr>";
    //         }
    //   echo "</tbody>
    //       </table>";
