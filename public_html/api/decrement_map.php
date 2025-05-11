<?php
header('Content-Type: application/json');
require_once 'config.php';

// Get raw POST body and decode
$data = json_decode(file_get_contents('php://input'), true);
// if (!isset($data['auth_token']) || $data['auth_token'] !== AUTH_TOKEN) {
//     http_response_code(403);
//     echo json_encode(["error" => "Invalid auth token"]);
//     exit;
// }
$ip = $_SERVER["REMOTE_ADDR"];
if($ip != AUTHORIZED_IP) {
    http_response_code(403);
    echo json_encode(["error" => "API restricted", "ip" => $ip]);
    exit;
}

// Read database
$db = json_decode(file_get_contents(DATABASE), true);
$maps = $db['maps'];
$index = $db['currentIndex'];

// Decrement
$index--;
if($index < 0)
    $index = 0;
$db['currentIndex'] = $index;

// Save
file_put_contents(DATABASE, json_encode($db, JSON_PRETTY_PRINT));
echo json_encode(["success" => true, "currentIndex" => $index]);