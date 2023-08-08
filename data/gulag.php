<?php

session_start();

require 'api.php';
require 'sql.php';

$current_stage="handle";

$handle_warning="";

if(!empty($_POST)) {

    if(is_null($_POST["bio"])) {
    
    $did_work = true;

    $query = "SELECT * FROM users WHERE handle =" . $_POST["handle"];
    $result = pg_query($dbconn, $query) or $did_work = false;

    if (!$did_work) {
    
        $current_stage="bio";

    } else {

        $handle_warning = "Somebody already has that handle!";

    }
    } else {
        $user = apiRequest($apiURLBase . 'user');
        $query = "INSERT INTO users (handle,descri,login) VALUES ('" . $_POST["handle"] . "','" . $_POST["bio"] . "','" . $user->login  . "');";
        $result = pg_query($dbconn, $query) or $did_work = false;
    
        header("location: /");
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP-X</title>
</head>
<body>
    
    <h1>Welcome to PHP-X</h1>

    <div id="input">
        <span id="warning">
            <?php
                echo $handle_warning;
            ?>
        </span>
        <form action="/gulag.php" method="post">
        <?php

        if($current_stage == "handle") echo '
            Choose your handle.<br>
            @<input type="text" name="handle" id="handle" placeholder="stinky-banana">
            <input type="submit" value="Submit">';
        if($current_stage == "bio") echo '
        <input style="display:none" type="text" name="handle" id="handle-done" placeholder="stinky-banana" value="' . $_POST["handle"]  . '"> 
        Set a description.<br>
        <input type="text" name="bio" id="bio" placeholder="Description"> <input type="submit" value="Submit">
        ';
        ?>
        </form>
    </div>

</body>
<script>

    /*function submit(wot) {
        if(wot == 'handle') {
            if(did_work) {
                document.getElementById("warning").innerHTML = "handle already exists";
            }
        }
    }*/
</script>
</html>