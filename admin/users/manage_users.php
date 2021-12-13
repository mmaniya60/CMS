<?php

    session_start();

    include_once('../../includes/connect.php');

    if(isset($_SESSION['logged_in'])){
        $query = $db->prepare("SELECT * FROM user");

        $query->execute();
        $users = $query->fetchAll();

?>

<!DOCTYPE html>
<html lang=en>
<head>
	<meta charset="utf-8">
	<title>Manage Users</title>
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
                        <h2>All Users</h2>
                        <a href="add_users.php">Add User</a>
                        <br /><br />

                        <table>
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>User Name</th>
                                    <th>Role</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['full_name'] ?></td>
                                        <td><?= $user['username'] ?></td>
                                        <?php if($user['role'] == 0): ?>
                                            <td>User</td>
                                        <?php else: ?>
                                            <td>Admin</td>
                                        <?php endif ?>
                                        <td><a href="edit_users.php?id=<?= $user['user_id'] ?>">Edit</a></td>
                                        <td><a href="delete_users.php?id=<?= $user['user_id'] ?>">Delete</a></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>

        <br /><br />
        <a href="../index.php">&larr; Back</a>
    </div>
</body>
</html>

<?php

    }
    else{
        header("Location: index.php");
    }

?>
