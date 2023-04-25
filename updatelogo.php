<?php
$pathinfo = pathinfo($_FILES["img_vectorizada"]["name"]);

$base = $pathinfo["filename"];

$base = preg_replace("/[^\w-]/", "_", $base);

$filename = $base . "." . $pathinfo["extension"];

$destination = __DIR__ . "/uploads/" . $filename;

$id = $_POST["id"];

$img_vectorizada = "./uploads/" . $filename;


// if ( ! $terms){
//     die("Terms must be accepted");
// }

$host = "localhost";
$dbname = "centraluniformes";
$username = "root";
$password = "";

$conn = mysqli_connect(hostname: $host,
               username: $username,
               password: $password,
               database: $dbname);

if (mysqli_connect_errno()) {
    die("Connection error: " . mysqli_connect_errno());
}

$sql = "UPDATE `logos` SET  `img_vectorizada`='". $img_vectorizada ."'   WHERE id =" . $id[0] ;

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

$pathinfo = pathinfo($_FILES["img_vectorizada"]["name"]);

$base = $pathinfo["filename"];

$base = preg_replace("/[^\w-]/", "_", $base);

$filename = $base . "." . $pathinfo["extension"];

$destination = __DIR__ . "/uploads/" . $filename;


$i = 1;

while (file_exists($destination)) {

    $filename = $base . "($i)." . $pathinfo["extension"];
    $destination = __DIR__ . "/uploads/" . $filename;

    $i++;
}

if (!move_uploaded_file($_FILES["img_vectorizada"]["tmp_name"], $destination)) {

    exit("Can't move uploaded file");
}


mysqli_stmt_execute($stmt);

echo "Cambios Guardados."; 

echo "<form action='CRUDS/logos/logos.php'>
        <button >Volver</button>
      </form>";
?>