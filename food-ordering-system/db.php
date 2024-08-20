<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "order_food";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check if a user is logged in
function checkLogin() {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
}

// Function to check if the logged-in user is an admin
function checkAdmin() {
    if ($_SESSION['role'] !== 'admin') {
        header("Location: index.php");
        exit();
    }
}
?>
