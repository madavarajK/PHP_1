<?php
include '../config/dp.php';

$username = $_POST['username'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if ($new_password !== $confirm_password) {
    header("Location: ../public/reset_password.php?error=New passwords do not match.");
    exit();
}

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
    header("Location: ../public/reset_password.php?error=Password must have at least 1 lowercase letter, 1 uppercase letter, 1 number, and 1 special character.");
    exit();
}

$sql = "UPDATE users SET password = :new_password WHERE username = :username";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':new_password', $new_password);
$stmt->bindParam(':username', $username);
$stmt->execute();

header("Location: ../public/Pass_change_Suc.php?success=Password has been reset successfully.");
?>
