<?php
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            $success = "Account created successfully. You can now log in.";
        } else {
            $error = "Error creating account. Please try again.";
        }
    } else {
        $error = "Username already exists. Please choose a different username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
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
        .register-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            text-align: center;
        }
        .register-container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
        .form-group {
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <img src="images/shoprite-logo.png" alt="Shoprite Logo">
        <h2>Create Account</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post" action="register.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
        </form>
        <div class="register-footer mt-3">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
