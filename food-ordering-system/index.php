<?php
include 'db.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food Ordering System</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="images/logo.jpg" alt="Logo">
            <h1>Welcome to Our Food Ordering System</h1>
            <p>Hello, <?php echo $_SESSION['username']; ?>! <a href="logout.php">Logout</a></p>
        </header>
        <main>
            <h2>Our Restaurants</h2>
            <div class="restaurants">
                <?php
                $sql = "SELECT * FROM restaurants";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    echo '<div class="restaurant-container">';
                        while($row = $result->fetch_assoc()) {
                        echo '<div class="restaurant">';
                        echo '<img src="images/' . $row["image"] . '" alt="' . $row["name"] . '">';
                        echo '<h3>' . $row["name"] . '</h3>';
                        echo '<p>' . $row["description"] . '</p>';
                        echo '<a href="restaurant.php?id=' . $row["id"] . '">View Menu</a>';
                        echo '</div>';
                        }
                    echo '</div>'; 
                }else {
                    echo "No restaurants found.";
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>
