<?php
require('header.php');

if (isset($_POST['id'])) {
  $id = $_POST['id'];

  $deleteQuery = "DELETE FROM posts WHERE id=:id";

  $stmt = $db->prepare($deleteQuery);
  $stmt->execute([":id" => $id]);
}

$query = "SELECT posts.id, posts.title, posts.content, posts.post_date, users.username AS post_by FROM posts LEFT JOIN users ON posts.post_by = users.id ORDER BY posts.id";

$stmt = $db->prepare($query);
$stmt->execute([]);
$posts = $stmt->fetchAll();

$usersession = isset($_SESSION['user']) ? $_SESSION['user'] : null;

if (isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'admin') {
  header("Location: index.php");
  exit;
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Manage Posts</title>
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
      background: #f1f1f1;
    }

    .page-card {
      border: none;
      border-radius: 18px;
      overflow: hidden;
    }

    .page-header {
      background: blue;
      color: white;
    }

    .stat-card {
      border: none;
      border-radius: 16px;
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
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h1"><i class="bi bi-file-earmark-text text-primary"></i> Manage Posts</h1>
      <div class="text-end">
        <a href="manage-posts-add.php" class="btn btn-primary"><i class="bi bi-plus-circle-fill"></i> Add New Post</a>
      </div>
    </div>
    <div class="row mb-4">

      <div class="col-md-4">
        <div class="card stat-card shadow-sm">
          <div class="card-body">
            <h6 class="text-muted mb-1">
              Total Posts
            </h6>

            <h2 class="mb-0">
              <?= count($posts) ?>
            </h2>
          </div>
        </div>
      </div>

    </div>
    <div class="card page-card shadow-lg">
      <div class="card-header page-header py-3">
        <h5 class="mb-0"><i class="bi bi-collection"></i> Posts</h5>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col" style="width: 40%;">Title</th>
            <th scope="col"">Poster</th>
            <th scope=" col" class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $post): ?>
            <tr>
              <th scope="row"><?= $post['id'] ?></th>
              <td><?= $post['title'] ?></td>
              <td class="fw-bold"><?= $post['post_by'] ?></td>

              <td class="text-end">
                <div class="buttons">
                  <a href="posts.php?id=<?= $post['id'] ?>" target="_blank" class="btn btn-primary btn-sm me-2">View</a>
                  <form method="post" class="d-inline">
                    <button type="submit" class="btn btn-danger btn-sm" type="submit" <?= $_SESSION['user']['role'] == 'admin' ? '' : 'disabled' ?>>Delete</button>
                    <input type="hidden" name="id" value="<?= $post['id'] ?>">
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="text-center">
      <a href="dashboard.php" class="btn btn-primary mt-3"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>

</body>

</html>