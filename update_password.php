<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$token = $data['token'];
$newPassword = $data['password'];

if (!$token || !$newPassword) {
    echo json_encode(['success' => false, 'message' => 'Token and new password are required']);
    exit;
}

$resetTokens = json_decode(file_get_contents('reset_tokens.json'), true);

if (!array_key_exists($token, $resetTokens)) {
    echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
    exit;
}

$username = $resetTokens[$token];
$users = json_decode(file_get_contents('users.json'), true);

foreach ($users as &$user) {
    if ($user['username'] === $username) {
        $user['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        file_put_contents('users.json', json_encode($users));
        
        // Remove the used token
        unset($resetTokens[$token]);
        file_put_contents('reset_tokens.json', json_encode($resetTokens));
        
        echo json_encode(['success' => true, 'message' => 'Password has been reset successfully']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'User not found']);
?>
