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

// Array Users
$users = [];

// Pengecekan Data dari Request
if (isset($data->username) && isset($data->password)) {

    // Pengecekan Data yang Kosong
    if (!empty($data->username) && !empty($data->password)) {

        $insert_query = "INSERT INTO `users`(username,password) VALUES(:username,:password)";

        $insert_stmt = $conn->prepare($insert_query);

        // Binding Data
        $insert_stmt->bindValue(':username', $data->username, PDO::PARAM_STR);
        $insert_stmt->bindValue(':password', $data->password, PDO::PARAM_STR);

        if ($insert_stmt->execute()) {
            $users = ['message' => 'Berhasil Menyimpan User!'];
        } else {
            $users = ['message' => 'Gagal Menyimpan User!'];
        }
    } else {
        $users = ['message' => 'Oops! Sepertinya ada form yang masih kosong. Tolong isi ya'];
    }
} else {
    $users = ['message' => 'Oops! Data yang dimasukkan belum Lengkap. Silakan isi semua form yang kosong'];
}

// Menampilkan Response dalam Bentuk JSON
echo json_encode($users);
