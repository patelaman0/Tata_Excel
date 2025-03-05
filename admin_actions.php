<?php
include "config.php";

if (isset($_POST['delete'])) {
    $conn->query("DELETE FROM uploads");
    array_map('unlink', glob("uploads/*"));
    echo "Redirecting in 3 seconds...";

    // Redirect after 3 seconds
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'All data has been deleted successfully.',
                icon: 'success',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'admin_dashboard.php';
            });
          </script>";
    exit();
}

if (isset($_POST['download'])) {
    $zip = new ZipArchive();
    $zip_file = "uploads.zip";

    if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
        foreach (glob("uploads/*") as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();
        
        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=$zip_file");
        readfile($zip_file);
        unlink($zip_file);
        exit();
    }
}
?>
