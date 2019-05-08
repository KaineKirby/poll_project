<?php
$dbuser = "root";
$dbpass = ""; //setting up standard variables required to start the database connections
$dbname = "poll";

session_start();
// $_SESSION['user_id'] = 1;
$db = new PDO('mysql:host=localhost; dbname='.$dbname, $dbuser ,$dbpass); //Creating connection between php and database
if(!isset($_SESSION['user_id'])) {
  $userQuery = $db->query("INSERT INTO users (user_name) VALUES ('') ");
  $userQuery2 = $db->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1"); //setting up a new user_id each time there is a new browser opened
  $currUser = $userQuery2->fetchObject();
  $_SESSION['user_id'] = $currUser->user_id; //setting the session with the id received from the query
}
 ?>
