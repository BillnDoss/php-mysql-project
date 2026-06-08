<?php
require('header.php');

if (!isset($_SESSION['user'])) {
  header("Location: index.php");
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
    <h2><a href='./logout.php?logout=true' class="btn btn-danger">Click me to logout</a></h2>

  </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>