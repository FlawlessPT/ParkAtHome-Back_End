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
// params: id, name, password, contact, email
//

$json = file_get_contents('php://input');

$obj = json_decode($json);

$id = $obj->id;
$name = $obj->name;
$password = $obj->password;
$contact = $obj->contact;
$email = $obj->email;

$query = "UPDATE user
            SET name = '$name', email = '$email', contact = '$contact', password = '$password'
            WHERE id=$id";
$result = mysqli_query($conn, $query);

if ($result) {
    if (mysqli_affected_rows($conn) > 0) {
        $finalObj = (object) ['message' => "success"];
    } else {
        $finalObj = (object) ['message' => "update_failed"];
    }
} else {
    $finalObj = (object) ['message' => "error"];
}

$response = json_encode($finalObj, JSON_PRETTY_PRINT);
echo $response;

mysqli_close($conn);
