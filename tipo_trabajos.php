<?php
include "config.php";
include "utils.php";


$dbConn =  connect($db);

/*
  listar todos los tipos_trabajos o solo uno
 */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['id'])) {
    //Mostrar un post
    $sql = $dbConn->prepare("SELECT * FROM tipos_trabajos where id=:id");
    $sql->bindValue(':id', $_GET['id']);
    $sql->execute();
    header("HTTP/1.1 200 OK");
    echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
    exit();
  } else {
    //Mostrar lista de post
    $sql = $dbConn->prepare("SELECT * FROM tipos_trabajos");
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    header("HTTP/1.1 200 OK");
    echo json_encode($sql->fetchAll());
    exit();
  }
}

// Crear un nuevo post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = $_POST;
  $sql = "INSERT INTO tipos_trabajos
          (nombre)
          VALUES
          (:nombre)";
  $statement = $dbConn->prepare($sql);
  bindAllValues($statement, $input);
  $statement->execute();
  $postId = $dbConn->lastInsertId();
  if ($postId) {
    $input['id'] = $postId;
    header("HTTP/1.1 200 OK");
    echo json_encode($input);
    exit();
  }
}

//Borrar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
  $id = $_GET['id'];
  $statement = $dbConn->prepare("DELETE FROM tipos_trabajos where id=:id");
  $statement->bindValue(':id', $id);
  $statement->execute();
  header("HTTP/1.1 200 OK");
  exit();
}

//Actualizar
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
  $input = $_GET;
  $tipos_trabajosId = $input['id'];
  $fields = getParams($input);

  $sql = "
          UPDATE tipos_trabajos
          SET $fields
          WHERE id='$tipos_trabajosId'
           ";

  $statement = $dbConn->prepare($sql);
  bindAllValues($statement, $input);

  $statement->execute();
  header("HTTP/1.1 200 OK");
  exit();
}


//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
