<?php
session_start();

$username = isset($_POST['username']) ? $_POST['username'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

if (isset($_POST['username'])) {


    $db = new PDO("mysql:host=localhost;dbname=disc_forum", "root", "root");

    $query = "SELECT * FROM users WHERE username=:username";

    $stmt = $db->prepare($query);
    $stmt->execute(array(
        ':username' => $username
    ));
    $user = $stmt->fetch();


    $is_password_match = password_verify($password, $user['password']);
    header("Location: login-form.php");

    // After checking if the password is verified, it will do 1 of 2 things 
    // if the user is logged in as a user it will redirect to index.php, but if logged in as admin it will redirect to dashboard.php
    if ($is_password_match) {
        $_SESSION['user'] = $user;
        // this session for role is for authentication purposes
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    }
}
