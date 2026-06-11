<?php
require('header.php');

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
  </style>
</head>

<body>
  <div class="container mx-auto my-5" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h1 class="h1">Change Password</h1>
    </div>
    <div class="card mb-2 p-4">
      <form method="POST" id="changePwdForm">
        <div class="mb-3">
          <div class="row">
            <div class="col">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required />
            </div>
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <div class="col">
              <label for="confirm_password" class="form-label">Confirm Password</label>
              <input
                type="password"
                class="form-control"
                id="confirm_password"
                name="confirm_password"
                required />
            </div>
          </div>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">
            Change Password
          </button>
        </div>
      </form>
    </div>
    <div class="text-center">
      <a href="manage-users.php" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Users</a>
    </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>