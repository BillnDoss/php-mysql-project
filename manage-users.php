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
      <h1 class="h1">Manage Users</h1>
      <div class="text-end">
        <a href="manage-users-add.php" class="btn btn-primary btn-sm">Add New User</a>
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
                <div class="buttons d-flex align-items-center">
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
      <a href="dashboard.php" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>