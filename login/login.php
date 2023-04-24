<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar sesión</title>
  <link rel="shortcut icon" href="../frontend/favicon.png">
  <link rel="stylesheet" href="./login.css">
</head>

<body>
  <div id='pagina'>
    <img src='./cu.png' alt=''/>
    <form action='../frontend/index.php'>
      <label>Nombre de usuario
        <input type='text'>
      </label>
      <label>Contraseña
        <input type='password'>
      </label>
      <div class='button'>
        <input type='submit' value='Iniciar sesión'>
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