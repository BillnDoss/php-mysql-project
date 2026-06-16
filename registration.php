<?php
$registerSuccess = false;

$username = isset($_POST['username']) ? $_POST['username'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

$db = new PDO("mysql:host=localhost;dbname=disc_forum", "root", "root");

$query = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";

$stmt = $db->prepare($query);
$stmt->execute(array(
    ':username' => $username,
    ':email' => $email,
    ':password' => password_hash($password, PASSWORD_BCRYPT),
    ':role' => 2
));
$registerSuccess = true;
header("Location: login-form.php");
exit;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registered</title>
</head>

<body>
    <h1>User has been successfully registered.</h1>
    <h2><a href='login-form.php' class="btn btn-primary">Click me to go to login form</a></h2>
</body>

</html>