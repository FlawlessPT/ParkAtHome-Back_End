<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "parkathome_web";

// Cria a ligação à BD
$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
// Verifica se a ligação falhou (ou teve sucesso)
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//
// params: name, plate
//

$name = $_GET["name"];
$plate = $_GET["plate"];
$state = 1;
$idUser = 1;

$query = "INSERT INTO vehicules (name, plate, state, idUser) VALUES ('$name', '$plate', $state, $idUser);";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "Sucesso";
} else {
    echo "Erro";
}

// $query = "SELECT * FROM vehicules";
// $result = mysqli_query($conn, $query);



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
