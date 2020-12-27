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

$query = "INSERT INTO user (name, username, password, contact, email) VALUES ('$name', '$username', '$password', '$contact', '$email');";
$result = mysqli_query($conn, $query);

if (!userExists($conn, $username)) {
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

function userExists($conn, $username) {
    $exists = false;

    $query = "SELECT id FROM user WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_assoc($result)) {
                $userId = true;
            }
        }
    }

    return $exists;
}

function getUserIdByUsername($conn, $username)
{
    $userId = 0;

    $query = "SELECT id FROM user WHERE username='$username'";
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
