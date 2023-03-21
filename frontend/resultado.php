<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resultado</title>
  <link rel="shortcut icon" href="favicon.png">
</head>
<body>
  <?php 
    if (isset($_REQUEST['camisa'])) {
      echo $_POST['camisa'];
      echo "<br>";
    }
    if (isset($_REQUEST['camiseta'])) {
      echo $_POST['camiseta'];
      echo "<br>";
    }
    if (isset($_REQUEST['polo'])) {
      echo $_POST['polo'];
      echo "<br>";
    }
    echo "<br>";
    // echo $_POST['tecnica'];
    if (isset($_REQUEST['serigrafiado'])) {
      echo $_POST['serigrafiado'];
      echo "<br>";
    }
    if (isset($_REQUEST['bordado'])) {
      echo $_POST['bordado'];
      echo "<br>";
    }
    if (isset($_REQUEST['impresion'])) {
      echo $_POST['impresion'];
      echo "<br>";
    }
    echo "<br><br>";
    if (isset($_REQUEST['pechoIzquierdo'])) {
      echo $_POST['pechoIzquierdo'];
      echo "<br>";
    }
    if (isset($_REQUEST['pechoDerecho'])) {
      echo $_POST['pechoDerecho'];
      echo "<br>";
    }
    if (isset($_REQUEST['espalda'])) {
      echo $_POST['espalda']; 
      echo "<br>";
    }
    echo "<br>";
    echo $_POST['logo']; 
  ?>
</body>
</html>