<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once '../config/Database.php';
    include_once '../models/User.php';

    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);
    $donnees = json_decode(file_get_contents("php://input"));

    if(!empty($donnees->userName) && !empty($donnees->userEmail)) {
        $userResult = $user->addUser($donnees->userName, $donnees->userEmail);
        http_response_code(200);
        echo json_encode(array("success" => "L'utilisateur a bien été ajouté !", "user" => $userResult));
    } else {
        echo json_encode(array("message" => "Tous les champs ne sont pas remplis !"));
    }
}elseif($_SERVER['REQUEST_METHOD'] !== "OPTIONS"){
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
