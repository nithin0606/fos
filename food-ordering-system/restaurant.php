<?php
include 'db.php';
checkLogin();

$restaurant_id = $_GET['id'];
$sql = "SELECT * FROM restaurants WHERE id = $restaurant_id";
$restaurant_result = $conn->query($sql);
$restaurant = $restaurant_result->fetch_assoc();

$sql = "SELECT * FROM dishes WHERE restaurant_id = $restaurant_id";
$dishes_result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $restaurant['name']; ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="images/logo.jpg" alt="Logo">
            <h1><?php echo $restaurant['name']; ?></h1>
        </header>
        <main>
            <div class="restaurantt">
            <img src="images/<?php echo $restaurant['image']; ?>" alt="<?php echo $restaurant['name']; ?>">
            <p><?php echo $restaurant['description']; ?></p>
            </div>
            <h2>Menu</h2>
            <div class="dishes-container">
            <?php
                if ($dishes_result->num_rows > 0) {
                    $count = 0;
                    while($row = $dishes_result->fetch_assoc()) {
                        echo '<div class="dish">';
                        echo '<img src="images/' . $row["image"] . '" alt="' . $row["name"] . '">';
                        echo '<h3>' . $row["name"] . '</h3>';
                        echo '<p>Dish ID:'.$row["id"].'</p>';
                        echo '<p>Price: &#8377;' . $row["price"] . '</p>';
                        echo '<a href="order.php?dish_id=' . $row["id"] . '">Order Now</a>';
                        echo '</div>';
                        $count++;
                        if ($count % 3 == 0) {
                            echo '<div class="clearfix"></div>'; // Clearfix for every third item
                        }
                    }
                } else {
                    echo "No dishes available.";
                }
            ?>
            </div>
        </main>

    </div>
</body>
</html>
