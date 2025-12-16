<?php
// login.php
session_start();
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
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; // Do NOT sanitize this

    // 1. Prepare SQL to select the user's details and the HASHED password
    $sql = "SELECT id, name, password_hash, role FROM users WHERE email = ?";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // 2. Verify the submitted password against the stored HASH
            if (password_verify($password, $user['password_hash'])) {
                // Password is correct! Start session.
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role']; // Store the role for Admin check
                
                // 3. Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: admin_dashboard.php'); // Redirect admins here
                } else {
                    header('Location: index.html'); // Redirect regular customers to home
                }
                exit();
            } else {
                echo "Invalid email or password.";
            }
        } else {
            echo "Invalid email or password.";
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>