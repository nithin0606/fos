<?php
include 'db.php';
checkLogin();

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

if (!isAdmin()) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="images/logo.jpg">
            <h1>Admin Dashboard</h1>
            <p>Hello, <?php echo $_SESSION['username']; ?>! <a href="logout.php">Logout</a></p>
        </header>
        <main>
            <div class="button-row">
                <form action="manage_restaurants.php">
                    <img src="images/mr.jpg"><br/>
                    <button type="submit">Manage Restaurants</button>
                </form>
                <form action="manage_dishes.php">
                    <img src="images/md.jpg"><br/>
                    <button type="submit">Manage Dishes</button>
                </form>
                <form action="manage_orders.php">
                    <img src="images/mo.jpg"><br/>
                    <button type="submit">Manage Orders</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
