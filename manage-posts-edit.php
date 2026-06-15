<?php
require('header.php');

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $query = "SELECT * FROM posts WHERE id=:id";
  $stmt = $db->prepare($query);
  $stmt->execute([
    ':id' => $id
  ]);
  $posts = $stmt->fetch();
}

if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id'])) {
  $title = $_POST['title'];
  $content = $_POST['content'];
  $id = $_POST['id'];

  $query = "UPDATE posts SET title=:title, content=:content WHERE id=:id";
  $stmt = $db->prepare($query);
  $stmt->execute([
    ":title" => $title,
    ":content" => $content,
    ":id" => $id
  ]);

  // checks the session role to redirect to specific page
  if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
    header("Location: index.php");
  } else {
    header("Location: manage-posts.php");
  }
  exit;
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Edit Post</title>
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

    .post-edit-card {
      border: none;
      border-radius: 18px;
      overflow: hidden;
    }

    .post-edit-header {
      background: linear-gradient(135deg, #0d6efd, #0b5ed7);
      color: white;
    }

    .form-label {
      font-weight: 600;
    }

    .form-control {
      border-radius: 12px;
    }

    textarea.form-control {
      min-height: 300px;
    }
  </style>
</head>

<body>
  <div class="container py-5">

    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="card post-edit-card shadow-lg">

          <div class="card-header post-edit-header p-4">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h2 class="mb-1">
                  <i class="bi bi-pencil-square me-2"></i>
                  Edit Post
                </h2>
              </div>

              <i class="bi bi-file-earmark-text fs-1 opacity-50"></i>
            </div>
          </div>
          <div class="card-body p-4">
            <form method="POST" id="changePostForm">
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= $posts['title'] ?>" />
              </div>
              <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" rows="10" name="content"><?= $posts["content"] ?></textarea>
              </div>
              <input type="hidden" name="id" value="<?= $id ?>">
              <div class="text-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Update</button>
              </div>
            </form>
          </div>
        </div>
        <div class="text-center">
          <a href="manage-posts.php" class="btn btn-primary mt-3"><i class="bi bi-arrow-left"></i> Back to Posts</a>
        </div>
        <script
          src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
          crossorigin="anonymous"></script>
</body>

</html>