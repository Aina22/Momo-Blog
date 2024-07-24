<?php
session_start();
if (!isset($_SESSION["utilisateur_id"])) {
    header("Location: Login.php");
    exit;
} else {
    $image_profile = $_SESSION["image_profile"];
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="adminlte.min.css">
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Change profile</title>
        <style>
            .toast-container {
                position: fixed;
                bottom: 1rem;
                right: 1rem;
                z-index: 1100;
            }
        </style>
    </head>

    <body class="dark_bg">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 border-b">
            <h3 class="mx-5 text-center">Change profile</h3>
        </nav>
        <div class="container">
            <form method="POST" enctype="multipart/form-data">
                <?php
                require ("db.php");
                $errorImport = $errorEmpty = $success = '';
                $image_extension = array("png", "jpg", "jpeg", "svg", "webp");
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_FILES['profile']) && !empty($_FILES['profile']['name'])) {
                        $file_name = $_FILES['profile']['name'];
                        $file_size = $_FILES['profile']['size'];
                        $file_tmp = $_FILES['profile']['tmp_name'];
                        $image = explode('.', $file_name);
                        $extension = strtolower(end($image));

                        if (in_array($extension, $image_extension)) {
                            if ($file_size <= 5000000) { // 5MB limit
                                $upload_dir = 'profile/';
                                if (!is_dir($upload_dir)) {
                                    mkdir($upload_dir, 0777, true);
                                }
                                $new_file_name = uniqid() . '.' . $extension; // To ensure a unique file name
                                $file_path = $upload_dir . $new_file_name;
                                if (move_uploaded_file($file_tmp, $file_path)) {
                                    // Update profile image in database
                                    $query = "UPDATE utilisateur SET image_profile = :image WHERE id_Utilisateur = :id";
                                    $updateImage = $DB->prepare($query);
                                    $updateImage->execute([
                                        ":image" => $file_path,
                                        ":id" => $_SESSION["utilisateur_id"]
                                    ]);
                                    $_SESSION['image_profile'] = $file_path;
                                    $success = 'Profile image updated successfully';
                                } else {
                                    $errorImport = 'Failed to upload file';
                                }
                            } else {
                                $errorImport = "The file size is too large, please select another one";
                            }
                        } else {
                            $errorImport = "Cannot import .$extension format";
                        }
                    } else {
                        $errorEmpty = "Please select a file to upload";
                    }
                }
                ?>
                <div class="form-group mb-3">
                    <label for="exampleInputFile" class="mb-2 text-light">Change profile</label>
                    <div class="input-group">
                        <div class="custom-file border-width-2 border rounded bg-light">
                            <input type="file" class="text-secondary rounded" id="exampleInputFile" name="profile">
                            <label class="custom-file-label text-dark px-2" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                    <small class="text-danger"><?= $errorEmpty ?></small>
                </div>
                <div class="card-footer ">
                    <button type="submit" class="btn btn-primary mx-2">Submit</button>
                    <a href="index.php" class="btn btn-primary">Cancel</a>
                </div>
            </form>
        </div>
        <div class="toast-container">
            <?php if (!empty($errorImport)) { ?>
                <div id="loginToast" class="toast align-items-center text-white bg-danger border-0" role="alert"
                    aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?= $errorImport ?>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            <?php } elseif (!empty($success)) { ?>
                <div id="loginToast" class="toast align-items-center text-white bg-success border-0" role="alert"
                    aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?= $success ?>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            <?php } ?>
        </div>

        <script src="popper.min.js"></script>
        <script src="bootstrap.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                const toastEl = document.getElementById('loginToast');
                if (toastEl) {
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
            });
        </script>
    </body>

    </html>

    <?php
}
?>
