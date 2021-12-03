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

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $data = $post->fetch_data($id);

        
?>
<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>Home - Movie CMS</title>
	<link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
	<div class="w3-top w3-black">
		<a href="index.php" class="w3-bar-item w3-button">Home</a>
		<a href="admin/index.php" class="w3-bar-item w3-button"><?= $value1 ?></a>
        <div class="w3-dropdown-hover w3-mobile">
            <button class="w3-button">Genres <em class="fa fa-caret-down"></em></button>
            <div class="w3-dropdown-content w3-bar-block w3-black">
                <?php foreach ($genres as $genre): ?>
                    <a href="categories.php?cid=<?= $genre['genre_id'] ?>" class="w3-bar-item w3-button w3-mobile" ><?= $genre['genres'] ?></a>
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
    
	<form class="search" action="search.php?search=" method="get" autocomplete="off">
		<input type="text" name="search" placeholder="Search...">
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