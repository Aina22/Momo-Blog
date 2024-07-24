<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>register</title>
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
    </style>
</head>

<body class="dark_bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4 text-dark">Sign Up</h1>
                        <form method="post" class="needs-validation">
                            <?php
                            require "db.php";
                            $avatar = 'profile/avatar.webp';
                            $emptyFirstname = $emptyLastname = $emptyEmail = $invalidEmail = $emailExist = $emptyPassword = $invalidPassword = "";

                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                // Initialize variables
                                $firstname = htmlspecialchars( isset($_POST['firstName']) ? $_POST['firstName'] : '');
                                $lastname = htmlspecialchars(isset($_POST['lastName']) ? $_POST['lastName'] : '');
                                $email = htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : '');
                                $password = htmlspecialchars(isset($_POST['password']) ? $_POST['password'] : '');

                                // Validation patterns
                                $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/i';
                                $emailVerification = !empty($email) && preg_match($pattern, $email);
                                $passwordPattern = '/^.{8,}$/';
                                $passwordVerification = !empty($password) && preg_match($passwordPattern, $password);

                                if (empty($firstname)) {
                                    $emptyFirstname = 'Please enter your firstname';
                                }
                                if (empty($lastname)) {
                                    $emptyLastname = 'Please enter your lastname';
                                }
                                if (empty($email)) {
                                    $emptyEmail = 'Please enter your email';
                                } elseif (!$emailVerification) {
                                    $invalidEmail = 'Please enter a valid email';
                                }
                                if (empty($password)) {
                                    $emptyPassword = 'Please enter your password';
                                } elseif (!$passwordVerification) {
                                    $invalidPassword = 'Password does not meet the requirement (less than 8 characters)';
                                } else {
                                    // Verify if email is already existe
                                    $email_existing_verification_sql_query = 'SELECT * FROM utilisateur WHERE email = :email ';
                                    $query = $DB->prepare($email_existing_verification_sql_query);
                                    $query->execute([
                                        ':email' => $email,
                                    ]);
                                    $Verify_email = $query->fetch(PDO::FETCH_ASSOC);
                                    if (!$Verify_email) {
                                        // Create an account
                                        $Account_creation_SQL_query = 'INSERT INTO utilisateur ( nom, prenom, mot_de_passe,email,image_profile) VALUES (:firstname, :lastname, :mdp, :email,:avatar )';
                                        $creation_query = $DB->prepare($Account_creation_SQL_query);
                                        $creation_query->execute([
                                            ":firstname" => $firstname,
                                            ":lastname" => $lastname,
                                            ":mdp" => $password,
                                            ":email" => $email,
                                            ":avatar" => $avatar
                                        ]);
                                        header("Location: login.php");
                                    } else {
                                        //Say email is already exist 
                                        $emailExist = 'This email is already exist';
                                    }


                                }


                            }
                            ?>
                           
                            <div class="mb-3">
                                <label for="firstName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName">
                                <small class="text-danger"><?= $emptyLastname ?></small>

                            </div>
                            <div class="mb-3">
                                <label for="lastnameName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName">
                                <small class="text-danger"><?= $emptyFirstname ?></small>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email">
                                <small
                                    class="text-danger"><?= $emptyEmail ?><?= $invalidEmail ?><?= $emailExist ?></small>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small
                                    class="text-danger"><?php echo $emptyPassword; ?><?php echo $invalidPassword; ?></small>
                            </div>
                            <div class="md-3 d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary w-3 h-2">Register</button>
                                <p class="my-2 text-dark"> I have an account <a href="login.php">login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="bootstrap.bundle.min.js"></script>
</body>

</html>