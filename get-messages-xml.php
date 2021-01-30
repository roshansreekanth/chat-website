<?php

    header("Content-type: text/xml");

    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
    header('Cache-Control: no-store, no-cache, must-revalidate'); 
    header('Cache-Control: post-check=0, pre-check=0', FALSE); 
    header('Pragma: no-cache');

    $db = mysqli_connect ("127.0.0.1", "dbname", "password");

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
    
    echo "<results>";

    $result = mysqli_query ($db, "SELECT * FROM messages WHERE id > {$_GET['message_id']} ORDER BY time DESC;");
    $message_id = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM messages ORDER BY id DESC LIMIT 1;"))['id']; // Gets the latest message id

    if(!$result)
    {
        echo "</results>";
        exit();
    }
    
    echo "<id><![CDATA[$message_id]]></id>\n";
    
    while ($message = mysqli_fetch_array ($result))
    {
        $decoded_message = urldecode($message['message']);
        $user_query = mysqli_query($db, "SELECT * FROM users WHERE id = {$message['user']};");
        $user = mysqli_fetch_array($user_query);
        $avatar_tag = "<img src=\"{$user['avatar']}\" alt=\"Display picture\" style=\"width:75px;height:75px;border-radius:50%;float:left; margin-right:20px;\">"; // Makes the picture fit in a circle
        
        if($user['avatar'] == null) // If the user has no avatar
        {
            $avatar_tag = "<span style=\"float:left;\"></span>";
        }

        echo "<message>";
            echo "<div_tag><![CDATA[<div style=\"border: 2px solid #dedede; background-color: #f1f1f1; border-radius: 5px; padding: 30px; margin: 10px auto; width:25%;\">]]></div_tag>\n";
            echo "<image_tag><![CDATA[$avatar_tag]]></image_tag>\n";
            echo "<name><![CDATA[<p style=\"font-weight:bold;\">{$user['name']}</p>]]></name>\n";
            echo "<message_contents><![CDATA[$decoded_message]]></message_contents>\n";
            echo "<time><![CDATA[<time style=\"float: right;color: #aaa;\">{$message['time']}</time>]]></time>\n";
        echo"</message>";
    }
    echo "</results>";

?>