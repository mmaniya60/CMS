<?php

session_start();

include_once('../../includes/connect.php');


    if(isset($_SESSION['logged_in'])){
        if(isset($_GET['id'])){
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$id || empty($id)) {
                echo '<script>alert("Incorrect ID passed!!!")</script>';
                exit;
            }

            $query = $db->prepare("DELETE FROM user WHERE user_id = ?");
            $query->bindValue(1, $id);
            $query->execute();
            $users = $query->fetch();

            header("Location: manage_users.php");
        }
    }
    else{
        header("Location: ../index.php");
    }

?>
