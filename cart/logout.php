<?php
  // TODO: start session
session_start(); 

//TODO: If the user is logged in, delete the seession vars to log them out
session_destroy();

 // TODO: Redirect to the login page
 $home_url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . '/login.php';
 header('Location: ' . $home_url);
?>
