<?php
include_once "../numTienda.php";
include_once "../conexion_exit.php";

$tsql = "SELECT DISTINCT
						CodigoAlmacen,
						CodigoArticulo,
						EjercicioPedido,
						SeriePedido,
						NumeroPedido,
						DescripcionArticulo,
						CodigoColor_
		FROM PedidoVentaLineas
		WHERE (CodigoArticulo NOT LIKE ('6000%') OR CodigoArticulo NOT LIKE ('6001%') OR CodigoArticulo NOT LIKE ('6002%') OR CodigoArticulo NOT LIKE ('6003%'))
		AND EX_Serigrafiado = -1
		AND TipoArticulo = 'M'
		-- AND CodigoAlmacen = '06'
		ORDER BY EjercicioPedido DESC, SeriePedido ASC, NumeroPedido ASC
		";


$getResults = sqlsrv_query($conn, $tsql);

$data = [];

while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
	$data[] = $row;
}

sqlsrv_free_stmt($getResults);
echo json_encode($data);
