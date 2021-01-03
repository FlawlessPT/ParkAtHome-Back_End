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
// params: name, username, password, contact, email
//

$json = file_get_contents('php://input');

$obj = json_decode($json);

$name = $obj->name;
$username = $obj->username;
$password = $obj->password;
$contact = $obj->contact;
$email = $obj->email;

if (!userExists($conn, $username)) {
    $query = "INSERT INTO user (name, username, password, contact, email) VALUES ('$name', '$username', '$password', '$contact', '$email');";
    $result = mysqli_query($conn, $query);

    $userId = getUserId($conn);

    if ($result) {
        if (insertVehicule($conn, $userId) && insertPaymentMethod($conn, $userId)) {
            $finalObj = (object) ['message' => "success", 'user_id' => $userId];
        } else {
            $finalObj = (object) ['message' => "vehicule_or_paymentMethod_not_inserted", 'user_id' => -1];
        }
    } else {
        $finalObj = (object) ['message' => "error", 'user_id' => -1];
    }
} else {
    $finalObj = (object) ['message' => "user_already_exists", 'user_id' => -1];
}

$response = json_encode($finalObj, JSON_PRETTY_PRINT);
echo $response;

function userExists($conn, $username)
{
    $exists = false;

    $query = "SELECT id FROM user WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $exists = true;
        }
    }

    return $exists;
}

function getUserId($conn)
{
    $userId = mysqli_insert_id($conn);

    $query = "SELECT id FROM user WHERE id=$userId";
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

function insertVehicule($conn, $idUser)
{
    $inserted = false;

    $sql = "INSERT INTO vehicule (idUser) VALUES ($idUser)";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_affected_rows($conn) > 0) {
            $inserted = true;
        }
    }

    return $inserted;
}

function insertPaymentMethod($conn, $idUser)
{
    $inserted = false;

    $sql = "INSERT INTO paymentMethod (idUser) VALUES ($idUser)";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_affected_rows($conn) > 0) {
            $inserted = true;
        }
    }

    return $inserted;
}

// $response = array();

// if ($result) {
//     if (mysqli_num_rows($result) > 0) {
//         $i = 0;
//         while ($row = mysqli_fetch_assoc($result)) {
//             $response[$i]['id'] = $row["id"];
//             $response[$i]['name'] = $row["name"];
//             $response[$i]['plate'] = $row["plate"];
//             $response[$i]['state'] = $row["state"];
//             $response[$i]['idUser'] = $row["idUser"];
//             $i++;
//         }
//         echo json_encode($response, JSON_PRETTY_PRINT);
//     }
// }

mysqli_close($conn);
