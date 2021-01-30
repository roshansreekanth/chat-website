<?php

    $db = mysqli_connect ("127.0.0.1", "dbnme", "password");

    if (!$db)
    {
        echo "Sorry! Can't connect to database\n";
        exit();
    }
    
    $charset_set = mysqli_set_charset ($db, 'utf8');
    
    if (!$charset_set)
    {
        echo "Sorry! Can't set character set\n";
        exit();
    }
    
    if (!mysqli_select_db ($db, "R00170592_db"))
    {
        echo "Sorry! Can't select database\n";
        exit();
    }
    
    $result = mysqli_query ($db, "SELECT * FROM messages ORDER BY time DESC LIMIT 20;");
    if(!$result)
    {
        echo "Database Error!\n";
    }

    while ($message = mysqli_fetch_array ($result))
    {

        $decoded_message = urldecode($message['message']); // Decodes the message (eg. %20 becomes a space)
        $user_query = mysqli_query($db, "SELECT * FROM users WHERE id = {$message['user']}");
        $user = mysqli_fetch_array($user_query);
        $avatar_tag = "<img src=\"{$user['avatar']}\" alt=\"Display picture\" style=\"width:75px;height:75px;border-radius:50%;float:left; margin-right:20px;\">"; // Makes the picture fit in a circle
        
        if($user['avatar'] == null) // If the user has no avatar
        {
            $avatar_tag = "<span style=\"float:left;\"></span>";
        }

        echo "<div style=\"border: 2px solid #dedede; background-color: #f1f1f1; border-radius: 5px; padding: 30px; margin: 10px auto; width:25%;\">
            {$avatar_tag}
            <p style=\"font-weight:bold;\">{$user['name']}:</p>
            <p>{$decoded_message}</p>
            <span style=\"float: right;color: #aaa;\">{$message['time']}</span></div>";
    }
?>