<?php 

    session_start();

	include_once('../includes/connect.php');
    include_once('../includes/post.php');

    $post = new Post;

    if(isset($_SESSION['logged_in'])){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            
            $query = $db->prepare("SELECT * FROM post WHERE post_id = ?");
            $query->bindValue(1, $id);
            $query->execute();

            header("Location: delete.php");
        }
        $posts = $post->fetch_all();

?>

<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>Update Review</title>
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
        <h2>Delete a Movie Review </h2><br />
        <?php if(isset($error)): ?>
            <div class="w3-panel w3-red">
                <h3>Error Found!</h3>
                <p><?= $error ?></p>
            </div>  
        <?php endif ?>
        <form action="process_update.php" method="get">
            <select onchange="this.form.submit();" name="id" class="w3-select">
                    <option value="" disabled selected>
                        Select a Movie Title
                    </option>
                <?php foreach($posts as $post): ?>
                    <option value="<?=$post['post_id']?>">
                        <?= $post['movie_title'] ?>
                    </option>
                <?php endforeach ?>
            </select>
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