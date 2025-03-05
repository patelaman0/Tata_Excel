<?php
session_start();
include "config.php";

$buttonClass = "btn-primary"; // Default button color

if (isset($_SESSION['admin'])) {
    header("Location: admin_dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $username;
        $buttonClass = "btn-success"; // Green button on success
        header("refresh:1; url=admin_dashboard.php"); // Redirect after 1 second
    } else {
        $error = "Invalid Username or Password!";
        $buttonClass = "btn-danger"; // Red button on error
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center vh-100">

<div class="card shadow-lg p-4 text-dark bg-light" style="max-width: 400px; width: 100%;">
    <h3 class="text-center mb-3">Admin Login</h3>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn w-100 <?= $buttonClass ?>">Login</button>
    </form>
    <div class="text-center mt-3">
        <a href="index.php" class="btn btn-outline-dark w-100">Back to Home</a>
    </div>
</div>

</body>
</html>
