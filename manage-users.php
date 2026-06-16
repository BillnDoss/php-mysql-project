<?php
require('header.php');

if (isset($_POST['id'])) {

  $deleteQuery = "UPDATE users SET deleted_at=:deleted_at WHERE id=:id";
  $stmt = $db->prepare($deleteQuery);
  $stmt->execute([
    ':deleted_at' => date("Y-m-d H:i:s"),
    ':id' => $_POST['id']
  ]);
}

$query = "SELECT * FROM users WHERE deleted_at IS NULL";
$stmt = $db->prepare($query);
$stmt->execute([]);
$users = $stmt->fetchAll();

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
      background: #f1f1f1;
    }

    .card {
      border: 0;
      border-radius: 12px;
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
  <div class="container mx-auto my-5" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <h1 class="h1">Manage Users</h1>
        <h2 class="h5 mb-0">User List</h2>
        <p class="fs-4 text-muted"><?= count($users) ?> active users</p>
      </div>

      <div class="text-end">
        <a href="manage-users-add.php" class="btn btn-primary btn-sm"> <i class="bi bi-person-plus"></i> Add New User</a>
      </div>
    </div>
    <div class="card mb-2 p-4">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
            <th scope="col" class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <?php
            $role_badge = "";
            switch ($user['role']) {
              case 'user':
                $role_badge = "bg-success";
                break;
              case 'admin':
                $role_badge = "bg-primary";
                break;
            }
            ?>
            <tr>
              <th scope="row"><?= $user['id'] ?></th>
              <td><?= $user['username'] ?></td>
              <td><?= $user['email'] ?></td>
              <td><span class="badge <?= $role_badge ?>"> <?= ucwords($user['role']) ?></span></td>
              <td class="text-end">
                <div class="buttons d-flex justify-content-end align-items-center">
                  <a href="manage-users-edit.php?id=<?= $user['id'] ?>" class="btn btn-success btn-sm me-2"><i class="bi bi-pencil"></i></a>
                  <a href="manage-users-changepwd.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm me-2"><i class="bi bi-key"></i></a>
                  <input type="hidden" name="id" value="<?= $user['id'] ?>">
                  <form method="post">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <button class="btn btn-danger btn-sm" value="<?= $user['id'] ?>"><i class="bi bi-trash"></i></button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="text-center">
      <a href="dashboard.php" class="btn btn-primary mt-2"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>