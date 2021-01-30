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
            $idSpace = $row["idSpace"];

            $row["vehicule"] = getVehiculeNameById($conn, $idVehicule);
            $row["plate"] = getVehiculePlateById($conn, $idVehicule);
            $row["park"] = getParkNameByIdSpace($conn, $idSpace);
            $row["pricePerHour"] = getParkPricePerHourByIdSpace($conn, $idSpace);

            $response[$i] = $row;
            $i++;
        }
        $finalObj = (object) ['message' => "success", 'savedSpaces' => $response];
    } else {
        $finalObj = (object) ['message' => "no_saved_spaces"];
    }
} else {
    $finalObj = (object) ['message' => "error"];
}


echo json_encode($finalObj, JSON_PRETTY_PRINT);

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

function getParkNameByIdSpace($conn, $idSpace)
{
    $park = "";

    $query = "SELECT idPark FROM space WHERE id=$idSpace";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_assoc($result)) {
                $idPark = $row["idPark"];

                $queryParkName = "SELECT name FROM park WHERE id=$idPark";
                $resultParkName = mysqli_query($conn, $queryParkName);

                if ($resultParkName) {
                    if (mysqli_num_rows($resultParkName) > 0) {
                        if ($row = mysqli_fetch_assoc($resultParkName)) {
                            $park = $row["name"];
                        }
                    }
                }
            }
        }
    }

    return $park;
}

function getParkPricePerHourByIdSpace($conn, $idSpace)
{
    $pricePerHour = "";

    $query = "SELECT idPark FROM space WHERE id=$idSpace";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_assoc($result)) {
                $idPark = $row["idPark"];

                $queryParkName = "SELECT pricePerHour FROM park WHERE id=$idPark";
                $resultParkName = mysqli_query($conn, $queryParkName);

                if ($resultParkName) {
                    if (mysqli_num_rows($resultParkName) > 0) {
                        if ($row = mysqli_fetch_assoc($resultParkName)) {
                            $pricePerHour = $row["pricePerHour"];
                        }
                    }
                }
            }
        }
    }

    return $pricePerHour;
}

mysqli_close($conn);
