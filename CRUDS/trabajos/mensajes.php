<?php
session_start();
$_SESSION["Volver"] = "trabajos.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de mensajes</title>
    <link rel="shortcut icon" href="../../frontend/img/favicon.png">
    <link rel="stylesheet" href="../cruds7.css">
</head>

<body onload='chat()'>
    <?php

    // Obtener mensajes
    $host = "localhost";
    $dbname = "centraluniformes";
    $username = "root";
    $password = "";

    $conn = mysqli_connect(
        hostname: $host,
        username: $username,
        password: $password,
        database: $dbname
    );

    $sql = "SELECT * FROM `chats` WHERE `ejercicio_pedido` = " . $_GET['ejercicio_pedido'] . " AND `serie_pedido` = '" . $_GET['serie_pedido'] . "' AND `numero_pedido` = " . $_GET['numero_pedido'] . " ORDER BY `fecha` ASC";

    $result = mysqli_query($conn, $sql);
    $mensajes = [];

    while ($mensaje = mysqli_fetch_assoc($result)) {
        if(isset($_SESSION['usuario'])) {
            switch ($mensaje['emisor']) {
                case 'serigrafia':
                    $mensajes[] = array_merge($mensaje, array('usuario' => 'emisor'));
                    break;
                case 'tienda':
                    $mensajes[] = array_merge($mensaje, array('usuario' => 'receptor'));
                    break;
            }
        } else {
            switch ($mensaje['emisor']) {
                case 'serigrafia':
                    $mensajes[] = array_merge($mensaje, array('usuario' => 'receptor'));
                    break;
                case 'tienda':
                    $mensajes[] = array_merge($mensaje, array('usuario' => 'emisor'));
                    break;
            }
        }
    }

    $mensajes = json_encode($mensajes);

    echo "
    <script>
        function elementFromHtml(html) {
            const template = document.createElement('template');
    
            template.innerHTML = html.trim();
    
            return template.content.firstElementChild;
        }

        function chat() {
            var mensajes = $mensajes;
            var chat = '<div id=\"chat\">';
            var fechaActual;
            if(mensajes.length > 0) {
                for (mensaje of mensajes) {
                    console.log(mensaje['fecha'].toString());
                    var fecha = mensaje['fecha'].toString().split(' ')[0];
                    var hora = mensaje['fecha'].toString().split(' ')[1];
                    hora = hora.split(':')[0] + ':' + hora.split(':')[1];
                    if(fechaActual !== fecha) {
                        fechaActual = fecha;
                        chat += '<div class=\"mensaje-fecha\">';
                        chat += '<p>' + fecha + '</p>';
                        chat += '</div>';
                    }
                    chat += '<div class=\"' + mensaje['usuario'] + '\">';
                    chat += '<p class=\"fecha-mensaje\">' + hora + '</p>';
                    chat += '<p class=\"texto-mensaje\">' + mensaje['mensaje'] + '</p>';
                    chat += '</div>';
                } 
            } else {
                chat += '<p class=\"mensaje-defecto\">Iniciar conversación</p>';
            }
            chat += '</div>';
            console.log(chat);
            chat = elementFromHtml(chat);
            document.getElementById('divChat').appendChild(chat);
        }
    </script>
    <h1>Registro de mensajes</h1>
    <h2>Número pedido venta: " . $_GET['ejercicio_pedido'] . "/" . $_GET['serie_pedido'] . "/" . $_GET['numero_pedido'] . "</h2>
    <div id='divChat'>
        <form action='mensajesenviar.php' method='post'>
            <input type='hidden' name='ejercicio_pedido' value='" . $_GET['ejercicio_pedido'] . "'>
            <input type='hidden' name='serie_pedido' value='" . $_GET['serie_pedido'] . "'>
            <input type='hidden' name='numero_pedido' value='" . $_GET['numero_pedido'] . "'>
            <input type='text' name='mensaje' placeholder='Escriba aquí su mensaje'>
            <button><ion-icon name='paper-plane-outline'></ion-icon></button>
        </form>
    </div>
    ";
    ?>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <?php include "./menuTrabajos.php" ?>

</body>

</html>