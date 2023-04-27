<?php
    include_once "../numTienda.php";
    include_once "../conexion_exit.php";
    
    $tsql = "   SELECT
    *
    FROM PedidoVentaLineas
    WHERE    EjercicioPedido = 2023
    AND (CodigoArticulo NOT LIKE ('6000%') OR CodigoArticulo NOT LIKE ('6001%') OR CodigoArticulo NOT LIKE ('6002%') OR CodigoArticulo NOT LIKE ('6003%'))
    AND SeriePedido = 'I'

            ";


    $getResults = sqlsrv_query($conn, $tsql);

    $data = [];

    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }

sqlsrv_free_stmt($getResults);
echo json_encode($data);
