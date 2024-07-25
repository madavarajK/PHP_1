<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <h3>All Users</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php
                session_start();
                if (!isset($_SESSION['admin_logged_in'])) {
                    header("Location: admin_login.php");
                    exit;
                }

                include __DIR__ . '/../config/dp.php';

                $sql = "SELECT id, username, phone FROM users WHERE is_admin = FALSE";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['phone']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <form action="../src/logout.php" method="post">
            <input type="submit" value="Logout" class="logout-button">
        </form>
    </div>
</body>
</html>
