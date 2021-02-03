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
// params: id, userId, idSpace, idPaymentMethod, idVehicule, amount, paidAt
//


$json = file_get_contents('php://input');
$obj = json_decode($json);

//remove
$id = $obj->id;

//add to history
$id = $obj->id;
$amount = $obj->amount;
$duration = $obj->duration;
$userId = $obj->userId;
$idSpace = $obj->idSpace;
$paymentMethod = $obj->paymentMethod;
$idVehicule = $obj->idVehicule;

$query = "DELETE FROM liveSavedSpaces WHERE id=$id;";
$result = mysqli_query($conn, $query);

if ($result) {
    if (mysqli_affected_rows($conn) > 0) {
        if (updatePlateState($conn, $idVehicule)) {
            $vehicule = getVehiculeNameById($conn, $idVehicule);
            $plate = getVehiculePlateById($conn, $idVehicule);

            $queryHistory = "INSERT INTO history (amount, duration, idSpace, vehicule, plate, paymentMethod, idUser)
                            VALUES ($amount, '$duration', $idSpace, '$vehicule', '$plate', '$paymentMethod', $userId)";

            $resultHistory = mysqli_query($conn, $queryHistory);

            if ($resultHistory) {
                if (mysqli_affected_rows($conn) > 0) {
                    $finalObj = (object) ['message' => "success"];
                } else {
                    $finalObj = (object) ['message' => "error_insert_history"];
                }
            } else {
                $finalObj = (object) ['message' => "error_insert_history"];
            }
        } else {
            $finalObj = (object) ['message' => "error_updating_plate_state"];
        }
    } else {
        $finalObj = (object) ['message' => "delete_failed"];
    }
} else {
    $finalObj = (object) ['message' => "error"];
}

$response = json_encode($finalObj, JSON_PRETTY_PRINT);
echo $response;

function getVehiculeNameById($conn, $idVehicule)
{
    $name = "";

    $query = "SELECT name FROM vehicule WHERE id=$idVehicule";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_assoc($result)) {
                $name = $row["name"];
            }
        }
    }

    return $name;
}

function getVehiculePlateById($conn, $idVehicule)
{
    $plate = "";

    $query = "SELECT plate FROM vehicule WHERE id=$idVehicule";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_assoc($result)) {
                $plate = $row["plate"];
            }
        }
    }

    return $plate;
}

function updatePlateState($conn, $idVehicule)
{
    $query = "UPDATE vehicule
            SET state = 0
            WHERE id=$idVehicule;";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_affected_rows($conn) > 0) {
            return true;
        }
    }

    return false;
}

mysqli_close($conn);
