<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db_connect.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

if (!empty($cart)) {
    $product_ids = array_keys($cart);
    $product_ids_str = implode(',', $product_ids);
    $query = "SELECT * FROM products WHERE id IN ($product_ids_str)";
    $result = $conn->query($query);

    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['quantity'] = $cart[$row['id']];
            $row['subtotal'] = $row['price'] * $row['quantity'];
            $total += $row['subtotal'];
            $products[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Shoprite</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .header {
            background-color: #c00017;
            color: white;
            padding: 10px 0;
        }
        .header img {
            height: 50px; 
            width: auto;
        }
        .header .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .container {
            margin-top: 20px;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .btn-block {
            width: 100%;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container d-flex justify-content-between align-items-center">
            <img src="images/shoprite-logo-header.jpg" alt="Shoprite Logo">
            <div>
                <a href="index.php" class="btn btn-secondary">Home</a>
                <a href="cart.php" class="btn btn-secondary">View Cart</a>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </header>
    <div class="container">
        <h2 class="text-center">Checkout</h2>
        <?php if (!empty($products)): ?>
            <div class="row">
                <div class="col-md-6">
                    <h4>Your Order</h4>
                    <ul class="list-group">
                        <?php foreach ($products as $product): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo $product['name']; ?> (x<?php echo $product['quantity']; ?>)
                                <span>R<?php echo number_format($product['subtotal'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Total</strong>
                            <strong>R<?php echo number_format($total, 2); ?></strong>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h4>Billing Details</h4>
                    <form id="checkout-form">
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="form-group">
                            <label for="zip_code">Zip Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="card_number">Card Number</label>
                            <input type="text" class="form-control" id="card_number" name="card_number" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Place Order</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center">Your cart is empty.</p>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#checkout-form').on('submit', function(event) {
                event.preventDefault();
                alert('Thank you! Your transaction was successful.');
                <?php unset($_SESSION['cart']); ?>
                window.location.href = 'index.php';
            });
        });
    </script>
</body>
</html>
