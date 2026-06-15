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
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .auth-card {
      border: 0;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      width: 100%;
      max-width: 420px;
      overflow: hidden;
    }

    .auth-header {
      background: linear-gradient(135deg, #0d6efd, #0a58ca);
      color: white;
      padding: 20px;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="card auth-card">

    <div class="auth-header">
      <h1 class="h4 mb-1">Create Account</h1>
      <p class="mb-0 opacity-75">Sign up to get started</p>
    </div>
    <div class="card p-4">
      <form method="POST" action="registration.php">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required />
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="email" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            required />
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <input
            type="password"
            class="form-control"
            id="confirm_password"
            name="confirm_password"
            required />
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary btn-fu">
            Sign Up
          </button>
        </div>
      </form>
    </div>

    <!-- links -->
    <div
      class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3">
      <a href="index.php" class="text-decoration-none"><i class="bi bi-arrow-left-circle"></i> Go back</a>
      <a href="login-form.php" class="text-decoration-none">Login here <i class="bi bi-arrow-right-circle"></i></a>
    </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>