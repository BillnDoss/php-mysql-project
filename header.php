<?php
session_start();


$db = new PDO("mysql:host=localhost;dbname=disc_forum", "root", "root");

// if(!isset($_SESSION['user'])){
//     header("Location: index.php");
//     exit;
// }
?>