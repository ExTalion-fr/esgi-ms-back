<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include_once '../config/Database.php';
    include_once '../models/Pins.php';

    $database = new Database();
    $db = $database->getConnection();
    $classPins = new Pins($db);

    $stmt = $classPins->getAllPins();
    $arrayAllPins = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
            $date = new DateTime($pinsCreatedAt, new DateTimeZone('Europe/Paris'));
            $pinsCreatedAt = $now->getTimestamp() - $date->getTimestamp();
            $pins = [
                "id" => $pinsId,
                "name" => $pinsName,
                "citation" => $pinsCitation,
                "userId" => $pinsUserId,
                "createdAt" => $classPins->time_to_str($pinsCreatedAt)
            ];
            $arrayAllPins[] = $pins;
        }
        $message = "Liste des pins";
    } else {
        $message = "Il n'y a pas de pins";
    }
    http_response_code(200);
    echo json_encode(array("success" => true, "message" => $message, "pins" => $arrayAllPins));
} else if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}