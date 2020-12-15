<?php
// Set Header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Require Config Database
require '../../config/database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// Get Data Form Request
$data = json_decode(file_get_contents("php://input"));

// Get ID From Parameter
$game_id = $_GET['id'];

// Array Games
$games = [];

// Pengecekan ID di parameter
if (isset($game_id)) {

    // Get game ID
    $get_game = "SELECT * FROM `games` WHERE id=:game_id";
    $get_stmt = $conn->prepare($get_game);
    $get_stmt->bindValue(':game_id', $game_id, PDO::PARAM_INT);
    $get_stmt->execute();

    // Pengecekan game Ada Tidaknya Dalam Database
    if ($get_stmt->rowCount() > 0) {

        // Fetch data dari Database
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);

        // Pengecekan Untuk Update Data
        $game_name = isset($data->name) ? $data->name : $row['name'];
        $game_genre = isset($data->genre) ? $data->genre : $row['genre'];
        $game_singlePlayer = isset($data->game_singlePlayer) ? $data->game_singlePlayer : $row['singlePlayer'];
        $game_multiplayer = isset($data->multiplayer) ? $data->multiplayer : $row['multiplayer'];
        $game_platform = isset($data->platform) ? $data->platform : $row['platform'];
        $game_release = isset($data->release) ? $data->release : $row['release'];
        $game_image_url = isset($data->image_url) ? $data->image_url : $row['image_url'];

        $update_query = "UPDATE `games` SET `name` = :name, `genre` = :genre, `singlePlayer` = :singlePlayer, `multiplayer` = :multiplayer, `platform` = :platform, `release` = :release, `image_url` = :image_url 
        WHERE id = :id";

        $update_stmt = $conn->prepare($update_query);

        // Binding Data & Menghilangkan Spesial Chars
        $update_stmt->bindValue(':name', $game_name, PDO::PARAM_STR);
        $update_stmt->bindValue(':genre', $game_genre, PDO::PARAM_STR);
        $update_stmt->bindValue(':singlePlayer', $game_singlePlayer, PDO::PARAM_STR);
        $update_stmt->bindValue(':multiplayer', $game_multiplayer, PDO::PARAM_STR);
        $update_stmt->bindValue(':platform', $game_platform, PDO::PARAM_STR);
        $update_stmt->bindValue(':release', $game_release, PDO::PARAM_STR);
        $update_stmt->bindValue(':image_url', $game_image_url, PDO::PARAM_STR);
        $update_stmt->bindValue(':id', $game_id, PDO::PARAM_INT);


        if ($update_stmt->execute()) {
            $games = ['message' => 'Berhasil Mengupdate Game!'];
        } else {
            $games = ['message' => 'Gagal Mengupdate Game!'];
        }
    } else {
        $games = ['message' => 'Oops! Invalid ID'];
    }

    // Menampilkan Response dalam Bentuk JSON
    echo json_encode($games);
}
