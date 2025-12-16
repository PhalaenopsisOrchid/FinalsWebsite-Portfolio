<?php
session_start();
require_once('config.php');

// Check if cart is empty
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

// Calculate grand total
$grand_total = 0;
foreach ($cart_items as $product_id => $quantity) {
    $product = getProductDetails($product_id);  // Assume this function fetches product details
    if ($product) {
        $price = floatval($product['price']);
        $grand_total += $price * $quantity;
    }
}

// Handle form submission (confirm the order)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture user information
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // For simplicity, just clear the cart and show a confirmation message
    unset($_SESSION['cart']);  // Clear the cart after order is placed
    
    // Optionally, you could store the order in the database here
    // saveOrder($name, $email, $address, $phone, $cart_items, $grand_total);
    
    $order_placed = true;
} else {
    $order_placed = false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout - Sprout Haven</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .checkout-info { padding: 20px; }
        .checkout-info input { width: 100%; padding: 10px; margin: 10px 0; }
        .total-row { border-top: 3px solid #ff523b; }
        .order-confirmation { font-size: 1.2em; color: green; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="navbar">
                <!-- Add Navbar Content -->
            </div>
        </div>
    </div>

    <div class="small-container">
        <h2 class="title">Checkout</h2>
        
        <?php if ($order_placed): ?>
            <div class="order-confirmation">
                <p>Thank you for your order, <?php echo htmlspecialchars($name); ?>!</p>
                <p>Your order has been successfully placed. A confirmation email will be sent to <?php echo htmlspecialchars($email); ?>.</p>
            </div>
            <a href="index.html" class="btn">Go to Homepage</a>
        <?php else: ?>
            <form action="checkout.php" method="POST">
                <div class="checkout-info">
                    <h3>Billing Information</h3>
                    <input type="text" name="name" placeholder="Full Name" required />
                    <input type="email" name="email" placeholder="Email Address" required />
                    <input type="text" name="address" placeholder="Shipping Address" required />
                    <input type="text" name="phone" placeholder="Phone Number" required />
                </div>

                <h3>Your Order</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #ddd; background-color: #f5f5f5;">
                        <th style="text-align: left; padding: 10px">Product</th>
                        <th style="padding: 10px">Price</th>
                        <th style="padding: 10px">Quantity</th>
                        <th style="padding: 10px">Subtotal</th>
                    </tr>

                    <?php foreach ($cart_items as $product_id => $quantity):
                        $product = getProductDetails($product_id); 
                        if ($product):
                            $price = floatval($product['price']);
                            $subtotal = $price * $quantity;
                    ?>
                    <tr style="border-bottom: 1px solid #ddd">
                        <td style="padding: 10px;"><?php echo htmlspecialchars($product['name']); ?></td>
                        <td style="padding: 10px">₱<?php echo number_format($price, 2); ?></td>
                        <td style="padding: 10px"><?php echo $quantity; ?></td>
                        <td style="padding: 10px">₱<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php endif; endforeach; ?>

                    <tr class="total-row">
                        <td colspan="3" style="text-align: right; padding: 10px; font-weight: bold;">Grand Total:</td>
                        <td style="padding: 10px; font-weight: bold;">₱<?php echo number_format($grand_total, 2); ?></td>
                    </tr>
                </table>

                <button type="submit" class="btn">Place Order</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <!-- Footer Content -->
        </div>
    </div>
</body>
</html>
