<?php
    session_start();

    include_once('../../includes/connect.php');
    
    if(isset($_POST['save'])){
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $username = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        if(empty($name) || empty($username) || empty($password) || empty($password1) || empty($email)){
            $error = "All fields are required!";
        }
        else if($password != $password1){
            $error = "Password does not match!";
        }
        else if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            $error = "Incorrect email format! Please retry.";
        }
        else{

            $query = $db->prepare("SELECT username FROM user WHERE username = ?");
            $query->bindValue(1, $username);
            $query->execute();

            $num = $query->rowCount();

            if($num > 0){
                $error = 'Username already exists.';
            }
            else{
                $query1 = $db->prepare("INSERT INTO user (username, password, full_name, email) VALUES (?, ?, ?, ?)");
                
                $query1->bindValue(1, $username);
                $query1->bindValue(2, password_hash($password, PASSWORD_DEFAULT));
                $query1->bindValue(3, $name);
                $query1->bindValue(4, $email);

                $query1->execute();

                header("Location: ../index.php");
            }


        }
    }

?>

<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>User Sign In</title>
	<link rel="stylesheet" href="../../styles/style.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
    <div class="w3-top w3-black">
		<a href="../../index.php" class="w3-bar-item w3-button">Home</a>
	</div>
    
    <div class="container">
        <h2>User Sign In</h2>
        <?php if(isset($error)): ?>
            <div class="w3-panel w3-red">
                <h3>Error Found!</h3>
                <p><?= $error ?></p>
            </div>
        <?php endif ?>

        <form  action="add_users.php" method ="POST" autocomplete="off">
            <input type="text" name="name" placeholder="Name" class="w3-input w3-animate-input" style="width:40%"><br /><br />
            <input type="text" name="user" placeholder="Username" class="w3-input w3-animate-input" style="width:40%"><br /><br />
            <input type="password" name="password" placeholder="Password" class="w3-input w3-animate-input" style="width:40%" ><br /><br />
            <input type="password" name="password1" placeholder="Confirm Password" class="w3-input w3-animate-input" style="width:40%"><br /><br />
            <input type="text" name="email" placeholder="E-mail" class="w3-input w3-animate-input" style="width:40%"><br /><br />
            <input class="w3-button w3-green" type="submit" name="save" value="Create Account"/><br /><br />
        </form>
        <br /> <br />
        <a href="../index.php">Already have an account?</a>
        
        <br /><br />
        <a href="../../index.php">&larr; Back</a>
    </div>
</body>
</html>
