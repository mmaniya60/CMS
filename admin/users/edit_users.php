<?php

session_start();

include_once('../../includes/connect.php');
include_once('../../includes/post.php');

$user = new Post;

    if(isset($_SESSION['logged_in'])){
        if(isset($_GET['id'])){

            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$id || empty($id)) {
                echo '<script>alert("Incorrect has been ID passed!!!")</script>';
                exit;
            }

            $users = $user->fetch_user($id);
        }

            if(isset($_POST['username'], $_POST['username'], $_POST['fullname'], $_POST['email'])){

                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $role = filter_input(INPUT_POST, 'role', FILTER_VALIDATE_INT);


                if(empty($username) || empty($fullname) || empty($email)){
                    $error = "All fields are required!";

                    $users = $user->fetch_user($id);
                }
                else if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
                    $error = "Incorrect email format! Please retry.";
                    
                    $users = $user->fetch_user($id);
                }
                else{
                    $query1 = $db->prepare("UPDATE user SET username=?, full_name=?, email=?, role=? WHERE user_id = ?;");
                    $query1->bindValue(1, $username);
                    $query1->bindValue(2, $fullname);
                    $query1->bindValue(3, $email);
                    $query1->bindValue(4, $role);
                    $query1->bindValue(5, $id);

                    $query1->execute();

                    header("Location: manage_users.php");
                }
            }


?>

<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>Edit Genre</title>
	<link rel="stylesheet" href="../../styles/style.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

	<div class="w3-top w3-black">
	<a href="../../index.php" class="w3-bar-item w3-button">Home</a>
    <a href="../index.php" class="w3-bar-item w3-button">Dashboard</a>
    <a href="../logout.php" onclick="return confirm('Are you sure?')" class="w3-bar-item w3-button w3-green w3-right">Logout</a>
	</div>
    
    <div class="container">
        <h2>Edit User</h2><br />
        <?php if(isset($error)): ?>
            <div class="w3-panel w3-red">
                <h3>Error Found!</h3>
                <p><?= $error ?></p>
            </div>
        <?php endif ?>

        <form action="edit_users.php" method="post" autocomplete="off">
            <input type="hidden" name="id" value="<?= $users['user_id'] ?>" />
            <input class="w3-input" type="text" name="username" value="<?= $users['username'] ?>" /><br /><br />
            <input class="w3-input" type="text" name="fullname" value="<?= $users['full_name'] ?>" /><br /><br />
            <input class="w3-input" type="text" name="email" value="<?= $users['email'] ?>" /><br /><br />
            <?php 
                if($users['role'] == 0){
                    $role = "User";
                    $role1 = "Admin";
                    $value = "1";
                }
                else{
                    $role = "Admin";
                    $role1 = "User";
                    $value = "0";
                }
            ?>
            <select class="w3-select" name="role">
                <option value="<?= $value ?>" selected><?= $role ?></option>
                <option value="<?= $value ?>"><?= $role1 ?></option>
            </select><br /><br />
            <input class="w3-button w3-green" type="submit" value="Edit User" />
        </form><br /> <br />

        <a href="manage_users.php">&larr; Back</a>
    </div>
</body>
</html>

<?php

    }
    else{
        header("Location: ../index.php");
    }

?>
