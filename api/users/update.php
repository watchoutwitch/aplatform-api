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
$user_id = $_GET['id'];

// Array Movies
$users = [];

// Pengecekan ID di parameter
if (isset($user_id)) {

    // Get User ID
    $get_user = "SELECT * FROM `users` WHERE id=:user_id";
    $get_stmt = $conn->prepare($get_user);
    $get_stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $get_stmt->execute();

    // Pengecekan User Ada Tidaknya Dalam Database
    if ($get_stmt->rowCount() > 0) {

        // Fetch data dari Database
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);

        $update_query = "UPDATE `users` SET password = :password WHERE id = :id";

        $update_stmt = $conn->prepare($update_query);

        // Binding Data
        $update_stmt->bindValue(':password', $data->password, PDO::PARAM_STR);
        $update_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);

        if ($update_stmt->execute()) {
            $users = ['message' => 'Berhasil Mengupdate User!'];
        } else {
            $users = ['message' => 'Gagal Mengupdate User!'];
        }
    } else {
        $users = ['message' => 'Oops! Invalid ID'];
    }

    // Menampilkan Response dalam Bentuk JSON
    echo json_encode($users);
}
