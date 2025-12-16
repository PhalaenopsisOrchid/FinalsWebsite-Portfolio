<?php
// 1. Include the configuration file
require_once('config.php'); 

// =========================================================================
// CART ACTION LOGIC (Add, Update, Remove)
// =========================================================================

if (isset($_POST['add_to_cart'])) { 
    $product_id = intval($_POST['product_id']); 
    $quantity = intval($_POST['quantity']); 
    
    if (isset($_SESSION['cart'][$product_id])) { 
        $_SESSION['cart'][$product_id] += $quantity; 
    } else { 
        $_SESSION['cart'][$product_id] = $quantity; 
    }
    header("Location: cart.php"); 
    exit(); 
} 

if (isset($_POST['update_cart'])) { 
    foreach ($_POST['quantity'] as $product_id => $quantity) { 
        $product_id = intval($product_id);
        $quantity = intval($quantity);
        
        if ($quantity <= 0) { 
            unset($_SESSION['cart'][$product_id]);
        } else { 
            $_SESSION['cart'][$product_id] = $quantity; 
        } 
    } 
    header("Location: cart.php"); 
    exit(); 
} 

if (isset($_GET['remove'])) { 
    $product_id = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]); 
    }
    header("Location: cart.php"); 
    exit(); 
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$grand_total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shopping Cart - Sprout Haven</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        .cart-info { display: flex; align-items: center; }
        .cart-info img { margin-right: 10px; border-radius: 5px; }
        .total-row { border-top: 3px solid #ff523b; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <a href="index.html">
                        <img src="Logo.png" alt="logo" width="120px" />
                    </a>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="shop.html">Products</a></li>
                        <li><a href="about.html">About</a></li>
                        <li><a href="contact.html">Contact</a></li>
                        <li><a href="account.html">Account</a></li>
                    </ul>
                </nav>
                <a href="cart.php">
                    <img src="Cart-icon.png" width="30px" height="30px" />
                </a>
            </div>
        </div>
    </div>

    <div class="small-container">
        <h2 class="title">Shopping Cart</h2>

        <?php if (!empty($cart_items)): ?>
            <form action="cart.php" method="POST">
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px">
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
                            $grand_total += $subtotal;
                    ?>
                    <tr style="border-bottom: 1px solid #ddd">
                        <td style="padding: 10px;">
                            <div class="cart-info">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" width="60" style="margin-right: 10px" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                <div>
                                    <?php echo htmlspecialchars($product['name']); ?><br>
                                    <a href="cart.php?remove=<?php echo $product_id; ?>" style="color: #ff523b; font-size: 14px;">Remove</a>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 10px">₱<?php echo number_format($price, 2); ?></td>
                        <td style="padding: 10px">
                            <input type="number" name="quantity[<?php echo $product_id; ?>]" value="<?php echo $quantity; ?>" min="1" style="width: 50px; text-align: center" />
                        </td>
                        <td style="padding: 10px">₱<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php endif; endforeach; ?>
                    
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right; padding: 10px; font-weight: bold;">Grand Total:</td>
                        <td style="padding: 10px; font-weight: bold;">₱<?php echo number_format($grand_total, 2); ?></td>
                    </tr>
                </table>

                <div style="text-align: right; margin-top: 20px;">
                    <button type="submit" name="update_cart" class="btn">Update Cart</button>
                    <button type="button" class="btn" onclick="window.location.href='checkout.php'">Proceed to Checkout</button>
                </div>
            </form>
        <?php else: ?>
            <p>Your cart is empty. <a href="shop.html">Go shopping!</a></p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col-1">
                    <h3>Download Our App</h3>
                    <p>Download our app for Android and IOS mobile phones.</p>
                    <div class="app-logo">
                        <img src="googleplay.png" />
                        <img src="app-store.png" />
                    </div>
                </div>
                <div class="footer-col-2">
                    <img src="Logo.png" alt="Logo" />
                    <p>Our purpose is to encourage others to try gardening.</p>
                </div>
                <div class="footer-col-3">
                    <h3>Follow Us</h3>
                    <ul>
                        <li><a href="https://facebook.com" target="_blank">Facebook</a></li>
                        <li><a href="https://instagram.com" target="_blank">Instagram</a></li>
                        <li><a href="https://twitter.com" target="_blank">Twitter</a></li>
                    </ul>
                </div>
            </div>
            <hr />
            <p class="copyright">Copyright 2025 - Sprout Haven</p>
        </div>
    </div>
</body>
</html>
