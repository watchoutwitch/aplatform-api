<?php
// Set Header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Require Config Database & Memanggilnya
require '../../config/database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// Get Data Form Request
$data = json_decode(file_get_contents("php://input"));

// Array Movies
$movies = [];

// Pengecekan Data dari Request
if (isset($data->title) && isset($data->description) && isset($data->year) && isset($data->duration) && isset($data->genre) && isset($data->rating) && isset($data->review) && isset($data->image_url)) {

    // Pengecekan Data yang Kosong
    if (!empty($data->title) && !empty($data->description) && !empty($data->year) && !empty($data->duration) && !empty($data->genre) && !empty($data->rating) && !empty($data->review) && !empty($data->image_url)) {

        // Query SQL
        $insert_query = "INSERT INTO `movies`(`title`,`description`,`year`,`duration`,`genre`,`rating`,`review`,`image_url`) VALUES(:title,:description,:year,:duration,:genre,:rating,:review,:image_url)";

        $insert_stmt = $conn->prepare($insert_query);

        // Binding Data
        $insert_stmt->bindValue(':title', $data->title, PDO::PARAM_STR);
        $insert_stmt->bindValue(':description', $data->description, PDO::PARAM_STR);
        $insert_stmt->bindValue(':year', $data->year, PDO::PARAM_STR);
        $insert_stmt->bindValue(':duration', $data->duration, PDO::PARAM_INT);
        $insert_stmt->bindValue(':genre', $data->genre, PDO::PARAM_STR);
        $insert_stmt->bindValue(':rating', $data->rating, PDO::PARAM_STR);
        $insert_stmt->bindValue(':review', $data->review, PDO::PARAM_STR);
        $insert_stmt->bindValue(':image_url', $data->image_url, PDO::PARAM_STR);

        if ($insert_stmt->execute()) {
            $movies = ['message' => 'Berhasil Menyimpan Movie!'];
        } else {
            $movies = ['message' => 'Gagal Menyimpan Movie!'];
        }
    } else {
        $movies = ['message' => 'Oops! Sepertinya ada form yang masih kosong. Tolong isi ya'];
    }
} else {
    $movies = ['message' => 'Oops! Data yang dimasukkan belum Lengkap. Silakan isi semua form yang kosong'];
}

// Menampilkan Response dalam Bentuk JSON
echo json_encode($movies);
