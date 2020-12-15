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
$user_id = $_GET['id'];

// Array Users
$users = [];

// Pengecekan ID di parameter
if (isset($user_id)) {

    // Get Movie ID 
    // Query SQL
    $check_user = "SELECT * FROM `users` WHERE id=:user_id LIMIT 1";
    $check_user_stmt = $conn->prepare($check_user);
    $check_user_stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $check_user_stmt->execute();

    // Pengecekan Movie Ada Tidaknya Dalam Database
    if ($check_user_stmt->rowCount() > 0) {

        // Query SQL
        $delete_user = "DELETE FROM `users` WHERE id=:user_id";
        $delete_user_stmt = $conn->prepare($delete_user);
        $delete_user_stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        if ($delete_user_stmt->execute()) {
            $users = ['message' => 'Berhasil Menghapus User!'];
        } else {
            $users = ['message' => 'Gagal Menghapus User!'];
        }
    } else {
        $users = ['message' => 'Oops! Invalid ID'];
    }

    // Menampilkan Response dalam Bentuk JSON
    echo  json_encode($users);
}
