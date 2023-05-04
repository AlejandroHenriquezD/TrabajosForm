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
    // Obtendo los datos de la consulta
    $numero_cliente = $row["CodigoCliente"];
    $cif_nif = $row["CifDni"];

    // Compruebo si los datos están en la otra base de datos
    $condicion = mysqli_query($conn2, "
        SELECT 
            1
        FROM clientes WHERE 
            numero_cliente = '".$numero_cliente."' AND
            cif_nif = '".$cif_nif."'
    ");

    // Si no están los introduzco
    if(mysqli_num_rows($condicion)==0) {
        $sql2 = "
            INSERT INTO clientes (
                numero_cliente,
                cif_nif,
                razon_social,
                nombre,
                dirección,
                correo,
                telefono) 
            VALUES (?,?,?,?,?,?,?)
        ";
        
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
}

sqlsrv_free_stmt($getResults);
echo json_encode($data);