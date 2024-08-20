<?php
include 'db.php'; // Include database connection and functions

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Determine which button was clicked
    if (isset($_POST['admin_login'])) {
        // Admin login
        $sql = "SELECT * FROM users WHERE username = ? AND role = 'admin'";
        $redirect_page = "admin.php"; // Redirect to admin dashboard
    } elseif (isset($_POST['customer_login'])) {
        // Customer login
        $sql = "SELECT * FROM users WHERE username = ? AND role = 'customer'";
        $redirect_page = "index.php"; // Redirect to customer dashboard
    } else {
        // Default behavior if neither button was clicked
        $error_message = "Invalid request.";
    }

    // Perform login authentication
    if (isset($sql)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Check if the password needs to be hashed (for existing plain text passwords)
            if (password_verify($password, $user['password']) || $user['password'] === $password) {
                // If the password is plain text, hash it and update the database
                if ($user['password'] === $password) {
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                    $update_stmt->bind_param("ss", $hashed_password, $username);
                    $update_stmt->execute();
                    $update_stmt->close();
                }

                // Set session variables
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect to appropriate page
                header("Location: $redirect_page");
                exit();
            } else {
                $error_message = "Invalid username or password. Please try again.";
            }
        } else {
            $error_message = "Invalid username or password. Please try again.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/lstyles.css">
</head>
<body>
    <div class="header-container">
        <img src="images/logo.jpg" alt="Logo" class="logo">
        <h1>Food ordering system</h1>
        <p>Your ultimate food ordering system. Order delicious meals from your favorite restaurants with ease!</p>
    </div>
    <div class="container">
        <header>
            <h1>Login</h1>
        </header>
        <main>
             <?php if (isset($_GET['message'])): ?>
                <p class="success"><?php echo htmlspecialchars($_GET['message']); ?></p>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <form action="login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <div class="button-row">
                    <button type="submit" name="admin_login">Admin Login</button>
                    <button type="submit" name="customer_login">Customer Login</button>
                </div>
                <div class="register-button-container">
                    <p>New user? register below </p>
                    <a href="register.php" class="register-button">Register</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
