<?php

//include 'sql.php';

// Performing SQL query
//  $query = ;
  
$dbconn = pg_connect("host=db dbname=postgres user=postgres password=postgress-password");


$result = pg_query($dbconn, "SELECT * FROM users"); //or die('Query failed: ' . pg_last_error());
  
 // echo $result;
  
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

?>