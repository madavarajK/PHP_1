<?php
// src/reset_password.php

include '../config/dp.php'; // Database connection

// Load Composer’s autoloader
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Check if the email exists
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $userId = $user['id'];
        $token = bin2hex(random_bytes(50)); // Generate a secure token
        $resetLink = "http://localhost/user-auth/public/change_password.php?token=$token";

        // Save the token to the database with an expiry time
        $stmt = $conn->prepare('INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)');
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour
        $stmt->execute([$userId, $token, $expiresAt]);

        // Send the reset email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth   = true;
            $mail->Username   = 'sample@gmail.com'; // SMTP username
            $mail->Password   = 'Your_App_Password'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port       = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('no-reply@yourdomain.com', 'User_Login');
            $mail->addAddress($email); // Add recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "To reset your password, please click the following link: <a href=\"$resetLink\">$resetLink</a>";

            // Send email
            $mail->send();
            echo 'A password reset link has been sent to your email address.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Email address not found.';
    }
}
?>
