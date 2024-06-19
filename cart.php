<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit();
}

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
    <title>Cart - Shoprite</title>
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
        .table th, .table td {
            vertical-align: middle;
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
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </header>
    <div class="container">
        <h2 class="text-center">Your Cart</h2>
        <?php if (!empty($products)): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td>R<?php echo number_format($product['price'], 2); ?></td>
                                <td>R<?php echo number_format($product['subtotal'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total</strong></td>
                            <td><strong>R<?php echo number_format($total, 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <a href="checkout.php" class="btn btn-primary btn-block">Proceed to Checkout</a>
            <form method="post" action="cart.php" class="mt-3">
                <button type="submit" name="clear_cart" class="btn btn-danger btn-block">Clear Cart</button>
            </form>
        <?php else: ?>
            <p class="text-center">Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
