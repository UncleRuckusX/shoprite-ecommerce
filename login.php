<?php
session_start();
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            text-align: center;
        }
        .login-container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
        .form-group {
            text-align: left;
        }
        .btn-primary { background-color: #c00017; border-color: #c00017; }
        .btn-primary:hover { background-color: #a00015; border-color: #a00015; }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="images/shoprite-logo.png" alt="Shoprite Logo">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
        </form>
        <div class="login-footer mt-3">
            <p>Don't have an account? <a href="register.php">Create Account</a></p>
        </div>
    </div>
</body>
</html>
