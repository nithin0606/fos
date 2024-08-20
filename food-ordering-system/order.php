<?php
include 'db.php';
checkLogin();
// Assuming you set $_SESSION['user_id'] after login
$_SESSION['user_id'] = 1; // Replace with actual user ID from your login logic

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $_POST['customer_name'];
    $customer_address = $_POST['customer_address'];
    $dish_id = $_POST['dish_id'];
    $quantity = $_POST['quantity'];
    

    $sql = "SELECT price FROM dishes WHERE id = $dish_id";
    $result = $conn->query($sql);
    $dish = $result->fetch_assoc();
    $total_price = $dish['price'] * $quantity;

    $sql = "INSERT INTO orders (customer_name, customer_address, dish_id, quantity, total_price, status) VALUES ('$customer_name', '$customer_address', $dish_id, $quantity, $total_price, 'pending')";
    if ($conn->query($sql) === TRUE) {
        header("Location: success.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$dish_id = $_GET['dish_id'];
$sql = "SELECT * FROM dishes WHERE id = $dish_id";
$result = $conn->query($sql);
if ($result) {
    $dish = $result->fetch_assoc();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="images/logo.jpg" alt="Logo">
            <h1>Place Your Order</h1>
        </header>
        <main>
            <form action="order.php" method="post">
                <label for="customer_name">Name:</label>
                <input type="text" id="customer_name" name="customer_name" required>
                
                <label for="customer_address">Address:</label>
                <textarea id="customer_address" name="customer_address" required></textarea>
                
                <label for="dish_id">Dish:</label>
                <select id="dish_id" name="dish_id" required>
                    <option value="<?php echo $dish['id']; ?>"><?php echo $dish['name']; ?></option>
                </select>
                
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" required>
                
                <button type="submit">Place Order</button>
            </form>
        </main>
    </div>
</body>
</html>
