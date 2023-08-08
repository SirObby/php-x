<?php

session_start();

require 'sql.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width='device-width', initial-scale=1.0">
    <title>PHP-X</title>
    <script src="https://kit.fontawesome.com/6ef3bc8c55.js" crossorigin="anonymous"></script>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <h1>welcome to php-x</h1>

    <nav>
        <a href="/">php-x</a>
        <?php
        require_once 'api.php';

        $is_logged_in;
        $res;

        if (!session('access_token')) {
            echo '<a href="/github.php">login with GitHub</a>';
        } else {
            $user = apiRequest($apiURLBase . 'user');

            $did_work = true;
            $query = "SELECT * FROM users WHERE login='" . $user->login . "';";
            $res = pg_query($dbconn, $query) or $did_work = false;

            if ($did_work) {
                echo '<a href="/profile.php">@' . pg_fetch_result($res, 0, "handle") . "</a>";
                $is_logged_in = true;
            }
        }
        ?>
    </nav>

    <div>
        <p>
            <form action="/goto_profile.php" method="post">
                <input type="text" name="id" id="id" placeholder="stinky-banana">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </p>

        <p>
            <?php
            if ($is_logged_in) {
                echo "tveet as @" . pg_fetch_result($res, 0, "handle") . "<br>";
                echo '<form action="/post.php" method="post" enctype="multipart/form-data">
                    <input type="text" name="tveet" id="tveet" placeholder="Good morning">
                    <button type="submit"><i class="fa-regular fa-paper-plane"></i></button>
                </form>';
                
                echo '<br>';

                echo "tveet with image as @" . pg_fetch_result($res, 0, "handle") . "<br>";
                echo '<form action="/post_image.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="fileToUpload" id="fileToUpload"><br><input type="text" name="tveet" id="tveet" placeholder="Good morning (with image)">
                    <button type="submit"><i class="fa-regular fa-paper-plane"></i></button>
                </form>';
            }
            //
            ?>
            
        </p>

        <p>
        <table>
            <tr>
                <td>
                    Timeline
                </td>
            </tr>

            <tr>
            
            <?php
            
            $did_work;
            $query = "SELECT * FROM tweets ORDER BY timestamp DESC;";
            $results = pg_query($dbconn, $query) or $did_work = false;

            while ($line = pg_fetch_array($results, null, PGSQL_ASSOC)) {
                echo "\t<tr>\n";
                $on = 0;
                $id = 0;
                foreach ($line as $col_value) {
                    $on += 1;
                    if($on == 2) $id = $col_value;
                    if($on == 2 || $on == 4 || $on == 7) 
                        {
                            //echo "other";   
                            if($on == 4) {

                                $did_work44;
                                $query44 = "SELECT * FROM users WHERE id=" . $col_value . ";";
                                $results44 = pg_query($dbconn, $query44) or $did_work44 = false;

                                echo "\t\t<td>@" . pg_fetch_result($results44,0,"handle") . "</td>\n";
                                //echo '@' . 
                            }
                            if($on == 7) {
                                if ($col_value != " "  && $col_value != "")
                                echo "\t\t<td><img src=\"/uploads/" . $col_value . "\" width=200px></img></td>\n";
                            }
                        }
                        else 
                    echo "\t\t<td>$col_value</td>\n";
                    if($on == 6) {
                        echo "\t\t<td><a href='/like.php?p=" . $id ."'><i class=\"fa-regular fa-thumbs-up\"></i></a> <a href='/retweet.php?p=" . $id ."'><i class=\"fa-solid fa-retweet\"></i></a></td>\n";
                    }
                }
                echo "\t</tr>\n";
            }

            ?>
        </tr>

        </table>
        </p>
    </div>



</body>

</html>
