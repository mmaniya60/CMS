<?php 

    session_start();

	include_once('../../includes/connect.php');

    if(isset($_SESSION['user_login'])){
?>

<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>User Account</title>
	<link rel="stylesheet" href="../../styles/style.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

	<div class="w3-top w3-black">
	<a href="../../index.php" class="w3-bar-item w3-button">Home</a>
    <a href="../index.php" class="w3-bar-item w3-button">Account Info</a>
    <a href="logout.php" onclick="return confirm('Are you sure?')" class="w3-bar-item w3-button w3-green w3-right">Logout</a>
	</div>

    <div class="container">
        <h2>User Account</h2>
        <ul>
            <li><a href="#">Under Development</a></li>

        </ul>
    </div>
</body>
</html>

<?php

    }
    else{
        header("Location: ../index.php");
    }

?>