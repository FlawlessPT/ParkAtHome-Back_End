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
// params: parkId
//

$json = file_get_contents('php://input');

$obj = json_decode($json);

$parkId = $obj->parkId;

$finalObj = (object) ['message' => "success", "totalSavedSpaces" => countParkSavedSpaces($conn, $parkId)];

//
// WARNING:
//
// THIS PAGE IS NOT BEING USED. IT'S HERE JUST IN CASE OF NEED TO ONLY GET TOTAL SAVED SPACES BY PARK.
// The response of this page is being sent on GetParks.php every park is get from database.
//

$response = json_encode($finalObj, JSON_PRETTY_PRINT);
echo $response;

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

mysqli_close($conn);
