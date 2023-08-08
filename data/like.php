<?php

session_start();

require 'api.php';
require 'sql.php';

$res;

if(!empty($_GET)) {

    $user = apiRequest($apiURLBase.'user');

    $did_work = true;
    $query = "SELECT * FROM users WHERE login='" . $user->login . "';";
    $res = pg_query($dbconn, $query) or $did_work = false;

    if($did_work) {
        //echo '<a href="/profile.php">@' . pg_fetch_result($res,0,"handle") . "</a>";
    }

    $did_work2 = true;
    $query2 = "SELECT id,likes FROM tweets WHERE id=" . $_GET["p"] . ";";
    $res2 = pg_query($dbconn, $query2) or $did_work2 = false;

    if($did_work) {
        //echo '<a href="/profile.php">@' . pg_fetch_result($res,0,"handle") . "</a>";
    }

    $did_work3 = true;
    $query3 = "UPDATE tweets SET likes = " . intval(pg_fetch_result($res2,0,"likes")) + 1 . " WHERE id = " . $_GET["p"] . ";";
    $res3 = pg_query($dbconn, $query3) or $did_work3 = false;

    header("location: /");

} else {
    header("location: /");
}

?>