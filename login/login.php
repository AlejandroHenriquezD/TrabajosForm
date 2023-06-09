<?php

session_start();

if (isset($_SESSION['usuario'])) {
  header("location: ../frontend/index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión</title>
  <link rel="shortcut icon" href="../frontend/favicon.png">
  <link rel="stylesheet" href="./login.css">
  <meta http-equiv='Expires' content='0'>
  <meta http-equiv='Last-Modified' content='0'>
  <meta http-equiv='Cache-Control' content='no-cache, mustrevalidate'>
  <meta http-equiv='Pragma' content='no-cache'>
</head>

<body>
  <script>
    function entrarSinSesion() {
      window.location = "../CRUDS/clientes/clientes.php";
    }
  </script>

  <?php
  if (isset($_SESSION['usuario_incorrecto'])) {
    echo "<div id='pagina' class='login-incorrecto'>";
  } else {
    echo "<div id='pagina'>";
  }
  ?>
    <img src='./cu.png' alt='' />
    <?php 
    if (isset($_SESSION['usuario_incorrecto'])) {
      echo "<p class='texto-error'>" . $_SESSION['usuario_incorrecto'] . "</p>";
    } else {
      echo "<p class='texto-error'>Texto error</p>";
    }
    ?>
    <form action='./verificarLogin.php' method='post'>
    <label>Nombre de usuario
      <input type='text' id='usuario' name='usuario' value=''>
    </label>
    <label>Contraseña
      <input type='password' id='contraseña' name='contraseña' value=''>
    </label>
    <div class='button'>
      <input type='submit' value='Iniciar sesión'>
      <div class='div-button' onclick='entrarSinSesion()'>
        <p>Entrar sin sesión</p>
      </div>
    </div>
  </form>
  </div>
  <div id='background'>
    <div class='ball' id='greenball1'></div>
    <div class='ball' id='greenball2'></div>
    <div class='ball' id='greenball3'></div>
    <div class='ball' id='redball1'></div>
    <div class='ball' id='redball2'></div>
    <div class='ball' id='redball3'></div>
    <div class='ball' id='yellowball1'></div>
    <div class='ball' id='yellowball2'></div>
    <div class='ball' id='yellowball3'></div>
  </div>
</body>

</html>