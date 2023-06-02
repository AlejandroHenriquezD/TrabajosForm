<?php 
// session_start(); 
echo "
<div id='fondo-confirmar-accion'>
  <div id='cuadro-confirmar-accion'>
    <p>" . $_SESSION['mensajeAccion'] . "</p>
    <form action='../volver.php' method='post'>
      <input type='submit' value='Volver'/>
    </form>
  </div>
</div>
<script type='module' src='https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js'></script>
<script nomodule src='https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js'></script>
";
?>