<?php
header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$message = $_SESSION['username'] . ": " . $data['message'];

$messages = json_decode(file_get_contents('messages.json'), true);
$messages[] = $message;
file_put_contents('messages.json', json_encode($messages));

echo json_encode(['message' => $message]);
?>
