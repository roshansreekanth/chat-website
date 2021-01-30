<?php
    $db = mysqli_connect("127.0.0.1", "dbname", "password");
    mysqli_select_db ($db, "R00170592_db");
    $charset_set = mysqli_set_charset ($db, 'utf8');

    $user_id = htmlentities($_POST['user_id']);
    $new_message = htmlentities($_POST['message']);
    
    $safe_message = mysqli_escape_string($db, $new_message);

    $query = "INSERT INTO messages VALUES (NULL, '$safe_message', $user_id, now());";

    $result = mysqli_query($db, $query);
?>