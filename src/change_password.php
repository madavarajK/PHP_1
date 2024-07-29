<?php
// src/change_password.php

include '../config/dp.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['password'];
    
    // Validate the token
    $stmt = $conn->prepare('SELECT user_id, expires_at FROM password_resets WHERE token = ?');
    $stmt->execute([$token]);
    $resetRequest = $stmt->fetch();
    
    if ($resetRequest) {
        $userId = $resetRequest['user_id'];
        $expiresAt = new DateTime($resetRequest['expires_at']);
        $now = new DateTime();

        if ($now <= $expiresAt) {
            // Token is valid, proceed with password update
            
            $stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
            $stmt->execute([$newPassword, $userId]);

            // Delete the reset request
            $stmt = $conn->prepare('DELETE FROM password_resets WHERE token = ?');
            $stmt->execute([$token]);

            header("Location: ../public/Pass_change_Suc.php");
        } else {
            header("Location: ../public/invalid_tok.php");
        }
    } else {
        header("Location: ../public/invalid_tok.php");
    }
}
?>
