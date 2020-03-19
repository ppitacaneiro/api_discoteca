<?php
header("Access-Control-Allow-Origin: 'http://localhost:4200/'");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/album.php';
include_once '../models/artist.php';
include_once '../models/song.php';

$database = new Database();
$db = $database->getConnection();

$album = new Album($db);
$artista = new Artist($db);
$song = new Song($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->artist) && !empty($data->title) && !empty($data->published) && !empty($data->content) && !empty($data->summary) && !empty($data->url_image)) {

    $idArtist = $artista->getIdArtistaByName($data->artist);
    if (empty($idArtist)) {
        $artista->setName($data->artist);
        $idArtist = $artista->set();
    } 

    $album->id_artist = $idArtist;
    $album->title = $data->title;
    $album->published = $data->published;
    $album->content = $data->content;
    $album->summary = $data->summary;
    $album->url_image = $data->url_image;
    $album->songs = $data->songs;

    if ($album->set()) {

        $song->id_album = $album->getIdAlbum();

        foreach ($album->songs as $cancion) {
            $song->title = $cancion;
            $song->setSong();   
        }

        http_response_code(200);
        echo json_encode(array("message" => "Album Creado."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Imposible crear album."));
    }
    
} else {
    
    $message = "Imposible crear album";

    if (empty($data->artist)) {
        $message .= "Falta el artista";
    } 

    if (empty($data->title)) {
        $message .= "Falta el Titulo";
    } 
    
    if (empty($data->published)) {
        $message .= "Falta la fecha de punlicacion";
    } 
    
    if (empty($data->content)) {
        $message .= "Falta el contenido";
    } 
    
    if (empty($data->summary)) {
        $message .= "Falta el resumen";
    } 
    
    if (empty($data->url_image)) {
        $message .= "Falta la imagen";
    } 

    http_response_code(400);
    echo json_encode(array("message" => $message));
}

?>