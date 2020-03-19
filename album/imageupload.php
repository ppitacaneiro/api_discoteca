<?php 
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");

include_once '../config/config.php';
include_once '../config/messages.php';

$response = array();
$upload_dir = '../' . UPLOADS_DIR;

if ($_FILES['image']) {
    
    $image_name = $_FILES["image"]["name"];
    $image_tmp_name = $_FILES["image"]["tmp_name"];
    $error = $_FILES["image"]["error"];

    if ($error > 0) {

        $response = array (
            "status" => "error",
            "error" => true,
            "message" => ERROR_UPLOAD_SERVER
        );

    } else {

        $random_name = rand(1000,1000000)."-".$image_name;
        $upload_name = strtolower($random_name);
        $upload_name = preg_replace('/\s+/', '-', $upload_name);
    
        if (move_uploaded_file($image_tmp_name , $upload_dir.$upload_name)) {
            
            $response = array (
                "status" => "success",
                "error" => false,
                "message" => UPLOAD_SERVER_OK,
                "url" => SERVER_URL . UPLOADS_DIR . $upload_name
            );

        } else {

            $response = array (
                "status" => "error",
                "error" => true,
                "message" => ERROR_UPLOAD_SERVER
            );
        }
    }

} else {

    $response = array (
        "status" => "error",
        "error" => true,
        "message" => NO_IMAGE_WAS_SENT
    );

}

echo json_encode($response);
?>