<?php
session_start();
if (!isset($_SESSION["utilisateur_id"])) {
    header("Location:Login.php");
    exit;
} else {
    $session_password = $_SESSION['password'];
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="adminlte.min.css">
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <title>Change Password</title>
    </head>

    <body class="dark_bg">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 border-b">
            <h3 class="mx-5 text-center">Change profile</h3>
        </nav>
        <div class="container ">
            <form class="d-flex flex-column gap-3" method="POST">
                <?php
                $verifyPassword = $success = '';
                require ('db.php');
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $last_password = $_POST['password1'];
                    $new_password = $_POST['password2'];
                    if ($last_password == $session_password) {
                        if (!empty($new_password)) {
                            $query = "UPDATE utilisateur SET mot_de_passe = :new_password WHERE id_Utilisateur = :id";
                            $changePassword = $DB->prepare($query);
                            $changePassword->execute([
                                ":new_password" => $new_password,
                                ":id" => $_SESSION["utilisateur_id"]
                            ]);
                            $_SESSION['password'] = $new_password;
                            $success = 'Password change successful';
                        } else {
                            $verifyPassword = 'New password cannot be empty';
                        }
                    } else {
                        $verifyPassword = 'Your current password is invalid';
                    }
                }
                ?>
                <div class="card-body d-flex flex-column gap-3">
                    <div class="form-group">
                        <label for="exampleInputPassword1" class="mb-2 text-light">Your current password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" name="password1">
                        <small class="text-danger"><?= $verifyPassword ?></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword2" class="mb-2 text-light">Enter new password</label>
                        <input type="password" class="form-control" id="exampleInputPassword2" name="password2">
                    </div>
                </div>

                <div class="card-footer ">
                    <button type="submit" class="btn btn-primary mx-2">Submit</button>
                    <a href="index.php" class="btn btn-primary">Cancel</a>
                </div>
            </form>
        </div>
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <?php if (!empty($success)) { ?>
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
        <script src="toast.js"></script>
    </body>

    </html>
    <?php
}
?>