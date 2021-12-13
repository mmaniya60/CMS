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
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$id || empty($id)) {
                echo '<script>alert("Incorrect has been ID passed!!!")</script>';
                exit;
            }
            else{
                $data = $post->fetch_join_data($id);
            }
        }

        if(isset($_POST['title'], $_POST['year'], $_POST['description'])){
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
            $description = $_POST['description']; //Can not validate due to the use of WYSIWYG
            $genre = filter_input(INPUT_POST, 'genre', FILTER_VALIDATE_INT);
            $year = filter_var($_POST['year'], FILTER_VALIDATE_INT, array("options"=> array("min_range"=>0000, "max_range"=> date('Y'))));
            $old_img = $_POST['old_image'];


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

            if(isset($_POST['delete'])){
                $data = $post->fetch_join_data($id);
                unlink("uploads/".$data['movie_image']);
            }

            if(empty($title) || empty($description) || empty($genre)){
                $error = "All fields are required!";
                $data = $post->fetch_join_data($id);
            }
            else if(in_array($file_ext, $extensions) === false){
                $error = "File extension is not allowed, Please choose an image with JPG or PNG extension.";
                $data = $post->fetch_join_data($id);
            }
            else if ($year === false) {
                $error = "Movie Year is invalid!";
                $data = $post->fetch_join_data($id);
            }
            else{
                
                $slug = slug($title);

                $query1 = $db->prepare("UPDATE post SET movie_slug=?, movie_title=? , movie_year=?, movie_description=?, movie_image=?, genre_id=?  WHERE post_id = ?");

                $query1->bindValue(1, $slug);
                $query1->bindValue(2, $title);
                $query1->bindValue(3, $year);
                $query1->bindValue(4, $description);

                if(empty($file_name)){
                    $query1->bindValue(5, $old_img);
                }
                else{
                    $query1->bindValue(5, $file_name);
                }
                
                $query1->bindValue(6, $genre);
                $query1->bindValue(7, $id);

                $query1->execute();

                if($_POST['delete'] == 'yes'){
                    $img_del = $db->prepare("UPDATE post SET movie_image = '' WHERE post_id = $id");
                    $img_del->execute();
                }

                if($genre !== $data['genre_id']){

                    $genre_id = $data['genre_id'];

                    $query5 = $db->prepare("UPDATE genre SET post = post - 1 WHERE genre_id = ?");
                    $query5->bindValue(1, $genre_id);
                    $query5->execute();

                    $query4 = $db->prepare("UPDATE genre SET post = post + 1 WHERE genre_id = ?");
                    $query4->bindValue(1, $genre);
                    $query4->execute();
                }

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
            <?php if(!empty($data['movie_image'])): ?>
            <input type="checkbox" name="delete" value="yes" />
            <label>delete image?</label>
            <?php endif ?>
            <br /> <br />
            <input type="hidden" name="old_image" value="<?= $data['movie_image'] ?>"/>
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
