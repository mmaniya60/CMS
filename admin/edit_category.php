<?php

session_start();

include_once('../includes/connect.php');
include_once('../includes/post.php');


    if(isset($_SESSION['logged_in'])){
        if(isset($_GET['id'])){

            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$id || empty($id)) {
                echo '<script>alert("Incorrect has been ID passed!!!")</script>';
                exit;
            }

            $query = $db->prepare("SELECT * FROM genre WHERE genre_id = ?");
            $query->bindValue(1, $id);
            $query->execute();
            $genres = $query->fetch();
        }

            if(isset($_POST['genre'])){

                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                if (!$id || empty($id)) {
                    echo '<script>alert("Incorrect has been ID passed!!!")</script>';
                    exit;
                }
                $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_STRING);

                if(empty($genre)){
                    $error = "Field is required!";

                    $query = $db->prepare("SELECT * FROM genre WHERE genre_id = ?");
                    $query->bindValue(1, $id);
                    $query->execute();
                    $genres = $query->fetch();
                }
                else{
                    $slug = slug($genre);

                    $query1 = $db->prepare("UPDATE genre SET genres = ?, genre_slug = ? WHERE genre_id = ?;");
                    $query1->bindValue(1, $genre);
                    $query1->bindValue(2, $slug);
                    $query1->bindValue(3, $id);

                    $query1->execute();

                    header("Location: categories.php");
                }
            }


?>

<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>Edit Genre</title>
	<link rel="stylesheet" href="../styles/style.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

	<div class="w3-top w3-black">
	<a href="../index.php" class="w3-bar-item w3-button">Home</a>
    <a href="index.php" class="w3-bar-item w3-button">Dashboard</a>
    <a href="logout.php" onclick="return confirm('Are you sure?')" class="w3-bar-item w3-button w3-green w3-right">Logout</a>
	</div>
    
    <div class="container">
        <h2>Edit Genre</h2><br />
        <?php if(isset($error)): ?>
            <div class="w3-panel w3-red">
                <h3>Error Found!</h3>
                <p><?= $error ?></p>
            </div>
        <?php endif ?>

        <form action="edit_category.php" method="post" autocomplete="off">
            <input type="hidden" name="id" value="<?= $genres['genre_id'] ?>" />
            <input class="w3-input" type="text" name="genre" value="<?= $genres['genres'] ?>" /><br /><br />
            <input class="w3-button w3-green" type="submit" value="Edit Genre" />
        </form><br /> <br />

        <a href="categories.php">&larr; Back</a>
    </div>
</body>
</html>

<?php

    }
    else{
        header("Location: index.php");
    }

?>
