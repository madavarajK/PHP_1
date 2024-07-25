<?php
// admin_login.php
include __DIR__ . '/../config/dp.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if admin
    $sql = "SELECT * FROM users WHERE username = :username AND is_admin = TRUE";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: ../public/admin_dashboard.php");
        exit;
    } else {
        header("Location: ../public/admin_login.php?error=Invalid credentials.");
        exit;
    }
}
?>
