<?php
include 'db.php';
checkAdmin();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_dish'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $restaurant_id = $_POST['restaurant_id'];

        // File upload handling
        if (isset($_FILES['dish_image']) && $_FILES['dish_image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['dish_image']['name'];
            $target_dir = "images/";
            $target_file = $target_dir . basename($_FILES['dish_image']['name']);

            // Move uploaded file to designated directory
            if (move_uploaded_file($_FILES['dish_image']['tmp_name'], $target_file)) {
                // Insert dish with image path into database
                $sql = "INSERT INTO dishes (name, price, image, restaurant_id) VALUES ('$name', '$price', '$image', '$restaurant_id')";
                if ($conn->query($sql) === TRUE) {
                    echo "New dish added successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Please select an image file.";
        }
    } elseif (isset($_POST['update_dish'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $restaurant_id = $_POST['restaurant_id'];

        // File upload handling (if updating image)
        $image = $_POST['current_image']; // assuming a hidden field for current image
        if (isset($_FILES['dish_image']) && $_FILES['dish_image']['error'] === UPLOAD_ERR_OK) {
            $new_image = $_FILES['dish_image']['name'];
            $target_dir = "images/";
            $target_file = $target_dir . basename($_FILES['dish_image']['name']);

            // Move uploaded file to designated directory
            if (move_uploaded_file($_FILES['dish_image']['tmp_name'], $target_file)) {
                // Update dish with new image path into database
                $sql = "UPDATE dishes SET name='$name', price='$price', image='$new_image', restaurant_id='$restaurant_id' WHERE id='$id'";
                if ($conn->query($sql) === TRUE) {
                    echo "Dish updated successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            // Update dish without changing the image
            $sql = "UPDATE dishes SET name='$name', price='$price', restaurant_id='$restaurant_id' WHERE id='$id'";
            if ($conn->query($sql) === TRUE) {
                echo "Dish updated successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } elseif (isset($_POST['delete_dish'])) {
        $id = $_POST['existing_dish'];
        $sql = "DELETE FROM dishes WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Dish deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Retrieve dishes for the dropdown menu
$dishes_result = $conn->query("SELECT * FROM dishes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Dishes</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="images/logo.jpg" alt="Logo">
            <h1>Manage Dishes</h1>
            <p><a href="admin.php">Home</a></p>
        </header>
        <main>
            <form action="manage_dishes.php" method="post" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <input type="text" name="name" required>
                <label for="price">Price:</label>
                <input type="text" name="price" required>
                <label for="dish_image">Image:</label>
                <input type="file" name="dish_image" accept="image/*" required>
                <label for="restaurant_id">Restaurant ID:</label>
                <input type="text" name="restaurant_id" required>
                <input type="submit" name="add_dish" value="Add Dish">
            </form>

            <h2>Manage Existing Dishes</h2>
            <form action="manage_dishes.php" method="post">
                <label for="existing_dish">Select Dish:</label>
                <select name="existing_dish" id="existing_dish" required>
                    <option value="">--Select a dish--</option>
                    <?php
                    if ($dishes_result->num_rows > 0) {
                        while ($row = $dishes_result->fetch_assoc()) {
                            echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                        }
                    }
                    ?>
                </select>
                <input type="submit" name="view_dish" value="View Dish">
                <input type="submit" name="edit_dish" value="Edit Dish">
                <input type="submit" name="delete_dish" value="Delete Dish">
            </form>

            <?php
            if (isset($_POST['view_dish']) || isset($_POST['edit_dish'])) {
                $dish_id = $_POST['existing_dish'];
                $dish_result = $conn->query("SELECT * FROM dishes WHERE id='$dish_id'");
                $dish = $dish_result->fetch_assoc();
                ?>
                <h2><?php echo $dish['name']; ?></h2>
                <div class="dish-details">
                    <img src="images/<?php echo $dish['image']; ?>" alt="<?php echo $dish['name']; ?>">
                    <p>Price: $<?php echo $dish['price']; ?></p>
                    <p>Restaurant ID: <?php echo $dish['restaurant_id']; ?></p>
                </div>
                <?php
                if (isset($_POST['edit_dish'])) {
                    ?>
                    <h3>Edit Dish</h3>
                    <form action="manage_dishes.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $dish['id']; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $dish['image']; ?>">
                        <label for="name">Name:</label>
                        <input type="text" name="name" value="<?php echo $dish['name']; ?>" required>
                        <label for="price">Price:</label>
                        <input type="text" name="price" value="<?php echo $dish['price']; ?>" required>
                        <label for="dish_image">Image:</label>
                        <input type="file" name="dish_image" accept="image/*">
                        <label for="restaurant_id">Restaurant ID:</label>
                        <input type="text" name="restaurant_id" value="<?php echo $dish['restaurant_id']; ?>" required>
                        <input type="submit" name="update_dish" value="Update Dish">
                    </form>
                    <?php
                }
            }
            ?>
        </main>
    </div>
</body>
</html>
