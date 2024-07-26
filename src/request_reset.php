<?php
// request_reset.php
include __DIR__ . '/../config/dp.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);

    $sql = "SELECT email FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(50));
        $expires = date("U") + 1800;

        $sql = "INSERT INTO password_resets (username, token, expires) VALUES (:username, :token, :expires)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires', $expires);
        $stmt->execute();

        $reset_link = "http://localhost/user-auth/public/reset_password.php?token=" . $token;
        $to = $user['email'];
        $subject = "Password Reset Request";
        $message = "Click on the link below to reset your password:\n\n" . $reset_link;
        $headers = "From: noreply@example.com";

        mail($to, $subject, $message, $headers);

        header("Location: ../public/request_reset.php?success=Password reset link has been sent to your email.");
        exit;
    } else {
        header("Location: ../public/request_reset.php?error=No user found with that username.");
        exit;
    }
}
?>
