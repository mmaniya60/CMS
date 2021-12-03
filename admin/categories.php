<?php

    session_start();

    include_once('../includes/connect.php');

    if(isset($_SESSION['logged_in'])){
        $query = $db->prepare("SELECT * FROM genre");
        $query->execute();
        $genres = $query->fetchAll();

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
        <?php if(isset($error)): ?>
            <div class="w3-panel w3-red">
                <h3>Error Found!</h3>
                <p><?= $error ?></p>
            </div>
        <?php endif ?>
        
        <main>
            <div>
                <section>
                <div>
                    <h2>All Genres</h2>
                    <a href="add_category.php">Add a New Genre &rarr;</a>
                    <br /><br />

                    <table>
                    <thead>
                        <tr>
                            <th>Genres</th>
                            <th>No. of posts</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($genres as $genre): ?>
                        <tr>
                        <td><?= $genre['genres'] ?></td>
                        <td><?= $genre['post'] ?></td>
                        <td><a href="edit_category.php?id=<?= $genre['genre_id'] ?>">Edit</a></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                    </table>
                </div>
                </section>
            </div>
        </main>

        <br /><br />
        <a href="add_category.php">Add a New Genre &rarr;</a>

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