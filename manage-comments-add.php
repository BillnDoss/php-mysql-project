<?php
require('header.php');

$query = "INSERT INTO comments (content, comment_date, comment_by, for_post) VALUES (:content, :comment_date, :comment_by, :for_post)";
$commented_date = date('Y-m-d H:i:s');


if (isset($_POST['content']) && isset($_GET['id'])) {
  $content = $_POST['content'];
  $for_post = $_GET['id'];
  $user = $_SESSION['user']['id'];

  $stmt = $db->prepare($query);
  $stmt->execute([
    ":content" => $content,
    ":comment_date" => $commented_date,
    ":comment_by" => $user,
    ":for_post" => $for_post,

  ]);
  header("Location: posts.php?id=" . $_GET['id']);
  exit;
}


?>

<!DOCTYPE html>
<html>

<head>
  <title>Add New Comment</title>
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
      <h1 class="h1">Add New Comment</h1>
    </div>
    <div class="card mb-2 p-4">
      <form method="POST">
        <input type="hidden" name="for_post" value="<?= $_GET['id'] ?>">
        <div class="mb-3">
          <div class="row">
            <div class="col">
              <label for="comment" class="form-label">Comment</label>
              <input type="text" class="form-control" id="content" name="content" required placeholder="Add Your Comment Here" />
            </div>
          </div>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
    <div class="text-center">
      <a href="posts.php?id=<?= $_GET['id'] ?>" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back to Posts</a>
    </div>
  </div>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>