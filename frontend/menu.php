<?php

  if(isset($_SESSION['usuario'])) {
    echo "
      <div id='menu-lateral'>
        <div id='desplegable-lateral' onclick='desplegarMenu()'>
          <div id='flecha-lateral'></div>
        </div>
        <div id='enlaces-menu'>
          
          <a href='../CRUDS/clientes/clientes.php'>Clientes</a>
          <p>Nuevo Trabajo</p>
          <a href='../CRUDS/posicion/posiciones.php'>Posiciones</a>
          <a href='../CRUDS/tipoArticulo/tiposarticulo.php'>Tipos de artículo</a>
          <a href='../CRUDS/tipoTrabajo/tipostrabajo.php'>Tipos de trabajo</a>
          <a href='../CRUDS/pedidos/pedidos.php'>Pedidos de Venta</a>
          <a href='../CRUDS/trabajos/trabajos.php'>Trabajos Serigrafía</a>
          <a class='sesion' href='../login/cerrarSesion.php'>Cerrar sesión</a>
        </div>
      </div>
    ";
  } else {
    echo "
      <div id='menu-lateral'>
        <div id='desplegable-lateral' onclick='desplegarMenu()'>
          <div id='flecha-lateral'></div>
        </div>
        <div id='enlaces-menu'>
          <a href='../CRUDS/clientes/clientes.php'>Clientes</a>
          <p>Nuevo Trabajo</p>
          <a href='../CRUDS/pedidos/pedidos.php'>Pedidos de Venta</a>
          <a href='../CRUDS/trabajos/trabajos.php'>Trabajos Serigrafía</a>
          <a class='sesion' href='../login/login.php'>Iniciar sesión</a>
        </div>
      </div>
    ";
  }

?>