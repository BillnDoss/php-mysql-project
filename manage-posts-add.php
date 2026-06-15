<?php
require('header.php');

$usersession = $_SESSION['user'] ?? null;
$role = $usersession['role'] ?? null;
$posterid = $usersession['id'] ?? null;


$query = "INSERT INTO posts (title, content, post_by, post_date) VALUES (:title, :content, :post_by, :post_date)";
$date_posted = date('Y-m-d H:i:s');
if (isset($_POST['title']) && isset($_POST['content'])) {
  $title = $_POST['title'];
  $content = $_POST['content'];
  $post_by = $posterid;


  $stmt = $db->prepare($query);
  $stmt->execute([
    ":title" => $title,
    ":content" => $content,
    ":post_by" => $post_by,
    ":post_date" => $date_posted,
  ]);
  header("Location: index.php");
  exit;
}


?>

<!DOCTYPE html>
<html>

<head>
  <title>Add New Post</title>
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

    .post-add-box {
      max-width: 850px;
      margin: 60px auto;
    }

    .post-add-header {
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      color: white;
      padding: 30px;
      border-radius: 18px;
      margin-bottom: 20px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .post-add-card {
      background: white;
      border-radius: 18px;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .form-control {
      border-radius: 12px;
      padding: 12px;
    }

    .btn-publish {
      border-radius: 12px;
      padding: 10px 22px;
    }

    .icon-circle {
      width: 45px;
      height: 45px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      margin-right: 10px;
    }
  </style>
</head>

<body>
  <div class="post-add-box">
    <div class="post-add-header d-flex align-items-center">
      <div class="icon-circle">
        <i class="bi bi-plus-circle"></i>
      </div>

      <div>
        <h1 class="mb-0">Add New Post</h1>
      </div>
    </div>
    <div class="post-add-card">
      <form method="POST" id="addPostForm">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Enter Post Title" />
        <label for="content" class="form-label">Content</label>
        <textarea class="form-control" id="content" rows="4" name="content" placeholder="Enter Post Content"></textarea>
        <input type="hidden" name="post_by" id="post_by" value="1">
        <div class="d-flex justify-content-end">

          <button type="submit" class="btn btn-primary btn-publish mt-3"><i class="bi bi-send"></i> Publish Post</button>
        </div>
    </div>
    <div class="text-center">
      <a href="manage-posts.php" class="btn btn-primary mt-3 <?= ($role === 'admin') ? '' : 'd-none' ?>"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
      <a href="index.php" class="btn btn-primary mt-3 <?= ($role !== 'admin') ? '' : 'd-none' ?>"> <i class="bi bi-arrow-left"></i> Back to Posts</a>
    </div>
  </div>
  </div>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>