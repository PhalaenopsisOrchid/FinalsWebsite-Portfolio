<?php
// Start the session (must be the very first thing)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sprouthaven"; // **CHANGE THIS if your database name is different**
$port = 3307;             // Your specified MySQL port

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check the connection
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
}

// Function to safely retrieve product details
function getProductDetails($product_id) { 
    global $conn; 
    $sql = "SELECT * FROM products WHERE product_id = ?"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("i", $product_id); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    return $result->fetch_assoc(); 
}

?>