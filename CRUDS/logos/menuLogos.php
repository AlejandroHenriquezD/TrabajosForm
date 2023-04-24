<?php
echo "
    <link rel='stylesheet' href='../menu2.css'>
    <div id='menu-lateral'>
        <div id='desplegable-lateral' onclick='desplegarMenu()'>
            <div id='flecha-lateral'></div>
        </div>
        <div id='enlaces-menu'>
            <a href='../../frontend/index.php'>Trabajos</a>
            <a href='../bocetos/bocetos.php'>Bocetos</a>
            <a href='../clientes/clientes.php'>Clientes</a>
            <a class='seleccionado' href='../logos/logos.php'>Logos</a>
            <a href='../posicion/posiciones.php'>Posiciones</a>
            <a href='../tipoArticulo/tiposarticulo.php'>Tipos de artículo</a>
            <a href='../tipoTrabajo/tipostrabajo.php'>Tipos de trabajo</a>
            <a href='../../login/login.php'>Iniciar sesión</a>
        </div>
    </div>";
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