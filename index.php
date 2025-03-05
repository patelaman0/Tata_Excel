<?php
include "config.php";

$downloadFile = "";

function compressImage($source, $destination, $quality) {
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    }
    imagejpeg($image, $destination, $quality);
    imagedestroy($image);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_number = strtoupper($_POST['vehicle_number']);
    $upload_type = $_POST['upload_type'];

    if ($upload_type == "handover") {
        $image = $_FILES['handover']['name'];
        $target = "uploads/" . $vehicle_number . ".jpg";

        if (move_uploaded_file($_FILES['handover']['tmp_name'], $target)) {
            compressImage($target, $target, 60);
            $query = "INSERT INTO uploads (vehicle_number, image, image_type) VALUES ('$vehicle_number', '$vehicle_number.jpg', 'handover')";
            $conn->query($query);
            $downloadFile = $target;
        }

    } elseif ($upload_type == "fitted") {
        $front_target = "uploads/" . $vehicle_number . "_f.jpg";
        $rear_target = "uploads/" . $vehicle_number . "_r.jpg";

        if (move_uploaded_file($_FILES['fitted_front']['tmp_name'], $front_target) &&
            move_uploaded_file($_FILES['fitted_rear']['tmp_name'], $rear_target)) {

            compressImage($front_target, $front_target, 60);
            compressImage($rear_target, $rear_target, 60);

            $images = "$vehicle_number" . "_f.jpg,$vehicle_number" . "_r.jpg";
            $query = "INSERT INTO uploads (vehicle_number, image, image_type) VALUES ('$vehicle_number', '$images', 'fitted')";
            $conn->query($query);
            $downloadFile = $front_target . "," . $rear_target;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tata Excel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script>
        function showUploadOptions() {
            var selection = document.getElementById("upload_type").value;
            document.getElementById("handover_section").style.display = (selection === "handover") ? "block" : "none";
            document.getElementById("fitted_section").style.display = (selection === "fitted") ? "block" : "none";
        }

        function triggerDownload(fileUrls) {
            var files = fileUrls.split(",");
            files.forEach(file => {
                var link = document.createElement("a");
                link.href = file.trim();
                link.download = file.split("/").pop();
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        }
    </script>
    <style>
    .admin-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #343a40;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }
        .admin-btn:hover {
            background: #212529;
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
<a href="admin_login.php" class="admin-btn">Admin Login</a>

<div class="card shadow-lg p-4" style="max-width: 500px; width: 100%;">
    <h3 class="text-center mb-3">Upload Vehicle Images</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Vehicle Number</label>
            <input type="text" name="vehicle_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Select Type</label>
            <select id="upload_type" name="upload_type" class="form-select" onchange="showUploadOptions()" required>
                <option value="">--Select--</option>
                <option value="handover">Handover</option>
                <option value="fitted">Fitted</option>
            </select>
        </div>

        <div id="handover_section" class="mb-3" style="display: none;">
            <label class="form-label">Upload Handover Image</label>
            <input type="file" name="handover" class="form-control">
        </div>

        <div id="fitted_section" class="mb-3" style="display: none;">
            <label class="form-label">Upload Fitted Front Image</label>
            <input type="file" name="fitted_front" class="form-control">
            <label class="form-label mt-2">Upload Fitted Rear Image</label>
            <input type="file" name="fitted_rear" class="form-control">
        </div>

        <button type="submit" name="submit" class="btn btn-primary w-100">Submit</button>
    </form>
</div>

<?php if (!empty($downloadFile)): ?>
<script>
    setTimeout(() => {
        triggerDownload("<?= $downloadFile ?>");
    }, 1500);
</script>
<?php endif; ?>

</body>
</html>
