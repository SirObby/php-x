<?php

session_start();

require 'api.php';
require 'sql.php';

$res;

if (!empty($_POST)) {

    echo exec('whoami');

    $user = apiRequest($apiURLBase . 'user');

    $did_work = true;
    $query = "SELECT * FROM users WHERE login='" . $user->login . "';";
    $res = pg_query($dbconn, $query) or $did_work = false;

    if ($did_work) {
        //echo '<a href="/profile.php">@' . pg_fetch_result($res,0,"handle") . "</a>";
    }

    $did_work2 = true;
    $query2 = "INSERT INTO tweets (name,user_id) VALUES ('" . $_POST["tveet"] . "'," . pg_fetch_result($res, 0, "id") . ");";
    $res2 = pg_query($dbconn, $query2) or $did_work2 = false;

    //header("location: /");
} else {
    //header("location: /");
}
