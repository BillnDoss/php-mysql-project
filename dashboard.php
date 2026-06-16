<?php
require('header.php');

if (isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'admin') {
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

    .dashboard-card {
      background: white;
      border-radius: 20px;
      padding: 35px;
      text-align: center;
      box-shadow: 0 6px 20px rgba(0, 0, 0, .08);
      transition: all .3s ease;
      height: 100%;
    }

    .dashboard-icon {
      font-size: 4rem;
      color: #0d6efd;
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
  <div class="container mx-auto my-5">
    <h1 class="h1 mb-4 text-center">Admin Dashboard</h1>
  </div>
  <div class="row g-4 justify-content-center">

    <div class="col-md-6">
      <div class="dashboard-card">

        <i class="bi bi-pencil-square dashboard-icon"></i>

        <h3>Manage Posts</h3>

        <a href="manage-posts.php" class="btn btn-primary">Access Posts</a>

      </div>
    </div>

    <div class="col-md-6">
      <div class="dashboard-card">

        <i class="bi bi-people-fill dashboard-icon text-success"></i>

        <h3>Manage Users</h3>
        <a href="manage-users.php" class="btn btn-success">Access Users</a>
      </div>
    </div>


  </div>
  <div class="mt-4 text-center">
    <a href="index.php" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Back to Forums View</a>
  </div>
  </div>
  <!-- The main is here for a body content to ensure the footer stays below the page instead of a awkward position -->
  <main class="flex-grow-1">
    <div class="container mx-auto my-5" style="max-width: 800px;">

    </div>
  </main>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>