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
    $donnees = json_decode(file_get_contents("php://input"));

    if(!empty($donnees->userId)){
        $sql = 'DELETE FROM user WHERE userId = :userId';
        $query = $db->prepare($sql);
        $query->bindParam(':userId', $donnees->userId);
        if ($query->execute()) {
            http_response_code(200);
            echo json_encode(array("success" => "L'utilisateur a bien été supprimé !"));
        } else {
            http_response_code(503);
            echo json_encode(array("error" => "Impossible de supprimer l'utilisateur"));
        }
    } else {
        echo json_encode(array("message" => "Il n'y a pas de paramètre"));
    }
}elseif($_SERVER['REQUEST_METHOD'] !== "OPTIONS"){
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
