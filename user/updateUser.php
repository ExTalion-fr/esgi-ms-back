<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include_once '../config/Database.php';
    $database = new Database();
    $db = $database->getConnection();
    $donnees = json_decode(file_get_contents("php://input"));

    if(!empty($donnees->userId) && !empty($donnees->userName) && !empty($donnees->userEmail)){
        $sql = 'UPDATE user SET userName = :userName, userEmail = :userEmail WHERE userId = :userId';
        $query = $db->prepare($sql);
        $query->bindParam(':userName', $donnees->userName);
        $query->bindParam(':userEmail', $donnees->userEmail);
        $query->bindParam(':userId', $donnees->userId);
        if ($query->execute()) {
            http_response_code(200);
            echo json_encode(array("success" => true, "message" => "L'utilisateur a bien été mis à jour !", "user" => $donnees));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de mettre à jour l'utilisateur"));
        }
    } else {
        echo json_encode(array("message" => "Tous les champs ne sont pas remplis !"));
    }
}elseif($_SERVER['REQUEST_METHOD'] !== "OPTIONS"){
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}