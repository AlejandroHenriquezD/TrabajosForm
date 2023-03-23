<?php

$logos = json_decode(file_get_contents("http://localhost/API/logos"), true);

echo "<select name='img' onchange='actualizar()'>";

for ($p = 0; $p < count($logos); $p++) {
    echo "<option value='" . $logos[$p]['id'] . "'>" . $logos[$p]['img'] . "</option>";
}
echo "</select>";

if (isset($_POST['img'])) {
    $valor_seleccionado = $_POST['img'];
    echo "La opción seleccionada es: " . $valor_seleccionado;
} else {
    echo "No se ha seleccionado ninguna opción";
}


$text = "";

echo $text;

echo "<img src='" . $text . "' alt='" . $text . "'/>";
