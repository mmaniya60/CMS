<?php 
	session_start();

	include_once('includes/connect.php');
	include_once('includes/post.php');

	$limit = 3;

	$header = "HTTP/1.0 404 Not Found";

	if(isset($_GET['page'])){
		$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    	if (!$page || empty($page)) {
        	header($header);
        	exit;
    	}
	}
	else{
		$page = 1;
	}

	$offset = ($page - 1) * $limit;

	$query = $db->prepare("SELECT * FROM post ORDER BY post_id DESC LIMIT {$offset}, {$limit}");
    
	$query->execute();
    $posts = $query->fetchAll();

	$name = "Sort by";

	if((isset($_SESSION['logged_in']) || isset($_SESSION['user_login'])) && isset($_GET['sid'], $_GET['slug'])){
		$sid = filter_input(INPUT_GET, 'sid', FILTER_VALIDATE_INT);
		$slug = filter_input(INPUT_GET, 'slug', FILTER_SANITIZE_STRING);

    	if (!$sid || !$slug || empty($sid) || empty($slug)) {
        	header($header);
        	exit;
    	}

		if($sid == '1' && $slug == 'title'){
			$name = "Sort by: Title";
			$query1 = $db->prepare("SELECT * FROM post ORDER BY movie_title ASC LIMIT {$offset}, {$limit}");

			$query1->execute();
			$posts = $query1->fetchAll();
		}
		else if($sid == '2' && $slug == 'date-posted'){
			$name = "Sort by: Date Posted";
			$query1 = $db->prepare("SELECT * FROM post ORDER BY posted_on ASC LIMIT {$offset}, {$limit}");

			$query1->execute();
			$posts = $query1->fetchAll();
		}
		else if($sid == '3' && $slug == 'latest-released'){
			$name = "Sort by: Latest Released";
			$query1 = $db->prepare("SELECT * FROM post ORDER BY movie_year DESC LIMIT {$offset}, {$limit}");

			$query1->execute();
			$posts = $query1->fetchAll();
		}
	}

	$query2 = $db->prepare("SELECT * FROM genre WHERE post > 0");

	$query2->execute();
	$genres = $query2->fetchAll();


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
	<title>Movie CMS - Home</title>
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
                    <a href="categories.php?cid=<?= $genre['genre_id'] ?>&slug=<?= $genre['genre_slug'] ?>" class="w3-bar-item w3-button w3-mobile" ><?= $genre['genres'] ?></a>
                <?php endforeach ?>
            </div>
		</div>

		<?php if(isset($_SESSION['logged_in']) || isset($_SESSION['user_login'])): ?>
			<div class="w3-dropdown-hover w3-mobile">
				<button class="w3-button"><?= $name ?><em class="fa fa-caret-down"></em></button>
				<div class="w3-dropdown-content w3-bar-block w3-black">
					<a href="index.php?sid=1&slug=title" class="w3-bar-item w3-button w3-mobile" >Title</a>
					<a href="index.php?sid=2&slug=date-posted" class="w3-bar-item w3-button w3-mobile" >Date Posted (old to new)</a>
					<a href="index.php?sid=3&slug=latest-released" class="w3-bar-item w3-button w3-mobile" >Latest Released (latest to oldest)</a>
				</div>
			</div>
		<?php endif ?>

		<?php if((isset($_SESSION['logged_in'])) || (isset($_SESSION['user_login']))): ?>
			<a href="<?= $action ?>" class="w3-bar-item w3-button w3-green w3-right"><?= $value ?></a>
		<?php else: ?>
			<a href="admin/users/add_users.php" class="w3-bar-item w3-button w3-green w3-right">Sign Up</a>
			<a href="<?= $action ?>" class="w3-bar-item w3-button w3-green w3-right"><?= $value ?></a>
		<?php endif ?>
	</div>

	<form class="search" action="search.php" method="get" autocomplete="off">
		<input type="text" name="search" placeholder="Search by Title...">
		<button type="submit" class="w3-bar-item w3-button w3-green">Go</button>
	</form>

	<div class="container">
		<h1><b>Movie Reviews</b></h1>
		<ul style="list-style-type:none;">
			<?php foreach ($posts as $post): ?>
				<li>
					<h2><a href="show.php?id=<?=$post['post_id']?>&slug=<?=$post['movie_slug']?>"><?= $post['movie_title'] ?></a></h2>
				</li>
				<li>
				<small>
                	by <?= $post['posted_by'] ?> on
                	<?= date("F d, Y, h:i a", strtotime($post['posted_on']))?>
            	</small>
				</li>
				<?php if(strlen($post['movie_description']) > 200): ?>
					<li>
						<?= substr($post['movie_description'], 0, 200)?>...<a href="show.php?id=<?=$post['post_id']?>&slug=<?=$post['movie_slug']?>">Read more</a>
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
			
			<?php if(isset($_GET['sid'])): ?>
				<li class="<?= $active ?>"><a href="index.php?page=<?= $i ?>&sid=<?= $sid ?>"><?= $i ?></a></li>
			<?php else: ?>
				<li class="<?= $active ?>"><a href="index.php?page=<?= $i ?>"><?= $i ?></a></li>
			<?php endif ?>
		
		<?php endfor ?>

		<?php if($page < $total_page): ?>
            <li><a href="index.php?page=<?= $page + 1 ?>">&rarr;</a></li>
        <?php endif ?>
	</ul>

	<?php

		}
		if($page > $total_page){
			header($header);
        	exit;
		}
	?>

</body>
</html>
