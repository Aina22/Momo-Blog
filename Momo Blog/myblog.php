<?php
session_start();
if (!isset($_SESSION["utilisateur_id"])) {
    header("Location:login.php");
}
$id = $_SESSION["utilisateur_id"];
$nom = $_SESSION["nom"];
$prenom = $_SESSION["prenom"];
$image_profile = $_SESSION["image_profile"];
$email = $_SESSION["email"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adminlte.min.css">
    <link rel="stylesheet" href="all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Momo blog</title>
    <style>
        /* Custom CSS for specific styling */
        .sidebar {
            height: 100vh;
            /* Full height of the viewport */
            overflow-y: auto;
            /* Allow scrolling within the sidebar if content exceeds height */
        }

        .main-content {
            overflow-y: auto;
            /* Allow scrolling within the main content area if content exceeds height */
        }
    </style>
</head>

<body>
    <div class="container-fluid overflow-hidden ">
        <div class="row vh-100 overflow-auto">
            <div class="col-12 col-sm-3 col-xl-2 px-sm-2 px-0 bg-dark d-flex sticky-top sidebar">
                <div
                    class="d-flex flex-sm-column flex-row flex-grow-1 align-items-center align-items-sm-start px-3 pt-2 text-white  ">
                    <a href="index.php"
                        class="d-flex align-items-center pb-sm-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-flex align-items-center">
                            <span class="d-none d-sm-inline">M</span>
                            <img src="peach.svg" height="20" width="20" alt="peach">
                            <span class="d-none d-sm-inline">m</span>
                            <span class="d-none d-sm-inline">o</span>
                        </span>
                    </a>
                    <ul class="nav nav-pills flex-sm-column flex-row flex-nowrap flex-shrink-1 flex-sm-grow-0 flex-grow-1 mb-sm-auto mb-0 justify-content-center align-items-center align-items-sm-start"
                        id="menu">
                        <li>
                            <a href="index.php" class="nav-link px-sm-0 px-2">
                                <i class="fa-solid fa-house"></i><span class="ms-1 d-none d-sm-inline ">Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="myblog.php" class="nav-link px-sm-0 px-2  ">
                                <i class="fa-solid fa-signs-post text-primary "></i><span
                                    class="ms-1 d-none d-sm-inline text-primary ">My
                                    blog</span>
                            </a>
                        </li>
                    </ul>
                    <div class="dropdown py-sm-4 mt-sm-auto ms-auto ms-sm-0 flex-shrink-1">
                        <a href="#"
                            class="d-flex align-items-center text-white text-decoration-none dropdown-toggle mb-2"
                            id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= $image_profile ?>" alt="hugenerd" width="28" height="28"
                                class="rounded-circle">
                            <span class="d-none d-sm-inline mx-1"><?= $prenom ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="changeProfile.php">Change profile</a></li>
                            <li><a class="dropdown-item" href="changePassword.php">Change password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto dark_bg overflowy-hidden ">
                    <div class="col main-content">
                        <div class="card mt-4 mx-2 bg-dark">
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <?php
                                    require ("db.php");
                                    $postEmpty = $errorImport = "";
                                    $image_extension = ["png", "jpg", "jpeg", "svg", "webp"];

                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        if (isset($_POST['postInput'])) {
                                            $postInput = $_POST['postInput'];

                                            if (!empty($postInput)) {
                                                if (isset($_FILES['addImage']) && !empty($_FILES['addImage']['name'])) {
                                                    $file_name = $_FILES['addImage']['name'];
                                                    $file_size = $_FILES['addImage']['size'];
                                                    $file_tmp = $_FILES['addImage']['tmp_name'];
                                                    $image = explode('.', $file_name);
                                                    $extension = strtolower(end($image));
                                                    if (in_array($extension, $image_extension)) {
                                                        if ($file_size <= 5000000) { // 5MB limit
                                                            $upload_dir = 'post/';
                                                            if (!is_dir($upload_dir)) {
                                                                mkdir($upload_dir, 0777, true);
                                                            }
                                                            $new_file_name = uniqid() . '.' . $extension; // To ensure a unique file name
                                                            $file_path = $upload_dir . $new_file_name;
                                                            if (move_uploaded_file($file_tmp, $file_path)) {
                                                                $query2 = "INSERT INTO article (contenu,id_Utilisateur) VALUES (:contenu,:id);";
                                                                $add_Post = $DB->prepare($query2);
                                                                $add_Post->execute([
                                                                    ":contenu" => $postInput,
                                                                    ":id" => $id
                                                                ]);
                                                                $postId = $DB->lastInsertId();
                                                                $query3 = "INSERT INTO imagepost (url_image,id_article) VALUES(:image_url,:id_post);";
                                                                $add_Post_with_image = $DB->prepare($query3);

                                                                $add_Post_with_image->execute([
                                                                    ":image_url" => $file_path,
                                                                    ":id_post" => $postId,
                                                                ]);
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
                                                    $query1 = "INSERT INTO article (contenu,id_Utilisateur) VALUES (:contenu,:id);";
                                                    $addPost = $DB->prepare($query1);
                                                    $addPost->execute([
                                                        ":contenu" => $postInput,
                                                        ":id" => $id
                                                    ]);

                                                }
                                            } else {
                                                $postEmpty = "Please Insert your Post";
                                            }
                                        } else {
                                            $postEmpty = "Please Insert your Post";
                                        }
                                    }
                                    ?>
                                    <input type="text" class="form-control" name='postInput'
                                        placeholder="What's new <?= $prenom ?>" aria-label="Username"
                                        aria-describedby="basic-addon1">
                                    <div class="mt-3">
                                        <input type="file" class="text-secondary d-noneaddImage d-none" name="addImage"
                                            id="addImage">
                                        <label for="addImage" class="text-primary">
                                            <i class="fa-regular fa-image text-primary mt-2"></i> Add image
                                        </label>
                                        <button type="submit" class="btn btn-primary float-right">Add Post</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                        $query4 = "SELECT 
                        article.contenu AS article_contenu, 
                        article.date_post AS date_article, 
                        utilisateur.nom AS utilisateur_nom,
                        utilisateur.prenom AS util isateur_prenom,
                        utilisateur.image_profile AS utilisateur_profile,
                        imagepost.url_image AS image_post
                        FROM article
                        INNER JOIN utilisateur ON article.id_utilisateur = utilisateur.id_Utilisateur 
                        LEFT JOIN imagepost ON article.id_Article = imagepost.id_article
                        WHERE utilisateur.id_Utilisateur = :utilisateur_id
                        ORDER BY date_post DESC;";

                        $show_post = $DB->prepare($query4);
                        $show_post->execute([":utilisateur_id"=>$id]);
                        while ($post = $show_post->fetch(PDO::FETCH_ASSOC)) {
                            $contenu = $post['article_contenu'];
                            $date = $post['date_article'];
                            $nom_user = $post['utilisateur_nom'];
                            $prenom_user = $post['utilisateur_prenom'];
                            $profile_img = $post['utilisateur_profile'];
                            $imagePost = $post['image_post'];
                            ?>
                            <div class="col pt-4 ">
                                <div class="card bg-dark">
                                    <div class="card-body">
                                        <div class="post p-3">
                                            <div class="card-title">
                                                <div class="user-block">
                                                    <img class="img-circle img-bordered-sm h blueborder"
                                                        src="<?= $profile_img ?>">
                                                    <span class="username">
                                                        <span class="hoverd"><?= $prenom_user ?>     <?= $nom_user ?></span>
                                                    </span>
                                                    <span class="description"><?= $date ?></span>
                                                </div>
                                                <div class="card-text text-light">
                                                    <p>
                                                        <?= $contenu ?>
                                                    </p>
                                                </div>
                                                <div class="row mb-3 d-flex flex-column">
                                                    <div class="col">
                                                        <img class="img-fluid" src="<?= $imagePost ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                </main>
            </div>
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
            <?php } ?>
        </div>
    </div>

    <script src="popper.min.js"></script>
    <script src="bootstrap.min.js"></script>
    <script src="all.min.js"></script>
</body>

</html>