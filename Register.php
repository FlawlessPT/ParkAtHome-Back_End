<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "parkathome_mobile";

// Cria a ligação à BD
$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
// Verifica se a ligação falhou (ou teve sucesso)
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//
// params: name, username, password, contact, email
//

$json = file_get_contents('php://input');

$obj = json_decode($json);

$name = str_shuffle("JOAOJOAO");
$username = str_shuffle("olapequenosdasdsg");
$password = $obj->password;
$contact = rand();
$email = str_shuffle("fsaddfasdad");

$query = "INSERT INTO users (name, username, password, contact, email) VALUES ('$name', '$username', '$password', '$contact', '$email');";
$result = mysqli_query($conn, $query);

if ($result) {
    $message = "Utilizador inserido!";
} else {
    $message = "Erro!";
}

$finalObj = (object) ['message' => $message];

$response = json_encode($finalObj, JSON_PRETTY_PRINT);
echo $response;



// $query = "SELECT * FROM vehicules";
// $result = mysqli_query($conn, $query);



// $response = array();

// if ($result) {
//     if (mysqli_num_rows($result) > 0) {
//         $i = 0;
//         while ($row = mysqli_fetch_assoc($result)) {
//             $response[$i]['id'] = $row["id"];
//             $response[$i]['name'] = $row["name"];
//             $response[$i]['plate'] = $row["plate"];
//             $response[$i]['state'] = $row["state"];
//             $response[$i]['idUser'] = $row["idUser"];
//             $i++;
//         }
//         echo json_encode($response, JSON_PRETTY_PRINT);
//     }
// }

mysqli_close($conn);
