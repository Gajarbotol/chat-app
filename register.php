<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$password = password_hash($data['password'], PASSWORD_BCRYPT);
$email = $data['email'];

$users = json_decode(file_get_contents('users.json'), true);

foreach ($users as $user) {
    if ($user['username'] === $username) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit;
    }
}

$users[] = ['username' => $username, 'password' => $password, 'email' => $email];
file_put_contents('users.json', json_encode($users));

session_start();
$_SESSION['username'] = $username;

echo json_encode(['success' => true]);
?>
