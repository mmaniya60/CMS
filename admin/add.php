<?php 

    session_start();

	include_once('../includes/connect.php');

    $query = $db->prepare("SELECT * FROM genre");
    $query->execute();
    $genres = $query->fetchAll();

    if(isset($_SESSION['logged_in'])){

        if(isset($_POST['title'], $_POST['year'], $_POST['description'])){
            $title = $_POST['title'];
            $description = $_POST['description'];
            $movie_genre = $_POST['genre'];
            $year = $_POST['year'];

            if(isset($_FILES['image'])){
                $file_name = $_FILES['image']['name'];
                $file_size = $_FILES['image']['size'];
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_type = $_FILES['image']['type'];
                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $extensions = array("jpeg", "jpg", "png", "");
    
                if(in_array($file_ext, $extensions) === true){
                    move_uploaded_file($file_tmp, "uploads/".$file_name);
                }
            }

            if(empty($title) || empty($description) || empty($movie_genre)){
                $error = " All fields are required!";
            }
            else if(in_array($file_ext, $extensions) === false){
                $error = "File extension is not allowed, Please choose an image with JPG or PNG extension.";
            }
            else{

                $query = $db->prepare("INSERT INTO post (genre_id, movie_title, movie_year, movie_description, movie_image, posted_by) VALUES (?, ?, ?, ?, ?, ?);");

                $query->bindValue(1, $movie_genre);
                $query->bindValue(2, $title);
                $query->bindValue(3, $year);
                $query->bindValue(4, $description);
                $query->bindValue(5, $file_name);
                $query->bindValue(6, $_SESSION['logged_in']);

                $query1 = $db->prepare("UPDATE genre SET post = post + 1 WHERE genre_id = $movie_genre");


                $query->execute();
                $query1->execute();

                header("Location: index.php");
            }
        }
?>

<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>Add Review</title>
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
        <h2>Add a Movie Review </h2><br />
        <?php if(isset($error)): ?>
            <div class="w3-panel w3-red">
                <h3>Error Found!</h3>
                <p><?= $error ?></p>
            </div>
        <?php endif ?>
        <form action="add.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <input class="w3-input"  type="text" name="title" placeholder="Movie Title" /><br /><br />
            <input class="w3-input" type="text" name="year" placeholder="Movie Year" /><br /><br />
            <select class="w3-select" name="genre">
                <option value="" disabled selected>Select Genre</option>
                <?php foreach($genres as $genre): ?>
                    <option value="<?= $genre['genre_id'] ?>"><?= $genre['genres'] ?></option>
                <?php endforeach ?>
            </select><br /><br />
            <textarea class="w3-input w3-border" rows="20" cols="50" type="text" name="description" placeholder="Movie Review"></textarea><br /><br />
            <input type="file" name="image"/><br /><br />
            <input class="w3-button w3-green" type="submit" name="submit" value="Add Review" />
        </form>
        <br /><br />

        <a href="index.php">&larr; Back</a>
    </div>
</body>
</html>

<?php
    }
    else{
        header("Location: index.php");
    }
?>