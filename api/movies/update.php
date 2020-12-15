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
$movie_id = $_GET['id'];

// Array Movies
$movies = [];

// Pengecekan ID di parameter
if (isset($movie_id)) {

    // Get Movie ID
    $get_movie = "SELECT * FROM `movies` WHERE id=:movie_id";
    $get_stmt = $conn->prepare($get_movie);
    $get_stmt->bindValue(':movie_id', $movie_id, PDO::PARAM_INT);
    $get_stmt->execute();

    // Pengecekan Movie Ada Tidaknya Dalam Database
    if ($get_stmt->rowCount() > 0) {

        // Fetch data dari Database
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);

        // Pengecekan Untuk Update Data
        $movie_title = isset($data->title) ? $data->title : $row['title'];
        $movie_description = isset($data->description) ? $data->description : $row['description'];
        $movie_year = isset($data->year) ? $data->year : $row['year'];
        $movie_duration = isset($data->duration) ? $data->duration : $row['duration'];
        $movie_genre = isset($data->genre) ? $data->genre : $row['genre'];
        $movie_rating = isset($data->rating) ? $data->rating : $row['rating'];
        $movie_review = isset($data->review) ? $data->review : $row['review'];
        $movie_image_url = isset($data->image_url) ? $data->image_url : $row['image_url'];

        $update_query = "UPDATE `movies` SET title = :title, description = :description, year = :year, duration = :duration, genre = :genre, rating = :rating, review = :review, image_url = :image_url 
        WHERE id = :id";

        $update_stmt = $conn->prepare($update_query);

        // Binding Data
        $update_stmt->bindValue(':title', $movie_title, PDO::PARAM_STR);
        $update_stmt->bindValue(':description', $movie_description, PDO::PARAM_STR);
        $update_stmt->bindValue(':year', $movie_year, PDO::PARAM_STR);
        $update_stmt->bindValue(':duration', $movie_duration, PDO::PARAM_STR);
        $update_stmt->bindValue(':genre', $movie_genre, PDO::PARAM_STR);
        $update_stmt->bindValue(':rating', $movie_rating, PDO::PARAM_STR);
        $update_stmt->bindValue(':review', $movie_review, PDO::PARAM_STR);
        $update_stmt->bindValue(':image_url', $movie_image_url, PDO::PARAM_STR);
        $update_stmt->bindValue(':id', $movie_id, PDO::PARAM_INT);


        if ($update_stmt->execute()) {
            $movies = ['message' => 'Berhasil Mengupdate Movie!'];
        } else {
            $movies = ['message' => 'Gagal Mengupdate Movie!'];
        }
    } else {
        $movies = ['message' => 'Oops! Invalid ID'];
    }

    // Menampilkan Response dalam Bentuk JSON
    echo json_encode($movies);
}
