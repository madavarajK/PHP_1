<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="../src/login_user.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <a href="register.php">Don't have an account? Register here</a>
        <br/>
        <a href="admin_login.php">Admin Login Click Here</a>
        <br/>
        <a href="reset_password.php">Forgot Password? Click Here</a>
    </div>
</body>
</html>
