<?php
include 'db.php';
checkAdmin();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['cancel_order'])) {
        $order_id = $_POST['existing_order'];
        $sql = "DELETE FROM orders WHERE id='$order_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Order canceled successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Retrieve orders for the dropdown menu
$orders_result = $conn->query("SELECT * FROM orders");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="images/logo.jpg" alt="Logo">
            <h1>Manage Orders</h1>
            <p><a href="admin.php">Home</a></p>
        </header>
        <main>
            <h2>Manage Existing Orders</h2>
            <form action="manage_orders.php" method="post">
                <label for="existing_order">Select Order:</label>
                <select name="existing_order" id="existing_order" required>
                    <option value="">--Select an order--</option>
                    <?php
                    if ($orders_result->num_rows > 0) {
                        while ($row = $orders_result->fetch_assoc()) {
                            echo '<option value="' . $row["id"] . '">' . $row["id"] . ' - ' . $row["customer_name"] . '</option>';
                        }
                    }
                    ?>
                </select>
                <input type="submit" name="view_order" value="View Order">
                <input type="submit" name="cancel_order" value="Cancel Order">
            </form>

            <?php
            if (isset($_POST['view_order'])) {
                $order_id = $_POST['existing_order'];
                $order_result = $conn->query("SELECT * FROM orders WHERE id='$order_id'");
                $order = $order_result->fetch_assoc();
                ?>
                <h2>Order Details</h2>
                <div class="order-details">
                    <p>ID: <?php echo $order['id']; ?></p>
                    <p>Customer Name: <?php echo $order['customer_name']; ?></p>
                    <p>Customer Address: <?php echo $order['customer_address']; ?></p>
                    <p>Dish ID: <?php echo $order['dish_id']; ?></p>
                    <p>Quantity: <?php echo $order['quantity']; ?></p>
                    <p>Total Price: $<?php echo $order['total_price']; ?></p>
                    <p>Status: <?php echo $order['status']; ?></p>
                </div>
                <?php
            }
            ?>
        </main>
    </div>
</body>
</html>
