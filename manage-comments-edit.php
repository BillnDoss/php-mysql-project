<?php
require('header.php');

// The null's here just tell the page to shush when it can't find anything related to these
$comment = null;
$post_id = null;

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $query = "SELECT * FROM comments WHERE id=:id";
  $stmt = $db->prepare($query);
  $stmt->execute([
    ':id' => $id
  ]);
  $comment = $stmt->fetch();

  if ($comment) {
    $post_id = $comment['for_post'];
  }
}

if (isset($_POST['content']) && isset($_POST['id']) && isset($_POST['post_id'])) {
  $content = $_POST['content'];
  $id = $_POST['id'];
  $post_id = $_POST['post_id'];

  $query = "UPDATE comments SET content=:content WHERE id=:id";
  $stmt = $db->prepare($query);
  $stmt->execute([
    ":content" => $content,
    ":id" => $id
  ]);
  header("Location: posts.php?id=" . $post_id);
  exit;
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Edit Comment</title>
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

    .comment-box {
      background: white;
      border-radius: 18px;
      padding: 25px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .comment-input {
      border-radius: 12px;
      padding: 15px;
      resize: none;
    }

    .btn-comment {
      border-radius: 12px;
      padding: 10px 20px;
    }

    .comment-header-box {
      max-width: 750px;
      margin: 60px auto;
    }

    .icon-circle {
      width: 45px;
      height: 45px;
      background: #0d6efd;
      border-radius: 50%;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      color: white;
      margin-right: 10px;
    }

    .comment-header {
      background: white;
      padding: 25px;
      border-radius: 18px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div class="comment-header-box">
    <div class="comment-header d-flex align-items-center">
      <div class="icon-circle">
        <i class="bi bi-pencil-square"></i>
      </div>
      <div>
        <h4 class="mb-0">Edit Comment</h4>
      </div>
    </div>
    <div class="comment-box">
      <form method="POST">
        <input type="hidden" name="id" value="<?= $comment['id'] ?>">
        <input type="hidden" name="post_id" value="<?= $post_id ?>">
        <label for="content" class="form-label">Comment</label>
        <textarea class="form-control comment-input" id="content" rows="4" name="content" placeholder="Add your edited comment here" required><?= $comment["content"] ?></textarea>
        <div class="d-flex justify-content-end mt-3">
          <button type="submit" class="btn btn-primary btn-comment"><i class="bi bi-send"></i> Update</button>
        </div>
      </form>
    </div>
    <div class="text-center">
      <a href="posts.php?id=<?= $post_id ?>" class="btn btn-primary mt-3"><i class="bi bi-arrow-left"></i> Back to Post</a>
    </div>
  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>