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

$query = "SELECT * FROM liveSavedSpaces WHERE idUser=$userId";
$result = mysqli_query($conn, $query);

$response = array();

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $idVehicule = $row["idVehicule"];

            $row["vehicule"] = getVehiculeNameById($conn, $idVehicule);

            unset($row["idVehicule"]);
            $response[$i] = $row;
            $i++;
        }
        $finalObj = (object) ['message' => "success", 'savedSpaces' => $response];

        echo json_encode($finalObj, JSON_PRETTY_PRINT);
    } else {
        $finalObj = (object) ['message' => "no_history"];
    }
} else {
    $finalObj = (object) ['message' => "error"];
}

function getVehiculeNameById($conn, $idVehicule)
{
    $vehicule = "";

    $query = "SELECT name FROM vehicule WHERE id=$idVehicule";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_assoc($result)) {
                $vehicule = $row["name"];
            }
        }
    }

    return $vehicule;
}

mysqli_close($conn);
