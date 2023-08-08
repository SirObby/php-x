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

    $filename = " ";

    $target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
echo $_FILES["fileToUpload"]["tmp_name"];
if($_FILES["fileToUpload"]) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }
  
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }
  
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }
  
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
      $filename = htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }


}
    echo "<br>";
    echo $filename;
    echo "<br>";
    echo htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));

    $did_work2 = true;
    $query2 = "INSERT INTO tweets (name,user_id,filename) VALUES ('" . $_POST["tveet"] . "'," . pg_fetch_result($res, 0, "id") . ",'" . $filename . "');";
    $res2 = pg_query($dbconn, $query2) or $did_work2 = false;

    //header("location: /");
} else {
    //header("location: /");
}
