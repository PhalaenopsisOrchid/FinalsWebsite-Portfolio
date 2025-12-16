<?php
// 1. Include the database connection and start the session
require_once('config.php'); 

// 2. Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Retrieve product_id and quantity from the form
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Ensure quantity is at least 1
    if ($quantity < 1) {
        $quantity = 1;
    }

    // 4. Fetch product details from the database
    $sql = "SELECT product_id, name, price, image FROM products WHERE product_id = $product_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Structure the item for the cart session
        $item_array = array(
            'product_id' => $product['product_id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'image' => $product['image']
        );

        // 5. Add/Update item in the session cart
        if (!isset($_SESSION['cart'])) {
            // Cart is empty, create a new cart array
            $_SESSION['cart'] = array();
            $_SESSION['cart'][$product_id] = $item_array;

        } elseif (array_key_exists($product_id, $_SESSION['cart'])) {
            // Product is already in cart, update quantity
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Product is not in cart, add new item
            $_SESSION['cart'][$product_id] = $item_array;
        }

        // 6. Redirect back to the product page or to the cart page
        header('Location: cart.html'); // Redirect to a separate cart page
        exit();

    } else {
        // Product not found
        echo "Error: Product not found.";
    }

} else {
    // Direct access to this file is not allowed
    header('Location: shop.html');
    exit();
}

$conn->close();
?>