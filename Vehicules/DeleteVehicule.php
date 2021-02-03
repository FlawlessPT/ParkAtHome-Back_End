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
// params: id, userId
//


$json = file_get_contents('php://input');
$obj = json_decode($json);

$id = $obj->id;

if (isVehiculeOnUse($conn, $id)) {
    $query = "DELETE FROM vehicule WHERE id=$id;";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_affected_rows($conn) > 0) {
            $finalObj = (object) ['message' => "success"];
        } else {
            $finalObj = (object) ['message' => "delete_failed"];
        }
    } else {
        $finalObj = (object) ['message' => "error"];
    }
} else {
    $finalObj = (object) ['message' => "plate_is_used"];
}


$response = json_encode($finalObj, JSON_PRETTY_PRINT);
echo $response;

function isVehiculeOnUse($conn, $id)
{
    $sql = "SELECT state FROM vehicule WHERE id=$id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_assoc($result)) {
                if ($row["state"] == 1) {
                    return false;
                }
            }
        }
    }

    return true;
}

mysqli_close($conn);
