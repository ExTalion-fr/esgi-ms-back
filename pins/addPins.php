<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once '../config/Database.php';
    include_once '../models/Pins.php';

    $database = new Database();
    $db = $database->getConnection();
    $pins = new Pins($db);
    $donnees = json_decode(file_get_contents("php://input"));

    if(!empty($donnees->pinsName) && !empty($donnees->pinsCitation) && $donnees->pinsUserId != 0) {
        $pinsResult = $pins->addPins($donnees->pinsName, $donnees->pinsCitation, $donnees->pinsUserId);
        
        http_response_code(200);
        echo json_encode(array("success" => "Le programme a bien été ajouté !", "pins" => $pinsResult));
    } else {
        echo json_encode(array("message" => "Tous les champs ne sont pas remplis !"));
    }
}elseif($_SERVER['REQUEST_METHOD'] !== "OPTIONS"){
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
