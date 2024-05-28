<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/Exception.php';
require 'libs/PHPMailer/PHPMailer.php';
require 'libs/PHPMailer/SMTP.php';

function sendResetEmail($email, $resetLink) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com'; // SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = '757ee4001@smtp-brevo.com'; // SMTP username
        $mail->Password   = 'fYWHv6g4yrZ9qDp3'; // SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587; // SMTP port

        //Recipients
        $mail->setFrom('gajarbotol@gmail.com', 'GAJARBOTOL');
        $mail->addAddress($email); // Add a recipient

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Link';
        $mail->Body    = 'Click the following link to reset your password: <a href="' . $resetLink . '">' . $resetLink . '</a>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];

if (!$username) {
    echo json_encode(['success' => false, 'message' => 'Username is required']);
    exit;
}

$users = json_decode(file_get_contents('users.json'), true);
$resetTokens = json_decode(file_get_contents('reset_tokens.json'), true);

foreach ($users as $user) {
    if ($user['username'] === $username) {
        // Generate a token
        $token = bin2hex(random_bytes(16));
        $resetTokens[$token] = $username;
        file_put_contents('reset_tokens.json', json_encode($resetTokens));

        // Create reset link
        $resetLink = "https://joinvideocall-invite.000webhostapp.com/public/reset_password.html?token=" . $token;
        
        // Send reset link via email
        if (sendResetEmail($user['email'], $resetLink)) {
            echo json_encode(['success' => true, 'message' => 'Password reset link has been sent to your email']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send reset email']);
        }
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Username not found']);
?>
