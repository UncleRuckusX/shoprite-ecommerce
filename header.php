<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shoprite E-commerce</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/scripts.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php"><img src="images/shoprite_logo.webp" alt="Shoprite Logo" style="width: 100px;"></a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
