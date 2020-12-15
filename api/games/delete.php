<?php
// Set Header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
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

    // Get Games ID 
    // Query SQL
    $check_game = "SELECT * FROM `games` WHERE id=:game_id";
    $check_game_stmt = $conn->prepare($check_game);
    $check_game_stmt->bindValue(':game_id', $game_id, PDO::PARAM_INT);
    $check_game_stmt->execute();

    // Pengecekan Games Ada Tidaknya Dalam Database
    if ($check_game_stmt->rowCount() > 0) {

        // Delete Games
        $delete_game = "DELETE FROM `games` WHERE id=:game_id";
        $delete_game_stmt = $conn->prepare($delete_game);
        $delete_game_stmt->bindValue(':game_id', $game_id, PDO::PARAM_INT);

        if ($delete_game_stmt->execute()) {
            $games = ['message' => 'Berhasil Menghapus Game!'];
        } else {
            $games = ['message' => 'Gagal Menghapus Game!'];
        }
    } else {
        $games = ['message' => 'Oops! Invalid ID'];
    }

    // Menampilkan Response dalam Bentuk JSON
    echo  json_encode($games);
}
