<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once '../config/Database.php';
    $database = new Database();
    $db = $database->getConnection();
    $donnees = json_decode(file_get_contents("php://input"));

    if (!empty($donnees->pinsId) && !empty($donnees->pinsName) && !empty($donnees->pinsCitation) && !empty($donnees->pinsUserId)) {
        $sql = 'UPDATE pins SET pinsName = :pinsName, pinsCitation = :pinsCitation, pinsUserId = :pinsUserId WHERE pinsId = :pinsId';
        $query = $db->prepare($sql);
        $query->bindParam(':pinsName', $donnees->pinsName);
        $query->bindParam(':pinsCitation', $donnees->pinsCitation);
        $query->bindParam(':pinsUserId', $donnees->pinsUserId);
        $query->bindParam(':pinsId', $donnees->pinsId);
        if ($query->execute()) {
            http_response_code(200);
            echo json_encode(array("success" => true, "message" => "La citation a bien été mis à jour !", "pins" => $donnees));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de mettre à jour la citation"));
        }
    } else {
        echo json_encode(array("message" => "Tous les champs ne sont pas remplis !"));
    }
} else if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
