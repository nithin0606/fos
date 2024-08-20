<?php
include 'db.php';
checkAdmin();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_restaurant'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        // File upload handling
        if (isset($_FILES['restaurant_image']) && $_FILES['restaurant_image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['restaurant_image']['name'];
            $target_dir = "images/";
            $target_file = $target_dir . basename($_FILES['restaurant_image']['name']);

            // Move uploaded file to designated directory
            if (move_uploaded_file($_FILES['restaurant_image']['tmp_name'], $target_file)) {
                // Insert restaurant with image path into database
                $sql = "INSERT INTO restaurants (id, name, description, image) VALUES ('$id', '$name', '$description', '$image')";
                if ($conn->query($sql) === TRUE) {
                    echo "New restaurant added successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Please select an image file.";
        }
    } elseif (isset($_POST['update_restaurant'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        // File upload handling (if updating image)
        $image = $_POST['current_image']; // assuming a hidden field for current image
        if (isset($_FILES['restaurant_image']) && $_FILES['restaurant_image']['error'] === UPLOAD_ERR_OK) {
            $new_image = $_FILES['restaurant_image']['name'];
            $target_dir = "images/";
            $target_file = $target_dir . basename($_FILES['restaurant_image']['name']);

            // Move uploaded file to designated directory
            if (move_uploaded_file($_FILES['restaurant_image']['tmp_name'], $target_file)) {
                // Update restaurant with new image path into database
                $sql = "UPDATE restaurants SET name='$name', description='$description', image='$new_image' WHERE id='$id'";
                if ($conn->query($sql) === TRUE) {
                    echo "Restaurant updated successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            // Update restaurant without changing the image
            $sql = "UPDATE restaurants SET name='$name', description='$description' WHERE id='$id'";
            if ($conn->query($sql) === TRUE) {
                echo "Restaurant updated successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } elseif (isset($_POST['delete_restaurant'])) {
        if (isset($_POST['existing_restaurant'])) { // Check if the index exists
            $id = $_POST['existing_restaurant'];
            $sql = "DELETE FROM restaurants WHERE id='$id'";
            if ($conn->query($sql) === TRUE) {
                echo "Restaurant deleted successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Please select a restaurant to delete.";
        }
    }
}

// Retrieve restaurants for the dropdown menu
$restaurants_result = $conn->query("SELECT * FROM restaurants");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Restaurants</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="images/logo.jpg" alt="Logo">
            <h1>Manage Restaurants</h1>
            <p><a href="admin.php">Home</a></p>
        </header>
        <main>
            <h2>Add New Restaurant</h2>
            <form action="manage_restaurants.php" method="post" enctype="multipart/form-data">
                <label for="id">ID:</label>
                <input type="text" name="id" required>
                <label for="name">Name:</label>
                <input type="text" name="name" required>
                <label for="description">Description:</label>
                <textarea name="description" required></textarea>
                <label for="restaurant_image">Image:</label>
                <input type="file" name="restaurant_image" accept="image/*" required>
                <input type="submit" name="add_restaurant" value="Add Restaurant">
            </form>

            <h2>Manage Existing Restaurants</h2>
            <form action="manage_restaurants.php" method="post">
                <label for="existing_restaurant">Select Restaurant:</label>
                <select name="existing_restaurant" id="existing_restaurant" required>
                    <option value="">--Select a restaurant--</option>
                    <?php
                    if ($restaurants_result->num_rows > 0) {
                        while ($row = $restaurants_result->fetch_assoc()) {
                            echo '<option value="' . $row["id"] . '">' . $row["id"] . ' - ' . $row["name"] . '</option>';
                        }
                    }
                    ?>
                </select>
                <input type="submit" name="view_restaurant" value="View Restaurant">
                <input type="submit" name="edit_restaurant" value="Edit Restaurant">
                <input type="submit" name="delete_restaurant" value="Delete Restaurant">
            </form>

            <?php
            if (isset($_POST['view_restaurant']) || isset($_POST['edit_restaurant'])) {
                if (isset($_POST['existing_restaurant'])) { // Check if the index exists
                    $restaurant_id = $_POST['existing_restaurant'];
                    $restaurant_result = $conn->query("SELECT * FROM restaurants WHERE id='$restaurant_id'");
                    $restaurant = $restaurant_result->fetch_assoc();
                    ?>
                    <h2><?php echo $restaurant['name']; ?></h2>
                    <div class="restaurant-details">
                        <img src="images/<?php echo $restaurant['image']; ?>" alt="<?php echo $restaurant['name']; ?>">
                        <p>Description: <?php echo $restaurant['description']; ?></p>
                    </div>
                    <?php
                    if (isset($_POST['edit_restaurant'])) {
                        ?>
                        <h3>Edit Restaurant</h3>
                        <form action="manage_restaurants.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $restaurant['id']; ?>">
                            <input type="hidden" name="current_image" value="<?php echo $restaurant['image']; ?>">
                            <label for="name">Name:</label>
                            <input type="text" name="name" value="<?php echo $restaurant['name']; ?>" required>
                            <label for="description">Description:</label>
                            <textarea name="description" required><?php echo $restaurant['description']; ?></textarea>
                            <label for="restaurant_image">Image:</label>
                            <input type="file" name="restaurant_image" accept="image/*">
                            <input type="submit" name="update_restaurant" value="Update Restaurant">
                        </form>
                        <?php
                    }
                } else {
                    echo "Please select a restaurant.";
                }
            }
            ?>
        </main>
    </div>
</body>
</html>
