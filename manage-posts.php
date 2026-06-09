<?php
require('header.php');

if (isset($_POST['id'])) {
  $id = $_POST['id'];

  $deleteQuery = "DELETE FROM posts WHERE id=:id";

  $stmt = $db->prepare($deleteQuery);
  $stmt->execute([":id" => $id]);
}

$query = "SELECT posts.id, posts.title, posts.content, posts.post_date, users.username AS post_by FROM posts LEFT JOIN users ON posts.post_by = users.id ORDER BY posts.id";

$stmt = $db->prepare($query);
$stmt->execute([]);
$posts = $stmt->fetchAll();

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
      <h1 class="h1">Manage Posts</h1>
      <div class="text-end">
        <a href="manage-posts-add.php" class="btn btn-primary btn-sm">Add New Post</a>
      </div>
    </div>
    <div class="card mb-2 p-4">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col" style="width: 40%;">Title</th>
            <th scope="col"">Poster</th>
            <th scope="col" class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $post): ?>
            <tr>
              <th scope="row"><?= $post['id'] ?></th>
              <td><?= $post['title'] ?></td>
              <td class="fw-bold"><?= $post['post_by'] ?></td>

              <td class="text-end">
                <div class="buttons">
                  <a
                    href="posts.php?id=<?= $post['id'] ?>"
                    target="_blank"
                    class="btn btn-primary btn-sm me-2">View</a>
                  <form method="post" class="d-inline">
                    <button type="submit" class="btn btn-danger btn-sm" type="submit" <?= $_SESSION['user']['role'] == 'admin' ? '' : 'disabled' ?>>Delete</button>
                    <input type="hidden" name="id" value="<?= $post['id'] ?>">
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