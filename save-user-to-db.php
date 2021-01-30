<?php
    function findexts($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        return $ext;
    }

    $tmp_file_name = $_FILES['img']['tmp_name'];
    $original_file_name = $_FILES['img']['name'];
    $file_extension = findexts($original_file_name);

    $new_file_name = md5($original_file_name).".".$file_extension;
    
    $full_path = "avatars/".$new_file_name;

    $db = mysqli_connect("127.0.0.1", "dbname", "password");
    $charset_set = mysqli_set_charset($db, 'utf8');
    mysqli_select_db ($db, "R00170592_db");

    $name = mysqli_escape_string($db, htmlentities($_POST['name']));

    $query = "INSERT INTO users VALUES (NULL, '$full_path', '$name');";

    if(isset($_FILES['img']) || $_FILES['img']['name'] != "")
    {
        $success = move_uploaded_file($tmp_file_name, $full_path);

        if($success)
        {
            echo "File uploaded!\n";
        }
        else
        {
            echo "Try again!\n";
            exit();
        }
    }
    else
    {
        echo "No avatar chosen\n";
        $query = "INSERT INTO users VALUES (NULL, NULL, '$name');";
    }

    $result = mysqli_query($db, $query);

    if($result)
    {
        echo "User was added\n";
    }
    else
    {
        echo "Database Error!\n";
    }

    mysqli_close($db);
?>