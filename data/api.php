<?php

# add github credentials
define('OAUTH2_CLIENT_ID', '4162c38ad7254916a993'); //add client id here
define('OAUTH2_CLIENT_SECRET', '1af3cf35f69d40dbe2e8edafe14e7cd3b47fe367'); //add client secret here

# URL of github api
$authorizeURL = 'https://github.com/login/oauth/authorize';
$tokenURL = 'https://github.com/login/oauth/access_token';
$apiURLBase = 'https://api.github.com/';


function apiRequest($url, $post=FALSE, $headers=array()) {
    $ch = curl_init($url);
  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Linux useragent'); //change agent string
  
    if($post)
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
  
    $headers[] = 'Accept: application/json';
  
    # add access token to header 
    if(session('access_token'))
      $headers[] = 'Authorization: Bearer ' . session('access_token');
  
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
    $response = curl_exec($ch);
    return json_decode($response); //decode response
  }
  
  # array key existence
  function get($key, $default=NULL) {
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
  }
  
  # array key existence
  function session($key, $default=NULL) {
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
  }

?>