<?php

session_start();
$db = new PDO("mysql:host=localhost;dbname=disc_forum", "root", "root");

$query = "SELECT posts.id, posts.title, posts.content, posts.post_date, users.username AS post_by FROM posts LEFT JOIN users ON posts.post_by = users.id ORDER BY posts.id";

$stmt = $db->prepare($query);
$stmt->execute([]);
$posts = $stmt->fetchAll();

// this can be used for any undefined user variables since the data is already being stored in here
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
  <link rel="stylesheet" href="styles.css">
  <style type="text/css">
    /* body and footer styling to fix footer not being positioned to the bottom of the page */
    body {
      display: flex;
      background: #f1f1f1;
      min-height: 100vh;
      flex-direction: column;
    }

    footer {
      margin-top: auto;
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
        <!-- This section is for navbar items being centered after logging in -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <?php if ($usersession) : ?>
            <ul class="navbar-nav mx-auto">
            </ul>
          <?php endif; ?>
          <!-- This section is for navbar items that remain at the right not logged in -->
          <?php if (!$usersession) : ?>
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a href="login-form.php" class="nav-link <?= isset($usersession) ? ' d-none' : '' ?>">Login</a>
              </li>
              <li class="nav-item">
                <a href="register-form.php" class="nav-link <?= isset($usersession) ? ' d-none' : '' ?>">Sign Up</a>
              </li>
            </ul>
          <?php endif; ?>
          <?php if ($usersession) : ?>
            <div class="nav-account">
              <button class="account-trigger" aria-haspopup="true">
                <!-- the specialchars just makes the username act like text instead of code that needs to be read -->
                <span><i class="bi bi-person-circle"></i></span> Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?> ▼
              </button>
              <div class="account-dropbox">
                <a href="dashboard.php" class="nav-link <?= isset($usersession) && $_SESSION['user']['role'] === 'admin' ? '' : 'd-none' ?>"><i class="bi bi-menu-button"></i>Dashboard</a>
                <a href="manage-users-edit.php?id=<?= $_SESSION['user']['id'] ?>" class="nav-link <?= isset($usersession) ? '' : 'd-none' ?>"><i class="bi bi-pencil"></i>Edit User</a>
                <a href="manage-users-changepwd.php?id=<?= $_SESSION['user']['id'] ?>" class="nav-link <?= isset($usersession) ? '' : 'd-none' ?>"><i class="bi bi-key"></i>Change Password</a>
                <a href="./logout.php?logout=true" class="nav-link <?= isset($_SESSION['user']) ? '' : ' d-none' ?>"><i class="bi bi-box-arrow-left"></i>Logout</a>
              </div>
            </div>
          <?php endif; ?>
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