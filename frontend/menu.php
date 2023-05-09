<?php

  if(isset($_SESSION['usuario'])) {
    echo "
      <div id='menu-lateral'>
        <div id='desplegable-lateral' onclick='desplegarMenu()'>
          <div id='flecha-lateral'></div>
        </div>
        <div id='enlaces-menu'>
          <p>Formulario</p>
          <a href='../CRUDS/bocetos/bocetos.php'>Bocetos</a>
          <a href='../CRUDS/clientes/clientes.php'>Clientes</a>
          <a href='../CRUDS/logos/logos.php'>Logos</a>
          <a href='../CRUDS/posicion/posiciones.php'>Posiciones</a>
          <a href='../CRUDS/tipoArticulo/tiposarticulo.php'>Tipos de artículo</a>
          <a href='../CRUDS/tipoTrabajo/tipostrabajo.php'>Tipos de trabajo</a>
          <a href='../CRUDS/pedidos/pedidos.php'>Pedidos</a>
          <a href='../CRUDS/trabajos/trabajos.php'>Trabajos</a>
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
          <p>Formulario</p>
          <a href='../CRUDS/bocetos/bocetos.php'>Bocetos</a>
          <a href='../CRUDS/clientes/clientes.php'>Clientes</a>
          <a href='../CRUDS/logos/logos.php'>Logos</a>
          <a href='../CRUDS/pedidos/pedidos.php'>Pedidos</a>
          <a href='../CRUDS/trabajos/trabajos.php'>Trabajos</a>
          <a class='sesion' href='../login/login.php'>Iniciar sesión</a>
        </div>
      </div>
    ";
  }

?>