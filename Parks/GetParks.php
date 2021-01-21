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
// params: 
//

$query = "SELECT * FROM park";
$result = mysqli_query($conn, $query);

$response = array();

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $row["totalSavedSpaces"] = countParkSavedSpaces($conn, $row["id"]);
            $response[$i] = $row;
            $i++;
        }
        $finalObj = (object) ['message' => "success", 'parks' => $response];

        echo json_encode($finalObj, JSON_PRETTY_PRINT);
    } else {
        $finalObj = (object) ['message' => "no_parks_found", 'parks' => $response];
    }
} else {
    $finalObj = (object) ['message' => "error", 'parks' => $response];
}

mysqli_close($conn);


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
