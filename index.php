<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db_connect.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

$query = "SELECT * FROM products WHERE name LIKE '%$search%'";
if ($filter == 'sale') {
    $query .= " AND on_sale = 1";
}
$result = $conn->query($query);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shoprite</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
        .card { 
            margin-bottom: 20px; 
        }
        .card-body { 
            padding: 20px; 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
            height: 100%; 
        }
        .card-img-top { 
            max-width: 100%; 
            max-height: 200px; 
            object-fit: contain; 
            border-bottom: 1px solid #ddd; 
        }
        .price-section { 
            margin-bottom: auto; 
        }
        .add-to-cart-section { 
            text-align: center; 
        }
        .btn-primary { 
            background-color: #c00017; 
            border-color: #c00017; 
        }
        .btn-primary:hover { 
            background-color: #a00015; 
            border-color: #a00015; 
        }
        .slideshow-container { 
            max-width: 100%; 
            position: relative; 
            margin: auto; 
            overflow: hidden; 
            margin-top: 20px;
        }
        .mySlides { 
            display: none; 
            width: 100%; 
        }
        .active { 
            display: block; 
        }
        .slideshow-container img { 
            width: 100%; 
        }
        @media (max-width: 768px) {
            .header .form-inline { 
                flex-direction: column; 
                align-items: flex-start; 
            }
            .header .form-inline .form-control, 
            .header .form-inline .btn { 
                margin-bottom: 5px; 
                width: 100%; 
            }
            .header .form-inline .btn:last-child { 
                margin-bottom: 0; 
            }
            .card-body { 
                padding: 10px; 
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container d-flex justify-content-between align-items-center flex-wrap">
            <img src="images/shoprite-logo-header.jpg" alt="Shoprite Logo">
            <form class="form-inline my-2 my-lg-0" method="get" action="index.php">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" name="search" aria-label="Search">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
                <a href="index.php?filter=sale" class="btn btn-warning ml-2">Specials</a>
            </form>
            <div class="d-flex align-items-center">
                <a href="cart.php" class="btn btn-secondary mr-2">View Cart</a>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </header>
    <div class="container mt-4">
        <h2 class="text-center">Products</h2>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['name']; ?></h5>
                            <div class="price-section">
                                <p class="card-text">
                                    <?php if ($product['on_sale']): ?>
                                        <span class="text-danger">R<?php echo $product['price']; ?></span>
                                        <del class="text-muted">R<?php echo $product['old_price']; ?></del>
                                        <span class="badge badge-success">Sale</span>
                                    <?php else: ?>
                                        R<?php echo $product['price']; ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="add-to-cart-section">
                                <form method="post" action="add_to_cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <div class="form-group">
                                        <label for="quantity">Quantity:</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="slideshow-container">
        <div class="mySlides active">
            <img src="images/promo1.webp" alt="Promo 1">
        </div>
        <div class="mySlides">
            <img src="images/promo2.webp" alt="Promo 2">
        </div>
        <div class="mySlides">
            <img src="images/promo3.webp" alt="Promo 3">
        </div>
        <div class="mySlides">
            <img src="images/promo4.webp" alt="Promo 4">
        </div>
        <div class="mySlides">
            <img src="images/promo5.webp" alt="Promo 5">
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var slides = $(".mySlides");
            var currentIndex = 0;

            function showSlide(index) {
                slides.removeClass("active").eq(index).addClass("active");
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % slides.length;
                showSlide(currentIndex);
            }

            setInterval(nextSlide, 3000);
        });
    </script>
</body>
</html>
