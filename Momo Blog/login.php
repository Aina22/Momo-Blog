<?php
session_start();
if (isset($_SESSION["utilisateur_id"])) {
    header('Location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Sign In</title>
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            max-width: 500px;
            width: 100%;
        }

        .toast-container {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 1100;
        }
    </style>
</head>

<body class="dark_bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4 text-dark">Sign In</h1>
                        <form method="post" class="needs-validation">
                            <?php
                            require ("db.php");
                            $emailEmpty = $passwordEmpty = $notconnected = "";
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                                $email = htmlspecialchars(isset($_POST["email"]) ? $_POST["email"] : "");
                                $password = htmlspecialchars(isset($_POST["password"]) ? $_POST["password"] : "");

                                if (empty($email)) {
                                    $emailEmpty = 'Please enter your email';
                                }
                                if (empty($password)) {
                                    $passwordEmpty = 'Please enter your password';
                                } else {
                                    $sqlQuery = "SELECT * from utilisateur WHERE email = :email AND mot_de_passe = :mdp";
                                    $is_existing_account = $DB->prepare($sqlQuery);
                                    $is_existing_account->execute([
                                        ":email" => $email,
                                        ":mdp" => $password,
                                    ]);
                                    $resultat = $is_existing_account->fetch(PDO::FETCH_ASSOC);
                                    if ($resultat) {
                                        session_start();
                                        $_SESSION["utilisateur_id"] = $resultat['id_Utilisateur'];
                                        $_SESSION["nom"] = $resultat['nom'];
                                        $_SESSION["prenom"] = $resultat['prenom'];
                                        $_SESSION["image_profile"] = $resultat['image_profile'];
                                        $_SESSION["email"] = $resultat['email'];
                                        $_SESSION['password'] = $resultat['mot_de_passe'];
                                        var_dump($resultat);
                                        header('location:index.php');
                                    } else {
                                        $notconnected = 'The information you entered is incorrect';
                                    }
                                }
                            }
                            ?>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email">
                                <small class="text-danger"><?= $emailEmpty ?></small>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small class="text-danger"><?= $passwordEmpty ?></small>
                            </div>
                            <div class="md-3 d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary h-2">Sign in</button>
                            </div>
                            <p class="my-2 text-dark"> I don't have an account <a href="register.php">sign up</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="toast-container">
        <?php if (!empty($notconnected)) { ?>
            <div id="loginToast" class="toast align-items-center text-white bg-danger border-0" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body">
                        <?= $notconnected ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        <?php } ?>
    </div>
    <script src="bootstrap.min.js"></script>
    <script src="toast.js"></script>
</body>

</html>