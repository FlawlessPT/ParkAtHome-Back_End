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
// params: userId, plate, parkId
//

// // TODO: FIX IDS SKIPPING ON SAVE SPACE

$json = file_get_contents('php://input');

$obj = json_decode($json);

$userId = $obj->userId;
$plate = $obj->plate;
$parkId = $obj->parkId;

if (saveSpace($conn, $userId, $plate, $parkId)) {
    $finalObj = (object) ['message' => "success"];
} else {
    $finalObj = (object) ['message' => "error"];
}

$response = json_encode($finalObj, JSON_PRETTY_PRINT);
echo $response;

function saveSpace($conn, $userId, $plate, $parkId)
{
    if (saveToLiveSavedSpaces($conn, $userId, $plate, $parkId)) {
        return true;
    }

    return false;
}

function saveToLiveSavedSpaces($conn, $userId, $plate, $parkId)
{
    date_default_timezone_set("Europe/Lisbon");

    $queryGetAllSpacesFromPark = "SELECT * FROM space WHERE idPark=$parkId";
    $resultGetAllSpacesFromPark = mysqli_query($conn, $queryGetAllSpacesFromPark);

    if ($resultGetAllSpacesFromPark) {
        if (mysqli_num_rows($resultGetAllSpacesFromPark) > 0) {
            while ($row = mysqli_fetch_assoc($resultGetAllSpacesFromPark)) {
                $idSpaceQuery = $row["id"];

                $idVehicule = getIdVehiculeByPlate($conn, $plate);

                $queryInsertLiveSpaces = "INSERT INTO livesavedspaces (idVehicule, idSpace, idUser) VALUES ($idVehicule, $idSpaceQuery, $userId)";
                $resultInsertLiveSpaces = mysqli_query($conn, $queryInsertLiveSpaces);

                if ($resultInsertLiveSpaces) {
                    if (mysqli_affected_rows($conn) > 0) {
                        return true;
                    }
                }
            }
        }
    }

    return false;
}

function getIdVehiculeByPlate($conn, $plate)
{
    $id = 0;

    $query = "SELECT id FROM vehicule WHERE plate='$plate'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_assoc($result)) {
                $id = $row["id"];
            }
        }
    }

    return $id;
}

mysqli_close($conn);


// $query = "INSERT INTO liveSavedSpaces (saved_at, idVehicule, idSpace) VALUES ('', '', '". date_default_timezone_get(). "');";
// $result = mysqli_query($conn, $query);

// if (!vehiculeIsAlreadySaved($conn, $plate)) {
//     if ($result) {
//         $finalObj = (object) ['message' => "success", 'user_id' => getUserIdByUsername($conn, $username)];
//     } else {
//         $finalObj = (object) ['message' => "error", 'user_id' => -1];
//     }
// } else {
//     $finalObj = (object) ['message' => "user_already_exists", 'user_id' => -1];
// }

// $response = json_encode($finalObj, JSON_PRETTY_PRINT);
// echo $response;

// function vehiculeIsAlreadySaved($conn, $id) {
//     $exists = false;

//     $query = "SELECT id FROM liveSavedSpaces WHERE idVehicule=$id";
//     $result = mysqli_query($conn, $query);

//     if ($result) {
//         if (mysqli_num_rows($result) > 0) {
//             if ($row = mysqli_fetch_assoc($result)) {
//                 $exists = true;
//             }
//         }
//     }

//     return $exists;
// }

// function getVehiculeIdByName($conn, $vehiculeName)
// {
//     $userId = 0;

//     $query = "SELECT id FROM vehicule WHERE name='$vehiculeName'";
//     $result = mysqli_query($conn, $query);

//     if ($result) {
//         if (mysqli_num_rows($result) > 0) {
//             if ($row = mysqli_fetch_assoc($result)) {
//                 $userId = $row["id"];
//             }
//         }
//     }

//     return $userId;
// }