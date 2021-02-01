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
// params: id, name, address, localization, totalSpaces, contact, email, pricePerHour
//

// on update park, check if has savedSpaces

$json = file_get_contents('php://input');

$obj = json_decode($json);

$id = $obj->id;
$name = $obj->name;
$address = $obj->address;
$localization = $obj->localization;
// $totalSpaces = $obj->totalSpaces;
$contact = $obj->contact;
$email = $obj->email;
$pricePerHour = $obj->pricePerHour;

$query = "UPDATE park
            SET name = '$name', address = '$address', localization = '$localization', 
            contact = '$contact', email = '$email', pricePerHour = $pricePerHour
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
