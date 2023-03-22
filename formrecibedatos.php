<?php

$datos = [];

//Tipos de trabajos
function tipos_trabajos()
{
    $tiposTrabajos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos"), true);
    echo "<h1>Tipos Trabajos</h1>";
    for ($p = 0; $p < count($tiposTrabajos); $p++) {
        echo "<p><input type='checkbox' id={{$tiposTrabajos[$p]['id']}} name={{$tiposTrabajos[$p]['id']}} value={{$tiposTrabajos[$p]['nombre']}} onclick={{tipos_articulos()}}/>" . "<label for={{$tiposTrabajos[$p]['id']}}>" . $tiposTrabajos[$p]['nombre'] . "</label></p>";
    }
}

//Tipos de articulos
function tipos_articulos()
{
    $tiposArticulos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos"), true);
    echo "<h1>Tipos Articulos</h1>";
    for ($p = 0; $p < count($tiposArticulos); $p++) {
        echo "<p><input type='checkbox' id={{$tiposArticulos[$p]['id']}} name={{$tiposArticulos[$p]['id']}} value={{$tiposArticulos[$p]['nombre']}}/>" . "<label for={{$tiposArticulos[$p]['id']}}>" . $tiposArticulos[$p]['nombre'] . "</label></p>";
    }
}

tipos_trabajos();
tipos_articulos();
