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
// params: username, password
//

$json = file_get_contents('php://input');

$obj = json_decode($json);

$username = $obj->username;
$password = $obj->password;

$query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $query);

$response = "";

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        if ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        $finalObj = (object) ['message' => "success", 'user' => $response];
    } else {
        $finalObj = (object) ['message' => "login_failed"];
    }
} else {
    $finalObj = (object) ['message' => "error"];
}

echo json_encode($finalObj, JSON_PRETTY_PRINT);

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
