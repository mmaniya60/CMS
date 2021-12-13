<?php
    session_start();

    include_once('includes/connect.php');
    include_once('includes/post.php');
    
    $query = $db->prepare("SELECT * FROM genre WHERE post > 0");

	$query->execute();
	$genres = $query->fetchAll();

    $value = '';
	$action = '';
	$value1 = '';
	if(isset($_SESSION['logged_in'])){
		$value = 'Logout';
		$value1 = 'Dashboard';
		$action = 'admin/logout.php';
	}
	else if(isset($_SESSION['user_login'])){
		$value = 'Logout';
		$value1 = 'Watchlist';
		$action = 'admin/logout.php';
	}
	else{
		$value = 'Login';
		$value1 = 'Watchlist';
		$action = 'admin/index.php';
	}

    $post = new Post;

    if(isset($_GET['id'], $_GET['slug'])){
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $slug = filter_input(INPUT_GET, 'slug', FILTER_SANITIZE_STRING);

    	if (!$id || !$slug || empty($id) || empty($slug)) {
            header("HTTP/1.0 404 Not Found");
        	exit;
    	}
        else{
            $data = $post->fetch_data($id, $slug);
            $num = $post->row_count($id, $slug);

            if($num == 0){
                header("HTTP/1.0 404 Not Found");
        	    exit;
            }
        }
        
?>
<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title><?= $data['movie_title'] ?></title>
	<link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
<body>
	<div class="w3-top w3-black">
		<a href="index.php" class="w3-bar-item w3-button">Home</a>
		<a href="admin/index.php" class="w3-bar-item w3-button"><?= $value1 ?></a>
        <div class="w3-dropdown-hover w3-mobile">
            <button class="w3-button">Genres <em class="fa fa-caret-down"></em></button>
            <div class="w3-dropdown-content w3-bar-block w3-black">
                <?php foreach ($genres as $genre): ?>
                    <a href="categories.php?cid=<?= $genre['genre_id'] ?>&slug=<?= $genre['genre_slug'] ?>" class="w3-bar-item w3-button w3-mobile" ><?= $genre['genres'] ?></a>
                <?php endforeach ?>
            </div>
		</div>
        <?php if((isset($_SESSION['logged_in'])) || (isset($_SESSION['user_login']))): ?>
			<a href="<?= $action ?>" onclick="<?php $click ?>" class="w3-bar-item w3-button w3-green w3-right"><?= $value ?></a>
		<?php else: ?>
			<a href="admin/users/add_users.php" onclick="<?php $click ?>" class="w3-bar-item w3-button w3-green w3-right">Sign Up</a>
			<a href="<?= $action ?>" onclick="<?php $click ?>" class="w3-bar-item w3-button w3-green w3-right"><?= $value ?></a>
		<?php endif ?>
	</div>
    
	<form class="search" action="search.php" method="get" autocomplete="off">
		<input type="text" name="search" placeholder="Search by Title...">
		<button type="submit" class="w3-bar-item w3-button w3-green">Go</button>
	</form>

	<div class="container">
            <h2><?= $data['movie_title'] ?></a></h2>
            <small>
                by <?= $data['posted_by'] ?> on
                <?= date("F d, Y, h:i a", strtotime($data['posted_on']))?>
            </small>
            <br />
            
            <p>
                <?= $data['movie_description'] ?>
            </p>
            <?php if(empty($data['movie_image']) === false): ?>
                <img src="admin/uploads/<?=$data['movie_image']?>" alt = "<?=$data['movie_image']?>" width="400" height="500" >
			<?php endif ?>
            <br /><br />
            <a href="index.php">&larr; Back</a>
            <?php
               include('comments.php');
             ?>
	</div>
</body>
</html>

<?php
    }
    else{
        header('Location: index.php');
        exit();
    }

?>
