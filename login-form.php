<?php
session_start();

// Forces you back into login.php after submitting
if (isset($_SESSION['user'])) {
  header("Location: login.php");
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
      display: flex;
      height: 100vh;
      align-items: center;
      justify-content: center;
    }

    .login-box {
      border: 0;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      width: 100%;
      max-width: 420px;
    }

    .login-header {
      background: linear-gradient(135deg, #0d6efd, #0a58ca);
      color: white;
      padding: 20px;
      border-top-left-radius: 16px;
      border-top-right-radius: 16px;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="card login-box">
    <div class="login-header">
      <h1 class="h4 mb-1">Login</h1>
      <p>Please enter your details</p>
    </div>
    <div class="card p-4">
      <form method="POST" action="login.php">
        <div class="mb-2">
          <label for="name">Username</label>
          <input type="text" class="form-control" name="username" id="username" placeholder="Username" required />
        </div>
        <div class="mb-2">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
      </form>

      <div
        class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3">
        <a href="index.php" class="text-decoration-none"><i class="bi bi-arrow-left-circle"></i> Go back</a>
        <a href="register-form.php" class="text-decoration-none">Register <i class="bi bi-arrow-right-circle"></i></a>
      </div>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"></script>
</body>

</html>