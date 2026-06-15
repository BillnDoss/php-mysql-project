<?php
require('header.php');

$usersession = $_SESSION['user'] ?? null;
$role = $usersession['role'] ?? null;

$id = $_GET['id'] ?? null;
if (isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['id'])) {
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $id = $_POST['id'];

  if ($password == $confirm_password) {
    $updateQuery = "UPDATE users SET password=:password WHERE id=:id";
    $stmt = $db->prepare($updateQuery);
    $stmt->execute([
      ":password" => password_hash($password, PASSWORD_BCRYPT),
      ":id" => $id
    ]);
  }

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

    .pwd-header {
      background: linear-gradient(135deg, #0d6efd, #0b5ed7);
      color: white;
    }
  </style>
</head>

<body>
  <div class="container mx-auto my-5" style="max-width: 700px;">
    <div class="card shadow-lg">
      <div class="pwd-header p-4">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h1 class="mb-1"><i class="bi bi-key me-2"></i> Change Password</h1>
          </div>
        </div>
      </div>
</div>
      <div class="card mb-2 p-4">
        <form method="POST" id="changePwdForm">
          <div class="mb-3">

            <div class="mb-3">
              <label class="form-label">New Password</label>
              <input type="password" class="form-control form-control-lg" name="password" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Confirm Password</label>
              <input type="password" class="form-control form-control-lg" name="confirm_password" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary"> Change Password</button>
            </div>
        </form>
      </div>
    </div>
    <div class="text-center">
      <a href="manage-users.php" class="btn btn-primary mt-3<?= ($role === 'admin') ? '' : 'd-none' ?>"><i class="bi bi-arrow-left"></i> Back to Users</a>
      <a href="index.php" class="btn btn-primary mt-3 <?= ($role !== 'admin') ? '' : 'd-none' ?>"> <i class="bi bi-arrow-left"></i> Back to Posts Page</a>
    </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>