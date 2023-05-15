<?php
$pathinfo = pathinfo($_FILES["pdf"]["name"]);

$base = $pathinfo["filename"];

$base = preg_replace("/[^\w-]/", "_", $base);

$filename = $base . "." . $pathinfo["extension"];

$destination = __DIR__ . "/uploads/" . $filename;

$nombre = $_POST["nombre"];
$pdf = "./uploads/" . $filename;
$id_cliente = $_POST["id_cliente"];


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

$sql = "INSERT INTO bocetos (nombre,pdf,id_cliente) VALUES (?,?,?)";

$stmt = mysqli_stmt_init($conn);

if (! mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
}

mysqli_stmt_bind_param($stmt, "ssi",
                       $nombre,
                       $pdf,
                       $id_cliente);



// echo "<form action='CRUDS/bocetos/bocetos.php'>
//         <button >Volver</button>
//       </form>";

    //   if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    //     exit('POST request method required');
    // }
    
    // if (empty($_FILES)) {
    //     exit('$_FILES is empty - is file_uploads set to "Off" in php.ini?');
    // }
    
    // if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
    
    //     switch ($_FILES["image"]["error"]) {
    //         case UPLOAD_ERR_PARTIAL:
    //             exit('File only partially uploaded');
    //             break;
    //         case UPLOAD_ERR_NO_FILE:
    //             exit('No file was uploaded');
    //             break;
    //         case UPLOAD_ERR_EXTENSION:
    //             exit('File upload stopped by a PHP extension');
    //             break;
    //         case UPLOAD_ERR_FORM_SIZE:
    //             exit('File exceeds MAX_FILE_SIZE in the HTML form');
    //             break;
    //         case UPLOAD_ERR_INI_SIZE:
    //             exit('File exceeds upload_max_filesize in php.ini');
    //             break;
    //         case UPLOAD_ERR_NO_TMP_DIR:
    //             exit('Temporary folder not found');
    //             break;
    //         case UPLOAD_ERR_CANT_WRITE:
    //             exit('Failed to write file');
    //             break;
    //         default:
    //             exit('Unknown upload error');
    //             break;
    //     }
    // }
    
    // // Reject uploaded file larger than 1MB
    // // if ($_FILES["image"]["size"] > 1048576) {
    // //     exit('File too large (max 1MB)');
    // // }
    
    // // Use fileinfo to get the mime type
    // $finfo = new finfo(FILEINFO_MIME_TYPE);
    // $mime_type = $finfo->file($_FILES["image"]["tmp_name"]);
    
    // $mime_types = ["image/gif", "image/png", "image/jpeg"];
            
    // if ( ! in_array($_FILES["image"]["type"], $mime_types)) {
    //     exit("Invalid file type");
    // }
    
    // Replace any characters not \w- in the original filename
    $pathinfo = pathinfo($_FILES["pdf"]["name"]);
    
    $base = $pathinfo["filename"];
    
    $base = preg_replace("/[^\w-]/", "_", $base);
    
    $filename = $base . "." . $pathinfo["extension"];
    
    $destination = __DIR__ . "/uploads/" . $filename;
    
    // Add a numeric suffix if the file already exists
    $i = 1;
    
    while (file_exists($destination)) {
    
        $filename = $base . "($i)." . $pathinfo["extension"];
        $destination = __DIR__ . "/uploads/" . $filename;
    
        $i++;
    }
    
    if ( ! move_uploaded_file($_FILES["pdf"]["tmp_name"], $destination)) {
    
        exit("Can't move uploaded file");
    
    }
    mysqli_stmt_execute($stmt);

    // echo "Registro Guardado."; 
    echo '
        <script>
            alert("Registro Guardado");
            window.location = "./CRUDS/clientes/clientes.php";
        </script>
    ';