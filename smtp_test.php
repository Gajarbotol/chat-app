<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/Exception.php';
require 'libs/PHPMailer/PHPMailer.php';
require 'libs/PHPMailer/SMTP.php';

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
    $mail->setFrom('gajarbotol@gmail.com', 'GAJAR BOTOL');
    $mail->addAddress('gajarbotol@gmail.com'); // Add a recipient

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'SMTP Test';
    $mail->Body    = 'This is a test email to verify SMTP settings.';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
