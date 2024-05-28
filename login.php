<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$password = $data['password'];

$users = json_decode(file_get_contents('users.json'), true);

foreach ($users as $user) {
    if ($user['username'] === $username && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['username'] = $username;
        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
?>
