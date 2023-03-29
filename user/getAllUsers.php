<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    include_once '../config/Database.php';
    include_once '../models/User.php';

    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    $users = $user->getAllUsers();
    $arrayAllUsers = [];
    if ($users->rowCount() > 0) {
        while ($row = $users->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user = [
                "id" => $userId,
                "name" => $userName,
                "email" => $userEmail
            ];
            $arrayAllUsers[] = $user;
        }
        $message = "Liste des utilisateurs";
    } else {
        $message = "Il n'y a pas d'utilisateurs";
    }
    http_response_code(200);
    echo json_encode(array("success" => true, "message" => $message, "users" => $arrayAllUsers));
} else if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}
