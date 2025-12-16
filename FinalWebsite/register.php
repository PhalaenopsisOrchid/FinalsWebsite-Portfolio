<?php
// register.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include config (replace with your actual connection details/config file)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sprouthaven";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; // Do NOT sanitize this with htmlspecialchars
    $role = htmlspecialchars($_POST['role'] ?? 'customer'); // Default to customer

    // 1. Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 2. Prepare SQL for insertion into a 'users' table
    // You will need a 'users' table with columns: id, name, email, password_hash, role
    $sql = "INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // 3. Bind parameters
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

        // 4. Execute and handle result
        if ($stmt->execute()) {
            echo "Registration successful. You can now <a href='account.html'>login</a>.";
            // Optionally redirect: header('Location: login.html'); exit();
        } else {
            // Check for duplicate entry (e.g., duplicate email)
            if ($conn->errno == 1062) {
                 echo "Error: This email is already registered.";
            } else {
                 echo "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>