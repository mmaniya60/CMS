<?php 

    session_start();

	include_once('../includes/connect.php');
    include_once('../includes/post.php');

    if(isset($_SESSION['logged_in'])){

        if(isset($_POST['genre'])){
            $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_STRING);

            if(empty($genre)){
                $error = "Field is required!";
            }
            else{

                $slug = slug($genre);
                $query = $db->prepare("INSERT INTO genre (genres, genre_slug) VALUES (?, ?);");
                $query->bindValue(1, $genre);
                $query->bindValue(2, $slug);

                $query->execute();

                header("Location: categories.php");
            }
        }

?>


<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>Add Genre</title>
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
        <h2>Add Genre </h2><br />
        <?php if(isset($error)): ?>
            <div class="w3-panel w3-red">
                <h3>Error Found!</h3>
                <p><?= $error ?></p>
            </div>
        <?php endif ?>
        <form action="add_category.php" method="post" autocomplete="off">
            <input class="w3-input" type="text" name="genre" placeholder="Genre" /><br /><br />
            <input class="w3-button w3-green" type="submit" name="submit" value="Add Genre" />
        </form>
        <br /><br />

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
