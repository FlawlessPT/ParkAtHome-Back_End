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
// params: userId
//

$json = file_get_contents('php://input');

$obj = json_decode($json);

$userId = $obj->userId;

$query = "SELECT * FROM history WHERE idUser=$userId";
$result = mysqli_query($conn, $query);

$response = array();

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $response[$i] = $row;
            $i++;
        }
        $finalObj = (object) ['message' => "success", 'history' => $response];

        echo json_encode($finalObj, JSON_PRETTY_PRINT);
    } else {
        $finalObj = (object) ['message' => "no_history"];
    }
} else {
    $finalObj = (object) ['message' => "error"];
}

mysqli_close($conn);
