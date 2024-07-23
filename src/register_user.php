<?php
// register_user.php
include __DIR__ . '/../config/dp.php';

function validate_password($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

function validate_phone($phone) {
    return preg_match('/^\d{10}$/', $phone);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $phone = trim($_POST['phone']);
    
    // Validate inputs
    if (empty($username) || empty($password) || empty($confirm_password) || empty($phone)) {
        header("Location: ../public/register.php?error=Please fill in all fields.");
        exit;
    }

    if (!validate_password($password)) {
        header("Location: ../public/register.php?error=Password must have at least one lowercase letter, one uppercase letter, one number, and one special character.");
        exit;
    }

    if ($password !== $confirm_password) {
        header("Location: ../public/register.php?error=Passwords do not match.");
        exit;
    }

    if (!validate_phone($phone)) {
        header("Location: ../public/register.php?error=Phone number must be 10 digits.");
        exit;
    }

    // Check if username already exists
    $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        header("Location: ../public/register.php?error=Username already taken.");
        exit;
    }

    // Insert new user
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO users (username, password, phone) VALUES (:username, :password, :phone)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $passwordHash);
    $stmt->bindParam(':phone', $phone);

    if ($stmt->execute()) {
        header("Location: ../public/login.php?success=Registration successful. Please log in.");
    } else {
        header("Location: ../public/register.php?error=Registration failed. Please try again.");
    }
}
?>
