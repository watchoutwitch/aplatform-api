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
$movie_id = $_GET['id'];

// Array Movies
$movies = [];

// Pengecekan ID di parameter
if (isset($movie_id)) {

    // Get Movie ID 
    // Query SQL
    $check_movie = "SELECT * FROM `movies` WHERE id=:movie_id";
    $check_movie_stmt = $conn->prepare($check_movie);
    $check_movie_stmt->bindValue(':movie_id', $movie_id, PDO::PARAM_INT);
    $check_movie_stmt->execute();

    // Pengecekan Movie Ada Tidaknya Dalam Database
    if ($check_movie_stmt->rowCount() > 0) {

        // Query SQL
        $delete_movie = "DELETE FROM `movies` WHERE id=:movie_id";
        $delete_movie_stmt = $conn->prepare($delete_movie);
        $delete_movie_stmt->bindValue(':movie_id', $movie_id, PDO::PARAM_INT);

        if ($delete_movie_stmt->execute()) {
            $movies = ['message' => 'Berhasil Menghapus Movie!'];
        } else {
            $movies = ['message' => 'Gagal Menghapus Movie!'];
        }
    } else {
        $movies = ['message' => 'Oops! Invalid ID'];
    }

    // Menampilkan Response dalam Bentuk JSON
    echo json_encode($movies);
}
