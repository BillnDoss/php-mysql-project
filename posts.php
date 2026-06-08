<?php
require("header.php");

// variables for role and session validation
$user = $_SESSION['user'] ?? null;
$posterid = $user['id'] ?? null;
$adminPerm = ($user['role'] ?? null) === 'admin';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $stmt = $db->prepare("SELECT posts.*, users.username AS username FROM posts LEFT JOIN users ON posts.post_by = users.id WHERE posts.id = :id");
  $stmt->execute([
    ':id' => $id
  ]);
  $posts = $stmt->fetch();

  // was missing ID for the comment buttons
  $stmt = $db->prepare("SELECT comments.id, comments.content, comments.comment_date, comments.comment_by, users.username AS commenter FROM comments LEFT JOIN users ON comments.comment_by = users.id WHERE for_post = :id AND comments.deleted_at IS NULL");
  $stmt->execute([
    ':id' => $id
  ]);
  $comments = $stmt->fetchAll();
}

// It couldnt delete because it was looking for ID not comments_id and it didnt filter the page to only show non deleted comments
if (isset($_POST['comments_id'])) {

  $deleteComment = "UPDATE comments SET deleted_at=:deleted_at WHERE id=:id";
  $stmt = $db->prepare($deleteComment);
  $stmt->execute([
    ':deleted_at' => date("Y-m-d H:i:s"),
    ':id' => $_POST['comments_id']
  ]);
  header("Location: posts.php?id=" . $_GET['id']);
  exit;
}

if (isset($_POST['posts_id'])) {
  $id = $_POST['posts_id'];

  $deleteQuery = "DELETE FROM posts WHERE id=:id";

  $stmt = $db->prepare($deleteQuery);
  $stmt->execute([":id" => $id]);

  header("Location: index.php");
  exit;
}


?>

<!DOCTYPE html>
<html>

<head>
  <title>Placeholder Post View</title>
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

  <div class="container mx-auto my-5" style="max-width: 500px;">
    <h1 class="h1 mb-4 text-center"><?= $posts['title'] ?></h1>
    <p>
      <?= $posts['post_date'] ?>
    </p>
    <p>
      <?= $posts['username'] ?>
    </p>
    <p>
      <?= $posts['content'] ?>
    </p>
    <?php if ($posterid && $posterid == $posts['post_by'] || $adminPerm) : ?>
      <div class="buttons d-flex align-items-center">
        <a
          href="manage-posts-edit.php?id=<?= $posts['id'] ?>"
          class="btn btn-success btn-sm me-2">
          <i class="bi bi-pencil"></i>
        </a>

        <form method="post">
          <input type="hidden" name="posts_id" value="<?= $posts['id'] ?>">
          <button type="submit" class="btn btn-danger btn-sm">
            <i class="bi bi-trash"></i>
          </button>
        </form>
      </div>
    <?php endif; ?>
    <?php foreach ($comments as $comment): ?>
      <div class="card">
        <p class="d-flex justify-content-between">
          <span><?= $comment['commenter'] ?></span> 
          <span><?= $comment['comment_date'] ?></span>
        </p>
        <p>
          <?= $comment['content'] ?>
        </p>
        <?php if ($posterid && ($posterid == $comment['comment_by'] || $posterid == $posts['post_by'] || $adminPerm)) : ?>
        <div class="buttons d-flex align-items-center">
          <a
            href="manage-comments-edit.php?id=<?= $comment['id'] ?>"
            class="btn btn-success btn-sm me-2"><i class="bi bi-pencil"></i></a>
          <form method="post">
            <input type="hidden" name="comments_id" value="<?= $comment['id'] ?>">
            <button class="btn btn-danger btn-sm" type="submit" value="<?= $comment['id'] ?>"><i class="bi bi-trash"></i></button>
          </form>
        </div>
      </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
  <div class="text-center">
    <a href="manage-comments-add.php?id=<?= $posts['id'] ?>" class="btn btn-primary btn-sm"> Add new Comment</a>
  </div>
  <div class="text-center mt-3">
    <a href="index.php" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
  </div>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</body>

</html>