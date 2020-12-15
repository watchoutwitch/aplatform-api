<?php
// Set Header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Require Config Database
require '../../config/database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// Get Data Form Request
$data = json_decode(file_get_contents("php://input"));

// Array Games
$games = [];

// Pengecekan Data dari Request
if (isset($data->name) && isset($data->genre) && isset($data->singlePlayer) && isset($data->multiplayer) && isset($data->platform) && isset($data->release) && isset($data->image_url)) {

    // Pengecekan Data yang Kosong (Except Column single Player & multiplayer)
    if (!empty($data->name) && !empty($data->genre) && !empty($data->platform) && !empty($data->release) && !empty($data->image_url)) {

        $insert_query = "INSERT INTO `games`(`name`,`genre`,`singlePlayer`,`multiplayer`,`platform`,`release`,`image_url`) VALUES(:name,:genre,:singlePlayer,:multiplayer,:platform,:release,:image_url)";

        $insert_stmt = $conn->prepare($insert_query);

        // Binding Data
        $insert_stmt->bindValue(':name', $data->name, PDO::PARAM_STR);
        $insert_stmt->bindValue(':genre', $data->genre, PDO::PARAM_STR);
        $insert_stmt->bindValue(':singlePlayer', $data->singlePlayer, PDO::PARAM_STR);
        $insert_stmt->bindValue(':multiplayer', $data->multiplayer, PDO::PARAM_STR);
        $insert_stmt->bindValue(':platform', $data->platform, PDO::PARAM_STR);
        $insert_stmt->bindValue(':release', $data->release, PDO::PARAM_STR);
        $insert_stmt->bindValue(':image_url', $data->image_url, PDO::PARAM_STR);

        if ($insert_stmt->execute()) {
            $games = ['message' => 'Berhasil Menyimpan Game!'];
        } else {
            $games = ['message' => 'Gagal Menyimpan Game!'];
        }
    } else {
        $games = ['message' => 'Oops! Sepertinya ada form yang masih kosong. Tolong isi ya'];
    }
} else {
    $games = ['message' => 'Oops! Data yang dimasukkan belum Lengkap. Silakan isi semua form yang kosong'];
}

// Menampilkan Response dalam Bentuk JSON
echo json_encode($games);
