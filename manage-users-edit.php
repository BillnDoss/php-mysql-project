<?php
require('header.php');

$usersession = $_SESSION['user'] ?? null;
$role = $usersession['role'] ?? null;

$query = "SELECT * FROM users WHERE id=:id";

$users = [];
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $stmt = $db->prepare($query);
  $stmt->execute([
    ':id' => $id
  ]);
  $users = $stmt->fetch();
}

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['id']) && isset($_POST['role'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $id = $_POST['id'];
  $role = $_POST['role'];

  $updateQuery = "UPDATE users SET username=:username, email=:email, role=:role WHERE id=:id";
  $stmt = $db->prepare($updateQuery);
  $stmt->execute([
    ":username" => $username,
    ":email" => $email,
    ":role" => $role,
    ":id" => $id
  ]);

  if ($_SESSION['role'] === 'admin') {
    header("Location: manage-users.php");
  } else {
    header("Location: index.php");
  }
  exit;
}
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
  <style type="text/css">
    body {
      background: #f1f1f1;
    }
  </style>
</head>

<body>
  <div class="container mx-auto my-5" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h1 class="h1">Edit User</h1>
    </div>
    <div class="card mb-2 p-4">
      <form method="POST" id="changeUserForm">
        <div class="mb-3">
          <div class="row">
            <div class="col">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" value="<?= $users['username'] ?>" />
            </div>
            <div class="col">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="<?= $users['email'] ?>" />
            </div>
          </div>
        </div>
        <div class="mb-3 <?= ($role === 'admin') ? '' : 'd-none' ?>">
          <label for="role" class="form-label">Role</label>
          <select class="form-control" id="role" name="role">
            <!-- this disables the option to select the default no role as a role -->
            <option value="" disabled>Select an option</option>
            <!-- option select selected php is to check for the data's role and make the option preselected upon editing -->
            <option value="user" <?= $users['role'] == "user" ? "selected" : '' ?>>User</option>
            <option value="admin" <?= $users['role'] == "admin" ? "selected" : '' ?>>Admin</option>
          </select>
        </div>
        <input type="hidden" name="id" value="<?= $users['id'] ?>">
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
    <div class="text-center">
      <a href="manage-users.php" class="btn btn-link btn-sm <?= ($role === 'admin') ? '' : 'd-none' ?>"><i class="bi bi-arrow-left"></i> Back to Users</a>
      <a href="index.php" class="btn btn-link btn-sm <?= ($role !== 'admin') ? '' : 'd-none' ?>"> <i class="bi bi-arrow-left"></i> Back to Posts Page</a>
    </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>