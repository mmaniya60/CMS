<?php 
	session_start();

	include_once('includes/connect.php');
	include_once('includes/post.php');

	$limit = 3;

	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}
	else{
		$page = 1;
	}

	$offset = ($page - 1) * $limit;

	$query = $db->prepare("SELECT * FROM post ORDER BY post_id DESC LIMIT {$offset}, {$limit}");
    
	$query->execute();
    	$posts = $query->fetchAll();

	if(isset($_GET['cid'])){
		$cid = $_GET['cid'];
	}
	
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
		<h1><b>Latest Movie Reviews</b></h1>
		<ul style="list-style-type:none;">
			<?php foreach ($posts as $post): ?>
				<li>
					<h2><a href="show.php?id=<?=$post['post_id']?>"><?= $post['movie_title'] ?></a></h2>
				</li>
				<li>
				<small>
                	by <?= $post['posted_by'] ?> on
                	<?= date("F d, Y, h:i a", strtotime($post['posted_on']))?>
            	</small>
				</li>
				<?php if(strlen($post['movie_description']) > 200): ?>
					<li>
						<?= substr($post['movie_description'], 0, 200)?>...<a href="show.php?id=<?=$post['post_id']?>">Read more</a>
					</li>
				<?php else: ?>
					<li><?= $post['movie_description'] ?></li>
				<?php endif ?>
				<?php if(empty($post['movie_image']) === false): ?>
					<li>
						<img src="admin/uploads/<?=$post['movie_image']?>" alt = "<?=$post['movie_image']?>" width="200" height="250" >
					</li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
	</div>
	
		<?php

		$query1 = $db->prepare("SELECT * FROM post");
		$query1->execute();
		$query1->fetchAll();
		
		$post_count = $query1->rowCount();


		if($post_count > 0){

			$total_posts = $post_count;
			$total_page = ceil($total_posts / $limit);

	?>

	<ul class="pagination">

		<?php if($page > 1): ?>
            <li><a href="index.php?page=<?= $page - 1 ?>">&larr;</a></li>
        <?php endif ?>

		<?php for($i = 1; $i <= $total_page; $i++): ?>
			<?php 
				if($i == $page){
					$active = "active";
				}
				else{
					$active = "";
				}
			?>
			
			<li class="<?= $active ?>"><a href="index.php?page=<?= $i ?>&sid=<?= $sid ?>"><?= $i ?></a></li>
		<?php endfor ?>

		<?php if($page < $total_page): ?>
            <li><a href="index.php?page=<?= $page + 1 ?>">&rarr;</a></li>
        <?php endif ?>
	</ul>

	<?php

		}

	?>
</body>
</html>
