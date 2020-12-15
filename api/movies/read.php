<?php
// Set Header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// Require Config Database
require '../../config/database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// Cek Terdapat Parameter ID atau Tidak
if (isset($_GET['id'])) {
    // Jika Terdapat Parameter
    $movie_id = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
        'options' => [
            'default' => 'all_movies',
            'min_range' => 1
        ]
    ]);
} else {
    $movie_id = 'all_movies';
}

// Query SQL
// Jika Terdapat ID Movie maka Tampilkan Movies yang dipilih jika tidak Tampilkan semua movies
$sql = is_numeric($movie_id) ? "SELECT * FROM `movies` WHERE `id`='$movie_id'" : "SELECT * FROM `movies`";

$stmt = $conn->prepare($sql);

$stmt->execute();

// Pengecekan Movie Ada Tidaknya Dalam Database
if ($stmt->rowCount() > 0) {

    // Array Movies
    $movies_array = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $movies_data = [
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'year' => $row['year'],
            'duration' => $row['duration'],
            'genre' => $row['genre'],
            'rating' => $row['rating'],
            'review' => $row['review'],
            'image_url' => $row['image_url'],
        ];
        // Push movies_data ke movies_array
        array_push($movies_array, $movies_data);
    }
    // Menampilkan Movies dalam Bentuk JSON
    echo json_encode(['movies' => $movies_array]);
} else {
    // Jika Tidak Terdapat Movie dalam Database 
    echo json_encode(['message' => 'Oops! Movie yang dicari tidak ditemukan']);
}
