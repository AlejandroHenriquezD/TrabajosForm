<?php

if(isset($_SESSION['usuario'])) {
  echo "
    <link rel='stylesheet' href='../../menu3.css'>
    <div id='menu-lateral'>
      <div id='desplegable-lateral' onclick='desplegarMenu()'>
        <div id='flecha-lateral'></div>
      </div>
      <div id='enlaces-menu'>
        <a href='../clientes/clientes.php'>Clientes</a>  
        <a href='../../frontend/index.php'>Nuevo Trabajo</a>
        <a href='../posicion/posiciones.php'>Posiciones</a>
        <a href='../tipoArticulo/tiposarticulo.php'>Tipos de artículo</a>
        <a href='../tipoTrabajo/tipostrabajo.php'>Tipos de trabajo</a>
        <a class='enlace-seleccionado' href='../pedidos/pedidos.php'>Pedidos de Venta</a>
        <a href='../trabajos/trabajos.php'>Trabajos Serigrafía</a>
        <a class='sesion' href='../../login/cerrarSesion.php'>Cerrar sesión</a>
      </div>
    </div>
  ";
} else {
  echo "
    <link rel='stylesheet' href='../../menu3.css'>
    <div id='menu-lateral'>
      <div id='desplegable-lateral' onclick='desplegarMenu()'>
        <div id='flecha-lateral'></div>
      </div>
      <div id='enlaces-menu'>
        <a href='../clientes/clientes.php'>Clientes</a>  
        <a href='../../frontend/index.php'>Nuevo Trabajo</a>
        <a class='enlace-seleccionado' href='../pedidos/pedidos.php'>Pedidos de Venta</a>
        <a href='../trabajos/trabajos.php'>Trabajos Serigrafía</a>
        <a class='sesion' href='../../login/login.php'>Iniciar sesión</a>
      </div>
    </div>
  ";
}

?>
<?php include "../background.php" ?>
<script>
function desplegarMenu() {
  var menu = document.getElementById('menu-lateral');
  var flecha = document.getElementById('flecha-lateral');
  var filtro = document.createElement('div');
  filtro.id = 'filtro';
  filtro.onclick = desplegarMenu;
  if (menu.classList.contains('menu-lateral-desplegado')) {
      menu.classList.remove('menu-lateral-desplegado');
      flecha.classList.remove('flecha-lateral-desplegada');
      document.getElementById('filtro').remove();
  } else {
      menu.classList.add('menu-lateral-desplegado');
      flecha.classList.add('flecha-lateral-desplegada');
      document.body.appendChild(filtro);
  }
}
</script>