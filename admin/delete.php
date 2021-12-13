<?php 

    session_start();

	include_once('../includes/connect.php');
    include_once('../includes/post.php');

    $post = new Post;

    if(isset($_SESSION['logged_in'])){
        if(isset($_GET['id'])){
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$id || empty($id)) {
                echo '<script>alert("Incorrect has been ID passed!!!")</script>';
                exit;
            }

            $query1 = $db->prepare("SELECT * FROM post WHERE post_id = ?");
            $query1->bindValue(1, $id);
            $query1->execute();
            $post = $query1->fetch();
            unlink("uploads/".$post['movie_image']);

            $query = $db->prepare("DELETE FROM post WHERE post_id = ?");
            $query->bindValue(1, $id);
            $query->execute();

            

            $sql = $db->prepare("UPDATE genre SET post = post - 1 WHERE genre_id = ?");
            $sql->bindValue(1, $post['genre_id']);
            $sql->execute();

            $query1 = $db->prepare("DELETE FROM comment WHERE post_id = ?");
            $query1->bindValue(1, $id);
            $query1->execute();            

            header("Location: delete.php");
        }

        $posts = $post->fetch_all();

?>

<!DOCTYPE html>
<html lang=en  >
<head>
	<meta charset="utf-8">
	<title>Delete Review</title>
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
        <h2>Delete a Movie Review</h2><br />
        <form action="delete.php" method="get">
            <select name="id" class="w3-select">
                    <option value="" disabled selected>
                        Select a movie title
                    </option>
                <?php foreach($posts as $post): ?>
                    <option value="<?=$post['post_id']?>">
                        <?= $post['movie_title'] ?>
                    </option>
                <?php endforeach ?>
            </select><br /> <br />
            <input class="w3-button w3-green" type="submit" name="delete" value="Delete" onclick="this.form.submit();" />
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
