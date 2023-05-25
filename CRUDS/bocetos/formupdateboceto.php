<?php 
session_start(); 
$_SESSION["Volver"] = "../clientes/datoscliente.php";
?>
<!DOCTYPE html>
<html>

<head>
    <title>Formulario</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="../cruds3.css">
</head>

<body>

    <h1>Boceto</h1>
    <?php
    if(isset($_POST["nombre"])) {
        $_SESSION["nombre_boceto"] = $_POST["nombre"][0];
    }
    $nombre = str_replace('+',' ',$_SESSION["nombre_boceto"]);
    echo "
    <div id='div-form'>
		<form action='../../updateboceto.php' method='post' enctype='multipart/form-data'>
			<label for='boceto'>Boceto</label>
			<input name='id[]' type='hidden' value=" . $_SESSION["id"] . "></input>
            <input name='id_boceto[]' type='hidden' value=" . $_POST["id_boceto"][0] . "></input>
			<input required type='file' id='boceto' name='boceto' accept='application/pdf'/>
			<button>Editar</button>
		</form>
	</div>";
    if(isset($_SESSION['confirmarAccion'])) {
        include "../confirmarAccion.php";
    }
    ?>
    <?php include "../clientes/menuCliente.php" ?>
</body>

</html>