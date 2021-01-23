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
// params: name
//

$json = file_get_contents('php://input');

$obj = json_decode($json);

$id = $obj->id;

$query = "SELECT * FROM park WHERE id=$id";
$result = mysqli_query($conn, $query);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        if ($row = mysqli_fetch_assoc($result)) {
            $row["totalSavedSpaces"] = countParkSavedSpaces($conn, $row["id"]);
            $response = $row;
        }

        $finalObj = (object) ['message' => "success", 'park' => $response];
    } else {
        $finalObj = (object) ['message' => "park_not_found"];
    }
} else {
    $finalObj = (object) ['message' => "error"];
}

function countParkSavedSpaces($conn, $parkId)
{
    $total = 0;

    $query = "SELECT idSpace FROM liveSavedSpaces";
    $result = mysqli_query($conn, $query);

    $response = array();

    if ($result) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $response[$i] = $row["idSpace"];
            $i++;

            $idSpace = $row["idSpace"];

            $query2 = "SELECT * FROM space WHERE id=$idSpace AND idPark=$parkId";
            $result2 = mysqli_query($conn, $query2);

            if ($result2) {
                while ($row = mysqli_fetch_assoc($result2)) {
                    $total++;
                }
            }
        }
    }


    return $total;
}

echo json_encode($finalObj, JSON_PRETTY_PRINT);

mysqli_close($conn);
