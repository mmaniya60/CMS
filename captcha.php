<?php
    
    session_start();

    $random_num = rand(111111, 999999);
    $_SESSION['captcha'] = $random_num;

    $layer = imagecreatetruecolor(80, 30);
    $bg = imagecolorallocate($layer, 255, 160, 120);
    imagefill($layer, 0, 0, $bg);
    
    $text_color = imagecolorallocate($layer, 0, 0, 0);
    imagestring($layer, 5, 5, 5, $random_num, $text_color);
    header('Content-Type: image/jpeg');
    imagejpeg($layer);

?>