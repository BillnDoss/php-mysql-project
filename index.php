<?php

session_start();
$db = new PDO("mysql:host=localhost;dbname=disc_forum", "root", "root");

$query = "SELECT posts.id, posts.title, posts.content, posts.post_date, users.username AS post_by FROM posts LEFT JOIN users ON posts.post_by = users.id ORDER BY posts.id";

$stmt = $db->prepare($query);
$stmt->execute([]);
$posts = $stmt->fetchAll();

$usersession = isset($_SESSION['user']) ? $_SESSION['user'] : null;
// The button giving error was because the page was trying to find a user session that doesn't exist hence giving a unknwon array error
// Solution was adding a variable with a isset session for user 

?>

<!DOCTYPE html>
<html>

<head>
  <title>Discussion Forum</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
    crossorigin="anonymous" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
  <style type="text/css">
    body {
      background: #f1f1f1;
    }
  </style>
</head>

<body>
  <div class="container-fluid bg-dark navbar-dark">
    <nav class="navbar navbar-expand-lg pt-0">
      <div class="container navbar-dark">
        <a href="index.php" class="navbar-brand">Discussion Forum</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item active">
              <a href="login-form.php" class="nav-link <?= isset($usersession) ? ' d-none' : '' ?>">Login</a>
            </li>
            <li class="nav-item">
              <a href="register-form.php" class="nav-link <?= isset($usersession) ? ' d-none' : '' ?>">Sign Up</a>
            </li>
            <li class="nav-item">
              <a href="dashboard.php" class="nav-link <?= isset($usersession) && $_SESSION['user']['role'] === 'admin' ? '' : 'd-none' ?>"><i class="bi bi-menu-button"></i>Dashboard</a>
            </li>
            <li class="nav-item">
              <a href="./logout.php?logout=true" class="nav-link<?= isset($_SESSION['user']) ? '' : ' d-none' ?>"><i class="bi bi-box-arrow-left"></i>Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>

  <div class="container mx-auto my-5" style="max-width: 500px;">

    <?php if (!isset($_SESSION['user'])): ?>

      <div class="text-center">
        <h1>Discussion Forum</h1>
        <p>Please log in to view posts.</p>

        <a href="login-form.php" class="btn btn-primary">Login</a>
        <a href="register-form.php" class="btn btn-primary">Sign Up</a>
      </div>

    <?php else: ?>

  </div>
  <div class="container mx-auto my-5" style="max-width: 500px;">

    <h1 class="h1 mb-4 text-center">All Posts</h1>
    <?php foreach ($posts as $post): ?>
      <div class="card mb-2">
        <div class="card-body">
          <h5 class="card-title text-capitalize"><?= $post['title'] ?></h5>
          <p class="card-text"><?= $post['post_date'] ?></p>
          <p class="card-text fw-bold"><?= $post['post_by'] ?></p>
          <div class="text-end">
            <a href="posts.php?id=<?= $post['id'] ?>" class="btn btn-primary btn-sm">Read More</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

  <?php endif; ?>
  </div>
  <div class="text-center mb-4">
    <a href="manage-posts-add.php" class="btn btn-primary <?= !isset($usersession) ? ' d-none' : '' ?>">Add New Post</a>
  </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
  <footer>
    <div class="container-fluid bg-dark py-4">
      <div class="container text-center">
        <div class="d-flex justify-content-center pb-2">
          <i class="bi bi-facebook text-white px-2"></i>
          <i class="bi bi-twitter text-white px-2"></i>
          <i class="bi bi-instagram text-white px-2"></i>
          <a href="https://github.com/BillnDoss/php-mysql-project" target="_blank"><i class="bi bi bi-github text-white px-2"></i></a>
        </div>
        <p class="text-white text-center">&copy; 2026 JTTY. All rights reserved.</p>
      </div>
    </div>
  </footer>
</body>

</html>