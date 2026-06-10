<?php
require('header.php');

if (!isset($_SESSION['user'])) {
  header("Location: index.php");
  exit;
}

$usersession = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>
<!DOCTYPE html>
<html>

<head>
  <title>Simple CMS</title>
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
    body {
      display: flex;
      background: #f1f1f1;
      min-height: 100vh;
      flex-direction: column;
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
                <a href="./logout.php?logout=true" class="nav-link <?= isset($_SESSION['user']) ? '' : ' d-none' ?>"><i class="bi bi-box-arrow-left"></i>Logout</a>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </nav>
  </div>
  <div class="container mx-auto my-5" style="max-width: 800px;">
    <h1 class="h1 mb-4 text-center">Dashboard</h1>
    <div class="row">
      <div class="col">
        <div class="card mb-2">
          <div class="card-body">
            <h5 class="card-title text-center">
              <div class="mb-1">
                <i class="bi bi-pencil-square" style="font-size: 3rem;"></i>
              </div>
              Manage Posts
            </h5>
            <div class="text-center mt-3">
              <a href="manage-posts.php" class="btn btn-primary btn-sm">Access</a>
            </div>
          </div>
        </div>
      </div>
      <!-- if the user is not an admin, hides user management tab -->
      <?php if ($_SESSION['user']['role'] == "admin"): ?>
        <div class="col">
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title text-center">
                <div class="mb-1">
                  <i class="bi bi-people" style="font-size: 3rem;"></i>
                </div>
                Manage Users
              </h5>
              <div class="text-center mt-3">
                <a href="manage-users.php" class="btn btn-primary btn-sm">Access</a>
              </div>
            </div>
          </div>
        </div>
    </div>
  <?php endif; ?>
  <div class="mt-4 text-center">
    <a href="index.php" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
  </div>
  </div>
  
  <main class="flex-grow-1">
<div class="container mx-auto my-5" style="max-width: 800px;">

</div>
  </main>

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