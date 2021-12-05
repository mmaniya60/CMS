<?php

    include_once('includes/connect.php');

    if(isset($_GET['id'])){
        $post_id = $_GET['id'];

        $query = $db->prepare("SELECT * FROM comment WHERE post_id = ? ORDER BY comment_id DESC");
        $query->bindValue(1, $post_id);
        $query->execute();

        $comments = $query->fetchAll();
    }
        if(isset($_POST['title'], $_POST['comment'], $_POST['fullname'])){
            $title = $_POST['title'];
            $comment = $_POST['comment'];
            $full_name = $_POST['fullname'];
            $post_id = $_GET['id'];

            if(empty($title) || empty($comment) || empty($full_name) || empty($post_id)){
                $error = "All fields are required!";
            }
            else{
                $query = $db->prepare(" INSERT INTO comment (post_id, name, title, comments) VALUES (?, ?, ?, ?)");

                $query->bindValue(1, $post_id);
                $query->bindValue(2, $full_name);
                $query->bindValue(3, $title);
                $query->bindValue(4, $comment);

                $query->execute();
                header("Location: show.php?id=$post_id");
            }
		
        }
	
	if(isset($_GET['did'])){
        $did = $_GET['did'];

        $query1 = $db->prepare("DELETE FROM comment WHERE comment_id=?");
        $query1->bindValue(1, $did);

        $query1->execute();

        header("Location: show.php?id=$post_id");

?>

<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>Comments</title>
	<link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
    
    <div class="container">
        <h3><b>Comment what you think...</b></h3><br>
        <?php if(isset($error)): ?>
            <div class="w3-panel w3-red">
                <h3>Error Found!</h3>
                <p><?= $error ?></p>
            </div>  
        <?php endif ?>
        <form action="show.php?id=<?= $post_id ?>" method="post" autocomplete="off">
            <input class="w3-input w3-animate-input" type="text" name="fullname" placeholder="Name" style="width:40%"/><br/>
            <input class="w3-input w3-animate-input" type="text" name="title" placeholder="Title" style="width:40%"/><br/>
            <textarea class="w3-input w3-border"cols="54%" rows="5" name="comment" placeholder="Comment"></textarea> <br />
            <input class="w3-button w3-green" type="submit" name="submit" value="Post Comment" />
        </form>
    </div>
    <div class="container">
        <h1><b>Comments</b></h1>

        <ul style="list-style-type:none;">
            <?php foreach ($comments as $comment): ?>
                <li>
                    <h2><?= $comment['title'] ?></h2>
                </li>
                <li>
                    <small>
                        by -
                        <?= $comment['name'] ?> on 
                        <?= date("F d, Y, h:i a", strtotime($comment['comment_time']))?>
			<?php if(isset($_SESSION['logged_in'])): ?>
                            <a href="comments.php?id=<?= $_GET['id'] ?>&did=<?= $comment['comment_id'] ?>">delete</a>
                        <?php endif ?>
                    </small>
                </li>
                <li><?= $comment['comments'] ?></li>
            <?php endforeach ?>
        </ul>
    </div>
</body>
</html>
