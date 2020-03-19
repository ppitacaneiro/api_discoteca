<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/album.php';
include_once '../models/song.php';
include_once '../utils/validator.php';

$database = new Database();
$db = $database->getConnection();

$album = new Album($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

if (isset($_GET['id'])) {
    $stmt = $album->get($_GET['id']);
    $num = $stmt->rowCount();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);
    
    if ($num > 0) {

        $albums_songs = array();
        $albums_songs = getAlbumSongs($id);

        $album_item = array (
            "id" => $id,
            "artist" => $name,
            "title" => $title,
            "songs" => $albums_songs,
            "published" => $published,
            "content" => html_entity_decode($content),
            "summary" => $summary,
            "url_image" => $url_image,
            "created_at" => $created_at
        );
        // var_dump($album_item);

        // set response code - 200 OK
        http_response_code(200);

        // var_dump($albums_array);
        echo json_encode($album_item);
    } else {
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user no products found
        echo json_encode(
            array("message" => "No se encontraron discos.")
        );
    }
} else {

    $searchParams = array (
        "artist" => $data->artist,
        "title" => $data->title,
        "fromDate" => $data->fromDate,
        "toDate" => $data->toDate
    );

    $stmt = $album->getAll($searchParams);
    $num = $stmt->rowCount();

    if ($num > 0) {
        $albums_array = array();
        $albums_array["albums"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $albums_songs = array();
            $albums_songs = getAlbumSongs($id);

            $album_item = array (
                "id" => $id,
                "artist" => $name,
                "title" => $title,
                "songs" => $albums_songs,
                "published" => $published,
                "content" => html_entity_decode($content),
                "summary" => $summary,
                "url_image" => $url_image,
                "created_at" => $created_at
            );

            array_push($albums_array["albums"],$album_item);
        }

        // set response code - 200 OK
        http_response_code(200);

        // var_dump($albums_array);
        echo json_encode($albums_array);
    } else {
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user no products found
        echo json_encode(
            array("message" => "No se encontraron discos.")
        );
    }
}

function getAlbumSongs($id_album) {

    global $db;

    $song = new Song($db);
    $songs = array();
    
    $stmt_songs = $song->getSongsByAlbumId($id_album);
    $num_stmt_songs = $stmt_songs->rowCount();
    
    if ($num_stmt_songs > 0) {
        while ($row = $stmt_songs->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            array_push($songs,$title);
        }
    }

    return $songs; 
}

?>