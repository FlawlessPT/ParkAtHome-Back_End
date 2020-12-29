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

$idSpace = $obj->id;
$name = $obj->id;


$query = "INSERT INTO liveSavedSpaces (saved_at, idVehicule, idSpace) VALUES ('', '', '". date_default_timezone_get(). "');";
$result = mysqli_query($conn, $query);

// TODO: GET A SPACE FROM SPACES TABLE AND CHECK IF IT IS ALREADY SAVED OR NOT
// TODO: FINISH THIS PAGE

if (!vehiculeIsAlreadySaved($conn, $plate)) {
    if ($result) {
        $finalObj = (object) ['message' => "success", 'user_id' => getUserIdByUsername($conn, $username)];
    } else {
        $finalObj = (object) ['message' => "error", 'user_id' => -1];
    }
} else {
    $finalObj = (object) ['message' => "user_already_exists", 'user_id' => -1];
}

$response = json_encode($finalObj, JSON_PRETTY_PRINT);
echo $response;

function vehiculeIsAlreadySaved($conn, $id) {
    $exists = false;

    $query = "SELECT id FROM liveSavedSpaces WHERE idVehicule=$id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_assoc($result)) {
                $exists = true;
            }
        }
    }

    return $exists;
}

function getVehiculeIdByName($conn, $vehiculeName)
{
    $userId = 0;

    $query = "SELECT id FROM vehicule WHERE name='$vehiculeName'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_assoc($result)) {
                $userId = $row["id"];
            }
        }
    }

    return $userId;
}

mysqli_close($conn);
