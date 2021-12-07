<?php 

    session_start();

	include_once('../includes/connect.php');
	include_once('../includes/post.php');

    $post = new Post;

    $query = $db->prepare("SELECT * FROM genre");
    $query->execute();
    $genres = $query->fetchAll();

    if(isset($_SESSION['logged_in'])){
        if(isset($_GET['id'])){
            $id = $_GET['id'];

            $data = $post->fetch_join_data($id);
        }

        if(isset($_POST['title'], $_POST['year'], $_POST['description'])){
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $genre = $_POST['genre'];
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

            if(empty($title) || empty($description) || empty($genre)){
                $error = "All fields are required!";
                $data = $post->fetch_join_data($id);
            }
            else if(in_array($file_ext, $extensions) === false){
                $error = "File extension is not allowed, Please choose an image with JPG or PNG extension.";
                $data = $post->fetch_join_data($id);
            }
            else{
                
                $query1 = $db->prepare("UPDATE post SET movie_title=? , movie_year=?, movie_description=?, movie_image=?, genre_id=?  WHERE post_id = ?");

                $query1->bindValue(1, $title);
                $query1->bindValue(2, $year);
                $query1->bindValue(3, $description);
                $query1->bindValue(4, $file_name);
                $query1->bindValue(5, $genre);
                $query1->bindValue(6, $id);

                $query4 = $db->prepare("UPDATE genre SET post = post + 1 WHERE genre_id = $genre");

                $query4->execute();
                $query1->execute();

                header("Location: update.php");
            }
        }
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
        <h2>Add a Movie Review </h2><br />
        <?php if(isset($error)): ?>
            <div class="w3-panel w3-red">
                <h3>Error Found!</h3>
                <p><?= $error ?></p>
            </div>  
        <?php endif ?>
        <form action="process_update.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <input class="w3-input" type="hidden" name="id" value="<?= $data['post_id'] ?>" />
            <input class="w3-input" type="text" name="title" value="<?= $data['movie_title'] ?>" /><br /><br />
            <input class="w3-input" type="text" name="year" value="<?= $data['movie_year'] ?>" /><br /><br />

            <select class="w3-select" name="genre">
                <option value="<?= $data['genre_id'] ?>" selected><?= $data['genres'] ?></option>
                <?php foreach($genres as $genre): ?>
                    <option value="<?= $genre['genre_id'] ?>"><?= $genre['genres'] ?></option>
                <?php endforeach ?>
            </select><br /><br />
            <label>Movie Description:</label><br />
            <textarea id="description" name="description"><?= $data['movie_description'] ?></textarea><br /><br />
            <input type="file" name="image" />  <br /> <br /> 
            <p><?= $data['movie_image'] ?></p>
            <img src="uploads/<?= $data['movie_image'] ?>" alt="<?= $data['movie_image'] ?>" height="150px" />
            <br /> <br />
            <input type="hidden" name="old_image"  value="<?= $data['movie_image'] ?>"/>
            <input class="w3-button w3-green" type="submit" value="Update Review" />
        </form>
        <br /><br />
        <a href="update.php">&larr; Back</a>
    </div>

    <script src="ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description');
    </script>

</body>
</html>

<?php
    }
    else{
        header("Location: index.php");
    }

?>
