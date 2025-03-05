<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: #121212;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .logout-container {
            opacity: 1;
            animation: fadeOut 2.5s forwards;
        }

        @keyframes fadeOut {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.1); }
            100% { opacity: 0; transform: scale(1.2); }
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top: 5px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="logout-container">
    <h2>Logging Out...</h2>
    <div class="spinner"></div>
</div>

<script>
    setTimeout(() => {
        window.location.href = "admin_login.php";
    }, 2500);
</script>

</body>
</html>
