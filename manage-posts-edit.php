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
      <h1 class="h1">Edit Post</h1>
    </div>
    <div class="card mb-2 p-4">
      <form method="POST" id="changePostForm">
        <div class="mb-3">
          <label for="title" class="form-label">Title</label>
          <input
            type="text"
            class="form-control"
            id="title"
            name="title"
            value="<?= $posts['title'] ?>" />
        </div>
        <div class="mb-3">
          <label for="content" class="form-label">Content</label>
          <textarea class="form-control" id="content" rows="10" name="content"><?= $posts["content"] ?></textarea>
        </div>
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="text-end">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
    <div class="text-center">
      <a href="manage-posts.php" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Posts</a>
    </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>