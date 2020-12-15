<?php
// Set Header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// Require Config Database
require '../../config/database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// Cek Terdapat Parameter ID atau Tidak
if (isset($_GET['id'])) {
    // Jika Terdapat Parameter
    $games_id = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
        'options' => [
            'default' => 'all_games',
            'min_range' => 1
        ]
    ]);
} else {
    $games_id = 'all_games';
}

// Query SQL
// Jika Terdapat ID Games maka Tampilkan Games yang dipilih jika tidak Tampilkan semua games
$sql = is_numeric($games_id) ? "SELECT * FROM `games` WHERE `id`='$games_id'" : "SELECT * FROM `games`";

$stmt = $conn->prepare($sql);

$stmt->execute();

// Pengecekan Games Ada Tidaknya Dalam Database
if ($stmt->rowCount() > 0) {

    // Array Games
    $games_array = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $games_data = [
            'id' => $row['id'],
            'name' => $row['name'],
            'genre' => $row['genre'],
            'singlePlayer' => $row['singlePlayer'],
            'multiplayer' => $row['multiplayer'],
            'platform' => $row['platform'],
            'release' => $row['release'],
            'image_url' => $row['image_url'],
        ];
        // Push games_data ke games_array
        array_push($games_array, $games_data);
    }
    // Menampilkan Games dalam Bentuk JSON
    echo json_encode(['games' => $games_array]);
} else {
    // Jika Tidak Terdapat Games dalam Database 
    echo json_encode(['message' => 'Oops! Games yang dicari tidak ditemukan']);
}
