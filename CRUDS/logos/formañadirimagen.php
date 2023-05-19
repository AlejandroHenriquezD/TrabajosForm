<?php 
session_start(); 
$_SESSION["Volver"] = "../clientes/datoscliente.php";
?>
<!DOCTYPE html>
<html>

<head>
	<title>Formulario</title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="../cruds.css">
</head>

<body>

	<h1>Logo</h1>
	<?php
	if(isset($_POST["id"])) {
    $_SESSION["id_logo"] = $_POST["id"][0];
  }
	echo "
	<div id='div-form'>
		<form action='../../updatelogo.php' method='post' enctype='multipart/form-data'>
			<label for='img_vectorizada'>Imagen Vectorizada</label>
			<input name='id[]' type='hidden' value=" . $_SESSION["id_logo"] . "></input>
			<input required type='file' id='img_vectorizada' name='img_vectorizada' accept='image/*'/>
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