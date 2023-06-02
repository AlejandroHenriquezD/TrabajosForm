<?php
session_start();
$url = $_SESSION['confirmarAccion'];
unset($_SESSION['confirmarAccion']);
header("location:".$url);
?>