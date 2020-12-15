<?php
// Set Header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
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

        // Query SQL
        $show_query = "SELECT * FROM `users` WHERE username = :username && password = :password LIMIT 1";
        $show_stmt = $conn->prepare($show_query);

        // Binding Data
        $show_stmt->bindValue(':username', $data->username, PDO::PARAM_STR);
        $show_stmt->bindValue(':password', $data->password, PDO::PARAM_STR);

        $show_stmt->execute();

        // Pengecekan Movie Ada Tidaknya Dalam Database
        if ($show_stmt->rowCount() > 0) {
            while ($row = $show_stmt->fetch(PDO::FETCH_ASSOC)) {
                $users_data = [
                    'id' => $row['id'],
                    'username' => $row['username'],
                    'password' => $row['password'],
                ];
                // Push movies_data ke movies_array
                array_push($users, $users_data);
            }
        } else {
            $users = ['message' => 'Invalid username or password!'];
        }
    } else {
        $users = ['message' => 'Oops! Sepertinya ada form yang masih kosong. Tolong isi ya'];
    }
} else {
    $users =  ['message' => 'Oops! Sepertinya ada form yang masih kosong. Tolong isi ya'];
}

// Menampilkan Response dalam Bentuk JSON
echo json_encode($users);
