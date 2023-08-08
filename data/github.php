<?php

require 'api.php';
require 'sql.php';

# start sessions
session_start();

// Start the login process by sending the user to Github's authorization page
if (get('action') == 'login') {
    // Generate a random hash and store in the session for security
    $_SESSION['state'] = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']);
    unset($_SESSION['access_token']);

    $params = array(
        'client_id' => OAUTH2_CLIENT_ID,
        'redirect_uri' => 'http://localhost:8080/github.php',
        'scope' => 'user',
        'state' => $_SESSION['state']
    );

    // Redirect the user to Github's authorization page
    header('Location: ' . $authorizeURL . '?' . http_build_query($params));
    die();
}

// to kill all Sessions and reset code base 
if (get('action') == 'exit') {
    unset($_SESSION['state']);
    unset($_SESSION['access_token']);
    session_destroy();
    exit();
}

// When Github redirects the user back here, there will be a "code" and "state" parameter in the query string
if (get('code')) {
    // Verify the state matches our stored state
    if (!get('state') || $_SESSION['state'] != get('state')) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        die();
    }

    // Exchange the auth code for a token
    $token = apiRequest($tokenURL, array(
        'client_id' => OAUTH2_CLIENT_ID,
        'client_secret' => OAUTH2_CLIENT_SECRET,
        'redirect_uri' => 'http://localhost:8080/github.php',
        'state' => $_SESSION['state'],
        'code' => get('code')
    ));
    $_SESSION['access_token'] = $token->access_token;

    header('Location: ' . $_SERVER['PHP_SELF']);
}

# if successful show results
if (session('access_token')) {
    $user = apiRequest($apiURLBase . 'user');

    // Performing SQL query
    $did_work = true;

    $query = 'SELECT * FROM users WHERE login = ' . $user->login;
    $result = pg_query($dbconn, $query) or $did_work = false;

    if ($did_work) {

        // Printing results in HTML
        echo "<table>\n";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "\t<tr>\n";
            foreach ($line as $col_value) {
                echo "\t\t<td>$col_value</td>\n";
            }
            echo "\t</tr>\n";
        }
        echo "</table>\n";

        // Free resultset
        pg_free_result($result);

        // Closing connection
        pg_close($dbconn);
    } else {
    
        echo "<a href='/gulag.php'>You haven't made an account yet, click here to make one.</a>";
        //header("Location: /gulag.php");

    }

    echo '<h3>Logged In</h3>';
    echo '<h4>' . $user->login . '</h4>';
    echo '<pre>';
    print_r($user);
    echo '</pre>';


    #print out full list of urls of github  
    print '<br /><br />';
    print '<h3>Full List of Urls on Github</h3>';
    $full = apiRequest($apiURLBase);
    foreach ($full as $key => $value) {
        print $key . '=>' . $value . '<br />';
    }
} else {

    # fail result if no session token
    echo '<h3>Not logged in</h3>';
    echo '<p><a href="?action=login">Log In</a></p>';
}
