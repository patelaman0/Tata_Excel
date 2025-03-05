<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

$result = $conn->query("SELECT * FROM uploads");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fa;
        }

        .welcome-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: #121212;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            opacity: 1;
            animation: fadeOut 2.5s forwards;
        }

        @keyframes fadeOut {
            0% { opacity: 1; }
            100% { opacity: 0; display: none; }
        }

        .dashboard {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 2s forwards 2.5s;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        img {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<!-- Welcome Animation -->
<div class="welcome-container">
    Welcome, Aman Patel
</div>

<!-- Dashboard Content -->
<div class="container mt-5 dashboard">
    <h2 class="mb-4">Admin Dashboard</h2>

    <!-- Action Buttons -->
    <form action="admin_actions.php" method="POST" class="mb-3">
        <button type="submit" name="download" class="btn btn-success">Download All</button>
        <button type="submit" name="delete" class="btn btn-danger">Delete All</button>
        <a href="logout.php" class="btn btn-secondary">Logout</a>
    </form>

    <!-- Data Table -->
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Sr. No.</th>
                <th>Vehicle Number</th>
                <th>Image Type</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $srno = 1; // Start Sr. No. from 1
            while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $srno++ ?></td> <!-- Increment Sr. No. -->
                <td><?= htmlspecialchars($row['vehicle_number']) ?></td>
                <td><?= ucfirst($row['image_type']) ?></td>
                <td>
                    <?php
                    $images = explode(",", $row['image']);
                    foreach ($images as $image) {
                        echo "<img src='uploads/" . trim($image) . "' width='100' class='me-2'>";
                    }
                    ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>
