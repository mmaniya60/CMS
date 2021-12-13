<?php 

    session_start();

	include_once('../includes/connect.php');



    if(isset($_SESSION['logged_in'])){
?>

<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>Admin Dashboard</title>
	<link rel="stylesheet" href="../styles/style.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

	<div class="w3-top w3-black">
	<a href="../index.php" class="w3-bar-item w3-button">Home</a>
    <a href="logout.php" onclick="return confirm('Are you sure?')" class="w3-bar-item w3-button w3-green w3-right">Logout</a>
	</div>

    <div class="container">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="add.php">Add a Movie Review</a></li>
            <li><a href="update.php">Update a Movie Review</a></li>
            <li><a href="delete.php">Delete a Movie Review</a></li>
            <li><a href="categories.php">Add or Update Genres</a></li>
            <li><a href="users/manage_users.php">Manage Users</a></li>
            <li><a href="logout.php" onclick="return confirm('Are you sure?')">Logout</a></li>

        </ul>
    </div>
</body>
</html>

<?php
    }

    else if(isset($_SESSION['user_login'])){
?>

<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>User Account Info</title>
	<link rel="stylesheet" href="../styles/style.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

	<div class="w3-top w3-black">
	<a href="../index.php" class="w3-bar-item w3-button">Home</a>
    <a href="logout.php" onclick="return confirm('Are you sure?')" class="w3-bar-item w3-button w3-green w3-right">Logout</a>
	</div>

    <div class="container">
        <h2>User Account Info</h2>
        <ul>
            <li><a href="users/watchlist.php">Watchlist</a></li>
            <li><a href="logout.php" onclick="return confirm('Are you sure?')">Logout</a></li>

        </ul>
    </div>
</body>
</html>

<?php
    }
    else{
            if(isset($_POST['username']) && isset($_POST['password'])){
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

                if(empty($username) || empty($password)){
                    $error = "All fields are required!";
                }
                else{
                    $query = $db->prepare("SELECT * FROM user WHERE username = ?");
                    $query->bindValue(1, $username);

                    $query->execute();
                    $data = $query->fetch();

                    if(password_verify($password, $data['password'])){
                        if($data['role'] == "1"){
                            $_SESSION['logged_in'] = $username;
                            echo '<h3>Admin Logged in Successfully</h3><br /> <p>Please wait until we redirect you to the dashbord.</p>';
                            header("refresh:3;url=index.php");
                            exit();
                        }
                        else if($data['role'] == "0"){
                            $_SESSION['user_login'] = $username;
                            echo '<h3>User Logged in Successfully</h3><br /> <p>Please wait until we redirect you to the dashbord.</p>';
                            header("refresh:3;url=index.php");
                            exit();
                        }
                    }
                    else{
                        $error = "Incorrect details!";
                    }
                }
            }
?>

<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>Admin Login</title>
	<link rel="stylesheet" href="../styles/style.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
    <div class="w3-top w3-black">
		<a href="../index.php" class="w3-bar-item w3-button">Home</a>
	</div>

    <div class="container">
        <h2>Enter Login Information:</h2><br>
        <?php if(isset($error)): ?>
            <div class="w3-panel w3-red">
                <h3>Error Found!</h3>
                <p><?= $error ?></p>
            </div>          
        <?php endif ?>
        <form action="index.php" method="post" autocomplete="off">
            <input type="text" name="username" placeholder="Username" class="w3-input w3-animate-input" style="width:40%"/><br/>
            <input type="password" name="password" placeholder="Password" class="w3-input w3-animate-input" style="width:40%"/><br/>
            <input class="w3-button w3-black" type="submit" value="Login" />
        </form>
        <br /><br />
        <a href="users/add_users.php">Sign Up &rarr;</a>

        <br /><br />
        <a href="../index.php">&larr; Return to the feed</a>
    </div>
</body>
</html>

<?php
    }
?>
